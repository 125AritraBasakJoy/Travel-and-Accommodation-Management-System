<?php
// Done by Ashik Ibadullah
class Signup extends Controller{
 public function index(){
    
    $this->view('signup');
 }
}
$signup = new Signup;
$signup->index();