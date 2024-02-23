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
        $query = "SELECT * FROM Products INNER JOIN Categories ON categories.category_id = products.category_id;";
        return $this->db->query($query)->result_array();
    }

    //get product by id
    function get_product_by_id($product_id)
    { 
        $query = "SELECT * FROM Products WHERE id=?";
        return $this->db->query($query, $this->security->xss_clean($product_id))->row_array();
    }

    function search_product($category, $name = NULL)
    {
        $result = $this->get_categories();
        $categories = array();
        foreach($result as $row)
        {
            $categories[] = $row['category'];
        }

        //checking if category is in categories table, might need to revisit to optimize code
        if (in_array($category, $categories))
        {
            $query = "SELECT * FROM Products INNER JOIN Categories ON categories.category_id = products.category_id WHERE categories.category = ?";
            if (!$name)
            {
                return $this->db->query($query, $this->security->xss_clean($category))->result_array();
            }
            else
            {
                $query .= " AND name LIKE CONCAT('%', ? , '%')";
                return $this->db->query($query, array($this->security->xss_clean($category), $this->security->xss_clean($name)))->result_array();
            }
        }
        else if ($category == "All")
        {
            $query = "SELECT * FROM Products INNER JOIN Categories ON categories.category_id = products.category_id";
            if ($name)
            {
                $query .= " WHERE name LIKE CONCAT('%', ? , '%')";
                return $this->db->query($query, $this->security->xss_clean($name))->result_array();
            }
            else
            {
                return $this->db->query($query)->result_array();
            }
        }
        //returning all products by default
        else 
        {
            return $this->get_all_products();
        }
    }

    // get all categories and their corresponding number of products 
    function get_categories()
    {
        $query = "SELECT categories.category_id, categories.category, COUNT(products.name) AS 'product_count' FROM Products INNER JOIN Categories ON categories.category_id = products.category_id GROUP BY products.category_id";
        return $this->db->query($query)->result_array();
    }
    
    //gets cart list as basis for number of items in cart
    function get_cart_list($user_id)
    {
        $query = "SELECT * FROM Cart_items WHERE user_id = ?";
        return $this->db->query($query, $this->security->xss_clean($user_id))->result_array();
    }

    //fetches similar products with the product_id
    function get_similar_products($product_id, $category_id)
    {
        $query = "SELECT * FROM Products WHERE category_id = ? AND id != ? LIMIT 4";
        $values = array($this->security->xss_clean($category_id), $this->security->xss_clean($product_id));
        return $this->db->query($query, $values)->result_array();
    }

    function add_to_cart($post, $user_id)
    {
        $query = "SELECT * FROM Cart_items WHERE user_id = ? AND product_id = ?";
        $values = array($this->security->xss_clean($user_id), $this->security->xss_clean($post['product_id']));
        $product = $this->db->query($query, $values)->row_array();
        if ($product)
        {
            $query = "UPDATE Cart_items SET quantity = ?, updated_at = ? WHERE product_id = ? AND user_id = ?";
            $values = array($this->security->xss_clean($post['quantity'] + $product['quantity']),
                            date("Y-m-d H:i:s"),
                            $this->security->xss_clean($post['product_id']),
                            $this->security->xss_clean($user_id));
            $this->db->query($query, $values);
            return;
        }
        else 
        {
            $query = "INSERT INTO Cart_items (user_id, product_id, quantity, created_at, updated_at) VALUES (?, ?, ?, ?, ?)";
            $values = array($this->security->xss_clean($user_id),
                            $this->security->xss_clean($post['product_id']),
                            $this->security->xss_clean($post['quantity']),
                            date("Y-m-d H:i:s"),
                            date("Y-m-d H:i:s"));
            $this->db->query($query, $values);
        }
    }

    //get all cart items with product details
    function get_cart_items($user_id)
    {
        $query = "SELECT * FROM Cart_items INNER JOIN Products ON cart_items.product_id = products.id WHERE user_id = ?";
        return $this->db->query($query, $this->security->xss_clean($user_id))->result_array();
    }
    
    //updates cart item quantity
    function update_cart_item($post, $user_id)
    {
        $query = "UPDATE Cart_items SET quantity = ?, updated_at = ? WHERE product_id = ? AND user_id = ?";
        $values = array($this->security->xss_clean($post['update_cart_item_quantity']),
                        date("Y-m-d H:i:s"),
                        $this->security->xss_clean($post['update_cart_item_id']),
                        $this->security->xss_clean($user_id));
        $this->db->query($query, $values);
        return;
    }

    //removes item from cart
    function remove_cart_item($product_id, $user_id)
    {
        $query = "DELETE FROM Cart_items WHERE product_id = ? AND user_id = ?";
        $values = array($this->security->xss_clean($product_id),
                        $this->security->xss_clean($user_id)
        );
        $this->db->query($query, $values);
        return;
    }

    function add_product($post)
    {
        $query = "INSERT INTO Products (name, price, stock, category_id, description, images, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    }

    //-------------------------------------------------------------

    //checks if product form has been filled correctly
    // function validate_product() 
    // {
    //     $this->form_validation->set_error_delimiters('<p class="errors">','</p>');
    //     $this->form_validation->set_rules('name', 'Name', 'required');
    //     $this->form_validation->set_rules('description', 'Description', 'required');
    //     $this->form_validation->set_rules('price', 'Price', 'required|numeric');
    //     $this->form_validation->set_rules('stock', 'Inventory Count', 'required|numeric');
        
    //     if(!$this->form_validation->run()) 
    //     {
    //         return validation_errors();
    //     } 
    //     else 
    //     {
    //         return "success";
    //     }
    // }

    // //creates product in the database
    // function create_product($product)
    // {
    //     $query = "INSERT INTO Products (name, description, price, stock, sold, created_at, updated_at) VALUES (?,?,?,?,?,?,?)";
    //     $values = array($this->security->xss_clean($product['name']), 
    //                     $this->security->xss_clean($product['description']), 
    //                     $this->security->xss_clean($product['price']), 
    //                     $this->security->xss_clean($product['stock']),
    //                     0,
    //                     date("Y-m-d, H:i:s"),
    //                     date("Y-m-d, H:i:s")); 

    //     return $this->db->query($query, $values);
    // }

    // //updates product details
    // function update_product($product)
    // {
    //     $query = "UPDATE Products SET name=?, description=?, price=?, stock=?, updated_at=? WHERE id=?;";
    //     $values = array($this->security->xss_clean($product['name']), 
    //                     $this->security->xss_clean($product['description']), 
    //                     $this->security->xss_clean($product['price']), 
    //                     $this->security->xss_clean($product['stock']),
    //                     date("Y-m-d, H:i:s"),
    //                     $this->security->xss_clean($product['product_id'])); 

    //     return $this->db->query($query, $values);
    // }

    // //deletes product from the database
    // function delete_product($product_id)
    // {
    //     $query = "DELETE FROM Products WHERE id=?;";
    //     return $this->db->query($query, array($this->security->xss_clean($product_id)));
    // }
}
?>