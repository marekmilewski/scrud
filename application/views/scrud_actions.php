<?php
$scrudID=(isset($scrudID) && $scrudID!='') ? $scrudID : '' ;

if($action=='add_column'){
$colID=(isset($data['columnID']) && $data['columnID']!='') ? $data['columnID'] : uniqid() ;    
?>
<div class="row jq" id="col-a<?php echo $colID?>">
<input type="hidden" name="columnID[]" value="<?php if(isset($data['columnID']) && $data['columnID']!='') echo $data['columnID']; ?>"/>
<div class="col-lg-4"><input type="text" class="form-control input-sm" name="column_name[]" value="<?php if(isset($data['column_name']) && $data['column_name']!='') echo $data['column_name']; ?>" placeholder="Nazwa kolumny"/></div>
<div class="col-lg-4"><input type="text" class="form-control input-sm" name="column_description[]" value="<?php if(isset($data['column_description']) && $data['column_description']!='') echo $data['column_description']; ?>" placeholder="Opis"/></div>
<div class="col-lg-2">
    <select name="column_align[]" class="form-control input-sm">
        <option value="center" <?php if(isset($data['column_align']) && $data['column_align']=='center') echo 'selected="selected" '; ?>>center</option>
        <option value="left" <?php if(isset($data['column_align']) && $data['column_align']=='left') echo 'selected="selected" '; ?>>left</option>
        <option value="right" <?php if(isset($data['column_align']) && $data['column_align']=='right') echo 'selected="selected" '; ?>>right</option>
    </select>
</div>    
<div class="col-lg-2"><input type="text" class="form-control input-sm" name="column_width[]" value="<?php if(isset($data['column_width']) && $data['column_width']!='') echo $data['column_width']; ?>" placeholder="Szerokość %"/></div>
</div>
<div class="row jq" id="col-b<?php echo $colID?>">
<div class="col-lg-11"><input type="text" class="form-control input-sm" name="column_params[]" value="<?php if(isset($data['column_params']) && $data['column_params']!='') echo $data['column_params']; ?>" placeholder="Parametry"/></div>
<div class="col-lg-1"><button onclick="deleteColumn('<?php echo $scrudID; ?>','<?php echo $colID; ?>')" type="button" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove-circle"></span></button></div>
</div>
<?php } elseif($action=='add_key'){
$keyID=(isset($data['keyID']) && $data['keyID']!='') ? $data['keyID'] : uniqid() ;  
?>
<div class="row jq" id="key<?php echo $keyID ?>">
<div class="col-lg-5"><input type="hidden" name="keyID[]" value="<?php if(isset($data['keyID']) && $data['keyID']!='') echo $data['keyID']; ?>"/>
<input type="text" class="form-control input-sm" name="key_name[]" value="<?php if(isset($data['key_name']) && $data['key_name']!='') echo $data['key_name']; ?>" placeholder="Nazwa klucza"/></div>
<div class="col-lg-1"><button onclick="deleteKey('<?php echo $scrudID; ?>','<?php echo $keyID; ?>')" type="button" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove-circle"></span></button></div>
</div>
<?php } elseif($action=='add_search') {
$searchID=(isset($data['searchID']) && $data['searchID']!='') ? $data['searchID'] : uniqid() ;    
?>
<div class="row jq" id="search<?php echo $searchID ?>">
<input type="hidden" name="searchID[]" value="<?php if(isset($data['searchID']) && $data['searchID']!='') echo $data['searchID']; ?>"/>
<div class="col-lg-5"><input type="text" class="form-control input-sm" name="search_name[]" value="<?php if(isset($data['search_name']) && $data['search_name']!='') echo $data['search_name']; ?>" placeholder="Nazwa pola"/></div>
<div class="col-lg-2">
    <select name="search_type[]" class="form-control input-sm">
        <option value="NUMBER" <?php if(isset($data['search_type']) && $data['search_type']=='NUMBER') echo 'selected="selected" '; ?>>NUMBER</option>
        <option value="IN" <?php if(isset($data['search_type']) && $data['search_type']=='IN') echo 'selected="selected" '; ?>>IN</option>
        <option value="LIKE" <?php if(isset($data['search_type']) && $data['search_type']=='LIKE') echo 'selected="selected" '; ?>>LIKE</option>
        <option value="FULLTEXT" <?php if(isset($data['search_type']) && $data['search_type']=='FULLTEXT') echo 'selected="selected" '; ?>>FULLTEXT</option>        
    </select>
</div>  
<div class="col-lg-1"><button onclick="deleteSearch('<?php echo $scrudID; ?>','<?php echo $searchID; ?>')" type="button" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove-circle"></span></button></div>
</div>
<?php } elseif($action=='add_field') {
$fieldID=(isset($data['fieldID']) && $data['fieldID']!='') ? $data['fieldID'] : uniqid() ;    
?>
<div class="row jq" id="field-a<? echo $fieldID; ?>">
<input type="hidden" name="fieldID[]" value="<?php if(isset($data['fieldID']) && $data['fieldID']!='') echo $data['fieldID']; ?>"/>
<div class="col-lg-3"><input type="text" class="form-control input-sm" name="field_name[]" value="<?php if(isset($data['field_name']) && $data['field_name']!='') echo $data['field_name']; ?>" placeholder="Nazwa pola"/></div>
<div class="col-lg-3"><input type="text" class="form-control input-sm" name="field_label[]" value="<?php if(isset($data['field_label']) && $data['field_label']!='') echo $data['field_label']; ?>" placeholder="Opis"/></div>
<div class="col-lg-2">
    <select name="field_type[]" class="form-control input-sm">
        <?php
        $options=array('text','hidden','password','select','checkbox','radio','textarea','file','editor','date','time','datetime');
        foreach($options as $opt){
            $selected=(isset($data['field_type']) && $data['field_type']==$opt) ? ' selected="selected" ' : '';
            echo '<option value="'.$opt.'" '.$selected.'>'.$opt.'</option>';            
        } ?>
    </select>
</div>    
<div class="col-lg-2">
    <select name="field_size[]" class="form-control input-sm">
        <?php
        for($i=1;$i<11;$i++){
            $selected=(isset($data['field_size']) && $data['field_size']==$i) ? ' selected="selected" ' : '';
            echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
        } ?>
    </select>
</div>
<div class="col-lg-2"><input type="text" class="form-control input-sm" name="field_default[]" value="<?php if(isset($data['field_default']) && $data['field_default']!='') echo $data['field_default']; ?>" placeholder="Wartość domyślna"/></div>
</div>
<div class="row jq" id="field-b<?php echo $fieldID; ?>">
<div class="col-lg-4"><input type="text" class="form-control input-sm" name="field_mysql_function[]" value="<?php if(isset($data['field_mysql_function']) && $data['field_mysql_function']!='') echo $data['field_mysql_function']; ?>" placeholder="MYSQL data"/></div>
<div class="col-lg-4"><input type="text" class="form-control input-sm" name="field_data[]" value="<?php if(isset($data['field_data']) && $data['field_default']!='') echo $data['field_data']; ?>" placeholder="Data"/></div>
<div class="col-lg-3"><input type="text" class="form-control input-sm" name="field_rules[]" value="<?php if(isset($data['field_rules']) && $data['field_rules']!='') echo $data['field_rules']; ?>" placeholder="Rules"/></div>
<div class="col-lg-1"><button onclick="deleteField('<?php echo $scrudID; ?>','<?php echo $fieldID; ?>')" type="button" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove-circle"></span></button></div>
</div>

<? }