<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Home extends APP_Controller {
  
  public function __construct() {
    parent::__construct();
  }
  
  public function index() {
    $this->layout->show('/home/index', $this->data);
  }

}