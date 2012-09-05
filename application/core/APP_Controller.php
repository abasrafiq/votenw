<?php

if (!defined('BASEPATH')){
  exit('No direct script access allowed');
}

class APP_Controller extends CI_Controller {

  protected $data = Array();
  protected $controllerName = "";
  protected $actionName = "";
  protected $controllerNameShort = "";
  protected $navItems = Array();
  protected $userdata;
  public $stocks;
  
  public function __construct(){

    parent::__construct();
    
    $this->controllerName = get_class($this);
    $this->data["controllerName"] = $this->controllerName;
    $this->controllerNameShort = strtolower($this->controllerName);
    $this->data["controllerNameShort"] = $this->controllerNameShort;
    $this->actionName = $this->uri->rsegment(2);
    $this->data["actionName"] = $this->actionName;
    $this->userdata = $this->session->userdata("logged_in");
    $this->data["userdata"] = $this->userdata;
    $this->checkLogin();
    
  }
  

  protected function checkLogin(){
    
    //NOT LOGGED IN -> REDIRECT LOGIN
    if( !$this->data["userdata"]["id"] && !( ($this->data["controllerNameShort"] == "login" || $this->data["controllerNameShort"] == "verifylogin")  && $this->actionName == "index" ) ){

      //IF AJAX REQUEST
      if($this->input->is_ajax_request()) {
        $arJsonReturn = Array(
          "error" => true,
          "message" => "Sie sind nicht eingeloggt. Bitte loggen Sie sich ein!",
        );
        echo(json_encode($arJsonReturn));
        die();
      }else{
        redirect('login', 'refresh');
        die();
      }

    }
    
  }
}