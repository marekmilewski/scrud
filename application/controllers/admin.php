<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends CI_Controller{

    public function __construct() {
        parent::__construct();
        $this->load->library('scrud_library');
    }
    
    public function index(){
    }


    public function view(){
        $scrud_data=$this->scrud_library->renderTable();
        $this->load->view('example',array('scrud_data'=>$scrud_data ));
    }

    
    public function add(){
        
        if( !$this->scrud_library->isValidForm() ){
            $scrud_data=$this->scrud_library->renderForm();
            $this->load->view('example',array('scrud_data'=>$scrud_data ));
        }
        else{
            $data=$this->scrud_library->getFormData();
            
            $this->ion_auth->register($data['username'], $data['password'], $data['email'], array('first_name'=>$data['first_name'], 'last_name'=>$data['last_name']) );    
            redirect($this->session->userdata('referer'));
        }
        
    }
    
   
    public function edit(){
        
        if( !$this->scrud_library->isValidForm() ){
            $scrud_data=$this->scrud_library->renderForm();
            $this->load->view('example',array('scrud_data'=>$scrud_data ));
        }
        else{
            $data=$this->scrud_library->getFormData();
            
            
            $this->ion_auth->update($id,$data);
            redirect($this->session->userdata('referer'));   
        }
            
    }
    
    
    public function delete(){
        $this->ion_auth->delete_user($keys['id']);
        redirect($this->session->userdata('referer'));
    }


}
?>
