<?php
class APP_Model extends CI_Model{
  
  protected $arReturn = Array();

  public function __construct(){
    parent::__construct();

    $this->arReturn = Array(
      "error" => false,
      "message" => "ok",
      "messageCssClass" => "success"
    );

  }

}