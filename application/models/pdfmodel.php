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

      "Template1" => Array(
        "filename" => "xy.pdf",
        "name_x" => 20,
        "name_y" => 50
      ),

      "Template2" => Array(
        "filename" => "zzz.pdf",
        "name_x" => 20,
        "name_y" => 50
      ),

    );

  }

  public function getData(){
    return $this->data;
  }


}

