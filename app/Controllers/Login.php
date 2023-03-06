<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserbiodataModel;

class Login extends BaseController
{

    public function __construct()
    {
        $this->user = new UserModel();
        $this->session = \Config\Services::session();
        $this->userbio = new UserbiodataModel();
    }

    public function index()
    {
        if(session()->get('isLogin')){
            return redirect()->to('home');
        }else{
            return view('login');
        }
    }

    public function prosses()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if ($username && $password) {
            // cek username
            $user = $this->user->where('username', $username)->first();
?>
            <link rel="stylesheet" href="<?= base_url() ?>/asset/css/sweetalert2-dark.min.css">
            <script src="<?= base_url() ?>/asset/js/sweetalert2.min.js"></script>

            <body>
            </body>
            <style>
                body {
                    font-family: "Helvetica Neue", Arial, Helvetica, sans-serif;
                    font-size: 1.124em;
                    font-weight: normal;
                }
            </style>
            <?php
            if ($user == null) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Username tidak ditemukan'
                    }).then((result) => {
                        if (result.value) {
                            window.location = "<?= site_url('login') ?>"
                        }
                    })
                </script>
                <?php
            } else {
                $userbio = $this->userbio->join('tbl_user tu','tu.nik_user = tbl_user_biodata.nik_user and tu.email_user = tbl_user_biodata.email_user')->where('tu.username', $username)->first();
                // cek password
                if ($this->checkPassword($password)) {
                    $session = [
                        'isLogin' => true,
                        'id_user_bio' => $userbio->id_user_bio,
                        'username' => $username,
                        'gambar' => $userbio->imguser,
                        'role' => $user->role,
                    ];
                    $this->session->set($session);
                ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Selamat',
                            text: 'Login berhasil'
                        }).then((result) => {
                            if (result.value) {
                                window.location = "<?= site_url() ?>"
                            }
                        })
                    </script>
                <?php
                } else {
                ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Password Salah'
                        }).then((result) => {
                            if (result.value) {
                                window.location = "<?= site_url('login') ?>"
                            }
                        })
                    </script>
                <?php
                }
            }
        }
    }

    function checkPassword($password)
    {
        $user = $this->user->where('password', md5($password))->first();
        if ($user)
            return true;
        else
            return false;
    }

    function logout(){
        $this->session->destroy();
        return redirect()->to('login');
    }
}
