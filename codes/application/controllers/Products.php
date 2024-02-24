<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Product');
	}

	public function index()
	{
		$result = $this->load_products();
		$result['cart_list'] = $this->check_cart();
		$this->load->view('products/catalogue', $result);
    }

    public function admin_products()
    {
		$this->check_admin();
		$result = $this->load_products();
		$this->load->view('products/admin_products', $result);
    }

	//default loading of all products
	public function load_products()
	{
		$this->session->unset_userdata('category');
        $products = $this->Product->get_all_products();
		// if($this->session->flashdata('search'))
		// {
		// 	$products = $this->sort("All", $this->session->flashdata('search'))[0];
		// }
		$categories = $this->Product->get_categories();
		$total_products = 0;

		foreach($categories as $category)
		{
			$total_products += $category['product_count'];
		}
		$result = array('userdata'=>$this->session->userdata('user'), 'products'=>$products, 'categories'=>$categories, 'total_products'=>$total_products, 'search'=>$this->session->flashdata('search'));
		return $result;
		// var_dump($result);
	}

	//checks products in cart
	public function check_cart()
	{
		$cart_list = array();
		if($this->session->userdata('user'))
		{
			$cart_list = $this->Product->get_cart_list($this->session->userdata('user')['user_id']);
		}
		return $cart_list;
	}

	public function check_admin()
	{
		if (!$this->session->userdata('user')['is_admin'])
		{
			redirect('products');
		}
	}

	//sorts category and name, if no category was given, it will use the previous category given
	public function sort($category, $name = NULL)
	{
		$products = $this->Product->search_product($category, $name);
		$categories = $this->Product->get_categories();
		$total_products = 0;
		foreach($categories as $category)
		{
			$total_products += $category['product_count'];
		}
		return array($products, $categories, $total_products);
	}

	//receives user input for category and name searched
	public function sort_category()
	{
		if ($this->input->post('category'))
		{
			$this->session->set_userdata('category', $this->input->post('category'));
		}
		$result = $this->sort($this->session->userdata('category'), $this->input->post('search'));
		$this->load->view('partials/sorted_products', array('products'=>$result[0]));
	}

	//receives user input for category and name searched
	public function admin_sort_category()
	{
		if ($this->input->post('category'))
		{
			$this->session->set_userdata('category', $this->input->post('category'));
		}
		$result = $this->sort($this->session->userdata('category'), $this->input->post('search'));
		$this->load->view('partials/admin_sorted_products', array('products'=>$result[0]));
	}

	//shows product page with similar products 
	public function view_product($product_id)
	{
		$product = $this->Product->get_product_by_id($product_id);
		$similar_products = $this->Product->get_similar_products($product['id'], $product['category_id']);
		$cart_list = $this->check_cart();
		$this->load->view('products/product_view', array('userdata'=>$this->session->userdata('user'), 'product'=>$product, 'cart_list'=>$cart_list, 'similar_products'=>$similar_products));
	}

	//goes to cart page
	public function cart()
	{
		$cart_items = $this->Product->get_cart_items($this->session->userdata('user')['user_id']);
		$this->load->view('products/cart', array('userdata'=>$this->session->userdata('user'), 'cart_items'=>$cart_items));
	}


	//adds item to cart
	public function add_to_cart()
	{
		$this->Product->add_to_cart($this->input->post(), $this->session->userdata('user')['user_id']);
		$cart_list = $this->check_cart();
		$this->load->view('partials/show_cart', array('cart_list'=>$cart_list));
	}

	//redirects to products with the search input
	public function search_product_from_view()
	{
		$this->session->set_flashdata('search', $this->input->post('search'));
		redirect("/products");
	}

	//updates cart by updating the quantity of the cart item or removing the item from the cart
	public function update_cart()
	{
		if($this->input->post('remove_cart_item_id'))
		{
			$this->Product->remove_cart_item($this->input->post('remove_cart_item_id'), $this->session->userdata('user')['user_id']);
		}
		else
		{
			$this->Product->update_cart_item($this->input->post(), $this->session->userdata('user')['user_id']);
		}
		$cart_items = $this->Product->get_cart_items($this->session->userdata('user')['user_id']);
		$this->load->view('partials/cart_items', array('userdata'=>$this->session->userdata('user'), 'cart_items'=>$cart_items));
	}

	public function add_product()
	{
		if ($this->input->post('action') === 'upload_image')
		{
			$this->do_upload();
		}

		//deleting the specific image in the uploads folder
		else if ($this->input->post('action') === 'remove_image')
		{
			$this->load->helper('directory');
			$files = directory_map('./assets/images/uploads/');
			
			for($i = 0; $i < count($files); $i++)
			{
				if ($i == $this->input->post('image_index'))
				{
					unlink('./assets/images/uploads/'.$files[$i]);
				}
			}

			//fetching the file names in the uploads folder
			$images = directory_map('./assets/images/uploads/');
			$this->load->view('partials/upload_image', array('images'=>$images));
		}

		//clearing the uploads folder
		else if ($this->input->post('action') === 'reset_form')
		{
			$this->load->helper('file');
			delete_files('./assets/images/uploads/');
		}

		else if ($this->input->post('action') === 'add_product')
		{
			$this->load->helper('file');
			$this->load->helper('directory');
			$images = directory_map('./assets/images/uploads/');
			$result = $this->Product->add_product($this->input->post(), $images);
			if ($result['id'])
			{
				mkdir("./assets/images/products/".$result['id'], 0777);
				foreach($images as $image)
				{
					rename("./assets/images/uploads/".$image, "./assets/images/products/".$result['id']."/".$image);
				}	
			}
			delete_files('./assets/images/uploads/');
			$this->load->view('partials/form_errors', array('errors'=>$result));
		}

	}

	public function do_upload()
	{
		$this->load->library('upload');

		$config['upload_path'] = './assets/images/uploads/';
		$config['allowed_types'] = 'jpeg|jpg|png';

		$files = $_FILES;
		$limit = count($files['image']['name']);
		if ($limit > 4)
		{
			$limit = 4;
		}

		//creating a loop to be able to upload more than 1 image
		for($i = 0; $i < $limit; $i++)
		{
			$_FILES['image']['name'] = $files['image']['name'][$i];
			$_FILES['image']['type'] = $files['image']['type'][$i];
			$_FILES['image']['tmp_name'] = $files['image']['tmp_name'][$i];
			$_FILES['image']['error'] = $files['image']['error'][$i];
			$_FILES['image']['size'] = $files['image']['size'][$i];  
			
			$this->upload->initialize($config);
			$this->upload->do_upload('image');
		}

		//fetching the file names in the uploads folder
		$this->load->helper('directory');
		$images = directory_map('./assets/images/uploads/');
		$this->load->view('partials/upload_image', array('images'=>$images));
	}

	public function delete()
	{
		$this->load->helper('file');
		$this->Product->delete($this->input->post('product_id'));
		delete_files('./assets/images/products/'.$this->input->post('product_id', TRUE));
		rmdir('./assets/images/products/'.$this->input->post('product_id', TRUE));
	}

	public function product_details()
	{
		$product = $this->Product->get_product_by_id($this->input->post('product_id'));
		$categories = $this->Product->get_categories();
		$this->load->view('/partials/edit_product_form', array('product'=>$product, 'categories'=>$categories));
	}
}