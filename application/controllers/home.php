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
      header('Content-type: application/zip');
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

    require_once("application/libraries/fpdf/fpdf.php");
    require_once("application/libraries/fpdi/fpdi.php");
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

      $fpdf = new FPDF;
      $fpdi = new FPDI;

      //globale PDF Einstellungen
      $fpdi->SetFont('Arial','',8);
      $fpdi->SetTextColor(0,0,0);

      $countTemplates ++;

      $templateFileName = "uploads/_pdfTemplates/".$template["filename"];

      if(!file_exists($templateFileName)){
        die("Konnte Templatedatei nicht finden");
      }



      //Set the source PDF file
      $pagecount = $fpdi->setSourceFile($templateFileName);

      $fpdi->AddPage();

      //Import the first page of the file
      $tpl = $fpdi->importPage(1);

      //Use this page as template
      $fpdi->useTemplate($tpl, 0, 0);

      
      //START Einträge schreiben
      //Firma
      $fieldName = "firma";
      $fpdi->SetXY($template[$fieldName."_x"], $template[$fieldName."_y"]);
      $fpdi->Write(0, utf8_decode($this->session->userdata($fieldName)));

      //Telefon
      $fieldName = "telefon";
      $fpdi->SetXY($template[$fieldName."_x"], $template[$fieldName."_y"]);
      $fpdi->Write(0, utf8_decode($this->session->userdata($fieldName)));

      //Telefon
      $fieldName = "verkaufspreis";
      $fpdi->SetXY($template[$fieldName."_x"], $template[$fieldName."_y"]);
      $fpdi->Write(0, utf8_decode($this->session->userdata($fieldName))." ".iconv("UTF-8", "CP1252", "€"));


      /*
      * Output Options
      * I: send the file inline to the browser. The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
      * D: send to the browser and force a file download with the name given by name.
      * F: save to a local file with the name given by name (may include a path).
      * S: return the document as a string. name is ignored.
      */
      $tmpPdfFileName = "tmp/".$countTemplates."_".$this->userdata["id"].".pdf";
      //erzeugte temporäre Datei Ablegen, wird später gelöscht
      $arTtmpPdfFileNames[] = $tmpPdfFileName;
      $fpdi->Output($tmpPdfFileName, "F");
      $fpdi->Close();

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