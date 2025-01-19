<?php

class Hotel extends Controller{
 public function index(){
    
    $this->view('hotel');
 }
}

$Hotel = new Hotel;
$Hotel->index();