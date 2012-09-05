<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Login extends APP_Controller {

 function __construct(){
   parent::__construct();
 }

 function index(){
   $this->layout->show('login/index', $this->data);
 }
 
 function logout(){
   $this->session->unset_userdata('logged_in');
   session_destroy();
   redirect('/', 'refresh');
 }


}

