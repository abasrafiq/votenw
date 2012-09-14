<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Upload extends APP_Controller {
  
  private $arInvoiceTypes;

  public function __construct() {
    parent::__construct();

    $this->load->model("Usermodel");
    $this->load->model("Uploadmodel");
    $this->arInvoiceTypes = $this->Uploadmodel->getInvoiceTypesAsArray();
    $this->data["arInvoiceTypes"] = $this->arInvoiceTypes;

  }
  
  public function index() {

    $this->data["error"] == FALSE;

    if($this->input->post("btnSubmitUpload")){

      $this->data["uploadData"] = $this->Uploadmodel->upload($this->userdata["id"]);

    }

    //Formular wurde nicht gesendet, Benutzerdaten laden
    $this->data["userUploads"] = $this->Uploadmodel->getUploads($this->data["userdata"]["id"]);

    $this->layout->show('/uploads/index', $this->data);  
    
  }


  public function update(){

    $field = "";
    switch($this->input->post("field")){
      case "t":
        $field = "type_id";
        break;
      case "p":
        $field = "price";
        break;
      default:
        $arReturn["error"] = TRUE;
        $arReturn["message"] = "Leider konnte die Rechnung nicht aktualisiert werden.";
        echo json_encode($arReturn);
        die();
    }

    $arReturn = $this->Uploadmodel->update($field, $this->input->post("value"), $this->input->post("uploadID"), $this->userdata["id"]);

    echo json_encode($arReturn);
  }



  public function delete(){
    $arReturn = $this->Uploadmodel->delete($this->input->post("uploadID"), $this->userdata["id"]);    
    echo json_encode($arReturn);
  }


}