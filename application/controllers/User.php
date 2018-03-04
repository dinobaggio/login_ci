<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $cookie = get_cookie('aplikasi_pos2');

        //$this->session->unset_userdata('user_login');
        //delete_cookie('aplikasi_pos2');

        if($this->session->user_login) {
            $user_login = json_decode($this->session->user_login);
            $is_admin = (boolean) $user_login->is_admin;
            if ($is_admin) {
                header("Location:".base_url('admin'));
            } else {
                header("Location:".base_url('home'));
            }
        } else {
            if ($cookie) {
                $user = $this->user_model->get_by_cookie($cookie);
                $this->user_model->set_by_cookie($user);
                if((boolean) $user->is_admin) {
                    header("Location:".base_url('admin'));
                } else {
                    header("Location:".base_url('home'));
                }
            }
        }
        
        
    }
    

    public function index()
    {
        
    }

    public function login () {
        
        $this->form_validation->set_rules(
            'username', 
            'Username', 
            'trim|required|alpha_numeric'
        );
        $this->form_validation->set_rules(
            'password', 
            'Password', 
            'trim|required'
        );

        if($this->form_validation->run() == false) {
            $value = $this->exists_inputan_login(
                $this->input->post('username'),
                $this->input->post('password')
            );
            
            $data = $this->data_form_login($value);
            $data['title'] = "Form Login";
            $this->load->view('user/login/v_login_form', $data);
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $remember_me = (boolean) $this->input->post('remember_me');
            $tugas = $this->user_model->cek_password($username, $password);

            if ($tugas) {
                if ($remember_me) {
                    $id_user = $this->user_model->cek_id_username($username);
                    $key = random_string('alnum', 64);
                    set_cookie('aplikasi_pos2', $key, 3600*24*30);
                    $tugas = $this->user_model->update_cookie($key, $id_user);
                    if ($tugas) {
                        $user = $this->user_model->get_user($id_user);
                        $session = '{
                            "id_user" : "'.$user->id_user.'",
                            "username" : "'.$user->username.'",
                            "is_admin" : "'.$user->is_admin.'"
                        }';
                        $this->user_model->daftar_session($session);
                        if ($user->is_admin) {
                            header("Location:".base_url('admin'));
                        } else {
                            header("Location:".base_url('home'));
                        }

                    } else {

                        $value = $this->exists_inputan_login($username, $password);
                        $data = $this->data_form_login($value);
                        $data['title'] = "Form Login";
                        $data['error'] = '* Kesalahan ketika login';
                        $this->load->view('user/login/v_login_form', $data);

                    }
                } else {
                    $id_user = $this->user_model->cek_id_username($username);
                    $user = $this->user_model->get_user($id_user);
                    
                    $session = '{
                        "id_user" : "'.$user->id_user.'",
                        "username" : "'.$user->username.'",
                        "is_admin" : "'.$user->is_admin.'"
                    }';
                    $this->user_model->daftar_session($session);
                    header("Location:".base_url('user'));
                }
            } else {
                $value = $this->exists_inputan_login($username, $password);
                $data = $this->data_form_login($value);
                $data['title'] = "Form Login";
                $data['error_password'] = '* Password salah';
                $this->load->view('user/login/v_login_form', $data);
            }
            
        }
    }

    public function register(){

        $this->form_validation->set_rules('username', 'Username', array(
            'trim',
            'required',
            'alpha_numeric',
            array(
                'username_exists',
                array($this->user_model, 'username_exists')
            )
        ));
        
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('confirm_pw', 'Konfirmasi Password', 'trim|required|matches[password]');

        $this->form_validation->set_message(array(
            'username_exists' => '* {field} Sudah ada',
            'required' => '* {field} Harap diisi',
            'matches' => '* {field} Tidak sama',
            'alpha_numeric' => '* {field} Hanya terdiri dari angka dan alfabet saja'

        ));

        if($this->form_validation->run() == false) {
            $value = $this->exists_inputan_register(
                $this->input->post('username'),
                $this->input->post('password'),
                $this->input->post('confirm_pw')
            );
            
            $data = $this->data_form_register($value);
            $data['title'] = "Form Register";
            $this->load->view('user/register/v_register_form', $data);
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $confirm_pw = $this->input->post('confirm_pw');
            $tugas = $this->user_model->create_user($username, $password);
            if ($tugas) {
                $data['title'] = "Registrasi Sukses";
                $this->load->view( 'user/register/v_register_sukses', $data);
            } else {
                $value = $this->exists_inputan_register($username, $password, $confirm_pw);
                $data = $this->data_form_register($value);
                $data['error'] = "* Error ketika mendaftarkan user!";
                $data['title'] = "Form Register";
                $this->load->view('user/register/v_register_form', $data);
            }
        }

    }

    public function logout() {
        $this->session->unset_userdata("user_login");
        delete_cookie("aplikasi_pos2");
        header("Location:".base_url('user/login'));
    }

    private function data_form_login($value) {
        $data['form_att'] = array(
            'id'=>'form_login',
        );
        $data['username_input'] = array(
            'type' => 'text',
            'name' => 'username',
            'placeholder' => 'Username',
            'value' => $value['username']
        );
        $data['password_input'] = array(
            'type' => 'password',
            'name' => 'password',
            'placeholder' => 'Password',
            'value' => $value['password']
        );
        $data['remember_input'] = array(
            'name' => 'remember_me',
            'value' => 'true',
            'checked' => false
        );
        $data['form_submit'] = array(
            'type' => 'submit',
            'value' => 'Login',
        );

        return $data;
    }

    private function data_form_register($value) {
        $data['form_att'] = array(
            'id' => 'form_register'
        );
        $data['username_input'] = array(
            'type' => 'text',
            'name' => 'username',
            'placeholder' => 'Username',
            'value' => $value['username']
        );
        $data['password_input'] = array(
            'type' => 'password',
            'name' => 'password',
            'placeholder' => 'Password',
            'value' => $value['password']
        );
        $data['confirm_pw'] = array(
            'type' => 'password',
            'name' => 'confirm_pw',
            'placeholder' => 'Confirm Password',
            'value' => $value['confirm_pw']
        );
        $data['form_submit'] = array(
            'type' => 'submit',
            'value' => 'Register',
        );
        return $data;
    }

    private function exists_inputan_register ($username = '', $password = '', $confirm_pw = '') {
        $value['username'] = '';
        $value['password'] = '';
        $value['confirm_pw'] = '';

        if (!empty($username)) {
            $value['username'] = $username;
        } 
        if (!empty($password)) {
            $value['password'] = $password;
        } 
        if (!empty($confirm_pw)) {
            $value['confirm_pw'] = $confirm_pw;
        }

        return $value;
    }

    private function exists_inputan_login ($username = '', $password = '') {
        $value['username'] = '';
        $value['password'] = '';

        if ($username) {
            $value['username'] = $username;
        } 
        if ($password) {
            $value['password'] = $password;
        }

        return $value;
    }


}

/* End of file Login.php */
