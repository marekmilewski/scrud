<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class login extends CI_Controller{
    
    
public function __construct() {
    parent::__construct();
    $this->load->library('form_validation');
}


public function index(){

    $this->form_validation->set_rules('username', 'Adres email', 'required');
    $this->form_validation->set_rules('password', 'Hasło', 'required|min_length[6]');

    
    if ($this->form_validation->run() == FALSE){
        $errors = $this->ion_auth->errors_array();        
        $this->load->view( 'admin/'.$this->router->fetch_class(),$errors );
    }
    else{
        if($this->ion_auth->login(trim($this->input->post('username')), trim($this->input->post('password'))) )
                redirect(base_url().'admin/example/view/19');
        else{

            $this->load->view( 'admin/'.$this->router->fetch_class(),$this->ion_auth->errors_array() );
        }
    }
    
    
    
}
    


    
}






?>