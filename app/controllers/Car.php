<?php

class Car extends Controller{
 public function index(){
    
    $this->view('car');
 }
}

$Car = new Car;
$Car->index();