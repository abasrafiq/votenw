<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

Class Validationmodel extends CI_Model
{

  private $isValid = TRUE; //Validation succeeded
  private $validationErrors = Array();
  private $postVars = Array();

  public function __construct() {
    parent::__construct();
  }

  /**
  * Validate User Form for PDF generation
  **/
  public function validateGenPdfForm($input){
    $this->postVars = Array(
      "nachname" => $input->post("nachname"),
      "vorname" => $input->post("vorname"),
      "firma" => $input->post("firma"),
      "strasse" => $input->post("strasse"),
      "plz" => $input->post("plz"),
      "ort" => $input->post("ort"),
      "email" => $input->post("email"),
      "telefon" => $input->post("telefon"),
    );

    if($input->post("nachname") == ""){
      $this->isValid = FALSE;
      $this->validationErrors[] = "Bitte geben Sie Ihren Nachnamen an";
    }

    if($input->post("email") == "" || !valid_email($input->post("email"))){
      $this->isValid = FALSE;
      $this->validationErrors[] = "Bitte geben Sie eine korrekte E-Mail Adresse an";
    }

  }

  public function isValid(){
    return $this->isValid;
  }

  public function getValidationErrors(){
    return $this->validationErrors;
  }

  public function getPostVars(){
    return $this->postVars;
  }


}

