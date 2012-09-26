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

    $this->data["error"] = FALSE;
    $this->data["pdfErrorMessages"] = Array();
    $this->data["validationErrors"] = Array();
    $this->downloadWasGenerated = FALSE;

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

  public function teilnahmebedingungen(){
    $this->layout->template("iframe");
    $this->layout->show('/home/teilnahmebedingungen', $this->data);  
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

    require_once("application/libraries/fpdf/cmykPdf.php");
    require_once("application/libraries/fpdi/fpdi.php");
    $this->load->model("Pdfmodel");

    $countTemplates = 0;
    $fontDir = dirname(__FILE__)."/../../fpdf_fonts/";
    //die($fontPath);
    define('FPDF_FONTPATH', $fontDir);
    
    //Adresse
    $textAdresse = $this->session->userdata("firma");
    $textAdresse.= ", ".$this->session->userdata("strasse");
    $textAdresse.= ", ".$this->session->userdata("plz");
    $textAdresse.= " ".$this->session->userdata("ort");
    
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

      $fpdi = new FPDI("P", "mm", Array($template["pageWidth"], $template["pageHeight"] ));


      //globale PDF Einstellungen
      $fpdi->AddFont('Arial', '', 'arial.php');
      $fpdi->AddFont('samsungimaginationmodernxbold', '', 'samsungimaginationmodernxbold.php');
      $fpdi->AddFont('samsungregular', '', 'samsungregular.php');

      

      $countTemplates ++;

      $templateFileName = "uploads/_pdfTemplates/".$template["filename"];

      if(!file_exists($templateFileName)){
        die("Konnte Templatedatei nicht finden");
      }

      //$pagecount = $fpdi->setSourceFile($templateFileName);

      $fpdi->AddPage();

      $fpdi->setSourceFile($templateFileName);

      //Import the first page of the file
      $tpl = $fpdi->importPage(1);

      //Use this page as template
      $fpdi->useTemplate($tpl);

      switch($template["filename"]){
        case "Vorlage1.pdf":
          $fpdi = $this->writePriceFieldAndReturnFpdi($fpdi, $template, "preis_gs3");
          break;

        case "Vorlage2.pdf":
          $fpdi = $this->writePriceFieldAndReturnFpdi($fpdi, $template, "preis_gs3");
          $fpdi = $this->writePriceFieldAndReturnFpdi($fpdi, $template, "preis_gn101");
          break;

        case "Vorlage3.pdf":
          $fpdi = $this->writePriceFieldAndReturnFpdi($fpdi, $template, "preis_gn101");
          $fpdi = $this->writePriceFieldAndReturnFpdi($fpdi, $template, "preis_gt7");
          $fpdi = $this->writePriceFieldAndReturnFpdi($fpdi, $template, "preis_gt101");
          break;
      }


      

      //Infozeile
      $fpdi->SetFont('samsungregular','', $template["info_fontSize"]);
      $fpdi->SetTextColor(0, 0, 0, 100);
      $textInfoline = "*Angebot gilt solange der Vorrat reicht und nur bei ";
      $textInfoline.= $textAdresse;
      $textInfoline.= "! Tippfehler und Irrtürmer vorbehalten!";
      $textInfoline = utf8_decode($textInfoline);
      $fpdi->SetXY(0, 733);
      $fpdi->Cell($template["pageWidth"], 0, $textInfoline."   ", 0, 2, "C"); 


      //Adresszeile
      $fpdi->SetFont('samsungimaginationmodernxbold','', $template["adress_fontSize"]);
      $fpdi->SetTextColor(0, 0, 0, 100);
      $fpdi->SetXY(0, 802);
      $fpdi->Cell($template["pageWidth"], 0, utf8_decode($textAdresse)."   ", 0, 2, "C"); 


      //Infozeile Footer
      /*
      $fpdi->SetFont('samsungregular','', $template["info_footer_fontSize"]);
      $fpdi->SetTextColor(0, 0, 0, 100);
      $textInfolineFooter = "Der Werbezeitraum ist bis 10. November 2012 festgelegt. Die WKZ-Ausschüttung erfolgt nach Einreichung eines Werbebelegs für mind. eines der aufgeführten Samsung Tablets im gültigen Werbezeitraum.";
      $textInfolineFooter = utf8_decode($textInfolineFooter);
      $fpdi->SetXY(0, 811);
      $fpdi->Cell($template["pageWidth"], 0, $textInfolineFooter."   ", 0, 2, "C"); 
      */


      /*
      * Output Options
      * I: send the file inline to the browser. The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
      * D: send to the browser and force a file download with the name given by name.
      * F: save to a local file with the name given by name (may include a path).
      * S: return the document as a string. name is ignored.
      */
      $tmpPdfFileName = "tmp/Anzeige_".$countTemplates."_".$this->userdata["id"].".pdf";
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


  private function writePriceFieldAndReturnFpdi($fpdi, $template, $priceFieldName){
    //Preis
    $fpdi->SetFont('samsungimaginationmodernxbold','', $template["preis_fontSize"]);
    $fpdi->SetTextColor(63, 53, 48, 20);
    $textPrice = utf8_decode($this->session->userdata($priceFieldName));
    $textWidthPrice = $fpdi->GetStringWidth($textPrice);
    $fpdi->SetXY($template[$priceFieldName."_x"], $template[$priceFieldName."_y"]);
    $fpdi->Cell($template["cellWidth"], 0, $textPrice."   ", 0, 2, "C"); 

    //Preisprefix
    $fpdi->SetFont('samsungregular','', $template["pricePostFix_fontSize"]);
    $textPricePostFix = ".-".iconv("UTF-8", "CP1252", "€");;
    $textWidthPricePostFix = $fpdi->GetStringWidth($textPricePostFix);
    $pricePostFixX = $template[$priceFieldName."_x"] + ($template["cellWidth"] / 2) + ($textWidthPrice / 2) - $template["minusIndentPostfix"];
    $fpdi->SetXY($pricePostFixX, $template[$priceFieldName."_y"] + 4); //TODO: flexible Y?
    $fpdi->Write(0, $textPricePostFix);

    //WKZ
    $fpdi->SetFont('samsungregular','', $template["wkz_fontSize"]);
    $textWkz = "wkz *";
    $textWidthWkz = $fpdi->GetStringWidth($textWkz);
    $wkzX = $template[$priceFieldName."_x"] + ($template["cellWidth"] / 2) + ($textWidthPrice / 2) + $textWidthPricePostFix - $textWidthWkz + 4 - $template["minusIndentPostfix"];
    $fpdi->SetXY($wkzX, $template[$priceFieldName."_y"] - 9); //TODO: flexible Y?
    $fpdi->Write(0, $textWkz);

    return $fpdi;
  }




}