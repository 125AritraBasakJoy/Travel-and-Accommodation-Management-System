<?php

class Flight extends Controller{
 public function index(){
    
    $this->view('flight');
 }
}

$Flight = new Flight;
$Flight->index();