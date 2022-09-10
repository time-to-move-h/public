<?php
declare(strict_types=1);
namespace Moviao\Data\Util;
use PDO;

/**
 * Description of InvitationUtils
 *
 * @author MoviaoOne
 */
class InvitationUtils extends BaseUtils  {
        
    // Check if invitation code is correct
    function isInvited($form) : bool {    		    
        $strSql = "SELECT 1 FROM invitations WHERE INV_ACCOUNT=? AND INV_CODE=?;";
        $stmt = $this->data->prepare($strSql);
        if ($stmt == false) {
            $this->getData()->setError(501);
            return false;
        }                   
        if (! $this->data->bindParam(1,$form->ACCOUNT,PDO::PARAM_STR)) {
            $this->getData()->setError(502);
            return false;
        }                   
        if (! $this->data->bindParam(2,$form->CODE,PDO::PARAM_INT)) {
            $this->getData()->setError(503);
            return false;
        }    
        if (! $this->data->execute()) {
            $this->getData()->setError(504);
            return false;
        }
        $row = $this->data->fetchColumn();        
        if ($row == false || $row != "1") {
            return false;
        } else {
            return true;
        } 
    }
}