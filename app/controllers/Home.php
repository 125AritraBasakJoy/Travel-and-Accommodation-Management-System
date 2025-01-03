<?php

class Home extends Controller{
 public function index(){
    
    $this->view('home');
 }
}

$home = new Home;
$home->index();