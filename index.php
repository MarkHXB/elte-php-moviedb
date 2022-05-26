<?php
include_once("_init.php");

// index.php
// start session
session_start();

// auto-loading controllers and models
$auth = new Auth(new Storage(new JsonIO("storage/users.json")));

// start routing
$router = new Router();
$router->get("/views", "HomeController", "show");
//$router->post("/add-todo", "TodoController", "addTodo");
$router->start();
//header("Location: /views/home.view.php");
?>