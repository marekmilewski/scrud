<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


class scrud_library {
    private $CI;
    private $scrudID;
    private $params;
    private $limit;
    
    
    private $form_fields;
    private $form_data;
    private $form_upload_errors;
    
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('scrud_model');
        $this->scrudID=$this->getScrudID();
        $this->params=$this->CI->uri->uri_to_assoc(5);
        $this->limit=20;
        
        $this->form_data=NULL;
        $this->upload_errors=NULL;
    }
    
    
    
    public function renderTable(){
        $this->CI->session->set_userdata(array('referer'=>current_url()) );

        if(isset($this->params['from']) && $this->params['from']!=''){
            $from=(int)$this->params['from'];
            unset($this->params['from']);
        }
        else
            $from=0;
        
        
        $description=$this->CI->scrud_model->getTableDescription($this->scrudID);
        $columns=$this->CI->scrud_model->getColumns($this->scrudID);
        $keys=$this->CI->scrud_model->getKeys($this->scrudID);
        $search_terms=$this->CI->scrud_model->getSearch($this->scrudID);        
        
        if(!$this->CI->input->post('search')){
            $search_terms=NULL;
            $search_value=NULL;
        }
        else
            $search_value=$this->CI->input->post('search');        
        
    
        $cols=array();
        foreach($columns as $col)
            $cols[].=$col['name'];
    
        foreach($keys as $key)
            $cols[].=$key['name'];
    
        $cols=  array_unique($cols);
    
    
        $data=$this->CI->scrud_model->getTableData($this->scrudID,$cols,$this->params,$search_terms,$search_value,$this->limit,$from);

        $this->CI->load->library('pagination');
        $config['base_url'] = base_url().'admin/'.$this->CI->router->fetch_class().'/view/'.$this->scrudID.'/from/';
        $config['total_rows'] = $this->CI->scrud_model->countResults($this->scrudID);
        $config['per_page'] = $this->limit;
        $config['uri_segment']=6;
    
        $this->CI->pagination->initialize($config);
        $pagination=$this->CI->pagination->create_links();
        return $this->CI->load->view('admin/scrud',array('columns'=>$columns,'keys'=>$keys,'search'=>$search_terms,'data'=>$data,'description'=>$description,'pagination'=>$pagination,'scrudID'=>$this->scrudID ),true );
    }
    
    
    public function isValidForm(){
        $form_valid=false;
        
        $this->form_fields=$this->CI->scrud_model->getFields($this->scrudID);       
        $this->CI->load->library('form_validation');
        $this->CI->form_validation->set_error_delimiters('','');
        
        $this->setFormValidation( $this->form_fields );
        
        $form_valid=$this->CI->form_validation->run();
        
        if ($form_valid)
            foreach($this->form_fields as $field)
                if($field['type']=='file' || $field['type']=='image'){
                
                    $required=false;
                
                    $tmp=explode(';',$field['data']);
                    $upload_config=array();
                
                    foreach($tmp as $tm){
                        $t=explode(':',$tm);
                        if($t[0]=='file_name')
                            $upload_config['file_name']=$this->CI->input->post($t[1]);
                        elseif($t[0]=='required' && $t[1]=='true')
                            $required=true;
                        else
                            $upload_config[$t[0]]=$t[1];
                    
                    }
                    $this->CI->load->library('upload', $upload_config);

                    $file_uploaded=$this->CI->upload->do_upload($field['name']);
                
                    if($file_uploaded){
                        $fdata=$this->upload->data();
                        $this->form_data[$field['name']]=$fdata['file_name'];
                    }
                    elseif(!$file_uploaded && $required){
                        $this->form_upload_errors=$this->upload->display_errors();
                        $form_valid=false;
                    }
                    
                    
            }        
            
    return $form_valid;        
    }
    
    
    private function setFormValidation(){
        foreach($this->form_fields as $field){
            if($field['rules']!='')
                $this->CI->form_validation->set_rules($field['name'], $field['label'], $field['rules']);
            else
                $this->CI->form_validation->set_rules($field['name'],'','');
            }
    
    }
    

    public function renderForm(){
        
        $errors=($this->form_upload_errors!='') ? NULL : $this->form_upload_errors ;
        
        if($this->CI->uri->segment(2)=='edit'){
            $select=array();
            
            foreach($this->form_fields as $field)
                $select[].=$field['name'];
                
            $data=$this->CI->scrud_model->getData($this->scrudID,$select, $this->getKeysFromURL() );
            $data=$data[0];
        }
        else
            $data=NULL;

        return $this->CI->load->view('admin/scrud',array('fields'=>$this->form_fields, 'data'=>$data, 'errors'=>$errors ),true );
    }
    
    
    
    public function getFormData(){

        foreach($this->form_fields as $field)
            if($field['type']!='file' || $field['type']!='image')
                $this->form_data[$field['name']]=$_POST[$field['name']];
    
    
    return $this->form_data;
    }
    
    
    public function addRecord($data){
        $this->CI->scrud_model->addRecord($this->scrudID,$data);
    }
    
    public function updateRecord($data){
        $this->CI->scrud_model->updateRecord($this->scrudID,$data,$this->getKeysFromURL() );
    }
    
    public function deleteRecord(){
        $keys=$this->getKeysFromURL();
        $this->CI->scrud_model->deleteRecord($this->scrudID,$keys);
    }
    
    
    
    
    
    
    private function getKeysFromURL(){
        $keys=$this->CI->uri->uri_to_assoc(5);
        if(isset($keys['from']) && $keys['from']!='')
            unset($keys['from']);    
    
        return $keys;
    }

    private function getScrudID(){
        $this->scrudID=$this->CI->uri->segment(4);
        if(!$this->scrudID)
            die('No scrudID !!!');
        else
            return $this->scrudID;
    }    
    
    
}


?>
