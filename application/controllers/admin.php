<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends CI_Controller{
    
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
    $this->session->set_userdata(array('referer'=>current_url()) );

    $params=$this->uri->uri_to_assoc(4);
    
    if(isset($params['from']) && $params['from']!=''){
        $from=(int)$params['from'];
        unset($params['from']);
    }
    else
        $from=0;
       
    $description=$this->scrud_model->getTableDescription($this->scrudID);
    $columns=$this->scrud_model->getColumns($this->scrudID);
    $keys=$this->scrud_model->getKeys($this->scrudID);
    $search_terms=$this->scrud_model->getSearch($this->scrudID);

    if(!$this->input->post('search')){
        $search_terms=NULL;
        $search_value=NULL;
    }
    else
        $search_value=$this->input->post('search');
    
    $cols=array();
    foreach($columns as $col)
        $cols[].=$col['name'];
    
    foreach($keys as $key)
        $cols[].=$key['name'];
    
    $cols=  array_unique($cols);
    
    
    $data=$this->scrud_model->getTableData($this->scrudID,$cols,$params,$search_terms,$search_value,$this->limit,$from);
    
    $this->load->library('pagination');
    $config['base_url'] = $this->config->item('base_url').$this->router->fetch_class().'/view/'.$this->scrudID.'/from/';
    $config['total_rows'] = $this->scrud_model->countResults($this->scrudID);
    $config['per_page'] = $this->limit;
    $config['uri_segment']=5;
    
    $this->pagination->initialize($config);
    $pagination=$this->pagination->create_links();

    $this->load->view($this->router->fetch_class(),array('columns'=>$columns,'keys'=>$keys,'search'=>$search_terms,'data'=>$data,'description'=>$description,'pagination'=>$pagination,'scrudID'=>$this->scrudID ) );
}

public function add(){
    $fields=$this->scrud_model->getFields($this->scrudID);
    
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('','');
    
    $this->setFormValidation($fields);
    
    if ($this->form_validation->run() == FALSE){      
        
        $this->load->view($this->router->fetch_class(),array('fields'=>$fields) );
        
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
        
            
        $this->ion_auth->register($data['username'], $data['password'], $data['email'], array('first_name'=>$data['first_name'], 'last_name'=>$data['last_name']) );    
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
            

        $keys=$this->getKeysFromURL();
        $id=$keys['id'];
        $update=array();
        $update['first_name']=$data['first_name'];
        $update['last_name']=$data['last_name'];
        $update['username']=$data['username'];
                
        $this->ion_auth->update($id,$update);
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
    $this->ion_auth->delete_user($keys['id']);
    redirect($this->session->userdata('referer'));
}



private function getScrudID(){
    $this->scrudID=$this->uri->segment(3);
    if(!$this->scrudID)
        die('Brak scrudID !!!');
    else
        return $this->scrudID;
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
