<?php
ini_set('display_errors', 1);
if (!isset($_GET['user']) or !isset($_GET['serverId'])) die ("Bad data");
$user = $_GET['user'];
$serverid = $_GET['serverId'];
include ('library/config.php');  

$checklogin = $db->prepare(' SELECT * FROM auth WHERE nickname = :nickname AND serverid = :serverid');
$checklogin->execute(array( ':nickname' => $user, ':serverid' => $serverid ));
$count = $checklogin->rowCount();
if($count == 1) {
    echo "YES";
}
else {
    echo "NO";    
}
?>
