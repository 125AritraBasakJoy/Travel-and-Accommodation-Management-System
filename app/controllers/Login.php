<?php

class Login extends Controller{
 public function index(){
    
    $this->view('login');
 }
}

$login = new Login;
$login->index();