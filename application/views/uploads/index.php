<h2>Laden Sie hier Ihre Rechnungen hoch </h2>
<p>
  Um eine Bezuschussung zu erhalten benötigen wir von Ihren gebuchten Anzeigen einen entprechenden Nachweis.
</p>

<div id="containerUploads">
  <?php if(!count($userUploads)){ ?>
    <p>Es liegen noch keine Uploads vor</p>
  <?php }else{ ?>

    <div id="containerTableUploads">
      <table id="tblUploads">
        
        <?php foreach($userUploads as $upload){ ?>
          <tr id="uploadRow_<?= $upload["md5"]; ?>">
            <td class="uploadIcon">
              <img src="<?= base_url(); ?>_assets/images/1pixtrans.gif" width="15" height="15" rel="<?= $upload["md5"]; ?>" />
            </td>
            <td class="fileName">
              <strong><?= truncate($upload["origFileName"], 17, "...".pathinfo($upload["origFileName"], PATHINFO_EXTENSION)); ?></strong>
            </td>
            <td class="type_id alignRight">
              <?= form_dropdown('type_id', $arInvoiceTypes, $upload["type_id"], "rel='".$upload["md5"]."'"); ?>
            </td>
            <td class="price">
              <?php

              //Feld
              //############################
              $fieldName = "price";
              $data = array(
                'name'        => $fieldName,
                'id'          => $fieldName,
                'value'       => $upload[$fieldName],
                'maxlength'   => '100',
                'rel'         => $upload["md5"]
              );
              ?>

              <?php
                echo form_input($data);
              ?>
            </td>
            <td class="priceInfo">
              <strong>Preis</strong><br />
              <span class="small">(netto)</span>
            </td>
          </tr>
        <?php } ?>
       
      </table>
    </div>

  <?php } ?>

  <?php
    $attributes = array('id' => 'frmUpload');
    echo form_open_multipart('upload', $attributes);
  ?>

  <div id="containerFileUpload">
    <div class="fileUpload">
      <?php
        //Feld
        //############################
        $fieldName = "userfile";
        $data = array(
          'name'        => $fieldName,
          'id'          => $fieldName,
          'size'        => 8,
        );
        echo form_upload($data);
      ?>
      
    </div>

    <input type="submit" name="btnSubmitUpload" id="btnSubmitUpload" class="btn-norm btn-small btn-beige floatRight" value="Hochladen" />
  </div>
  <?php echo form_close(); ?>

  <?php if($uploadData["error"] == TRUE){ ?>
    <span class="error" id="validationError">
      Beim Dateiupload sind Fehler aufgetreten.<br />
      Erlaubt sind die Formate gif, jpg, jpeg, bmp, png, zip, doc, pdf, rar
    </span>
  <?php } ?>

  <div id="uploadActionbuttons">
    <a class="btn-norm btn-back floatLeft" href="<?= base_url(); ?>home">Zurück</a>
    <a class="btn-norm floatRight" href="<?= base_url(); ?>login/logout">Logout</a>
  </div>

</div>