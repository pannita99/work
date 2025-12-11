<?php
function isLoggedIn(){ return !empty($_SESSION['user']); }
function requireLogin(){ if(!isLoggedIn()){ header('Location: login.php'); exit; } }
function currentUser(){ return $_SESSION['user'] ?? null; }
function isAdmin(){ $u=currentUser(); return $u && $u['role']==='admin'; }
function isSeller(){ $u=currentUser(); return $u && $u['role']==='seller'; }
function isUser(){ $u=currentUser(); return $u && $u['role']==='user'; }
