<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Model {

    function __construct()
    {   
        parent::__construct();
        $this->load->helper("security");
        $this->load->library("form_validation");
    }

    //get all products
    function get_all_products()
    {
        $query = "SELECT * FROM Products;";
        return $this->db->query($query)->result_array();
    }

    //get product by id
    function get_product_by_id($product_id)
    { 
        $query = "SELECT * FROM Products WHERE id=?";
        return $this->db->query($query, $this->security->xss_clean($product_id))->row_array();
    }


    function get_product_by_category($category)
    {
        $result = $this->get_categories();
        $categories = array();
        foreach($result as $row)
        {
            $categories[] = $row['category'];
        }

        //checking if category is in categories table
        if (in_array($category, $categories))
        {
            $query = "SELECT * FROM Products INNER JOIN Categories ON categories.category_id = products.category_id WHERE categories.category = ?";
            return $this->db->query($query, $this->security->xss_clean($category))->result_array();
        }
        else
        {
            return $this->get_all_products();
        }
    }

    function get_categories()
    {
        $query = "SELECT categories.category, COUNT(products.name) AS 'product_count' FROM Products INNER JOIN Categories ON categories.category_id = products.category_id GROUP BY products.category_id";
        return $this->db->query($query)->result_array();
    }

    //checks if product form has been filled correctly
    function validate_product() 
    {
        $this->form_validation->set_error_delimiters('<p class="errors">','</p>');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('price', 'Price', 'required|numeric');
        $this->form_validation->set_rules('stock', 'Inventory Count', 'required|numeric');
        
        if(!$this->form_validation->run()) 
        {
            return validation_errors();
        } 
        else 
        {
            return "success";
        }
    }

    //creates product in the database
    function create_product($product)
    {
        $query = "INSERT INTO Products (name, description, price, stock, sold, created_at, updated_at) VALUES (?,?,?,?,?,?,?)";
        $values = array($this->security->xss_clean($product['name']), 
                        $this->security->xss_clean($product['description']), 
                        $this->security->xss_clean($product['price']), 
                        $this->security->xss_clean($product['stock']),
                        0,
                        date("Y-m-d, H:i:s"),
                        date("Y-m-d, H:i:s")); 

        return $this->db->query($query, $values);
    }

    //updates product details
    function update_product($product)
    {
        $query = "UPDATE Products SET name=?, description=?, price=?, stock=?, updated_at=? WHERE id=?;";
        $values = array($this->security->xss_clean($product['name']), 
                        $this->security->xss_clean($product['description']), 
                        $this->security->xss_clean($product['price']), 
                        $this->security->xss_clean($product['stock']),
                        date("Y-m-d, H:i:s"),
                        $this->security->xss_clean($product['product_id'])); 

        return $this->db->query($query, $values);
    }

    //deletes product from the database
    function delete_product($product_id)
    {
        $query = "DELETE FROM Products WHERE id=?;";
        return $this->db->query($query, array($this->security->xss_clean($product_id)));
    }
}
?>