<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VerifyLogin extends APP_Controller {

 function __construct()
 {
   parent::__construct();
   $this->load->model('Usermodel','',TRUE);
 }

 function index()
 {
   //This method will have the credentials validation
   $this->load->library('form_validation');
   $this->form_validation->set_rules('code', 'Code', 'trim|required|xss_clean|callback_check_database');
   //$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

   if($this->form_validation->run() == FALSE)
   {
     //Field validation failed.&nbsp; User redirected to login page
     $this->layout->show('login/index', $this->data);
   }
   else
   {
     //Go to private area
     redirect('home', 'refresh');
   }

 }

 function check_database()
 {
   //Field validation succeeded.&nbsp; Validate against database
   $code = $this->input->post('code');

   //query the database
   $result = $this->Usermodel->login($code);

   if($result)
   {
     $sess_array = array();
     foreach($result as $row)
     {
       $sess_array = array(
         'id' => $row->id,
         'username' => $row->username,
         'email' => $row->email,
         'vorname' => $row->vorname,
         'nachname' => $row->nachname,
         'code' => $row->code
       );
       $this->session->set_userdata('logged_in', $sess_array);
       
     }
     return TRUE;
   }
   else
   {
     $this->form_validation->set_message('check_database', 'Dieser Code wurde nicht gefunden');
     return false;
   }
 }
}
