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
        $products = $this->Product->get_all_products();
		$categories = $this->Product->get_categories();
		$total_products = 0;
		foreach($categories as $category)
		{
			$total_products += $category['product_count'];
		}
		$this->load->view('products/catalogue', array('userdata'=>$this->session->userdata('user'), 'products'=>$products, 'categories'=>$categories, 'total_products'=>$total_products));
    }

    public function admin_products()
    {
		$this->check_loggedin();
		$this->check_admin();
		$products = $this->Product->get_all_products();
		$categories = $this->Product->get_categories();
		$total_products = 0;
		foreach($categories as $category)
		{
			$total_products += $category['product_count'];
		}

		$this->load->view('products/admin_products', array('userdata'=>$this->session->userdata('user'), 'products'=>$products, 'categories'=>$categories, 'total_products'=>$total_products));
    }

	public function check_loggedin()
	{
		if ($this->session->userdata('logged_in') === false)
		{
			redirect('users/login');
		}
	}

	public function check_admin()
	{
		if ($this->session->userdata('is_admin'))
		{
			redirect('users/login');
		}
	}

	public function sort($category)
	{
		$products = $this->Product->get_product_by_category($category);
		$categories = $this->Product->get_categories();
		$total_products = 0;
		foreach($categories as $category)
		{
			$total_products += $category['product_count'];
		}
		return array($products, $categories, $total_products);
	}

	public function product_sort()
	{
		$result = $this->sort($this->input->post('category'));
		$this->load->view('products/catalogue', array('userdata'=>$this->session->userdata('user'), 'products'=>$result[0], 'categories'=>$result[1], 'total_products'=>$result[2]));
	}

	public function admin_sort()
	{
		$result = $this->sort($this->input->post('category'));
		$this->load->view('products/admin_products', array('userdata'=>$this->session->userdata('user'), 'products'=>$result[0], 'categories'=>$result[1], 'total_products'=>$result[2]));
	}
}
