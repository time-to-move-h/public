<?php
declare(strict_types=1);
//$sessionUser->checkAuth();
header('content-type: text/javascript');

$result = null;
$user = null;
try {
    if ($sessionUser->isValid()) {
        $user = new \Moviao\Data\UsersCommon();       
        $user->iniDatabase();
        $user->setSession($sessionUser);
        $r = $user->getUserInfo();  
        if (count($r) > 0) {
            if ($r['result'] === true) {
               $result = $r['data']; 
            }
        }    
    }
} catch (Exception $ex) {
    error_log("auth.php : $ex");
    exit();
} finally {
   if ($user != null) $user->disconnect();
}
?>
function UserData(ndisp, uuid, picture) {
    this.ndisp=ndisp;
    this.uuid=uuid;    
    this.picture=picture;
} 
function getUserInfo() {
    var uinfo = new UserData('<?php if ($result != null) echo $result['NDISP'];?>','<?php if ($result != null) echo $result['UUID'];?>','<?php if ($result != null) echo $result['PICTURE'];?>');
    return uinfo;
}
<?php exit(); ?>