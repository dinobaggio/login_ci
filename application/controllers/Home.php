<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    
    public function __construct()
    {
        parent::__construct();
        if($this->session->user_login) {
            $user_login = json_decode($this->session->user_login);
            if ($user_login->is_admin) {
                header("Location:".base_url('admin'));
            }
        }
    }
    

    public function index()
    {
        $user_login = json_decode($this->session->user_login);
        if ($user_login) {
            $data['username'] = $user_login->username;
        }
        $data['title'] = 'Welcome';
        
        $this->load->view('user/v_home', $data);
    }

}

/* End of file Home.php */
