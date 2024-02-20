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
        $this->load->view('products/catalogue', array('userdata'=>$this->session->userdata('user'), 'products'=>$products));
    }

    public function admin_products()
    {
        $this->load->view('products/admin_products', array('userdata'=>$this->session->userdata('user')));
    }

	public function check_loggedin()
	{
		if (!$this->session->userdata('logged_in'))
		{
			redirect('users/login');
		}
	}
}
