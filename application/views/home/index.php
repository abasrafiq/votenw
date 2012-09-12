<h2>Ihre Stammdaten</h2>
<p>
  Bitte überprüfen Sie die von uns angegebenen Daten und geben Sie mögliche Änderungen bitte entsprechend ein. <span class="lightGray" id="pflichtangaben">(* = Pflichtfeld)</span>
</p>

<?php
if($pdfErrorMessages){
?>
<h3 class="error">Beim Erzeugen der PDF Dateien sind Fehler aufgetreten:</h3>
<ul class="error">
  <?php
    foreach($pdfErrorMessages as $error){
  ?>
    <li><?= $error ?></li>
  <?php
    }
  ?>
</ul>
<?
}
?>

<?php
if($validationErrors){
?>
<h3 class="error">Es sind Fehler aufgetreten:</h3>
<ul class="error">
  <?php
    foreach($validationErrors as $error){
  ?>
    <li><?= $error ?></li>
  <?php
    }
  ?>
</ul>
<?
}
?>


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

      <h2 id="h2Verkaufspreis">Ihr Verkaufspreis in Euro.*</h2>

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
      <label for="<?php echo($fieldName); ?>">E-Mail</label>
      <?php
        echo form_input($data);
      ?>
    </div>



    <?php
    //Feld
    //############################
    $fieldName = "verkaufspreis";
    $data = array(
      'name'        => $fieldName,
      'id'          => $fieldName,
      'value'       => $userData[$fieldName],
      'maxlength'   => '100',
    );
    ?>

    <div class="formRow">
      <?php
        echo form_input($data);
      ?>
    </div>



  </div>
</div>
<div class="clear"></div>

<div class="container_50_50">
  <div class="containerLeft">

    <?php
    //SUBMIT BUTTON ADRESSDATEN
    //############################
    ?>
    <div class="formRow">
    <?php
      echo form_submit('btnSubmitAdressdaten', 'Daten speichern', 'class="btn-norm"');
    ?>
    </div>

  </div>

  <div class="containerRight">

    <?php
    //SUBMIT BUTTON PDF GENERIEREN
    //############################
    ?>
    <div class="formRow">
      <?php
        echo form_submit('btnSubmitForm', 'PDFs generieren', 'class="btn-norm"');
      ?>
    </div>

  </div>
</div>

<?php
echo form_close();
?>