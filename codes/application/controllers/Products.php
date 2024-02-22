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
		$result['cart_items'] = $this->check_cart();
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
		$categories = $this->Product->get_categories();
		$total_products = 0;

		foreach($categories as $category)
		{
			$total_products += $category['product_count'];
		}

		$result = array('userdata'=>$this->session->userdata('user'), 'products'=>$products, 'categories'=>$categories, 'total_products'=>$total_products);
		return $result;
	}

	//checks products in cart
	public function check_cart()
	{
		$cart_items = array();
		if($this->session->userdata('user'))
		{
			$cart_items = $this->Product->get_cart_items($this->session->userdata('user')['user_id']);
		}
		return $cart_items;
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

	//receives user input for category
	public function sort_category()
	{
		// var_dump($this->input->post());
		if ($this->input->post('category'))
		{
			$this->session->set_userdata('category', $this->input->post('category'));
		}
		$result = $this->sort($this->session->userdata('category'));
		$this->load->view('partials/sorted_products', array('products'=>$result[0]));

	}

	//receives user input for category
	public function admin_sort_category()
	{
		if ($this->input->post('category'))
		{
			$this->session->set_userdata('category', $this->input->post('category'));
		}
		$result = $this->sort($this->input->post('category'));
		$this->load->view('products/admin_products', array('userdata'=>$this->session->userdata('user'), 'products'=>$result[0], 'categories'=>$result[1], 'total_products'=>$result[2]));
	}

	//receives user input for name
	public function sort_name() 
	{
		$result = $this->sort($this->session->userdata('category'), $this->input->post('search'));
		$cart_items = $this->check_cart();
		$this->load->view('partials/sorted_products', array('userdata'=>$this->session->userdata('user'), 'products'=>$result[0], 'categories'=>$result[1], 'total_products'=>$result[2], 'cart_items'=>$cart_items));
	}

	//shows product page with similar products 
	public function view_product($product_id)
	{
		$product = $this->Product->get_product_by_id($product_id);
		$similar_products = $this->Product->get_similar_products($product['id'], $product['category_id']);
		$cart_items = $this->check_cart();
		$this->load->view('products/product_view', array('userdata'=>$this->session->userdata('user'), 'product'=>$product, 'cart_items'=>$cart_items, 'similar_products'=>$similar_products));
	}

	//goes to cart page
	public function cart()
	{
		$cart_items = $this->check_cart();
		$this->load->view('products/cart', array('cart_items'=>$cart_items));
	}

	public function add_to_cart()
	{
		$this->Product->add_to_cart($this->input->post(), $this->session->userdata('user')['user_id']);
		$cart_items = $this->check_cart();
		$this->load->view('partials/show_cart', array('cart_items'=>$cart_items));
		// $this->output->enable_profiler();
	}
}
