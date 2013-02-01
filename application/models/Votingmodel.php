<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

Class Votingmodel extends App_Model{

  public function __construct(){
    parent::__construct();
  }

  /**
  /* gets all votes per qid
  /* im limit is present, limit the result
  */
  public function getVotes($qid = 0){
    
    if($qid == 0){
      die("no qid given for getVotes");
    }


    //prefill with all answers
    $this->db->select('*');
    $this->db->from("answers");

    $tmpResult = $this->db->get()->result_array();

    $result = Array();

    foreach($tmpResult as $answer){
      $result["votes"][$answer["id"]]["count"] = 0;

      //get answertitle

      $answer = $this->Answermodel->getAnswer($answer["id"]);
      $answer = $answer[0];
      $result["votes"][$answer["id"]]["title"] = $answer["title"];
      $result["votes"][$answer["id"]]["id"] = $answer["id"];

      
      $countAllVotes ++;
    }


    //Real count
    $this->db->select('*');
    $this->db->from("questions_users");
    $this->db->where('question_id', $qid);

    $tmpResult = $this->db->get()->result_array();

    $countAllVotes = 0;

    foreach($tmpResult as $singleVote){
      $result["votes"][$singleVote["answers_id"]]["count"] += 1;
      $countAllVotes ++;
    }
    $result["countAllVotes"] = $countAllVotes;

    //percentage
    foreach($result["votes"] as $key => $val){
      $result["votes"][$key]["percentage"] = round($val["count"] * 100 / $countAllVotes, 2);
    }

    //preprint($result);

    return $result;
    
  }

  /**
  /* Get already voted data for $qid
  /* @ return Array
  */
  function getAlreadyVoted($qid = false, $ip = ""){
    
    if(!$qid){
      die("no qid for already voted given");
    }

    if($ip == ""){
      $ip = $this->input->ip_address();
    }

    $this->db->select('*');
    $this->db->from("questions_users");
    $this->db->where("question_id", $qid);
    $this->db->where("user_ip", $ip);

    $result = $this->db->get()->result_array();

    if(count($result)){
      $result = $result[0];
      $result["alreadyVoted"] = true;
      $resultAnswer = $this->Answermodel->getAnswer($result["answers_id"]);
      $result["answer"] = $resultAnswer[0];
    }else{
      $result = Array("alreadyVoted" => false);
    }

    return $result;

  }


  /**
  /* saves a vote
  */
  public function save($qid, $answerId, $ip = ""){

    if($qid > 0 && $answerId > 0 && $ip != ""){

      $alreadyVoted = $this->Votingmodel->getAlreadyVoted($qid, $ip);
      if(!$alreadyVoted["alreadyVoted"]){
        //TO: Check if qid and answerId exist
        $answer = Array(
          "question_id" => $qid,
          "answers_id" => $answerId,
          "user_ip" => $ip
        );
        $this->db->insert("questions_users", $answer); 

        $this->arReturn["message"] = "Danke für Dein Voting zu";
      }else{
        $this->arReturn["error"] = true;
        $this->arReturn["messageCssClass"] = "error";
        $this->arReturn["message"] = "Fehler Sie haben bereits abgestimmt.";
      }

    }else{
      $this->arReturn["error"] = true;
      $this->arReturn["message"] = "Fehler beim Speichern";
      $this->arReturn["messageCssClass"] = "error";
    }

    return $this->arReturn;
  }

}

