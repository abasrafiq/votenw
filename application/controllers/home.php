<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Home extends APP_Controller {
  
  private $postData = Array();
  private $pdfWasGenerated = FALSE;
  private $SESSIONKEY_GENERATED_PDF_FILENAME = "generatedPDFFileName";
  private $pdfTemplateFileName = "pdfTemplates/jQuery-17-Visual-Cheat-Sheet.pdf";
  private $pdfOutputFileName = "Individualisiertes_PDF.pdf";

  public function __construct() {
    parent::__construct();
  }
  
  public function index() {
    
    $this->data["error"] == FALSE;


    $this->load->model("Validationmodel");
    $this->Validationmodel->validateGenPdfForm($this->input);
    $this->data["postVars"] = $this->Validationmodel->getPostVars();

    if($this->input->post("btnSubmitForm") != ""){
      //Formular wurde abgesendet
      if($this->Validationmodel->isValid()){
        
        //Validierung OK
        $this->load->model("Pdfmodel");
        $this->Pdfmodel->genPdf($this->input);

        if($this->Pdfmodel->isError()){
          //PDF Generierungsfehler
          $this->data["error"] = TRUE;
          $this->data["pdfErrorMessages"] = $this->Pdfmodel->getErrorMessages();
        }
        
      }else{
        //Validation FEHLER
        $this->data["error"] = TRUE;
        $this->data["validationErrors"] = $this->Validationmodel->getValidationErrors();
      }

      if($this->data["error"] == FALSE){

        //Formular übermittelt, keine Fehler - Daten Session ablegen
        $this->postData = Array(
          "pdfData_name" => $this->data["postVars"]["name"], //From Validation postVars
          "pdfData_email" => $this->data["postVars"]["email"], //From Validation postVars
        );
        $this->session->set_userdata($this->postData);

        $this->pdfWasGenerated = TRUE;
        $this->generatePDF();
          
      }

    }

    if(!$this->pdfWasGenerated){
      //PDF wurde nicht erzeugt, Startseite/Formular anzeigen
      $this->layout->show('/home/index', $this->data);  
    }else{
      //Validierung Ok, PDF wurde erzeugt, Redirect zur direct Download Seite
      redirect("home/pdfGenerated");
    }
    
  }



  /** 
  /* Auf diese Seite wird weiter geleitet, wenn die Validierung Ok ist und der Download erzeugt wurde
  */
  public function pdfGenerated(){
    //PDF wurde erzeugt, View mit verstecktem iFrame zum automatischen Download und die Meldungen anzeigen
    if(!$this->pdfFileExists()){
      $this->data["error"] = TRUE;
    }
    $this->layout->show('/home/pdfGenerated', $this->data);
  }



  /**
  /* Der eigentliche Download, der über das versteckte iFrame aufgerufen wird 
  */
  public function pdfDownload(){
    
    $tmpFileName = $this->session->userdata($this->SESSIONKEY_GENERATED_PDF_FILENAME);

    if($this->pdfFileExists()){
      header('Content-Disposition: attachment; filename="'.$this->pdfOutputFileName.'"');
      readfile($tmpFileName);
      unlink($tmpFileName);
    }

  }



  /**
  /* Helper Funktion - Existiert die PDF Datei aus der Session?
  */
  private function pdfFileExists(){
    $tmpFileName = $this->session->userdata($this->SESSIONKEY_GENERATED_PDF_FILENAME);
    return file_exists($tmpFileName);
  }



  /** 
  /* Erzeugt das PDF File und legt es im Filesystem + den Namen in der Session ab
  */
  private function generatePDF(){

    $templateFileName = $this->pdfTemplateFileName;
    $this->load->library('fpdf');
    $this->load->library('fpdi');

    $this->fpdi->AddPage();

    //Set the source PDF file
    $pagecount = $this->fpdi->setSourceFile($templateFileName);

    //Import the first page of the file
    $tpl = $this->fpdi->importPage(1);

    //Use this page as template
    $this->fpdi->useTemplate($tpl, 20, 30, 170);

    $this->fpdi->SetFont('Arial','',8);
    $this->fpdi->SetTextColor(0,0,0);
    $this->fpdi->SetXY(20, 20);
    //$this->fpdi->Rotate(90);
    //$this->fpdi->Image('think.jpg',120,240,20,20);
    //$this->fpdi->Image('think.jpg',120,260,20,20);
    $this->fpdi->Write(0, $this->session->userdata("pdfData_name")." ".$this->session->userdata("pdfData_email"));

    /*
    * Output Options
    * I: send the file inline to the browser. The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
    * D: send to the browser and force a file download with the name given by name.
    * F: save to a local file with the name given by name (may include a path).
    * S: return the document as a string. name is ignored.
    */
    $tmpFileName = "tmp/df_".md5(time()).".pdf";
    $this->fpdi->Output($tmpFileName, "F");

    $this->session->set_userdata($this->SESSIONKEY_GENERATED_PDF_FILENAME, $tmpFileName);

  }




}