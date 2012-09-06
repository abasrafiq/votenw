<h1>Ihre Einladung</h1>
<?
if($error){
?>
<h2>Fehler! Ihre Einladung konnte nicht erzeugt werden</h2>
<?
}else{
?>
  <p>Der Download Ihrer Einladung startet automatisch. Viel Spaß!</p>
  <iframe src="<?= base_url(); ?>home/pdfDownload"></iframe>
<?
}
?>
<a class="btn-norm" href="<?= base_url(); ?>home">Zurück zur Startseite</a>