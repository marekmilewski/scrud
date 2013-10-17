<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class example extends CI_Controller{
    //put your code here
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
            $this->scrud_library->addRecord($data);
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
            $this->scrud_library->updateRecord($data);
            redirect($this->session->userdata('referer'));
        }
            
    }
    
    
    public function delete(){
        $this->scrud_library->deleteRecord();
        redirect($this->session->userdata('referer'));
    }
    
    
}

?>
