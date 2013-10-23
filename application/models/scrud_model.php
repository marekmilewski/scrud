<?php

class scrud_model extends CI_Model {
    //put your code here

    
public function __construct(){
    parent::__construct();
}


public function getAdminTables(){
    $query=$this->db->get('scrud_tables');
    $data=array();
    
    foreach($query->result_array() as $row)
        array_push($data, $row);
    
    return $data;
}


public function insertScrudData($table_name,$data){
    $this->db->insert($table_name,$data);
    return $this->db->insert_id();
}


public function updateScrudData($table_name,$data,$where){
    $this->db->where($where);
    $this->db->update($table_name, $data);    
}
    
public function getTableName($scrudID){
    $query=$this->db->select('name')->get_where('scrud_tables',array('scrudID'=>$scrudID) );
    foreach($query->result_array() as $row)
        return $row['name'];
}
  
public function getTableDescription($scrudID){
    $query=$this->db->select('description')->get_where('scrud_tables',array('scrudID'=>$scrudID) );
    foreach($query->result_array() as $row)
        return $row['description'];
}

public function getColumns($scrudID){
    $query=$this->db->get_where('scrud_columns', array('scrudID' => $scrudID));
    $data=array();
    foreach($query->result_array() as $row)
        array_push($data, $row);
    
    return $data;
}


public function getKeys($scrudID){
    $query=$this->db->get_where('scrud_keys',array('scrudID'=>$scrudID));
    $keys=array();
    foreach($query->result_array() as $row)
        array_push($keys, $row);
    
    return $keys;
}


public function getSearch($scrudID){
    $query=$this->db->get_where('scrud_searchs',array('scrudID'=>$scrudID));
    $search=array();
    foreach($query->result_array() as $row)
        array_push($search, $row);
    
    return $search;
}


private function getMysqlDataFunction($query){
     $query=$this->db->query($query);
     
     $data=array();
     foreach ($query->result_array() as $row){
         $data[].=$row['value'].':'.$row['label'];
     }
     
     return implode(';',$data);
}

public function getFields($scrudID){
    $query=$this->db->get_where('scrud_fields',array('scrudID'=>$scrudID));
    
    $data=array();
    foreach($query->result_array() as $row){
        
        if($row['mysql_function']!=''){
            $row['data']=$this->getMysqlDataFunction($row['mysql_function']);
            
        }
        
        array_push($data, $row);
    }
    return $data;
}
    
    
public function deleteAdminData($scrudID){
    $tables = array('scrud_tables', 'scrud_columns', 'scrud_keys','scrud_searchs','scrud_fields');
    $this->db->where('scrudID',(int)$scrudID);
    $this->db->delete($tables);   
}



public function getTableData($scrudID,$columns,$params,$search_terms,$search_value,$limit,$from=0){
    
    $table_name=$this->getTableName($scrudID);

    if(!is_null($search_terms) && !is_null($search_value)){
        
        foreach($search_terms as $term){
            switch($term['type']){
                case 'IN': $this->db->where_in($term['name'], $search_value); break;
                case 'LIKE': $this->db->like($term['name'], $search_value); break;
                default: $this->db->where($term['name'], $search_value); break;
            } 
        }

     }
     
     $this->db->select(implode(',',$columns));
     
     if(!empty($params))
         $this->db->where($params);
     
     $this->db->from($table_name);
     $this->db->limit($limit,$from);
     
     $query = $this->db->get();
     $data=array();
     
     foreach($query->result_array() as $row)
         array_push($data, $row);
    
     return $data;
     
}


public function countResults($scrudID){
    return $this->db->count_all_results( $this->getTableName($scrudID) );
}



public function addRecord($scrudID,$data){
    $this->db->insert($this->getTableName($scrudID), $data);
}


public function updateRecord($scrudID,$data,$where){
    $this->db->update($this->getTableName($scrudID), $data, $where);
}



public function getData($scrudID,$select,$where ){
    $table_name=$this->getTableName($scrudID);
    
    $this->db->select(implode(',',$select));
    $this->db->from($table_name);
    $this->db->where($where);
    $query=$this->db->get();
    
    $data=array();
     
     foreach($query->result_array() as $row)
         array_push($data, $row);
     
    return $data;
}


public function deleteData($table,$where){
    $this->db->delete($table, $where); 
}

// --------------------------------------------------------------------------




    

    

    

    

    
    
    private function getResults($query){
        $query = $this->db->query($query);
        
        $data=array();
        foreach ($query->result_array() as $row){
            array_push($data, array('value'=>$row['value'],'label'=>$row['label']) );
        }
        
        return $data;
    }
    
    


    

    
    

    
    

    
    

    
    public function deleteRecord($scrudID,$where){
        $table_name=$this->getTableName($scrudID);
        $this->db->delete($table_name, $where);
    }
    
    
}

?>
