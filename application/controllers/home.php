<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Home extends APP_Controller {
  
  public function __construct() {
    parent::__construct();
  }
  
  public function index() {

    $question = $this->Questionmodel->getQuestion("api");
    //preprint($question);
    
    /*
    $previousQuestion = $this->Questionmodel->getPreviousQuestion(1);
    preprint($previousQuestion);
    */

    $this->data["nextQuestion"] = $this->Questionmodel->getNextQuestion(2);
    if($this->data["nextQuestion"]){
      preprint($this->data["nextQuestion"]);
    }else{
      echo("no next question<br />");
      echo($this->db->last_query());
    }

    $this->data["previousQuestion"] = $this->Questionmodel->getPreviousQuestion(1);
    if($this->data["previousQuestion"]){
      preprint($this->data["previousQuestion"]);
    }else{
      echo("<br />no previous question<br />");
      echo($this->db->last_query());
    }

    

    $this->layout->show('/home/index', $this->data);  
  }

}