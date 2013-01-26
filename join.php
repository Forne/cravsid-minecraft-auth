<?php
ini_set('display_errors', 1);
if (!isset($_GET['sessionId']) or !isset($_GET['user']) or !isset($_GET['serverId'])) die ("Bad data");
$postsessionid = $_GET['sessionId'];
$postuser = $_GET['user'];
$postserverid = $_GET['serverId'];
include ('library/config.php');           
$checklogin = $db->prepare(' SELECT * FROM auth WHERE token = :token AND nickname = :nickname');
$checklogin->execute(array( ':nickname' => $postuser, ':token' => $postsessionid ));
$count = $checklogin->rowCount();
if($count == 1)
{
    $updatelogin = $db->prepare('UPDATE auth SET serverid = :serverid WHERE token = :token AND nickname = :nickname');
    $updres = $updatelogin->execute(array( ':nickname' => $postuser, ':token' => $postsessionid, ':serverid' => $postserverid ));
    if ($updres == true) {
        echo "OK";
    } else {
        echo "Bad login";
    }
}
else
{
    echo "Bad login";
}
?>