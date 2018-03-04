<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->model("user_model");
        $cookie = get_cookie('aplikasi_pos2');

        if(!$this->session->user_login) {
            if ($cookie) {
                $user = $this->user_model->get_by_cookie($cookie);
                $this->user_model->set_by_cookie($user);
                if(!(boolean) $user->is_admin) {
                    header("Location:".base_url('home'));
                }
            } else {
                header("Location:".base_url('user/login'));
            }
        } else {
            $user_login = json_decode($this->session->user_login);
            if (!$user_login->is_admin) {
                header("Location:".base_url('user/login'));
            }
        }
    }
    

    public function index()
    {
        $user_login = json_decode($this->session->user_login);
        $data['title'] = "Home Admin";
        $data['username'] = $user_login->username;
        $this->load->view('user/admin/v_home_admin', $data);
    }

}

/* End of file Admin.php */
