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
        "firma_x" => 10,
        "firma_y" => 10,
        "telefon_x" => 10,
        "telefon_y" => 30
      ),

      "Template1" => Array(
        "filename" => "jquery.pdf",
        "firma_x" => 20,
        "firma_y" => 10,
        "telefon_x" => 20,
        "telefon_y" => 50
      ),

    );

  }

  public function getData(){
    return $this->data;
  }


}

