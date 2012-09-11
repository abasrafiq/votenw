<h1>Individualisierung</h1>
<p>
  Geben Sie Ihren Namen und Ihre E-Mail Adresse ein, um Ihren personalisierten Gutschein herunter laden zu können.
</p>

<?php
if($pdfErrorMessages){
?>
<h3 class="error">Beim Erzeugen der PDF Datei sind Fehler aufgetreten:</h3>
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

  <?php
  //Feld
  //############################
  $fieldName = "nachname";
  $data = array(
    'name'        => $fieldName,
    'id'          => $fieldName,
    'value'       => $postVars[$fieldName],
    'maxlength'   => '100',
  );
  ?>

  <div class="formRow">
    <label for="<?php echo($fieldName); ?>">Nachname:</label>
    <?php
      echo form_input($data);
    ?>
  </div>




  <?php
  //Feld
  //############################
  $fieldName = "vorname";
  $data = array(
    'name'        => $fieldName,
    'id'          => $fieldName,
    'value'       => $postVars[$fieldName],
    'maxlength'   => '100',
  );
  ?>

  <div class="formRow">
    <label for="<?php echo($fieldName); ?>">Vorname:</label>
    <?php
      echo form_input($data);
    ?>
  </div>




  <?php
  //Feld
  //############################
  $fieldName = "firma";
  $data = array(
    'name'        => $fieldName,
    'id'          => $fieldName,
    'value'       => $postVars[$fieldName],
    'maxlength'   => '100',
  );
  ?>

  <div class="formRow">
    <label for="<?php echo($fieldName); ?>">Firma:</label>
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
    'value'       => $postVars[$fieldName],
    'maxlength'   => '100',
  );
  ?>

  <div class="formRow">
    <label for="<?php echo($fieldName); ?>">Straße:</label>
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
    'value'       => $postVars[$fieldName],
    'maxlength'   => '5',
  );
  ?>

  <div class="formRow">
    <label for="<?php echo($fieldName); ?>">PLZ:</label>
    <?php
      echo form_input($data);
    ?>
  </div>



  <?
  //Feld
  //############################
  $fieldName = "ort";
  $data = array(
    'name'        => $fieldName,
    'id'          => $fieldName,
    'value'       => $postVars[$fieldName]
  );
  ?>

  <div class="formRow">
    <label for="<?php echo($fieldName); ?>">Ort:</label>
    <?php
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
    'value'       => $postVars[$fieldName],
    'maxlength'   => '100',
  );
  ?>

  <div class="formRow">
    <label for="<?php echo($fieldName); ?>">E-Mail:</label>
    <?php
      echo form_input($data);
    ?>
  </div>



  <?php
  //SUBMIT BUTTON ADRESSDATEN
  //############################
  ?>
  <div class="formRow">
    <?php
      echo form_submit('btnSubmitAdressdaten', 'Adressdaten aktualisieren', 'class="btn-norm"');
    ?>
  </div>



  <?php
  //SUBMIT BUTTON PDF GENERIEREN
  //############################
  ?>
  <div class="formRow">
    <?php
      echo form_submit('btnSubmitForm', 'Gutschein erzeugen', 'class="btn-norm"');
    ?>
  </div>

<?php
echo form_close();
?>