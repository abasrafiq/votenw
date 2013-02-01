<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Questions extends APP_Controller {
  
  public function __construct() {
    parent::__construct();
  }
  
  public function index(){
    $this->show();
  }

  public function ajaxShow($slug, $showVoting, $showPlain ) {
    $this->show($slug, $showVoting, $showPlain );
  }

  public function show($slug = "", $showVoting = 0, $showPlain = 0) {
    $this->data["questionData"] = $this->Questionmodel->getQuestion($slug, "", 1);
    
    $this->data["alreadyVoted"] = $this->data["questionData"]["userVoted"]["alreadyVoted"];
    if($this->data["alreadyVoted"]){
      $showVoting = true;
    }
    $this->data["showVoting"] = $showVoting;

    $this->data["randomQuestions"] = $this->Questionmodel->getRandomQuestion($slug);

    //preprint($this->data["questionData"]); 

    if(!$this->data["questionData"]){
      die("question not found");
    }
    
    /*
    $previousQuestion = $this->Questionmodel->getPreviousQuestion(1);
    preprint($previousQuestion);
    */
    $this->data["nextQuestion"] = $this->Questionmodel->getNextQuestion($this->data["questionData"]["id"]);
    $this->data["previousQuestion"] = $this->Questionmodel->getPreviousQuestion($this->data["questionData"]["id"]);

    if($showPlain){
      $this->layout->template("empty");
      $this->load->view('/questions/_showQuestion', $this->data);  
    }else{
      $this->layout->show('/questions/show', $this->data);  
    }

  }

  public function saveQuestion(){
    $qid = $this->input->post("qid");
    $voteId = $this->input->post("voteId");
    
    echo json_encode($this->Votingmodel->save($qid, $voteId, $this->input->ip_address()));

  }

  public function showRandomContainer($slug){
    $this->data["randomQuestions"] = $this->Questionmodel->getRandomQuestion($slug);
    $this->load->view('/questions/_randomQuestion', $this->data);
  }

}