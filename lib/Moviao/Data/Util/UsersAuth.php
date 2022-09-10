<?php
declare(strict_types=1);
namespace Moviao\Data\Util;
class UsersAuth extends UsersUtils
{
    public function getInfo(\Moviao\Data\Rad\UsersData $userdata): array
    {
        $info = array();
        $info['NDISP'] = $userdata->get_NDISP();
        $info['NNAME'] = $userdata->get_NNAME();
        $info['LNAME'] = $userdata->get_LNAME();
        $info['FNAME'] = $userdata->get_FNAME();
        $info['ACCTYP'] = (int) $userdata->get_ACCTYP();
        return $info;
    }

    public function authenticate(\Moviao\Data\Rad\UsersData $userdata): bool
    {
        if ($userdata->get_USR() <= 0) {
            $this->getData()->setError(45654654656);
            return false;
        } else if ($userdata->get_ACTIVE() === 0) {
            $this->getData()->setError(4564654654);
            return false;
        } else if ($userdata->get_LOCKED() === 1) {
            $this->getData()->setError(7895446546);
            return false;
        }
        // -----------------------------------------------------
        $iduser = strval($userdata->get_USR());

        if (parent::getData()->getSession()->isValid() === false) {            
            parent::getData()->getSession()->setIDUSER($iduser);
        }

        // Update Last Access
        parent::updateLastAcc($iduser, 0);
        return true;
    }
}