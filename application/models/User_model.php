<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function username_exists ($username) {
        $data = array('username' => $username);
        $query = $this->db->get_where('user', $data);
        $num_rows = $query->num_rows();

        switch($num_rows) {
            case 0 : 
            return true;
            break;
            default :
            return false;
        }
    }

    public function create_user($username, $password) {
        $data = array(
            'username' => $username,
            'password' => $this->hash_password($password),
            'is_admin' => '0',
            'created' => date('Y-m-j H:i:s')
        );
        return $this->db->insert('user', $data);
    }

    public function cek_password($username, $password) {
        $this->db->select('password');
        $this->db->from('user');
        $this->db->where('username', $username);

        $hash = $this->db->get()->row('password');

        return $this->varifikasi_hash_password($password, $hash);

    }

    public function cek_id_username ($username) {
        $this->db->select('id_user');
        $this->db->from('user');
        $this->db->where('username', $username);

        return $this->db->get()->row('id_user');
    }

    public function get_user ($id_user) {
        $query = $this->db->get_where('user', array(
            'id_user' => $id_user
        ));
        return $query->row();
    }

    public function update_cookie ($cookie, $id_user) {
        $data = array(
            'cookie' => $cookie
        );
        $this->db->where('id_user', $id_user);
        return $this->db->update('user', $data);
    }

    public function daftar_session ($data) {
        $this->session->set_userdata(array(
            "user_login" => $data
        ));
    }

    public function get_by_cookie ($cookie) {
        $data = array('cookie' => $cookie);
        $query = $this->db->get_where('user', $data);
        $user = $query->row();
        return $user;
    }

    public function set_by_cookie($user) {
        $session = '{
            "id_user" : "'.$user->id_user.'",
            "username" : "'.$user->username.'",
            "is_admin" : "'.$user->is_admin.'"
        }';
        $this->daftar_session($session);
    }
    
    private function hash_password($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    private function varifikasi_hash_password($password, $hash) {
        return password_verify($password, $hash);
    }

}

/* End of file User_model.php */
