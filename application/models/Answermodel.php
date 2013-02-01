<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

Class Answermodel extends App_Model{

  private $tableName = "answers";

  public function __construct(){
    parent::__construct();
  }

  /**
  /* If id is present, get answer with od, otherwiese return all answers
  /* im limit is present, limit the result
  */
  public function getAnswer($id = 0, $limit = 0, $offset = 0){
    
    $this->db->select('*');
    $this->db->from($this->tableName);

    if($id > 0){
      $this->db->where('id', $id); 
    }

    if($limit > 0){
      $this->db->limit($limit, $offset); 
    }

    return $this->db->get()->result_array();
    
  }

}

