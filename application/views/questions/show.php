<div class="row">

  <div id="containerQuestion" class="eight columns">
    <? $this->load->view('/questions/_showQuestion', $data); ?>
  </div>
  
  <div id="containerRandomQuestions" class="four columns">
    <? $this->load->view('/questions/_randomQuestion', $data); ?>
  </div>
  
</div>
  