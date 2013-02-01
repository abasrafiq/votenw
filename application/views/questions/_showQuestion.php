<div id="questionInner">

  <div class="containerVotes">

    <h2 class="questionTitle"><?= $questionData["title"]; ?></h1>
    <h3 class="questionDescription"><?= $questionData["description"]; ?></h2>
    
    <div class="row questionNav">
      <div class="six mobile-two columns questionNavPreviousQuestion">
        <? 
        if($previousQuestion){
          echo anchor("questions/show/#".$previousQuestion["slug"], "Vorherige Frage", Array("id" => "linkPreviousQuestion", "class" => "linkToQuestion questionNavigation", "rel" => $previousQuestion["slug"]));
        }
        ?>
      </div>

      <div class="six mobile-two columns questionNavNextQuestion">
      <? 
      if($nextQuestion){
        echo anchor("questions/show/#".$nextQuestion["slug"], "Nächste Frage", Array("id" => "linkNextQuestion", "class" => "linkToQuestion questionNavigation", "rel" => $nextQuestion["slug"]));
      }
      ?>
      </div>
      
    </div>

  
    <div class="row">
    
        <?
          foreach($questionData["votes"]["votes"] as $vote){
            if(!$showVoting){
        ?>
              <div class="voteRow">
                <button class="large button" value="<?= $vote["id"]; ?>"><?= $vote["title"]; ?> <?= $questionData["title"]; ?></button>
              </div>

        <?
            }else{
        ?>
              <div class="pieChartContainer">
                <div class="percentage chart" data-percent="<?= $vote["percentage"]; ?>">
                  <span><?= $vote["percentage"]; ?> %</span>
                </div>
                <div class="pieChartLabel"><?= $vote["title"]; ?> <?= $questionData["title"]; ?></div>
              </div>
              
        <?
            }
          }
        ?>
        
        <input type="hidden" name="slug" id="slug" value="<?= $questionData["slug"] ?>" />
        <input type="hidden" name="qid" id="qid" value="<?= $questionData["id"] ?>" />
        
    </div> 
    
    
    <div class="row">
      <div class="twelve columns">
        <? if($questionData["userVoted"]["alreadyVoted"] === true){ ?>
          <p class="alreadyVoted">Sie haben mit <span><?= $questionData["userVoted"]["answer"]["title"]; ?></span> abgestimmt</p>
        <? } ?>
      </div>
    </div>
    
    
  </div>

  You are <?= $userIP; ?>
  

 
</div>