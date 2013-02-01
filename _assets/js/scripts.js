Sammy(function() {
  
  this.get('#:slug', function() {
    slug = this.params.slug;
    showQuestion(slug);
  });

  this.get('', function() {
  });


 
}).run();



$(function() {
    initVoting();

    initAlsResponsive();

    initPieChart();

});


function initPieChart(){
  
}


function initAlsResponsive(){
  $(window).resize(function(){
    $(".als-viewport, .als-wrapper").css( {"width": "100%"} );
  });
}

function initVoting(){

  initVoteButton();

  initRandomQuestionSlider();

}


function showQuestion(slug){
  $("#questionInner").fadeOut("fast", function(){

    $.ajax({
      type: "POST",
      url: baseUrl + "questions/ajaxShow/" + slug + "/0/1"
    }).success(function( data ) {
      $("#containerQuestion").html(data);
      $("#questionInner").fadeIn();

      $('.chart').easyPieChart({
        //your configuration goes here
      });

      initVoteButton();
      updateRandomContainer();

    });

  });
}


function initRandomQuestionSlider(){

  $(".randomQuestionWrapper").als({
    visible_items: 6,
    scrolling_items: 1,
    orientation: "vertical",
    circular: "yes",
    autoscroll: "no"
  });
}

function updateRandomContainer(){
  var slug = $("#slug").val();
  $("#containerRandomQuestions").load(baseUrl + "questions/showRandomContainer/" + slug, function(){
    initRandomQuestionSlider();
  });
}


function showVotingResults(slug){
  $.ajax({
    type: "POST",
    url: baseUrl + "questions/show/" + slug + "/1/1"
  }).success(function( data ) {
    $("#containerQuestion").html(data);
    $("#questionInner").fadeIn();

    $('.chart').easyPieChart({
      //your configuration goes here
    });
    updateRandomContainer();

  });
}


function initVoteButton(){
  $(".voteRow .button").each(function(){

    $(this).on("click", function(e){

      e.preventDefault();

      var voteId = $(this).val();
      var qid = $('#qid').val();

      if(!voteId || voteId === undefined){
        var data = {};
        data.message = "Bitte Eine Antwort auswählen.";
        data.messageCssClass = "error";
        self.userMessage(data);
      }else{
        $.ajax({
          type: "POST",
          url: baseUrl + "questions/saveQuestion",
          data: { voteId: voteId, qid: qid },
          dataType: "json"
        }).success(function( data ) {
          
          if(!self.isAjaxResponseError(data)){
            self.userMessage(data);
            
            $("#questionInner").fadeOut("fast", function(){
              showVotingResults($("#slug").val());
            });
            
          }else{
          }
        });

      }
    });

  });
}

//Steht in data.error true, wird eine Fehlermeldung ausgegeben
function isAjaxResponseError(data){
  var self = this;

  if(data.error){
    self.userMessage(data);
    return true;
  }

  return false;
}

// Kurze Flash-Message an den Benutzer
function userMessage(data){
  var messageContainer = $("#messagesContainer");
  
  notyType = data.messageCssClass;
  if(notyType === ""){
    notyType = "success";
  }
  
  noty({
    text: data.message,
    dismissQueue: true,
    timeout: 1500,
    type: notyType

  });
}