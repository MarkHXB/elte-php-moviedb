<?php
require_once('_init.php');

if($auth->is_authenticated()){
    $auth->logout();
    header("Location: index.php?action=logout");
    die;
} else{
    header("Location: index.php?action=logout");
    die;
}
