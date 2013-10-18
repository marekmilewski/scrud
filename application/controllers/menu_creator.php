<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class menu_creator extends CI_Controller{
    
private $scrudID;
private $limit;

public function __construct() {
    parent::__construct();
    $this->load->library('scrud_library');
    $this->config->load('menu_creator');
}
    

private function renderMenu(){
    $this->load->model('menu_model');
    $items=$this->menu_model->getMenu(0);
    
    $fixed=($this->config->item('fixed')!='') ? $this->config->item('fixed') : '' ;
    $style=($this->config->item('style')!='') ? $this->config->item('style') : 'navbar-default' ;
    
    $out='';
    $out.='<nav class="navbar '.$style.' '.$fixed.'" role="navigation">';
    
        $out.='<div class="navbar-header">';
            $out.='<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">';
            $out.='<span class="sr-only">Toggle navigation</span>';
            
            $out.='<span class="icon-bar"></span>';
            $out.='<span class="icon-bar"></span>';
            $out.='<span class="icon-bar"></span>';
            
            $out.='</button>';
            if($this->config->item('brand')!='')
                $out.='<a class="navbar-brand" href="#">'.$this->config->item('brand').'</a>';
        $out.='</div>';
    
        
        $out.='<div class="collapse navbar-collapse navbar-ex1-collapse">';
            $out.='<ul class="nav navbar-nav">';
            
            
            
            
            foreach($items as $item){
                $subitems=$this->menu_model->getMenu($item['menuID']);
                
                if(count($subitems)>0){
                    $out.='<li class="dropdown">';
                    $out.='<a href="#"  class="dropdown-toggle" data-toggle="dropdown">'.$item['name'].' <b class="caret"></b></a>';
                    $out.='<ul class="dropdown-menu" role="menu">';
                                  
                    foreach($subitems as $subitem)
                        $out.='<li><a href="'.base_url().$subitem['link'].'">'.$subitem['name'].'</a></li>';
                    
                    $out.='</ul>';
                    $out.='</li>';
                    
                }
                else
                    $out.='<li><a href="'.base_url().$item['link'].'">'.$item['name'].'</a></li>';
                
            }
        
        
            $out.='</ul>';
        $out.='</div>';
        
        
    $out.='</nav>';
    
    $this->load->helper('file');
    file_put_contents('application/views/menu.php', $out);

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
            $this->renderMenu();
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
            $this->renderMenu();
            redirect($this->session->userdata('referer'));
        }
            
    }
    
    
    public function delete(){
        $this->scrud_library->deleteRecord();
        $this->renderMenu();
        redirect($this->session->userdata('referer'));
    }
    
    
}
?>
