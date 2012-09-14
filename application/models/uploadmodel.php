<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

Class Uploadmodel extends CI_Model{

  function getUploads($userID = 0){
    $this -> db -> select('*');
    $this -> db -> from('pdf_invoices');
    $this->db->join('pdf_invoices_types', 'pdf_invoices.type_id = pdf_invoices_types.id', 'left');
    $this -> db -> where('user_id = ' . "'" . $userID . "'");
    $this -> db -> order_by('pdf_invoices.id', 'DESC');

    $query = $this -> db -> get();

    return $query->result_array();
  }

  /**
  /* Rechnungstype als Assoc Array zurückliefern
  */
  function getInvoiceTypesAsArray(){
    
    $arReturn = Array();

    $this -> db -> select('*');
    $this -> db -> from('pdf_invoices_types');

    $query = $this -> db -> get();

    $arReturn[0] = "- Typ auswählen -";

    foreach($query->result_array() as $row){
      $arReturn[$row["id"]] = $row["name"];
    }

    return $arReturn;
  }



  /**
  /* Update upload field
  */
  public function update($field, $value, $md5, $userID){
    $arReturn = Array(
      "error" => FALSE,
      "message" => "Rechnung wurde aktualisiert",
    );

    if($field == "type_id" && $value <= 0){
      $arReturn["message"] = "";
    }

    $data = Array(
      $field => $value,
    );
    $this->db->where('user_id', $userID);
    $this->db->where('md5', $md5);
    $this->db->update('pdf_invoices', $data); 



    return $arReturn;
  }




  /**
  /* Update upload field
  */
  public function delete($md5, $userID){
    $arReturn = Array(
      "error" => FALSE,
      "message" => "Upload wurde gelöscht",
    );

    $this -> db -> select('*');
    $this -> db -> from('pdf_invoices');
    $this->db->where('user_id', $userID);
    $this->db->where('md5', $md5);
    $query = $this -> db -> get();
    $result =  $query->result_array();
    $row = $result[0];


    $file = "uploads/".$userID."/".$md5.".".pathinfo($row["origFileName"], PATHINFO_EXTENSION);
    unlink($file);

    $this->db->where('user_id', $userID);
    $this->db->where('md5', $md5);
    $this->db->delete('pdf_invoices'); 

    return $arReturn;
  }



  /**
  /* Upload a user file
  */
  function upload($userID){

    $arReturn = Array(
      "error" => FALSE,
      "errorMessages" => Array(),
      "data" => Array()
    );
    

    if($userID <= 0){
      $arReturn["error"] = TRUE;
      $arReturn["errorMessages"] = Array("Benutzer nicht gefunden");
    }else{
      $md5 = md5(time());
      $config['upload_path'] = 'uploads/'.$userID;
      $config['allowed_types'] = 'gif|jpg|png|zip|doc|pdf|rar|bmp|jpeg';
      $config['max_size'] = '5000';
      $config['file_name'] = $md5;

      if(!file_exists($config['upload_path'])){
        mkdir($config['upload_path']);
      }

      $this->load->library('upload', $config);


      if ( !$this->upload->do_upload()){
        //Upload failed
        $arReturn["data"] = $this->upload->data();
        $arReturn["error"] = TRUE;
        $arReturn["errorMessages"] = $this->upload->display_errors();
        
      }else{
        //Upload Ok

        $arReturn["data"] = $this->upload->data();
        
        $data = array(
          'user_id' => $userID,
          'md5' => $md5,
          'origFileName' => $arReturn["data"]["client_name"],
        );

        $this->db->insert('pdf_invoices', $data); 

      }

      $arReturn["data"]["fullFileName"] = $config["upload_path"]."/".$arReturn["data"]["file_name"].$arReturn["data"]["file_extension"];

    }
    //preprint($arReturn["data"]);
    

    return $arReturn;

  }

}

