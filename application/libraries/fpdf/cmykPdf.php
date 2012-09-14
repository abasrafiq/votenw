<?php
/*
    Author     : Fernando Herrero
    Mail       : fherrero[at]noticiasdenavarra.com
    Program    : pdf-cmyk.php
    License    : GPL v2
    Description: Allow to use CMYK color space:
                 SetDrawColor() => Set draw color to black
                 SetDrawColor(int gray) => value in % (0 = black, 100 = white)
                 SetDrawColor(int red, int green, int blue) => 0 to 255
                 SetDrawColor(int cyan, int magenta, int yellow, int black) => values in % (0 to 100)
                 SetFillColor and SetTextColor same as SetDrawColor
    Date       : 2004-01-22
*/
require('fpdf.php');

class cmykPDF extends FPDF {

    function SetDrawColor() {
        //Set color for all stroking operations
        switch(func_num_args()) {
            case 1:
                $g = func_get_arg(0);
                $this->DrawColor = sprintf('%.3f G', $g / 100);
                break;
            case 3:
                $r = func_get_arg(0);
                $g = func_get_arg(1);
                $b = func_get_arg(2);
                $this->DrawColor = sprintf('%.3f %.3f %.3f RG', $r / 255, $g / 255, $b / 255);
                break;
            case 4:
                $c = func_get_arg(0);
                $m = func_get_arg(1);
                $y = func_get_arg(2);
                $k = func_get_arg(3);
                $this->DrawColor = sprintf('%.3f %.3f %.3f %.3f K', $c / 100, $m / 100, $y / 100, $k / 100);
                break;
            default:
                $this->DrawColor = '0 G';
        }
        if($this->page > 0)
            $this->_out($this->DrawColor);
    }

    function SetFillColor() {
        //Set color for all filling operations
        switch(func_num_args()) {
            case 1:
                $g = func_get_arg(0);
                $this->FillColor = sprintf('%.3f g', $g / 100);
                break;
            case 3:
                $r = func_get_arg(0);
                $g = func_get_arg(1);
                $b = func_get_arg(2);
                $this->FillColor = sprintf('%.3f %.3f %.3f rg', $r / 255, $g / 255, $b / 255);
                break;
            case 4:
                $c = func_get_arg(0);
                $m = func_get_arg(1);
                $y = func_get_arg(2);
                $k = func_get_arg(3);
                $this->FillColor = sprintf('%.3f %.3f %.3f %.3f k', $c / 100, $m / 100, $y / 100, $k / 100);
                break;
            default:
                $this->FillColor = '0 g';
        }
        $this->ColorFlag = ($this->FillColor != $this->TextColor);
        if($this->page > 0)
            $this->_out($this->FillColor);
    }

    function SetTextColor() {
        //Set color for text
        switch(func_num_args()) {
            case 1:
                $g = func_get_arg(0);
                $this->TextColor = sprintf('%.3f g', $g / 100);
                break;
            case 3:
                $r = func_get_arg(0);
                $g = func_get_arg(1);
                $b = func_get_arg(2);
                $this->TextColor = sprintf('%.3f %.3f %.3f rg', $r / 255, $g / 255, $b / 255);
                break;
            case 4:
                $c = func_get_arg(0);
                $m = func_get_arg(1);
                $y = func_get_arg(2);
                $k = func_get_arg(3);
                $this->TextColor = sprintf('%.3f %.3f %.3f %.3f k', $c / 100, $m / 100, $y / 100, $k / 100);
                break;
            default:
                $this->TextColor = '0 g';
        }
        $this->ColorFlag = ($this->FillColor != $this->TextColor);
    }
}

//Example
/*
define('FPDF_FONTPATH', 'font/');
require('pdf-cmyk.php');

$pdf = new cmykPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 20);
$pdf->SetLineWidth(1);

$pdf->SetDrawColor(50, 0, 0, 0);
$pdf->SetFillColor(100, 0, 0, 0);
$pdf->SetTextColor(100, 0, 0, 0);
$pdf->Rect(10, 10, 20, 20, 'DF');
$pdf->Text(10, 40, 'Cyan');

$pdf->SetDrawColor(0, 50, 0, 0);
$pdf->SetFillColor(0, 100, 0, 0);
$pdf->SetTextColor(0, 100, 0, 0);
$pdf->Rect(40, 10, 20, 20, 'DF');
$pdf->Text(40, 40, 'Magenta');

$pdf->SetDrawColor(0, 0, 50, 0);
$pdf->SetFillColor(0, 0, 100, 0);
$pdf->SetTextColor(0, 0, 100, 0);
$pdf->Rect(70, 10, 20, 20, 'DF');
$pdf->Text(70, 40, 'Yellow');

$pdf->SetDrawColor(0, 0, 0, 50);
$pdf->SetFillColor(0, 0, 0, 100);
$pdf->SetTextColor(0, 0, 0, 100);
$pdf->Rect(100, 10, 20, 20, 'DF');
$pdf->Text(100, 40, 'Black');

$pdf->SetDrawColor(128, 0, 0);
$pdf->SetFillColor(255, 0, 0);
$pdf->SetTextColor(255, 0, 0);
$pdf->Rect(10, 50, 20, 20, 'DF');
$pdf->Text(10, 80, 'Red');

$pdf->SetDrawColor(0, 127, 0);
$pdf->SetFillColor(0, 255, 0);
$pdf->SetTextColor(0, 255, 0);
$pdf->Rect(40, 50, 20, 20, 'DF');
$pdf->Text(40, 80, 'Green');

$pdf->SetDrawColor(0, 0, 127);
$pdf->SetFillColor(0, 0, 255);
$pdf->SetTextColor(0, 0, 255);
$pdf->Rect(70, 50, 20, 20, 'DF');
$pdf->Text(70, 80, 'Blue');

$pdf->SetDrawColor(50);
$pdf->SetFillColor(0);
$pdf->SetTextColor(0);
$pdf->Rect(10, 90, 20, 20, 'DF');
$pdf->Text(10, 120, 'Gray');

$pdf->Output();
*/