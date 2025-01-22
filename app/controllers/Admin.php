<?php

class Admin extends Controller{
 public function index(){
    $this->view($_GET["url"]);
 }
}

$Admin = new Admin;
$Admin->index();