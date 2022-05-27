<?php
require_once("classes/io.php");
require_once("classes/storage.php");
require_once("classes/auth.php");
require_once("classes/series.php");
require_once("classes/validation.php");

// index.php
// start session
session_start();

// auto-loading controllers and models
$series = new Series(new Storage(new JsonIO("storage/series.json")));
$auth = new Auth(new Storage(new JsonIO("storage/users.json")));

//init functions
function getIsEmpty()
{
    return  !isset($_GET) ^ count($_GET) === 0;
}
function postIsEmpty()
{
    return !isset($_GET) ^ count($_GET) === 0;
}

function getIsAlive($key)
{
    return isset($_GET[$key]) && strlen($_GET[$key]) != 0;
}

function postKeyIsValid($key)
{
    return isset($_POST[$key]) && strlen($_POST[$key]) != 0;
}
function error($msg)
{
    return $msg ? "<p class='error'>$msg</p>" : "";
}
function isAuthenticated(){
    return isset($_SESSION["user"]);
}