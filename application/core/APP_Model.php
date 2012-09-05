<?php
class APP_Model extends CI_Model{
  
  protected $userdata;
  protected $arReturn = Array();

  public function __construct(){
    parent::__construct();
    $this->userdata = $this->session->userdata('logged_in');

    $this->arReturn = Array(
      "error" => false,
      "message" => "ok",
      "messageCssClass" => "success"
    );

  }

}