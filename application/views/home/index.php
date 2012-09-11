<h2>Ihre Stammdaten</h2>
<p>
  Bitte überprüfen Sie die von uns angegebenen Daten und geben Sie mögliche Änderungen bitte entsprechend ein.
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

<div class="container_50_50">
  <div class="containerLeft">


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
        'value'       => $postVars[$fieldName],
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
        'value'       => $postVars[$fieldName],
        'maxlength'   => '100',
      );
      ?>

      <div class="formRow">
        <label for="<?php echo($fieldName); ?>">Telefon *</label>
        <?php
          echo form_input($data);
        ?>
      </div>

      <h2>Ihr Verkaufspreis in Euro.*</h2>

  </div>





  <div class="containerRight">

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
      <label for="<?php echo($fieldName); ?>">Ansprechpartner</label>
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
      <label for="<?php echo($fieldName); ?>">PLZ / Ort *</label>
      <?php
        echo form_input($data);

        //Feld
        //############################
        $fieldName = "ort";
        $data = array(
          'name'        => $fieldName,
          'id'          => $fieldName,
          'value'       => $postVars[$fieldName]
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
      'value'       => $postVars[$fieldName],
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
      'value'       => $postVars[$fieldName],
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

<p class="lightGray">Die mit "*" gekennzeichneten Angaben, sind Pflichtfelder</p>


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