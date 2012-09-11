<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

Class Usermodel extends CI_Model{

  function login($code){
   $this -> db -> select('*');
   $this -> db -> from('users');
   $this -> db -> where('code = ' . "'" . $code . "'");
   $this -> db -> limit(1);

   $query = $this -> db -> get();

   if($query -> num_rows() == 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
  }

  //Gibt alle Benutzerdaten aus der Datenbank zurück oder FALSE wenn Benutzer via Code nicht gefunden
  public function getUserdata($id){
    $this -> db -> select('*');
    $this -> db -> from('users');
    $this -> db -> where('id = ' . "'" . $id . "'");
    $this -> db -> limit(1);

    $query = $this -> db -> get();
    if($query -> num_rows() == 1){
      $arReturn = Array();
      $result = $query->result_array();

      foreach($result[0] as $key => $value){
        $arReturn[$key] = $value;
      }
      return $arReturn;
    }else{
      return false;
    }
  }


  public function updateData($input, $userID){
    $data = Array(
      "nachname" => $input->post("nachname"),
      "vorname" => $input->post("vorname"),
      "firma" => $input->post("firma"),
      "strasse" => $input->post("strasse"),
      "plz" => $input->post("plz"),
      "ort" => $input->post("ort"),
      "email" => $input->post("email"),
      "telefon" => $input->post("telefon"),
      "verkaufspreis" => $input->post("verkaufspreis"),
    );
    $this->db->where('id', $userID);
    $this->db->update('users', $data); 
  }



}

