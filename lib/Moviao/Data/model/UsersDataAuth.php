<?php
declare(strict_types=1);

/* 
@author Moviao Ltd.
All rights reserved 2022-2023.
DataClass Users Auth extends UsersData
This class was created because we need extra data related to the current user logged in
*/

namespace Moviao\Data\model;

class UsersDataAuth extends \Moviao\Data\Rad\UsersData {     

    private $USR_TYPACO = null; // Email, Google, Facebook

    public function get_TYPACO() : int {
        return $this->USR_TYPACO;
    } 

    public function set_TYPACO(int $USR_TYPACO) {
        $this->USR_TYPACO=$USR_TYPACO;
    }

}