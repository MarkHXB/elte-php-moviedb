<?php
require_once('_init.php');

if(userIsAuthenticated($auth)){
    header("Location: index.php?error=login_error");
    die;
}
?>

<h1>DETAILS</h1>