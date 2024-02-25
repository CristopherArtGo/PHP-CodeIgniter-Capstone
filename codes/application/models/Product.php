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
        $query = "SELECT categories.category_id, categories.category, COUNT(products.id) AS 'product_count' FROM products RIGHT JOIN categories ON categories.category_id = products.category_id GROUP BY categories.category_id";
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

    function add_product($post, $images)
    {
        $image_json = array(1=>$this->security->xss_clean($post['main_image']));
        $count = 2;
        foreach($images as $image)
        {
            if($image_json[1] != $image)
            {
                $image_json[$count] = $image;
                $count++;
            }
        }
        $image_json = json_encode($image_json, TRUE);
        $query = "INSERT INTO Products (name, price, stock, category_id, description, images, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $values = array($this->security->xss_clean($post['product_name']),
                        $this->security->xss_clean($post['price']),
                        $this->security->xss_clean($post['inventory']),
                        $this->security->xss_clean($post['category']),
                        $this->security->xss_clean($post['description']),
                        $image_json,
                        date("Y-m-d H:i:s"),
                        date("Y-m-d H:i:s"));
        if ($this->db->query($query, $values))
        {
            $query = "SELECT id FROM Products WHERE name = ?";
            $result = $this->db->query($query, $this->security->xss_clean($post['product_name']))->row_array();
            return $result;    
        }
        else 
        {
            return "Adding product failed.";                
        }
    }

    function edit_product($post, $images)
    {
        $image_json = array(1=>$this->security->xss_clean($post['main_image']));
        $count = 2;
        foreach($images as $image)
        {
            if($image_json[1] != $image)
            {
                $image_json[$count] = $image;
                $count++;
            }
        }
        $image_json = json_encode($image_json, TRUE);
        $query = "UPDATE Products SET name = ?, price = ?, stock = ?, category_id = ?, description = ?, images = ?, updated_at = ? WHERE id = ?";
        $values = array($this->security->xss_clean($post['product_name']),
                        $this->security->xss_clean($post['price']),
                        $this->security->xss_clean($post['inventory']),
                        $this->security->xss_clean($post['category']),
                        $this->security->xss_clean($post['description']),
                        $image_json,
                        date("Y-m-d H:i:s"),
                        $this->security->xss_clean($post['product_id']));
        if ($this->db->query($query, $values))
        {
            return array('id'=>$post['product_id']);    
        }
        else 
        {
            return "Editing product failed.";                
        }
    }

    function delete($product_id)
    {
        $query = "DELETE FROM Products WHERE id = ?";
        $this->db->query($query, $this->security->xss_clean($product_id));
    }

    function validate_shipping()
    {
        $this->form_validation->set_error_delimiters('<p class="errors">','</p>');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('address_1', 'Address 1', 'required');
        $this->form_validation->set_rules('address_2', 'Address 2', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('zip_code', 'Zip Code', 'required|numeric');
        
        if(!$this->form_validation->run()) 
        {
            return validation_errors();
        } 
        else 
        {
            return "success";
        }
    }

    function validate_shipping_billing()
    {
        $this->form_validation->set_error_delimiters('<p class="errors">','</p>');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('address_1', 'Address 1', 'required');
        $this->form_validation->set_rules('address_2', 'Address 2', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('zip_code', 'Zip Code', 'required|numeric');
        
        $this->form_validation->set_error_delimiters('<p class="errors">','</p>');
        $this->form_validation->set_rules('first_name_billing', 'Billing - First Name', 'required');
        $this->form_validation->set_rules('last_name_billing', 'Billing - Last Name', 'required');
        $this->form_validation->set_rules('address_1_billing', 'Billing - Address 1', 'required');
        $this->form_validation->set_rules('address_2_billing', 'Billing - Address 2', 'required');
        $this->form_validation->set_rules('city_billing', 'Billing - City', 'required');
        $this->form_validation->set_rules('state_billing', 'Billing - State', 'required');
        $this->form_validation->set_rules('zip_code_billing', 'Billing - Zip Code', 'required|numeric');
        
        if(!$this->form_validation->run()) 
        {
            return validation_errors();
        } 
        else 
        {
            return "success";
        }
    }

    function get_cart_total($user_id)
    {
        $query = "SELECT SUM(products.price * quantity) AS total FROM Cart_items INNER JOIN Products ON cart_items.product_id = products.id WHERE user_id = ?";
        return $this->db->query($query, $user_id)->row_array()['total'];
    }

    function create_order($user_id, $post)
    {
        $total_amount = $this->get_cart_total($user_id) + 5;
        $receiver = $post['first_name']." ".$post['last_name'];
        $shipping_address = array($post['address_1'], $post['address_2'], $post['city'], $post['state'], $post['zip_code']);
        $shipping_address  = implode(", ", $shipping_address);
        $values = array($this->security->xss_clean($user_id),$total_amount, $receiver, $shipping_address);
        $same_billing_address = "1";
        $billing_address = NULL;
        if ($post['same_billing_info'] != "on")
        {
            $billing_address = array($post['first_name_billing']." ".$post['last_name_billing'], $post['address_1_billing'], $post['address_2_billing'], $post['city_billing'], $post['state_billing'], $post['zip_code_billing']);
            $billing_address = implode(", ", $billing_address);
            $same_billing_address = "0";
        }
        $values[] = $billing_address;
        $values[] = $same_billing_address;
        $values[] = date("Y-m-d H:i:s");
        $values[] = date("Y-m-d H:i:s");

        $query = "INSERT INTO Orders (user_id, total_amount, receiver, shipping_address, billing_address, same_billing_address, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $this->db->query($query, $values);

        $query = "SELECT id FROM Orders ORDER BY id DESC ";
        return $this->db->query($query)->row_array()['id'];
    }

    function cart_to_order($user_id, $order_id)
    {
        $cart_items = $this->get_cart_items($user_id);
        foreach ($cart_items as $cart_item)
        {
            $query = "INSERT INTO Order_items (order_id, product_id, quantity, created_at, updated_at) VALUES (?, ?, ?, ?, ?)";
            $values = array($order_id,
                            $cart_item['product_id'],
                            $cart_item['quantity'],
                            date("Y-m-d H:i:s"),
                            date("Y-m-d H:i:s"));
            $this->db->query($query, $values);
        }

        //add sold and deduct stock
        foreach ($cart_items as $cart_item)
        {
            $query = "UPDATE Products SET stock = ?, sold = ?, updated_at = ? WHERE id = ?";
            $values = array('quantity - '.$cart_item['quantity'],
                            $cart_item['quantity'],
                            date("Y-m-d H:i:s"),
                            $cart_item['product_id']);
            $this->db->query($query, $values);
        }

        $query = "DELETE FROM Cart_items WHERE user_id = ?";
        $this->db->query($query, $user_id);
    }

    function get_all_orders()
    {
        $query = "SELECT * FROM Orders ORDER by created_at DESC";
        return $this->db->query($query)->result_array();
    }

    function search_orders($status_id, $name = NULL)
    {

        if ($status_id >= 1 && $status_id <= 4)
        {
            $query = "SELECT * FROM Orders INNER JOIN Order_status ON order_status.status_id = orders.status_id WHERE orders.status_id = ?";
            if (!$name)
            {
                return $this->db->query($query, $this->security->xss_clean($status_id))->result_array();
            }
            else
            {
                $query .= " AND receiver LIKE CONCAT('%', ?, '%')";
                return $this->db->query($query, array($this->security->xss_clean($status_id), $this->security->xss_clean($name)))->result_array();
            }
        }
        else if ($status_id == "All")
        {
            $query = "SELECT * FROM Orders";
            if ($name)
            {
                $query .= " WHERE receiver LIKE CONCAT('%', ?, '%')";
                return $this->db->query($query, $this->security->xss_clean($name))->result_array();
            }
            else
            {
                return $this->get_all_orders();
            }
        }
        //returning all products by default
        else 
        {
            return $this->get_all_orders();
        }
    }

    function update_status($order_id, $status)
    {
        $query = "UPDATE Orders SET status_id = ?, updated_at = ? WHERE id = ?";
        $values = array($status,
                        date("Y-m-d H:i:s"),
                        $order_id);
        $this->db->query($query, $values);
        return "updated";
    }

    function get_order_statuses()
    {
        $query = "SELECT order_status.status_id, order_status.status, COUNT(orders.id) AS 'order_count' FROM Orders RIGHT JOIN Order_status ON order_status.status_id = orders.status_id GROUP BY order_status.status_id";
        return $this->db->query($query)->result_array();
    }

    function get_order_items($order_id)
    {
        $query = "SELECT * FROM Order_items INNER JOIN Products ON order_items.product_id = products.id WHERE order_id = ? ";
        return $this->db->query($query, $order_id)->result_array();
    }
}
?>