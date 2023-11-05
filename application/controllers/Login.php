<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('login/index');
        } else {
            $this->dologin();
        }
    }

    public function dologin()
    {
        $userEmail = $this->input->post('email');
        $password = $this->input->post('password');

        // Find the user by email
        $user = $this->db->get_where('tb_user', ['email' => $userEmail])->row_array();

        // If the user exists
        if ($user) {
            // Check the password
            if (password_verify($password, $user['password'])) {
                $data = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ];
                $userId = $user['id'];
                $this->session->set_userdata($data);

                // Check the user role
                if ($user['role'] == 'admin') {
                    $this->_updateLastLogin($userId);
                    redirect('admin/menu');
                } elseif ($user['role'] == 'sekretaris') {
                    $this->_updateLastLogin($userId);
                    redirect('surat');
                }
            } else {
                // Incorrect password
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"><b>Error:</b> Incorrect Password.</div>');
                redirect('/');
            }
        } else {
            // User not registered
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"><b>Error:</b> User Not Registered.</div>');
            redirect('/');
        }
    }

    private function _updateLastLogin($userId)
    {
        $sql = "UPDATE tb_user SET last_login = now() WHERE id = $userId";
        $this->db->query($sql);
    }

    public function logout()
    {
        // Destroy all sessions
        $this->session->sess_destroy();
        redirect(site_url('login'));
    }

    public function block()
    {
        $data = [
            'user' => infoLogin(),
            'title' => 'Access Denied!'
        ];
        $this->load->view('login/error404', $data);
    }
}
