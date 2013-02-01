<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

Class Questionmodel extends CI_Model{

  private $tableName = "questions";

  public function __construct(){
    parent::__construct();
  }


  /**
  /* get one or mor questions with full data
  /* get all answers for questions and if present for user ip
  */
  public function getQuestion($slug = "", $ip = "", $limit = 0, $order, $offset = 0){
    
    $this->db->select('*');
    $this->db->from($this->tableName);
    if($slug != ""){
      $this->db->where('slug', $slug);
    }

    if($ip != ""){
      $this->db->where('ip', $ip); 
    }

    if($limit > 0){
      $this->db->limit($limit, $offset); 
    }

    if(!$order){
      $this->db->order_by("id", "desc"); 
    }

    $tmpResult = $this->db->get()->result_array();

    //echo($this->db->last_query());

    if(!$tmpResult){
      return false;
    }

    $result = Array(); 

    foreach($tmpResult as $question){

      $countVotes = 0; //complete count of votes for this question
      $slug = $question["slug"]; //the question's slug

      $result[$slug] = $question;

      $result[$slug]["votes"] = $this->Votingmodel->getVotes($question["id"]);

      //Already Voted?
      $result[$slug]["userVoted"] = $this->Votingmodel->getAlreadyVoted($question["id"]);

    }

    if(count($result) == 1){
      foreach($result as $key => $questionData){
        $result = $questionData;
      }
    }
    
    //preprint($result);

    return $result;

  }



  




  /**
  /* get a list of random questions
  /* Exlude questions with slugs found in $arExludeSlugs
  /* im limit is present, limit the result
  */
  function getRandomQuestion($slug, $limit = 10){
    
    $this->db->select('*');
    $this->db->from($this->tableName);
    $this->db->order_by("RAND()"); 
    if(count(arExludeSlugs)){
      $this->db->where("slug <>", $slug);
    }
    $this->db->limit($limit, $offset); 

    $tmpResult = $this->db->get()->result_array();
    $result = Array();

    foreach($tmpResult as $question){
      $result[$question["slug"]] = $question;
      $result[$question["slug"]]["userVoted"] = $this->Votingmodel->getAlreadyVoted($question["id"]);
    }
    
    return $result;
  }



  /**
  /* get one or a list of questions without answerdata
  /* If slug is present, get question with slug, otherwiese return all questions
  /* im limit is present, limit the result
  */
  public function getQuestionSingle($slug = "", $limit = 0, $offset = 0){
    
    $this->db->select('*');
    $this->db->from($this->tableName);
    $this->db->order_by("id", "desc"); 

    if($slug){
      $this->db->where('slug', $slug); 
    }

    if($limit > 0){
      $this->db->limit($limit, $offset); 
    }

    $result = $this->db->get()->result_array();
    
    if(count($result)){
      return $result[0];
    }

    return false;

  }



  /**
  /* get the previous question in database
  /* careful! because of desc id ordering, previous is the one with higher id value (older)!
  */
  public function getPreviousQuestion($currentId = false){
    
    if(!$currentId){
      return false;
    }

    $this->db->select('*');
    $this->db->from($this->tableName);
    //$this->db->order_by("id", "desc"); 

    $this->db->where('id >', $currentId); 

    $this->db->limit(1); 

    $result = $this->db->get()->result_array();
    
    if(count($result)){
      return $result[0];
    }

    return false;

  }


  /**
  /* get the next question in database
  /* careful! because of desc id ordering, next is the one with less id value (newer)!
  */
  public function getNextQuestion($currentId = false){

    if(!$currentId){
      return false;
    }

    $this->db->select('*');
    $this->db->from($this->tableName);
    $this->db->order_by("id", "desc"); 

    $this->db->where('id <', $currentId); 

    $this->db->limit(1); 
    $result = $this->db->get()->result_array();

    if(count($result)){
      return $result[0];
    }

    return false;

  }



}

