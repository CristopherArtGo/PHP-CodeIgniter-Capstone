<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends CI_Model {

    function __construct()
    {   
        parent::__construct();
        $this->load->helper("security");
    }

    //fetching 5 rows in relation to the pages shown
    function get_5_requests($page)
    {
        $query = "SELECT * FROM Requests ORDER BY created_at DESC LIMIT 5 OFFSET ? ;";
        $values = array($this->security->xss_clean($page) * 5 - 5);
        return $this->db->query($query, $values)->result_array();
    }
}
?>
