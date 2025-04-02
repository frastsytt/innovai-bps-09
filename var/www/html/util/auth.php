<?php
include 'common.php';

session_start();
if(isset($_SESSION['auth'])){
    $auth = unserialize($_SESSION['auth']);
    if(!$auth->isEnable()){
        header("Location: /dashboard/login.php");
    }
}else{
    header("Location: /dashboard/login.php");
}
