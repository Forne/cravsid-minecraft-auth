<?php
if (!isset($_POST['user']) or !isset($_POST['password']) or !isset($_POST['version'])) die ("Bad data 1");
$user = $_POST["user"];
$password = $_POST["password"];
$version = $_POST['version'];
include 'library/CravsID.php';
include 'library/config.php';

if ($version != $config['version']) die ("Old version");
$api = new CravsID();
$token = $api->auth($user, $password);
if (isset($token["access_token"])) {
    $user = $api->getUserInfo($token["access_token"]);
    if ($user['id'] > 0) {
        $mctoken = md5(uniqid(rand(),true));
        $gamemd5 = $config['md5'];
        $st1 = $db->prepare('SELECT * FROM auth WHERE uid=:uid');
        $st1->execute(array(':uid'=>$user['id']));
        $res1 = $st1->fetchAll();
        if (count($res1) == 0) {
            $add = $db->prepare("INSERT INTO auth(uid,nickname,token) VALUES (:uid,:nickname,:token)");
            $addres = $add->execute(array(':uid'=>$user['id'], ':nickname'=>$user['username'], ':token'=>$mctoken));
            if ($addres == true) {
                $usermd5 = md5($user['username']);
                echo $gamemd5.':'.$usermd5.':'.$user['username'].':'.$mctoken.':';
            } else {
                die('Bad login');
            }
        } elseif (count($res1) == 1) {
            $upd = $db->prepare("UPDATE auth SET nickname = ?, token = ? WHERE uid = ?");
            $updres = $upd->execute(array($user['username'], $mctoken, $user['id']));
            if ($updres == true) {
                $usermd5 = md5($user['username']);
                echo $gamemd5.':'.$usermd5.':'.$user['username'].':'.$mctoken.':';
            } else {
                die('Bad login');
            }
        } else {
            die('Bad login');
        }
    } else {
        die('Bad login');
    }
} else {
    die('Bad login');
}
?>