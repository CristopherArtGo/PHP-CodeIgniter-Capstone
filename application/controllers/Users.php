<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// $this->load->model('User');
	}

	public function index()
	{
		$this->load->view('users/login');

		// redirect('/users/login');
	}

	public function login()
	{
		$this->load->view('users/login');
	}

	public function signup()
	{
		$this->load->view('users/signup');
	}

	public function logged_in()
	{
		if(!$this->session->userdata('logged_in'))
		{
			redirect("/");
		}
	}
}
