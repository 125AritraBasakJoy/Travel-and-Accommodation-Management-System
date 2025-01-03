<?php

class _404 extends Controller{
 public function index(){
    
    $this->view('404');
 }
}

$home = new _404;
$home->index();