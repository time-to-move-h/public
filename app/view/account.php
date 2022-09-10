<?php require("inc/auth.inc.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Moviao ! Account</title>
<meta name="viewport" content="width=device-width, initial-scale=1">             
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" type="text/css" href="/dist/css/m/m.css">       
<link rel="stylesheet" type="text/css" href="/dist/css/styles.css">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="../bower_components/html5shiv/dist/html5shiv.js"></script>
<script src="../bower_components/respond/dest/respond.min.js"></script>
<![endif]-->
<link rel="SHORTCUT ICON" href="/img/favicon.ico" /> 
</head>
<body>
<?php require("inc/header.inc.php");?>    
<main>
<div class="container">    
<div class="row">    
<div id="moviao-central" class="">         
    
<span class="align-center"> .... Account .... </span>
    
    
    
    
    
    
    
                    <form class="uk-form uk-form-horizontal" data-bind="submit: onSubmit">   
                         <fieldset data-uk-margin>                                            
                             
                                <div class="uk-form-row">
                                <label class="uk-form-label">NickName</label>
                                <div class="uk-form-controls">
                                    <input type="text" data-bind="value: data().NNAME" class="uk-form-width-large" placeholder="Enter a NickName ...">
                                </div>
                                </div>
                             
                                <div class="uk-form-row">
                                    <label class="uk-form-label">Email</label>
                                    <div class="uk-form-controls">
                                    <input data-bind="value: data().MAIL" class="uk-form-width-large" placeholder="Enter a Email ...">
                                    </div>
                                  </div>
                             
                                <div class="uk-form-row">
                                    <label class="uk-form-label">Password</label>
                                    <div class="uk-form-controls">
                                        <input type="password" data-bind="value: data().PWD" class="uk-form-width-large">
                                    </div>
                                  </div>
                              
                               <div class="uk-form-row">
                               <label class="uk-form-label">First Name</label>
                               <div class="uk-form-controls">
                                    <input type="text" data-bind="value: data().FNAME" class="uk-form-width-large" placeholder="Enter a First Name ...">
                               </div>
                               </div>
                        

                             <div class="uk-form-row">
                               <label class="uk-form-label">Last Name</label>
                               <div class="uk-form-controls">
                                    <input type="text" data-bind="value: data().LNAME" class="uk-form-width-large" placeholder="Enter a Last Name ...">                                  
                               </div>
                             </div>
                               

                             
   
                             
                             <div class="uk-form-row">
                               <label class="uk-form-label">Country</label>
                               <div class="uk-form-controls">
                                   <select data-bind="options: countries,optionsText: 'name',value: 'iso',value: selectedCountry"></select>
                               
                               </div>
                            </div>
                               
                             <div class="uk-form-row">
                               <label class="uk-form-label">City</label>
                               <div class="uk-form-controls">
                               <input data-bind="value: data().CITY" class="uk-form-width-large"></select>
                               </div>
                             </div>
                             
                                                          <div class="uk-form-row">
                               <label class="uk-form-label">Street</label>
                               <div class="uk-form-controls">
                                   <textarea cols="50" rows="" class="uk-form-width-large" data-bind="value: data().STREET" placeholder="Enter a long description ..."></textarea>                                    
                               </div>
                             </div>
                             
                             <div class="uk-form-row">
                               <label class="uk-form-label">Street Number</label>
                               <div class="uk-form-controls">
                                   <textarea cols="50" rows="" class="uk-form-width-large" data-bind="value: data().STREETN" placeholder="About ..."></textarea>
                               </div>
                            </div>
                             
                             
                             
                             <div class="uk-form-row">
                               <label class="uk-form-label">Box</label>
                               <div class="uk-form-controls">
                                   <textarea cols="50" rows="" class="uk-form-width-large" data-bind="value: data().BOX" placeholder="Organisation ..."></textarea>
                               </div>
                            </div>
                             
                            <div class="uk-form-row">
                               <label class="uk-form-label">Postal Code</label>
                               <div class="uk-form-controls">
                               <input data-bind="value: data().PCODE" class="uk-form-width-large"></select>
                               </div>
                             </div>

                             
                         </fieldset>
                         <hr>
                         <button class="uk-button uk-button-primary uk-button-large">
                           Create a Profile
                         </button>    
                       </form> 
    
    
    
    
    
    
    
    
    
    
    
    
<!--                
<div class="">
                    <dl class="">
                        <dt><img class="" src="img/placeholder/placeholder_avatar.svg" alt=""></dt>
                        <dd>                            
                            <form class="">
                                <fieldset>
                                    <legend>Write Something ...</legend>
                                    <div class=""><textarea class="" data-bind="value: twall"></textarea></div>
                                    <div class=""><a id="post" class="" href="#" data-bind="click: pushWall">Post</a></div>
                                </fieldset>
                            </form>  
                            <hr>
                        </dd>                        
                    </dl>
                    <dl class="uk-description-list-line" data-bind="foreach: walls">
                        <dt>
                            <img class="" src="/img/placeholder/placeholder_avatar.svg" alt="">
                            <span data-bind="text: ndisp"></span><a data-bind="attr: { href: url, title: details }">@<span data-bind="text: nname"></span></a>
                        </dt>
                        <dd>                             
                            <div class=""><span data-bind="text: datins"></span></div>
                            <div class=""><span data-bind="text: desc"></span></div>
                        </dd>                        
                    </dl>                    
               </div>            
-->

</div>      
</div>
</div>
</main>       
<script src="/dist/js/lib/lazyload.min.js"></script> 
<script src="/ctrl/common.js"></script>   
<script src="/ctrl/app/+profile.js"></script>
</body>
</html>