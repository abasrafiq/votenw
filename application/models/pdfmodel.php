<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

Class Pdfmodel extends CI_Model
{

  private $data = Array();

  public function __construct() {
    parent::__construct();

    //Preis 1 = Samsung Galaxy S III
    //Preis 2 = Galaxy Note 10.1

    $preis_y = 658;
    $preis_fontSize = 80;
    $wkz_fontSize = 20;
    $pricePostFix_fontSize = 50;
    $minusIndentPostfix = 12;//wkz und postfix (.-€ rüber ziehen, da Preis Leerzeichen schreibt um kompletten Block zu zentrieren)
    $cellWidth = 146;
    $pageWidth = 600; //mm
    $pageHeight = 847; //mm
    $middlePriceX = 227; //x-koordinate für Preisfeld in der Mitte (wird bei Template 1 und 3 verwendet)
    $adress_fontSize = 50;
    $info_fontSize = 12; //Font-Größe der ersten Infozeile
    $info_footer_fontSize = 11; //Font-Größe der Infozeile im Fußbereich

    //Alle vorhanden Templates mit Felddefintionen
    $arBase = Array(
        "pageWidth" => $pageWidth,
        "preis_fontSize" => $preis_fontSize,
        "wkz_fontSize" => $wkz_fontSize,
        "adress_fontSize" => $adress_fontSize,
        "info_footer_fontSize" => $info_footer_fontSize,
        "pricePostFix_fontSize" => $pricePostFix_fontSize,
        "minusIndentPostfix" => $minusIndentPostfix,
        "pageHeight" => $pageHeight,
        "cellWidth" => $cellWidth, //Preisezelle
    );

    //Vorlage 1
    $templateName = "Vorlage1";
    $this->data[$templateName] = Array(
        "filename" => $templateName.".pdf",
        "preis_gs3_x" => $middlePriceX, 
        "preis_gs3_y" => $preis_y,
    );
    $this->data[$templateName] = array_merge($this->data[$templateName], $arBase);
    
    
    //Vorlage 2
    $templateName = "Vorlage2";
    $this->data[$templateName] = Array(
        "filename" => $templateName.".pdf",
        "preis_gs3_x" => 133, 
        "preis_gs3_y" => $preis_y,
        "preis_gn101_x" => 323, 
        "preis_gn101_y" => $preis_y,
    );
    $this->data[$templateName] = array_merge($this->data[$templateName], $arBase);


    //Vorlage 3
    $templateName = "Vorlage3";
    $this->data[$templateName] = Array(
        "filename" => $templateName.".pdf",
        "preis_gn101_x" => 37, 
        "preis_gn101_y" => $preis_y,
        "preis_gt7_x" => $middlePriceX,
        "preis_gt7_y" => $preis_y,
        "preis_gt101_x" => 416,
        "preis_gt101_y" => $preis_y,
    );
    $this->data[$templateName] = array_merge($this->data[$templateName], $arBase);

  }

  public function getData(){
    return $this->data;
  }


}

