<?php 

$this->load->view($this->config->item('views_directory').'header'); 


if ($this->uri->segment($this->config->item('action_segment'),'view')=='view'){
?>    


<div class="row">
<div class="col-md-12 center"><a href="<?php echo base_url().$this->config->item('controllers_directory'); ?>scrud_admin/add" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span>&nbsp;Dodaj tabelę</a></div> 
</div>

<br/>
<div class="row">
<div class="col-md-12">    
<table class="table table-bordered table-hover">
<thead>
<tr style="background-color:#EFEFEF;color:black;font-size:13px;">
<th style="text-align:center;border-bottom:0;width:5%;">Edycja</th> 
<th style="text-align:center;border-bottom:0;width:10%;">scrudID</th>
<th style="text-align:left;border-bottom:0;">Nazwa tabeli</th>
<th style="text-align:center;border-bottom:0;width:5%;">Usuń</th> 
</tr>
</thead>    
    
<tbody>    
    
 <?php 

if(is_array($data) && sizeof($data)>0) 
foreach($data as $row){ ?>
<tr>
<td>
<a class="btn btn-success btn-xs pull-left" href="<?php echo base_url().$this->config->item('controllers_directory').$controller.'/edit/'.$row['scrudID']; ?>"><span class="glyphicon glyphicon-edit"></span>&nbsp;Edycja</a>
</td>
<td style="text-align:center;"><?php echo $row['scrudID'];?></td>
<td><?php echo $row['name'];?></td>
<td>
<a class="btn btn-danger btn-xs pull-right" href="javascript:confirmDialog('<?php echo base_url().$this->config->item('controllers_directory').$controller.'/delete/'.$row['scrudID']; ?>');"><span class="glyphicon glyphicon-remove"></span>&nbsp;Usuń</a>
</td>
</tr>
<?php }; ?>    
    
</tbody>    
</table>
</div>
</div>

<?php

}
elseif($this->uri->segment( $this->config->item('action_segment') )=='add' || $this->uri->segment( $this->config->item('action_segment') )=='edit'){ ?>
<div class="form">
<form method="post" action="<?php echo current_url();?>" class="form-horizontal" role="form">
    
<div class="form-group <?php if(form_error('table_name')!='') echo 'has-error'; ?>">
<label for="table_name" class="col-lg-2 control-label">Nazwa tabeli</label>
<div class="col-lg-4"><input type="text" class="form-control input-sm" name="table_name" id="table_name" placeholder="Nazwa tabeli" value="<?php echo set_value('table_name',$table_name); ?>"></div>
<div class="col-lg-6"><input type="text" class="form-control input-sm" name="table_description" id="table_description" placeholder="Opis tabeli" value="<?php echo set_value('table_description',$table_description); ?>"></div>
</div>
    
<div class="form-group <?php if(form_error('column_name[]')!='') echo 'has-error'; ?>">
<label for="tables_name" class="col-lg-2 control-label">Kolumny</label>
<div class="col-lg-10">
<div id="columns">
    <?php
    if(isset($columns['columnID']))
        for($i=0;$i<sizeof($columns['columnID']);$i++)
            $this->load->view($this->config->item('views_directory').'scrud_actions',array('action'=>'add_column','data'=>array('columnID'=>$columns['columnID'][$i], 'column_name'=>$columns['column_name'][$i], 'column_description'=>$columns['column_description'][$i],'column_align'=>$columns['column_align'][$i], 'column_width'=>$columns['column_width'][$i], 'column_params'=>$columns['column_params'][$i]    ) ) );
   ?>
</div>  
<a href="javascript:addColumn();" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-plus"></span>&nbsp;Dodaj kolumnę</a>    
</div>
</div>
    
<div class="form-group <?php if(form_error('key_name[]')!='') echo 'has-error'; ?>">
<label for="tables_name" class="col-lg-2 control-label">Klucze</label>
<div class="col-lg-10">
<div id="keys">
    <?php
    if(isset($keys['keyID']))
        for($i=0;$i<sizeof($keys['keyID']);$i++)
            $this->load->view($this->config->item('views_directory').'scrud_actions',array('action'=>'add_key','data'=>array('keyID'=>$keys['keyID'][$i], 'key_name'=>$keys['key_name'][$i]) ) );
    ?>    
</div>
<a href="javascript:addKey();" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-plus"></span>&nbsp;Dodaj klucz</a>     
</div>
</div>

<div class="form-group <?php if(form_error('search_name[]')!='') echo 'has-error'; ?>">
<label for="tables_name" class="col-lg-2 control-label">Wyszukiwanie</label>
<div class="col-lg-10">
<div id="searchs">
    <?php
    if(isset($searchs['searchID']))
        for($i=0;$i<sizeof($searchs['searchID']);$i++)
            $this->load->view($this->config->item('views_directory').'scrud_actions',array('action'=>'add_search','data'=>array('searchID'=>$searchs['searchID'][$i], 'search_name'=>$searchs['search_name'][$i], 'search_type'=>$searchs['search_type'][$i] ) ) );
    ?>        
</div>
<a href="javascript:addSearch();" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-plus"></span>&nbsp;Dodaj pole wyszukiwania</a>     
</div>
</div>

<div class="form-group <?php if(form_error('field_name[]')!='') echo 'has-error'; ?>">
<label for="tables_name" class="col-lg-2 control-label">Formularz</label>
<div class="col-lg-10">
<div id="fields">
    <?php
    if(isset($fields['fieldID']))
        for($i=0;$i<sizeof($fields['fieldID']);$i++)
            $this->load->view($this->config->item('views_directory').'scrud_actions',array('action'=>'add_field','data'=>array('fieldID'=>$fields['fieldID'][$i], 'field_name'=>$fields['field_name'][$i],'field_label'=>$fields['field_label'][$i],'field_type'=>$fields['field_type'][$i],'field_size'=>$fields['field_size'][$i],'field_default'=>$fields['field_default'][$i],'field_mysql_function'=>$fields['field_mysql_function'][$i], 'field_data'=>$fields['field_data'][$i], 'field_rules'=>$fields['field_rules'][$i]  ) ) );
    ?>            
</div>
<a href="javascript:addField();" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-plus"></span>&nbsp;Dodaj pole</a>         
</div>
</div>


<div class="row">
    <div class="col-md-12 center">
    <input class="btn btn-default" onclick="document.location.href='<?php echo $this->session->userdata('referer'); ?>'" type="button" value="Anuluj"/>
    <input class="btn btn-primary" type="submit" name="send" value="<?php if($this->uri->segment( $this->config->item('action_segment') )=='add') echo 'Dodaj'; else echo 'Zapisz'; ?>"/>
    </div>
</div>
    
</form>        
</div>  
    
<?php } 

$this->load->view($this->config->item('views_directory').'footer'); ?>