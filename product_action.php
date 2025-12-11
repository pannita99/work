<?php
session_start();
require '../inc/config.php';
require '../inc/functions.php';
if(!isLoggedIn() || !isAdmin()) exit;
if($_SERVER['REQUEST_METHOD']==='POST'){
  $id = intval($_POST['id']);
  if(isset($_POST['approve'])){ $pdo->prepare("UPDATE products SET approved=1 WHERE id=?")->execute([$id]); }
}
header('Location: dashboard.php');