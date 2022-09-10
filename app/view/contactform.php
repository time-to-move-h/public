<?php require("inc/auth2.inc.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Moviao ! Contact us</title>
<meta name="viewport" content="width=device-width, initial-scale=1">        
<?php require("inc/styles.inc.php");?> 
</head>
<body class="fixed-sn moviao-skin">
<?php require("inc/script.inc.php"); ?>
<?php require("inc/header.inc.php");?>

    
<div class="body-container">
<main>
<div class="container">    
<div class="row"> 

<div class="col-lg-6 offset-lg-3 col-md-12">  
<div class="moviao-central">        
<!--Naked Form-->
<div class="card-body">

    <!--Header-->
    <div class="text-xs-center">
        <h3>Contact us</h3>
        <hr class="m-t-2 m-b-2">
    </div>

    <!--Body-->    
    <br>

    <form data-bind="submit: onSubmit">
    <!--Body-->
    <div class="md-form">
        <i class="fa fa-user prefix"></i>
        <input type="text" id="name" class="form-control" data-bind="value : name" required="">
        <label for="name">Your name</label>
    </div>

    <div class="md-form">
        <i class="fa fa-envelope prefix"></i>
        <input type="text" id="email" class="form-control" data-bind="value : email" required="">
        <label for="email">Your email</label>
    </div>

    <div class="md-form">
        <i class="fa fa-pencil prefix"></i>
        <input type="text" id="subject" class="form-control" data-bind="value : subject" required="">
        <label for="subject">Subject</label>
    </div>


    <div class="text-xs-center">
        <button class="btn btn-primary">Submit</button>

<!--        <div class="call">
            <br>
            <p>Or would you prefer to call?
                <br>
                <span><i class="fa fa-phone"> </i></span> +x</p>
        </div>-->
    </div>
    </form>

</div>
<!--Naked Form-->
</div>
</div>

</div>  
</div>            
</main>
</div>


</body>
</html>