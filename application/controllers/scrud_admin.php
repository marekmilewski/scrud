<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class scrud_admin extends CI_Controller{
    
    
public function __construct() {
    parent::__construct();
    $this->load->model('scrud_model');
    $this->load->helper('form');
}

public function index(){
    $this->view();
}    
    
    
public function view(){
    $this->session->set_userdata( array('referer'=>current_url() ) );
    $controller=$this->router->fetch_class();
    $data=$this->scrud_model->getAdminTables();
    $this->load->view('scrud_admin',array('action'=>NULL, 'controller'=>$controller,'data'=>$data) );
}

public function add(){
    $this->load->library('form_validation');
    
    
    $this->form_validation->set_rules('table_name', 'Nazwa tabeli', 'required');
    $this->form_validation->set_rules('column_name[]', 'Nazwa kolumny', 'required');
    $this->form_validation->set_rules('key_name[]', 'Nazwa klucza', 'required');
    $this->form_validation->set_rules('field_name[]', 'Nazwa pola', 'required');
    
    if ($this->form_validation->run() == FALSE){      
        $data=$this->prepareForm();
        $this->load->view('scrud_admin',array('action'=>NULL, 'table_name'=>$data['table_name'],'columns'=>$data['columns'],'keys'=>$data['keys'],'searchs'=>$data['searchs'], 'fields'=>$data['fields']) );
    }
    else{
        $scrudID=$this->scrud_model->insertScrudData('scrud_tables',array('name'=>$this->input->post('table_name'),'description'=>$this->input->post('table_description') ));

        // ---------------------------------------------------------------------------------------

        $columnID=$this->input->post('columnID');
        $column_name=$this->input->post('column_name');
        $column_description=$this->input->post('column_description');
        $column_align=$this->input->post('column_align');
        $column_width=$this->input->post('column_width');
        $column_params=$this->input->post('column_params');
        
        if(!empty($columnID) && $scrudID>0 )
            foreach($columnID as $key => $value)
                $this->scrud_model->insertScrudData('scrud_columns',array('scrudID'=>$scrudID,'name'=>$column_name[$key],'description'=>$column_description[$key],'align'=>$column_align[$key],'width'=>$column_width[$key],'params'=>$column_params[$key]) );

        // ---------------------------------------------------------------------------------------

        $keyID=$this->input->post('keyID');
        $key_name=$this->input->post('key_name');
        
        if(!empty($keyID) && $scrudID>0 )
            foreach($key_name as $k => $value)
                $this->scrud_model->insertScrudData('scrud_keys',array('scrudID'=>$scrudID,'name'=>$value) );

        // ---------------------------------------------------------------------------------------

        $searchID=$this->input->post('searchID');
        $search_name=$this->input->post('search_name');
        $search_type=$this->input->post('search_type');
        
        if(!empty($searchID) && $scrudID>0 )
            foreach($searchID as $key => $value)
                $this->scrud_model->insertScrudData('scrud_searchs',array('scrudID'=>$scrudID,'name'=>$search_name[$key],'type'=>$search_type[$key]) );

        // ---------------------------------------------------------------------------------------                

        $fieldID=$this->input->post('fieldID');
        $field_name=$this->input->post('field_name');
        $field_label=$this->input->post('field_label');
        $field_type=$this->input->post('field_type');
        $field_size=$this->input->post('field_size');
        $field_default=$this->input->post('field_default');
        $field_mysql_function=$this->input->post('field_mysql_function');
        $field_data=$this->input->post('field_data');
        $field_rules=$this->input->post('field_rules');
        
        if(!empty($fieldID) && $scrudID>0 )
            foreach($fieldID as $key => $value)
                $this->scrud_model->insertScrudData('scrud_fields',array('scrudID'=>$scrudID,'name'=>$field_name[$key], 'label'=>$field_label[$key],'type'=>$field_type[$key], 'size'=>$field_size[$key],
                     'default'=>$field_default[$key],'mysql_function'=>$field_mysql_function[$key], 'data'=>$field_data[$key], 'rules'=>$field_rules[$key] ) );
    
        redirect($this->session->userdata('referer'));
    }
    
    
}



public function edit(){
    $this->load->library('form_validation');
    
    if($this->uri->segment(3)>0){
       
        $this->form_validation->set_rules('table_name', 'Nazwa tabeli', 'required');
        $this->form_validation->set_rules('column_name[]', 'Nazwa kolumny', 'required');
        $this->form_validation->set_rules('key_name[]', 'Nazwa klucza', 'required');
        $this->form_validation->set_rules('field_name[]', 'Nazwa pola', 'required');
        
        $scrudID=(int)$this->uri->segment(3);
        
        if ($this->form_validation->run() == FALSE){      
            $data=$this->prepareForm($scrudID);
            $this->load->view('scrud_admin',array('scrudID'=>$scrudID,'action'=>NULL, 'table_name'=>$data['table_name'],'table_description'=>$data['table_description'], 'columns'=>$data['columns'],'keys'=>$data['keys'],'searchs'=>$data['searchs'], 'fields'=>$data['fields']) );
    }
    else{
        
        $this->scrud_model->updateScrudData('scrud_tables',array('name'=>$this->input->post('table_name'),'description'=>$this->input->post('table_description') ),array('scrudID'=>$scrudID) );
        
        // ---------------------------------------------------------------------------------------

        $columnID=$this->input->post('columnID');
        $column_name=$this->input->post('column_name');
        $column_description=$this->input->post('column_description');
        $column_align=$this->input->post('column_align');
        $column_width=$this->input->post('column_width');
        $column_params=$this->input->post('column_params');
        
        if(!empty($columnID) && $scrudID>0 )
            foreach($columnID as $key => $value)
                if($value>0)
                    $this->scrud_model->updateScrudData('scrud_columns',array('name'=>$column_name[$key],'description'=>$column_description[$key],'align'=>$column_align[$key],'width'=>$column_width[$key],'params'=>$column_params[$key]),
                            array('scrudID'=>$scrudID,'columnID'=>$columnID[$key]) );
                else
                    $this->scrud_model->insertScrudData('scrud_columns',array('scrudID'=>$scrudID,'name'=>$column_name[$key],'description'=>$column_description[$key],'align'=>$column_align[$key],'width'=>$column_width[$key],'params'=>$column_params[$key]) );
                    
        // ---------------------------------------------------------------------------------------

        $keyID=$this->input->post('keyID');
        $key_name=$this->input->post('key_name');
        
        if(!empty($keyID) && $scrudID>0 )
            foreach($keyID as $k => $value)
                if($value>0){ echo '*'.$value.'* zapisuje'.'<br/>';
                    $this->scrud_model->updateScrudData('scrud_keys',array('name'=>$key_name[$k]),array('scrudID'=>$scrudID,'keyID'=>$keyID[$k]) );
                }else{
                    echo '*'.$value.'* dodaje'.'<br/>';
                    $this->scrud_model->insertScrudData('scrud_keys',array('scrudID'=>$scrudID,'name'=>$key_name[$k]) );
                }
        // ---------------------------------------------------------------------------------------        
        
        
                
        $searchID=$this->input->post('searchID');
        $search_name=$this->input->post('search_name');
        $search_type=$this->input->post('search_type');
        
        if(!empty($searchID) && $scrudID>0 )
            foreach($searchID as $key => $value)
                if($value>0)
                    $this->scrud_model->updateScrudData('scrud_searchs',array('name'=>$search_name[$key],'type'=>$search_type[$key]),array('scrudID'=>$scrudID,'searchID'=>$searchID[$key]) );
                else
                    $this->scrud_model->insertScrudData('scrud_searchs',array('scrudID'=>$scrudID,'name'=>$search_name[$key],'type'=>$search_type[$key]),array('scrudID'=>$scrudID,'searchID'=>$searchID[$key]) );
                
        // ---------------------------------------------------------------------------------------               
        
        $fieldID=$this->input->post('fieldID');
        $field_name=$this->input->post('field_name');
        $field_label=$this->input->post('field_label');
        $field_type=$this->input->post('field_type');
        $field_size=$this->input->post('field_size');
        $field_default=$this->input->post('field_default');
        $field_mysql_function=$this->input->post('field_mysql_function');
        $field_data=$this->input->post('field_data');
        $field_rules=$this->input->post('field_rules');

        
        if(!empty($fieldID) && $scrudID>0 )
            foreach($fieldID as $key => $value)
                if($value>0)
                    $this->scrud_model->updateScrudData('scrud_fields',array('name'=>$field_name[$key], 'label'=>$field_label[$key],'type'=>$field_type[$key], 'size'=>$field_size[$key],
                     'default'=>$field_default[$key],'mysql_function'=>$field_mysql_function[$key], 'data'=>$field_data[$key], 'rules'=>$field_rules[$key] ),
                     array('scrudID'=>$scrudID,'fieldID'=>$fieldID[$key]) );
                else
                    $this->scrud_model->insertScrudData('scrud_fields',array('scrudID'=>$scrudID,'name'=>$field_name[$key], 'label'=>$field_label[$key],'type'=>$field_type[$key], 'size'=>$field_size[$key],
                     'default'=>$field_default[$key],'mysql_function'=>$field_mysql_function[$key], 'data'=>$field_data[$key], 'rules'=>$field_rules[$key] ) );

            
        redirect($this->session->userdata('referer'));
        
    }   
    
    }
}
    
    
private function prepareForm($scrudID=NULL){    
    $data=array();
    
    if( !$this->input->post() && $this->uri->segment(2)=='edit'){
        $data['table_name']=$this->scrud_model->getTableName($scrudID);
        $data['table_description']=$this->scrud_model->getTableDescription($scrudID);
        $data['columns']=$this->prepareData('column',$this->scrud_model->getColumns($scrudID));
        $data['keys']=$this->prepareData('key',$this->scrud_model->getKeys($scrudID));
        $data['searchs']=$this->prepareData('search',$this->scrud_model->getSearch($scrudID));
        $data['fields']=$this->prepareData('field',$this->scrud_model->getFields($scrudID));    
    }
    else{
        $data['table_name']=$this->input->post('table_name');
        $data['table_description']=$this->input->post('table_description');
        $data['columns']['columnID']=$this->input->post('columnID');
        $data['columns']['column_name']=$this->input->post('column_name');
        $data['columns']['column_description']=$this->input->post('column_description');
               
        $data['columns']['column_align']=$this->input->post('column_align');
        $data['columns']['column_width']=$this->input->post('column_width');
        $data['columns']['column_params']=$this->input->post('column_params');
        
        $data['keys']['keyID']=$this->input->post('keyID');
        $data['keys']['key_name']=$this->input->post('key_name');
        
        $data['searchs']['searchID']=$this->input->post('searchID');
        $data['searchs']['search_name']=$this->input->post('search_name');
        $data['searchs']['search_type']=$this->input->post('search_type');
        
        $data['fields']['fieldID']=$this->input->post('fieldID');
        $data['fields']['field_name']=$this->input->post('field_name');
        $data['fields']['field_label']=$this->input->post('field_label');
        $data['fields']['field_mysql_function']=$this->input->post('field_mysql_function');
        $data['fields']['field_data']=$this->input->post('field_data');
        $data['fields']['field_default']=$this->input->post('field_default');
        $data['fields']['field_type']=$this->input->post('field_type');
        $data['fields']['field_size']=$this->input->post('field_size');
        $data['fields']['field_rules']=$this->input->post('field_rules');        
    }
    
    return $data;
}


private function prepareData($type,$data){
    $new_data=array();

    foreach($data as $dt)
        foreach($dt as $key=>$value){
            if(!isset($new_data[($key!=$type.'ID') ? $type.'_'.$key : $key ])) $new_data[($key!=$type.'ID') ? $type.'_'.$key : $key ]=array();
                $new_data[($key!=$type.'ID') ? $type.'_'.$key : $key ][].=$value;
        }

        return $new_data;
}

public function add_column(){
    $this->load->view('scrud_actions',array('action'=>'add_column') );

}

public function add_key(){
    $this->load->view('scrud_actions',array('action'=>'add_key') );
}

public function add_search(){
    $this->load->view('scrud_actions',array('action'=>'add_search') );
}

public function add_field(){
    $this->load->view('scrud_actions',array('action'=>'add_field') );
}


public function delete(){
    $this->scrud_model->deleteAdminData($this->uri->segment(3,0));
    redirect($this->session->userdata('referer'));
}
    


public function delete_column(){
    $this->scrud_model->deleteData('scrud_columns',$this->input->post());
    die();
}

public function delete_key(){
    $this->scrud_model->deleteData('scrud_keys',$this->input->post());
    die();
}
  
public function delete_search(){
    $this->scrud_model->deleteData('scrud_searchs',$this->input->post());
    die();
}

public function delete_field(){
    $this->scrud_model->deleteData('scrud_fields',$this->input->post());
    die();
}
    

    


    
    
    
    
    
    
}

?>
