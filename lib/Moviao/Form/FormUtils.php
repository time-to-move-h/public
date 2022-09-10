<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2018-2019.
// Class FormUtils    
namespace Moviao\Form;		
class FormUtils {
function __construct() {}
function debugFormPost() {
    //echo var_dump($_POST);
    if(isset($_POST)) {
        foreach($_POST as $key=>$val) {
            echo $key.'=>'. var_dump($val).'<br>';
        }
    } else {
        echo "There is no form data !";
    }
}}