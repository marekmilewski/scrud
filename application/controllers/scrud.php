<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class scrud extends CI_Controller{
    
private $scrudID;
private $limit;

public function __construct() {
    parent::__construct();
    
    if (!$this->ion_auth->logged_in())
        redirect(base_url().'login');
        
    $this->load->model('scrud_model');
    $this->scrudID=$this->getScrudID();
    $this->limit=20;
}
    
public function index(){
}

public function view(){
    

    
    

       



}

public function add(){

        
        $this->scrud_model->addRecord($this->scrudID,$data);
        redirect($this->session->userdata('referer'));
    }
    
    
}

public function edit(){

    $fields=$this->scrud_model->getFields($this->scrudID);
    
    $select=array();
    foreach($fields as $field)
        $select[].=$field['name'];
    
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('','');
    
    $this->setFormValidation($fields);

    if ($this->form_validation->run() == FALSE){      
        if(!$this->input->post()){
            $data=$this->scrud_model->getData($this->scrudID,$select, $this->getKeysFromURL() );
            $this->load->view($this->router->fetch_class(),array('fields'=>$fields,'data'=>$data[0]) );
        }
        else{
            $this->load->view($this->router->fetch_class(),array('fields'=>$fields));
        }
        
    }
    else{
        $data=$this->getPostData($fields);
        
        foreach($fields as $field)
            if($field['type']=='file' || $field['type']=='image'){
                
                $required=false;
                
                $tmp=explode(';',$field['data']);
                $upload_config=array();
                
                foreach($tmp as $tm){
                    $t=explode(':',$tm);
                    if($t[0]=='file_name')
                        $upload_config['file_name']=$this->input->post($t[1]);
                    elseif($t[0]=='required' && $t[1]=='true')
                        $required=true;
                    else
                        $upload_config[$t[0]]=$t[1];
                    
                }
                $this->load->library('upload', $upload_config);

                $file_uploaded=$this->upload->do_upload($field['name']);
                
                if($file_uploaded){
                   $fdata=$this->upload->data();
                   $data[$field['name']]=$fdata['file_name'];
                }
                elseif(!$file_uploaded && $required)
                   $this->load->view($this->router->fetch_class(),array('fields'=>$fields,'errors'=>$this->upload->display_errors()));
                
                       
            }
            
        $this->scrud_model->updateRecord($this->scrudID,$data,$this->getKeysFromURL() );
        redirect($this->session->userdata('referer'));        
    }
    
}


private function getKeysFromURL(){
    $keys=$this->uri->uri_to_assoc(4);
    if(isset($keys['from']) && $keys['from']!='')
        unset($keys['from']);    
    
    return $keys;
}

public function delete(){
    $keys=$this->getKeysFromURL();
    $this->scrud_model->deleteRecord($this->scrudID,$keys);
    redirect($this->session->userdata('referer'));
}






private function getForm(){
    return $this->scrud_model->getForm($this->scrudID);
}


private function setFormValidation($fields){
    
    foreach($fields as $field){
        if($field['rules']!='')
            $this->form_validation->set_rules($field['name'], $field['label'], $field['rules']);
        else
            $this->form_validation->set_rules($field['name'],'','');
    }
    
}



private function getPostData($fields){
    $data=array();

    foreach($fields as $field){
        $data[$field['name']]=$_POST[$field['name']];
    }
    
    return $data;
}




}
?>
