<?php

class menu_model extends CI_Model {
    //put your code here

    
public function __construct(){
    parent::__construct();
}



public function getMenu($parentID){
    $query=$this->db->get_where('menu', array('parentID' => $parentID));
    $data=array();
    foreach($query->result_array() as $row)
        array_push($data, $row);
    
    return $data;
}

    
}

?>
