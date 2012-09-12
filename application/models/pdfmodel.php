<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

Class Pdfmodel extends CI_Model
{

  private $data;

  public function __construct() {
    parent::__construct();

    //Alle vorhanden Templates mit Felddefintionen
    $this->data = Array(

      "Template2" => Array(
        "filename" => "regEx_CS.pdf",
        "name_x" => 200,
        "name_y" => 10
      ),

      "Template1" => Array(
        "filename" => "jquery.pdf",
        "name_x" => 20,
        "name_y" => 50
      ),

    );

  }

  public function getData(){
    return $this->data;
  }


}

