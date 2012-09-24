<h2>Ihre Stammdaten</h2>
<p>
  Bitte überprüfen Sie die von uns angegebenen Daten und geben Sie mögliche Änderungen bitte entsprechend ein. <span class="lightGray" id="pflichtangaben">(* = Pflichtfeld)</span>
</p>




<?php
$formAttributes = array('id' => 'dfpdf_form');
echo form_open("", $formAttributes);
?>

<div class="container_50_50">
  <div class="containerLeft">


      <?php
      //Feld
      //############################
      $fieldName = "firma";
      $data = array(
        'name'        => $fieldName,
        'id'          => $fieldName,
        'value'       => $userData[$fieldName],
        'maxlength'   => '100',
      );
      ?>

      <div class="formRow">
        <label for="<?php echo($fieldName); ?>">Firmenname *</label>
        <?php
          echo form_input($data);
        ?>
      </div>



      <?php
      //Feld
      //############################
      $fieldName = "strasse";
      $data = array(
        'name'        => $fieldName,
        'id'          => $fieldName,
        'value'       => $userData[$fieldName],
        'maxlength'   => '100',
      );
      ?>

      <div class="formRow">
        <label for="<?php echo($fieldName); ?>">Straße *</label>
        <?php
          echo form_input($data);
        ?>
      </div>


      <?php
      //Feld
      //############################
      $fieldName = "telefon";
      $data = array(
        'name'        => $fieldName,
        'id'          => $fieldName,
        'value'       => $userData[$fieldName],
        'maxlength'   => '100',
      );
      ?>

      <div class="formRow">
        <label for="<?php echo($fieldName); ?>">Telefon *</label>
        <?php
          echo form_input($data);
        ?>
      </div>



      <?php
      //Feld
      //############################
      $fieldName = "www";
      $data = array(
        'name'        => $fieldName,
        'id'          => $fieldName,
        'value'       => $userData[$fieldName],
      );
      ?>

      <div class="formRow">
        <label for="<?php echo($fieldName); ?>">Internetadresse</label>
        <?php
          echo form_input($data);
        ?>
      </div>



      

  </div>





  <div class="containerRight">

    <?php
    //Feld
    //############################
    $fieldName = "ansprechpartner";
    $data = array(
      'name'        => $fieldName,
      'id'          => $fieldName,
      'value'       => $userData[$fieldName],
      'maxlength'   => '100',
    );
    ?>

    <div class="formRow">
      <label for="<?php echo($fieldName); ?>">Ansprechpartner *</label>
      <?php
        echo form_input($data);
      ?>
    </div>


    <?php
    //Feld
    //############################
    $fieldName = "plz";
    $data = array(
      'name'        => $fieldName,
      'id'          => $fieldName,
      'value'       => $userData[$fieldName],
      'maxlength'   => '5',
    );
    ?>

    <div class="formRow">
      <label for="<?php echo($fieldName); ?>">PLZ / Ort *</label>
      <?php
        echo form_input($data);

        //Feld
        //############################
        $fieldName = "ort";
        $data = array(
          'name'        => $fieldName,
          'id'          => $fieldName,
          'value'       => $userData[$fieldName]
        );
        echo form_input($data);

      ?>
    </div>


    <?php
    //Feld
    //############################
    $fieldName = "email";
    $data = array(
      'name'        => $fieldName,
      'id'          => $fieldName,
      'value'       => $userData[$fieldName],
      'maxlength'   => '100',
    );
    ?>

    <div class="formRow">
      <label for="<?php echo($fieldName); ?>">E-Mail Adresse *</label>
      <?php
        echo form_input($data);
      ?>
    </div>


    <?php
    //SUBMIT BUTTON ADRESSDATEN
    //############################
    ?>
    <div class="formRow" id="formRowSubmitAdressdaten">
      <label>&nbsp;</label>
      <?php
        echo form_submit('btnSubmitAdressdaten', 'Daten speichern', 'class="btn-norm btn-small"');
      ?>
    </div>



    

  


  </div>
</div>
<div class="clear"></div>

<?php
if($pdfErrorMessages){
?>
<p class="error">Beim Erzeugen der PDF Dateien sind Fehler aufgetreten.</p>
<?php
} 
?>

<div class="container_50_50" id="containerVerkaufspreis">

  <div class="containerLeft">
    <h2 class="h2Verkaufspreis">Preis / Galaxy Note 10.1</h2>
  </div>
  <div class="containerRight">

    <?php
    //Feld
    //############################
    $fieldName = "preis_gn101";
    $data = array(
      'name'        => $fieldName,
      'id'          => $fieldName,
      'value'       => $userData[$fieldName],
      'maxlength'   => '100',
      'class'       => 'verkaufspreis'
    );
    ?>
    <div class="formRow">
      <?php
        echo form_input($data);
      ?>
      Euro inkl. Mwst.
    </div>
  </div>

  <!--- -->
  <div class="containerLeft">
    <h2 class="h2Verkaufspreis">Preis / Galaxy Tab2 7.0</h2>
  </div>
  <div class="containerRight">

    <?php
    //Feld
    //############################
    $fieldName = "preis_gt7";
    $data = array(
      'name'        => $fieldName,
      'id'          => $fieldName,
      'value'       => $userData[$fieldName],
      'maxlength'   => '100',
      'class'       => 'verkaufspreis'
    );
    ?>
    <div class="formRow">
      <?php
        echo form_input($data);
      ?>
      Euro inkl. Mwst.
    </div>

  </div>

  <!--- -->
  <div class="containerLeft">
    <h2 class="h2Verkaufspreis">Preis / Galaxy Tab2 10.1</h2>
  </div>
  <div class="containerRight">

    <?php
    //Feld
    //############################
    $fieldName = "preis_gt101";
    $data = array(
      'name'        => $fieldName,
      'id'          => $fieldName,
      'value'       => $userData[$fieldName],
      'maxlength'   => '100',
      'class'       => 'verkaufspreis'
    );
    ?>
    <div class="formRow">
      <?php
        echo form_input($data);
      ?>
      Euro inkl. Mwst.
    </div>
  </div>


</div>


<div class="container_50_50" id="containerSubmitButtons">
  
  <div class="containerLeft">
    <div class="formRow">
        <a id="aRechnungHochladen" class="btn-norm btn-beige block" href="<?= base_url(); ?>upload">Upload Rechnung</a>
      </div>
  </div>

    <div class="frmRow" id="frmRowTeilnahmebedingungen">
      <input type="checkbox" value="1" name="chkTeilnahmebedingungen" />&nbsp;Ich akzeptiere die <a href="#">Teilnahmebedingungen</a>
    </div>

  <div class="containerLeft">
    <?php
    //SUBMIT BUTTON PDF GENERIEREN
    //############################
    ?>
    <div class="formRow">
      <?php
        echo form_submit('btnSubmitForm', 'PDFs generieren', 'class="btn-norm block" id="btnSubmitForm"');
      ?>
    </div>
  </div>

</div>



<?php
echo form_close();
?> 


<?php
if($validationErrors){
?>
<span class="error" id="validationError">
  Bitte füllen Sie alle Pflichtfelder aus, achten Sie auf eine korrekte E-Mail Adresse und akzeptieren Sie die Teilnahmebedingungen.
</span>
<?php
} 
?>

   