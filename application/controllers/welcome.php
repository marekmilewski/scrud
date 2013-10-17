<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class welcome extends CI_Controller {
    
    
public function __construct() {
    parent::__construct();
    
    if (!$this->ion_auth->logged_in())
        redirect(base_url().'login');
}


public function index(){
    $this->load->view('welcome');    
}


}
