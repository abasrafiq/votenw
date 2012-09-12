<h2>Download Ihrer individuellen PDF Dateien</h>
<?
if($error){
?>
<p>Fehler! Ihr Download konnte nicht erzeugt werden</p>
<?
}else{
?>
  <p>Der Download Ihrer individuellen PDF-Dateien startet automatisch.</p>
  
  <iframe src="<?= base_url(); ?>home/pdfDownload"></iframe>
<?
}
?>
<div class="clear"></div>
<br />
  <a class="btn-norm" href="<?= base_url(); ?>login/logout">Logout</a>
