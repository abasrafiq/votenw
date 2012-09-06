<h1>Individualisierung</h1>
<p>
  Geben Sie Ihren Namen und Ihre E-Mail Adresse ein, um Ihren personalisierten Gutschein herunter laden zu können.
</p>

<?php
if($pdfErrorMessages){
  preprint($pdfErrorMessages);
}
?>

<?php
if($validationErrors){
  preprint($validationErrors);
}
?>

<?php
echo form_open();
?>

<?php
  //Feld NAME
  //############################
  $fieldName = "name";
  $data = array(
    'name'        => $fieldName,
    'id'          => $fieldName,
    'value'       => $postVars["name"]."xxx",
    'maxlength'   => '100',
  );
  ?>

  <div class="formRow">
    <label for="<?php echo($fieldName); ?>">Ihr Name:</label>
    <?php
      echo form_input($data);
    ?>
  </div>



  <?
  //Feld E-MAIL
  //############################
  $fieldName = "email";
  $data = array(
    'name'        => $fieldName,
    'id'          => $fieldName,
    'value'       => $postVars["email"]."yyy"
  );
  ?>

  <div class="formRow">
    <label for="<?php echo($fieldName); ?>">Ihre E-Mail Adresse</label>
    <?php
      echo form_input($data);
    ?>
  </div>



  <?php
  //SUBMIT BUTTON
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