<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">  
<title>Bootstrap 101 Template</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet">
    
<!--[if lt IE 9]>
    <script src="<?php echo base_url();?>js/html5shiv.js"></script>
<script src="<?php echo base_url();?>js/respond.min.js"></script>
<![endif]-->
    
</head>
<body style="background-color:#fafafa;">
 
<div class="container">

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        
        <div class="panel panel-default" style="margin-top:150px;">
            <div class="panel-heading"><h5 style="margin:0;padding:0;">Admin CMS</h5></div>
            <div class="panel-body" style="padding:30px;">
                
            <form action="<?php echo base_url().'admin/login'; ?>" method="post" role="form">
                <div class="form-group <?php if(form_error('username')!='') echo 'has-error'; ?>" style="margin:0;">
                    <label for="username" style="font-size:13px;font-weight:normal;">Adres email</label>
                        <div class="input-group input-group-sm">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                        <input name="username" type="text" value="<?php echo set_value('username'); ?>" class="form-control" id="username" placeholder="Wprowadź nazwę użytkownika">                    
                        </div>
                </div>
                <div class="form-group <?php if(form_error('password')!='') echo 'has-error'; ?>">
                    <label for="password" style="font-size:13px;font-weight:normal;">Hasło</label>
                        <div class="input-group input-group-sm">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                        <input name="password" type="password" value="<?php echo set_value('password'); ?>" class="form-control" id="password" placeholder="Wprowadź hasło">                    
                        </div>
                </div>
                <div><button type="submit" class="btn btn-primary pull-right">Zaloguj się&nbsp;<span class="glyphicon glyphicon-log-in"></span></button></div>
            </form>
                <div style="font-size:11px;"><a href="#">Zapomniałeś hasło ? kliknij tutaj</a></div>                
                
                
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div style="text-align:center;font-size:11px;color:gray;">Powered by <span style="color:#428bca;">EMACOM</span></div>
            </div>
        </div>



</div>

    
</div>
    
    
    
</body>
</html>