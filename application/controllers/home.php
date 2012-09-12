<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Home extends APP_Controller {
  
  private $postData = Array();
  private $pdfWasGenerated = FALSE;
  private $SESSIONKEY_GENERATED_DOWNLOAD_FILENAME  = "generatedPDFFileName";
  private $downloadFileName = "Templates.zip";

  public function __construct() {
    parent::__construct();

    $this->load->model("Usermodel");

  }
  
  public function index() {

    $this->data["error"] == FALSE;

    $this->load->model("Validationmodel");
    $this->Validationmodel->validateGenPdfForm($this->input);
    

    if($this->input->post("btnSubmitForm") != "" || $this->input->post("btnSubmitAdressdaten") != ""){
      //Formular wurde gesendet Daten speichern egal ob Adfressdaten aktualisieren oder Gutschein erzeugen
      $this->postData = $this->Validationmodel->getPostVars($this->input);
      $this->data["userData"] = $this->postData;
      //Formular wurde abgesendet
      if($this->Validationmodel->isValid()){
        
        //Validierung OK, Userdaten speichern
        
        $this->Usermodel->updateData($this->input, $this->userdata["id"]);
        $this->session->set_userdata($this->postData);

        if($this->input->post("btnSubmitForm") != ""){
          $this->downloadWasGenerated = TRUE;
          $this->generateDownload();
        }elseif($this->input->post("btnSubmitAdressdaten") != ""){
          $this->session->set_flashdata("flashMessages", "Ihre Daten wurden aktualisiert");
          redirect("home");
        }
        
      }else{
        //Validation FEHLER
        $this->data["error"] = TRUE;
        $this->data["validationErrors"] = $this->Validationmodel->getValidationErrors();
      }

    }else{
      //Formular wurde nicht gesendet, Benutzerdaten laden
      $this->data["userData"] = $this->Usermodel->getUserdata($this->data["userdata"]["id"]);
    }

    if(!$this->downloadWasGenerated){
      //PDF wurde nicht erzeugt, Startseite/Formular anzeigen
      $this->layout->show('/home/index', $this->data);  
    }else{
      //Validierung Ok, PDF wurde erzeugt, Redirect zur direct Download Seite
      redirect("home/downloadGenerated");
    }
    
  }



  /** 
  /* Auf diese Seite wird weiter geleitet, wenn die Validierung Ok ist und der Download erzeugt wurde
  */
  public function downloadGenerated(){
    //PDF wurde erzeugt, View mit verstecktem iFrame zum automatischen Download und die Meldungen anzeigen
    if(!$this->downloadFileExists()){
      $this->data["error"] = TRUE;
    }
    $this->layout->show('/home/pdfGenerated', $this->data);
  }



  /**
  /* Der eigentliche Download, der über das versteckte iFrame aufgerufen wird 
  */
  public function pdfDownload(){
    
    $tmpFileName = $this->session->userdata($this->SESSIONKEY_GENERATED_DOWNLOAD_FILENAME );

    if($this->downloadFileExists()){
      header('Content-Disposition: attachment; filename="'.$this->downloadFileName.'"');
      readfile($tmpFileName);
      unlink($tmpFileName);
    }

  }



  /**
  /* Helper Funktion - Existiert die PDF Datei aus der Session?
  */
  private function downloadFileExists(){
    $tmpFileName = $this->session->userdata($this->SESSIONKEY_GENERATED_DOWNLOAD_FILENAME );
    return file_exists($tmpFileName);
  }



  /** 
  /* Erzeugt das PDF File und legt es im Filesystem + den Namen in der Session ab
  */
  private function generateDownload(){

    $countTemplates = 0;

    $this->load->model("Pdfmodel");
    $arTtmpPdfFileNames = Array(); //Alle erzeugten temporären PDF Dateien die wieder gelöscht werden

    $templates = $this->Pdfmodel->getData();

    

    //ZIP Datei erstellen
    $tmpZipFileName = "tmp/".md5(time()).".zip";
    $zip = new ZipArchive;
    $zipResource = $zip->open( $tmpZipFileName, ZipArchive::CREATE );
    if ($zipResource == FALSE){
      die("Konnte zip-Download nicht erzeugen");
    }

    foreach($templates as $template){
      $this->load->library('fpdf');
      $this->load->library('fpdi');
      //globale PDF Einstellungen
      $this->fpdi->SetFont('Arial','',8);
      $this->fpdi->SetTextColor(0,0,0);

      $countTemplates ++;

      $templateFileName = "uploads/_pdfTemplates/".$template["filename"];

      if(!file_exists($templateFileName)){
        die("Konnte Templatedatei nicht finden");
      }

      //Set the source PDF file
      $pagecount = $this->fpdi->setSourceFile($templateFileName);

      $this->fpdi->AddPage();

      //Import the first page of the file
      $tpl = $this->fpdi->importPage(1);

      //Use this page as template
      $this->fpdi->useTemplate($tpl, 0, 0);

      
      $this->fpdi->SetXY(20, 20);
      //$this->fpdi->Rotate(90);
      //$this->fpdi->Image('think.jpg',120,240,20,20);
      //$this->fpdi->Image('think.jpg',120,260,20,20);
      $this->fpdi->Write(0, $this->session->userdata("telefon")." ".$this->session->userdata("email"));



      /*
      * Output Options
      * I: send the file inline to the browser. The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
      * D: send to the browser and force a file download with the name given by name.
      * F: save to a local file with the name given by name (may include a path).
      * S: return the document as a string. name is ignored.
      */
      $tmpPdfFileName = "tmp/df_".md5(time())."_".$countTemplates.".pdf";
      //erzeugte temporäre Datei Ablegen, wird später gelöscht
      $arTtmpPdfFileNames[] = $tmpPdfFileName;
      $this->fpdi->Output($tmpPdfFileName, "F");
      $this->fpdi->Close();

      //PDF zu zip hinzufügen
      $zip->addFile($tmpPdfFileName);
      
    }

    $zip->close();

    
    //Alle temporären PDFs löschen
    foreach($arTtmpPdfFileNames as $tmpPdfFileName){
      unlink($tmpPdfFileName);
    }


    $this->Usermodel->updatePdfDownloaded($this->userdata["id"]);

    $this->session->set_userdata($this->SESSIONKEY_GENERATED_DOWNLOAD_FILENAME , $tmpZipFileName);

  }




}