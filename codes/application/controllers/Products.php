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
		$result['cart_list'] = $this->check_cart();
		$this->load->view('products/catalogue', $result);
    }

	//default loading of all products
	public function load_products()
	{
		$this->session->unset_userdata('category');
		$categories = $this->Product->get_categories();
        $products = $this->Product->get_all_products();
		$result = array('userdata'=>$this->session->userdata('user'), 'categories'=>$categories, 'total_products'=>count($products), 'search'=>$this->session->flashdata('search'));
		return $result;
	}

	//checks products in cart
	public function check_cart()
	{
		$cart_list = array();
		if($this->session->userdata('user'))
		{
			$cart_list = $this->Product->get_cart_list($this->session->userdata('user')['user_id']);
		}
		return $cart_list;
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
		$this->load->view('partials/sorted_products', array('products'=>$result[0]));
	}

	//shows product page with similar products 
	public function view_product($product_id)
	{
		$product = $this->Product->get_product_by_id($product_id);
		$similar_products = $this->Product->get_similar_products($product['id'], $product['category_id']);
		$cart_list = $this->check_cart();
		$this->load->view('products/product_view', array('userdata'=>$this->session->userdata('user'), 'product'=>$product, 'cart_list'=>$cart_list, 'similar_products'=>$similar_products));
	}

	//goes to cart page
	public function cart()
	{
		$cart_items = $this->Product->get_cart_items($this->session->userdata('user')['user_id']);
		$total = number_format($this->Product->get_cart_total($this->session->userdata('user')['user_id']) + 5, 2);
		$this->load->view('products/cart', array('userdata'=>$this->session->userdata('user'), 'cart_items'=>$cart_items, 'cart_total'=>$total));
	}


	//adds item to cart
	public function add_to_cart()
	{
		$this->Product->add_to_cart($this->input->post(), $this->session->userdata('user')['user_id']);
		$cart_list = $this->check_cart();
		$this->load->view('partials/show_cart', array('cart_list'=>$cart_list));
	}

	//redirects to products with the search input
	public function search_product_from_view()
	{
		$this->session->set_flashdata('search', $this->input->post('search'));
		redirect("/products");
	}

	//updates cart by updating the quantity of the cart item or removing the item from the cart
	public function update_cart()
	{
		if($this->input->post('remove_cart_item_id'))
		{
			$this->Product->remove_cart_item($this->input->post('remove_cart_item_id'), $this->session->userdata('user')['user_id']);
		}
		else
		{
			$this->Product->update_cart_item($this->input->post(), $this->session->userdata('user')['user_id']);
		}
		$cart_items = $this->Product->get_cart_items($this->session->userdata('user')['user_id']);
		$total = number_format($this->Product->get_cart_total($this->session->userdata('user')['user_id']) + 5, 2);
		$this->load->view('partials/cart_items', array('userdata'=>$this->session->userdata('user'), 'cart_items'=>$cart_items, 'total'=>$total));
	}

	public function validate_shipping_details()
	{
		$result = $this->Product->validate_shipping($this->input->post());
		if (!$this->input->post('same_billing_info'))
		{
			$result = $this->Product->validate_shipping_billing($this->input->post());
		}
		if ($result == "success")
		{
			$this->session->set_userdata('shipping', $this->input->post(NULL, TRUE));
		}
		$this->load->view('partials/form_errors', array('errors'=>$result));
	}

	public function handlePayment()
    {
		$total = number_format($this->Product->get_cart_total($this->session->userdata('user')['user_id']) + 5, 2);

        require_once('application/libraries/stripe-php/init.php');
    
        \Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));
     
        \Stripe\Charge::create ([
                "amount" =>  100 * $total,
                "currency" => "usd",
                "source" => $this->input->post('stripeToken'),
                "description" => "Order" 
        ]);
            
        $this->session->set_flashdata('success', 'Payment has been successful.');
             
		//create order
		$order_id = $this->Product->create_order($this->session->userdata('user')['user_id'], $this->session->userdata('shipping'));

		//move cartitem to orderitems and clear cart
		$this->Product->cart_to_order($this->session->userdata('user')['user_id'], $order_id);

        redirect('/products/cart', 'refresh');
    }


}