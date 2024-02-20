<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User');
	}

	public function index()
	{
		redirect("/users/login");
	}

	public function login()
	{
		$this->check_loggedin();
		$this->load->view('users/login', array('errors'=>$this->session->flashdata('errors')));
	}

	public function signup()
	{
		$this->check_loggedin();
		$this->load->view('users/signup', array('errors'=>$this->session->flashdata('errors')));
	}

	public function check_loggedin()
	{
		if ($this->session->userdata('logged_in'))
		{
			redirect('products');
		}
	}

	public function validate_signup()
	{
		//validate if all inputs are valid
		$result = $this->User->validate_signup($this->input->post());
		if ($result != "success")
		{
			$this->session->set_flashdata('errors', $result);
			redirect('users/signup');
		}
		else
		{
			//create and login user
			$this->User->create_user($this->input->post());
			$this->login_user($this->input->post());
		}
	}

	public function validate_login()
	{
		//validate if all inputs are valid
		$result = $this->User->validate_login($this->input->post());
		if ($result != "success")
		{
			$this->session->set_flashdata('errors', $result);
			redirect('users/login');
		}
		else
		{
			$this->login_user($this->input->post());
		}
	}

	public function login_user($user_credentials)
	{
		$result = $this->User->validate_credentials($user_credentials);
		if ($result != "success")
		{
			$this->session->set_flashdata('errors', $result);
			redirect('users/login');	
		}
		else
		{
			$user = $this->User->get_user_by_email($user_credentials['email']);
			$user = array('user_id' => $user['id'], 'first_name' => $user['first_name'], 'is_admin' => $user['is_admin'], 'logged_in' => true);
			$this->session->set_userdata('user', $user);
			redirect('/products');
		}

	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect("/");
	}
}
