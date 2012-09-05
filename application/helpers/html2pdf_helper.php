<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


    function html2pdf( $content = '', $filename = '', $mode = 'S', $papersize="letter", $orientation = "P" ){

        
        require_once("html2pdf/html2pdf.class.php");
        $html2pdf = new HTML2PDF( $orientation, $papersize, 'en' );

        $html2pdf->pdf->SetDisplayMode( 'fullpage' );

        //second param: show or not show
        $html2pdf->WriteHTML( $content, FALSE );

        // second paramter $mode:
		// '', FALSE, or 'I' :  header('Content-Type: application/pdf'); is sent to display the pdf inline.
		// TRUE or 'S': returns the pdf file contents to a variable
		// 'F': saves the pdf file to a directory

        return $html2pdf->Output( $filename, $mode );

    }