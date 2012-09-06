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
      "name" => $input->post("name"),
      "email" => $input->post("email"),
    );

    if($input->post("name") == ""){
      $this->isValid = FALSE;
      $this->validationErrors[] = "Bitte geben Sie einen Namen an";
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

