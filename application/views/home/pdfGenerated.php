<h2>Download Ihrer individuellen PDF Dateien</h>
<?
if($error){
?>
<p class="error">Fehler! Ihr Download konnte nicht erzeugt werden</p>
<?
}else{
?>
  <p>Der Download Ihrer individuellen PDF-Dateien startet automatisch.</p>
  
  <iframe id="iframeHiddenDownload" src="<?= base_url(); ?>home/pdfDownload"></iframe>
<?
}
?>
<div class="clear"></div>
<br />
<a class="btn-norm btn-back" href="<?= base_url(); ?>home">Zurück</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn-norm" href="<?= base_url(); ?>login/logout">Logout</a>
