<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

Class Pdfmodel extends CI_Model
{

  private $error = FALSE; //If an error was raised during pdf generation etc.
  private $errorMessages = Array();
  private $generatedPDFFileName = "pdfFileName";

  public function __construct() {
    parent::__construct();
  }


  /*
  * Generate PDF and deliver if input validation is Ok
  */
  public function genPdf($input){
    //$this->errorMessages[] = "Fehler: Datei nicht gefunden";
    //$this->error = TRUE;
  }

  public function isError(){
    return $this->error;
  }

  public function getErrorMessages(){
    return $this->errorMessages;
  }

  public function getGeneratedPDFFileName(){
    return $this->generatedPDFFileName;
  }

}

