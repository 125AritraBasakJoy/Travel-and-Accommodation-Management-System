<?php
// Done by Ashik Ibadullah
class Tripplanner extends Controller{
 public function index(){
    
    $this->view('tripplan');
 }
}
$Tripplanner = new Tripplanner;
$Tripplanner->index();