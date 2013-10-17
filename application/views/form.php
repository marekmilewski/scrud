<?php

echo current_url();



function drawField($field){

$out='';

switch($field['type']){
    case 'text';
    case 'hidden';
    case 'password';
        $out='<input name="'.$field['name'].'" type="'.$field['type'].'" class="form-control" id="'.$field['name'].'" placeholder="'.$field['label'].'" value="'.set_value($field['name'], ($field['value']!='') ? $field['value'] : $field['default'] ).'"/>';
    break;
    case 'select';
        $out.='<select name="'.$field['name'].'" class="form-control">';
        foreach($field['options'] as $option)
            $out.='<option value="'.$option['value'].'" '.set_select($field['name'], $option['value'], ($field['default']==$option['value']) ? TRUE : FALSE ).'>'.$option['label'].'</option>';
        $out.='</select>';
    break;
    
    
    case 'checkbox';
        $name_arr=(sizeof($field['options'])>1) ? $name_cd='[]' : '' ;
                
        foreach($field['options'] as $option){
            $out.='<label><input name="'.$field['name'].$name_cd.'" type="checkbox" value="'.$option['value'].'">'.$option['label'].'</label>';
        }
    break;
    
    case 'radio';
        foreach($field['options'] as $option){
            $out.='<label>';
            $out.='<input type="radio" '.set_radio($field['name'],$option['value'], TRUE).' name="'.$field['name'].'" id="'.$field['name'].'" value="'.$option['value'].'" checked>';
            $out.=$option['label'];
            $out.='</label>';
        }

  
    break;
    
    

    case 'textarea';
        $out='<textarea name="'.$field['name'].'" class="form-control" rows="3">'.set_value($field['name'], ($field['value']!='') ? $field['value'] : $field['default'] ).'</textarea>';
    break;

    case 'file';
        $out='<input name="'.$field['name'].'" type="file"/>';
    break;

    case 'editor';
        $out.='<textarea id="'.$field['name'].'" name="'.$field['name'].'">'.set_value($field['name'], ($field['value']!='') ? $field['value'] : $field['default'] ).'</textarea>';
        $out.="<script type=\"text/javascript\">
	//<![CDATA[
	CKEDITOR.replace( '".$field['name']."',{
	enterMode		: 2,
	shiftEnterMode	: 2
	});
	//]]>
	</script>";
  
    break;

    default: $out=''; break;
}
    
return $out;    
}




?>




<?php echo validation_errors(); ?>

<form method="post" action="<?php echo current_url();?>" class="form-horizontal" role="form">
    
<?php foreach($fields as $field){ ?>
<div class="form-group <?php if(form_error($field['name'])!='') echo 'has-error';?>">
<label for="<?php echo $field['name']; ?>" class="col-lg-2 control-label"><?php echo $field['label']; ?></label>
<div class="col-lg-<?php echo $field['size']; ?>">
<?php echo drawField($field); echo form_error($field['name']); ?>
</div>
</div>
<?php } ?>

<div class="row">
<div class="col-lg-12 center">
    <input name="cancel" class="btn btn-default" type="button" onclick="document.location.href='<?php echo $referer; ?>';" value="Anuluj"/>&nbsp;
    <input name="send" class="btn btn-primary" type="submit" value="<?php echo $send;?>"/>&nbsp;
</div>
</div>
        
</form>