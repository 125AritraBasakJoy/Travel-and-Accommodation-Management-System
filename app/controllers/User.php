<?php

class User extends Controller{
 public function index(){
    
    $this->view('user');
 }
}

$home = new User;
$home->index();