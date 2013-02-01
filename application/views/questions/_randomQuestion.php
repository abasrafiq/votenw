<div class="als-container randomQuestionWrapper">

  <h2>Zufällig</h2>
    
  <span class="als-prev"><img src="<?= base_url(); ?>_assets/images/randomQuestionPrev.png" alt="prev" title="previous" /></span>
  <div class="als-viewport">
    <ul class="als-wrapper">
    <?
    foreach($randomQuestions as $randomQuestion){
      //Already voted?
      $cssClassAlreadyVoted = "";
      $alreadyVoted = $randomQuestion["userVoted"]["alreadyVoted"];
      if($alreadyVoted){
        $cssClassAlreadyVoted = " alreadyVoted";
      }

    ?>
      <li class="als-item<?= $cssClassAlreadyVoted ?>">
        <a href="<?= base_url(); ?>questions/show/#<?= $randomQuestion["slug"]; ?>" class="linkToQuestion" rel="<?= $randomQuestion["slug"]; ?>">
          <?= $randomQuestion["title"]; ?>
        </a>
      </li>
    <?
      }
      
    ?>
    </ul>
    
  </div>

  <span class="als-next"><img src="<?= base_url(); ?>_assets/images/randomQuestionNext.png" alt="next" title="next" /></span>
  
</div>