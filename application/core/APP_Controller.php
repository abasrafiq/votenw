<?php

if (!defined('BASEPATH')){
  exit('No direct script access allowed');
}

class APP_Controller extends CI_Controller {

  protected $data = Array();
  protected $controllerName = "";
  protected $actionName = "";
  protected $controllerNameShort = "";

  public function __construct(){

    parent::__construct();
    
    $this->controllerName = get_class($this);
    $this->data["controllerName"] = $this->controllerName;
    $this->controllerNameShort = strtolower($this->controllerName);
    $this->data["controllerNameShort"] = $this->controllerNameShort;
    $this->actionName = $this->uri->rsegment(2);
    $this->data["actionName"] = $this->actionName;

    $this->load->model("Questionmodel");
    $this->load->model("Answermodel");
    $this->load->model("Votingmodel");

    $this->data["userIP"] = $this->input->ip_address();;
    
  }

}