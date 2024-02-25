<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Product');
		$this->load->helper('directory');
		$this->load->helper('file');
		$this->load->library('upload');
	}

	public function index()
	{
		$this->check_admin();
		$orders = $this->Product->get_all_orders();
		$statuses = $this->Product->get_order_statuses();
		$this->load->view('products/admin_orders', array('userdata'=>$this->session->userdata('user'), 'statuses'=>$statuses,'orders'=>$orders));
	}

	public function sort_status()
	{
		if ($this->input->post('status'))
		{
			$this->session->set_userdata('status', $this->input->post('status'));
		}
		$orders = $this->Product->search_orders($this->input->post('status'), $this->input->post('search'));
		$statuses = $this->Product->get_order_statuses();
		$this->load->view('partials/admin_sorted_orders', array('userdata'=>$this->session->userdata('user'), 'orders'=>$orders, 'statuses'=>$statuses));
	}

	public function update_status()
	{
		$result = $this->Product->update_status($this->input->post('order_id'), $this->input->post('status'));
	}

    public function products()
    {
		$this->check_admin();
		$result = $this->load_products();
		delete_files('./assets/images/uploads/');
		$this->load->view('products/admin_products', $result);
    }

	//default loading of all products
	public function load_products()
	{
		$this->session->unset_userdata('category');
        $products = $this->Product->get_all_products();
		$categories = $this->Product->get_categories();
		$result = array('userdata'=>$this->session->userdata('user'), 'categories'=>$categories, 'total_products'=>count($products), 'search'=>$this->session->flashdata('search'));
		return $result;
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
		$this->load->view('partials/admin_sorted_products', array('products'=>$result[0]));
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
			$images = directory_map('./assets/images/uploads/');
			
			for($i = 0; $i < count($images); $i++)
			{
				if ($i == $this->input->post('image_index'))
				{
					unlink('./assets/images/uploads/'.$images[$i]);
				}
			}

			//fetching the file names in the uploads folder
			$images = directory_map('./assets/images/uploads/');
			$this->load->view('partials/upload_image', array('images'=>$images));
		}

		//clearing the uploads folder
		else if ($this->input->post('action') === 'reset_form')
		{
			delete_files('./assets/images/uploads/');
		}

		else if ($this->input->post('action') === 'add_product')
		{
			$images = directory_map('./assets/images/uploads/');
			if (!count($images))
			{
				return;
			}
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

		else if ($this->input->post('action') === 'edit_product')
		{
			$images = directory_map('./assets/images/uploads/');
			if (!count($images))
			{
				return;
			}
			$result = $this->Product->edit_product($this->input->post(), $images);

			if ($result['id'])
			{
				delete_files("./assets/images/products/".$result['id']);
				foreach($images as $image)
				{
					rename("./assets/images/uploads/".$image, "./assets/images/products/".$result['id']."/".$image);
				}	
			}
			delete_files('./assets/images/uploads/');
			$this->load->view('partials/form_errors', array('errors'=>$result));
		}

		else
		{
			return;
		}
	}

	public function do_upload()
	{
		$config['upload_path'] = './assets/images/uploads/';
		$config['allowed_types'] = 'jpeg|jpg|png';

		$files = $_FILES;
		$limit = 4 - count(directory_map('./assets/images/uploads/'));
		$img_upload_count = count($files['image']['name']);
		if ($img_upload_count > $limit)
		{
			$img_upload_count = $limit;
		}

		//creating a loop to be able to upload more than 1 image
		for($i = 0; $i < $img_upload_count; $i++)
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
		$images = directory_map('./assets/images/uploads/');
		$this->load->view('partials/upload_image', array('images'=>$images));
	}

	public function delete()
	{
		$this->Product->delete($this->input->post('product_id'));
		delete_files('./assets/images/products/'.$this->input->post('product_id', TRUE));
		rmdir('./assets/images/products/'.$this->input->post('product_id', TRUE));
	}

	public function product_details()
	{
		$product = $this->Product->get_product_by_id($this->input->post('product_id'));
		$categories = $this->Product->get_categories();
		$images = directory_map('./assets/images/products/'.$this->input->post('product_id', TRUE));
		foreach($images as $image)
		{
			copy('./assets/images/products/'.$this->input->post('product_id', TRUE).'/'.$image, './assets/images/uploads/'.$image);
		}
		$images = directory_map('./assets/images/uploads/');
		$this->load->view('/partials/edit_product_form', array('product'=>$product, 'categories'=>$categories, 'images'=>$images));
	}

	public function get_order_items()
	{
		$order_items = $this->Product->get_order_items($this->input->post('order_id'));
		$this->load->view('partials/order_items', array('order_items'=>$order_items));
	}
}	