<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model {

    function __construct()
    {   
        parent::__construct();
        $this->load->helper("security");
        $this->load->library("form_validation");
    }

    //gets all users
    function get_all_users()
    {
        $query = "SELECT * FROM Users;";
        return $this->db->query($query)->result_array();
    }

    //get user details by email
    function get_user_by_email($email)
    { 
        $query = "SELECT * FROM Users WHERE email=?";
        return $this->db->query($query, $this->security->xss_clean($email))->row_array();
    }

    //get basic user details for the session by id
    function get_user_by_id($user_id)
    { 
        $query = "SELECT id, first_name, last_name, email FROM Users WHERE id=?";
        return $this->db->query($query, $this->security->xss_clean($user_id))->row_array();
    }

    //creates user in the database
    function create_user($user)
    {
        //adding salt for security
        $salt = bin2hex(openssl_random_pseudo_bytes(22));
            
        //checks if there are no users in the database, make the first user as admin
        // $users = $this->get_all_users();
        // if (count($users) === 0)
        // {
        //     $is_admin = 1;
        // }
        $query = "INSERT INTO Users (first_name, last_name, email, password, salt, created_at, updated_at) VALUES (?,?,?,?,?,?,?)";
        $values = array($this->security->xss_clean($user['first_name']), 
                        $this->security->xss_clean($user['last_name']), 
                        $this->security->xss_clean($user['email']), 
                        //encrypt the password
                        md5($this->security->xss_clean($user["password"])),
                        $this->security->xss_clean($salt),
                        date("Y-m-d, H:i:s"),
                        date("Y-m-d, H:i:s")); 

        return $this->db->query($query, $values);
    }

    //checks if the login form has been filled correctly
    function validate_login() 
    {
        $this->form_validation->set_error_delimiters('<p class="errors">','</p>');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
    
        if(!$this->form_validation->run()) 
        {
            return validation_errors();
        } 
        else 
        {
            return "success";
        }
    }

    //checks if the credentials are correct to login
    function validate_credentials($post) 
    {
        $user = $this->get_user_by_email($post['email']);
        $hash_password = md5($this->security->xss_clean($post['password']));
        if($user && $user['password'].$user['salt'] == $hash_password.$user['salt']) 
        {
            return "success";
        }
        else 
        {
            return "<p class='errors'>Incorrect credentials.</p>";
        }
    }

    //checks if the register form has been filled correctly
    function validate_signup($post) 
    {
        $this->form_validation->set_error_delimiters('<p class="errors">','</p>');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');        
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        
        if(!$this->form_validation->run()) 
        {
            return validation_errors();
        }
        //rejecting if email has already been taken
        else if($this->get_user_by_email($post['email'])) 
        {
            return "<p class='errors'>Email already taken.</p>";
        }
        else 
        {
            return "success";
        }
    }

    //checks if the user info update form has been filled correctly
    function validate_user_info()
    {
        $this->form_validation->set_error_delimiters('<p class="errors">','</p>');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');        
        
        if(!$this->form_validation->run()) 
        {
            return validation_errors();
        }
        else 
        {
            return "success";
        }

    }

    //updates user info in the database
    public function update_user_info($user, $post)
    {
        $query = "UPDATE Users SET first_name=?, last_name=?, email=?, updated_at=? WHERE id=?;";
        $values = array($this->security->xss_clean($post['first_name']), 
                        $this->security->xss_clean($post['last_name']),
                        $this->security->xss_clean($post['email']),
                        date("Y-m-d, H:i:s"),
                        $this->security->xss_clean($user['id']));   
        
        return $this->db->query($query, $values);
    }

    //checks if the user password update form has been filled correctly
    function validate_user_password()
    {
        $this->form_validation->set_error_delimiters('<p class="errors">','</p>');
        $this->form_validation->set_rules('old_password', 'Old Password', 'required|min_length[8]');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[8]|differs[old_password]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');        
        
        if(!$this->form_validation->run()) 
        {
            return validation_errors();
        }
        else 
        {
            return "success";
        }
    }

    //updates user password in the database
    public function update_user_password($user, $post)
    {
        //new salt is also created for security
        $salt = bin2hex(openssl_random_pseudo_bytes(22));
        $query = "UPDATE Users SET password=?, salt=?, updated_at=? WHERE id=?;";
        $values = array(md5($this->security->xss_clean($post['new_password'])), 
                        $this->security->xss_clean($salt),
                        date("Y-m-d, H:i:s"),
                        $this->security->xss_clean($user['id']));   
        
        return $this->db->query($query, $values);
    }
}
?>