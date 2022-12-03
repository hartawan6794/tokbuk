<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserbiodataModel;
use App\Models\UserModel;

class UserApi extends BaseController
{

    public function __construct()
    {
        $this->userModel = new UserbiodataModel();
        $this->user = new UserModel();
    }

    public function index()
    {
        var_dump(date('dmy'));
        // $data = $this->userModel->select()->findAll();
        // return $this->response->setJson($data);
    }

    public function login()
    {
        $response = array();
        $fields['username'] = $this->request->getPostGet('username');
        $fields['password'] = $this->request->getPostGet('password');

        if ($fields['username'] == null || $fields['username'] == "") {
            $response['success'] = false;
            $response['mesagge'] = "Username belum di isi";
            // $response['data'] = [];
        } else {
            if ($fields['password'] == null || $fields['password'] == "") {
                $response['success'] = false;
                $response['mesagge'] = "Password belum di isi";
                // $response['data'] = [];
            } else {
                $data = $this->user->select('*')->join('tbl_user_biodata tub', 'tbl_user.email_user = tub.email_user AND tbl_user.nik_user = tub.nik_user')->where(['tbl_user.username' => $fields['username']])->where(['tbl_user.password' => md5($fields['password'])])->orWhere(['tbl_user.email_user' => $fields['username']])->where(['tbl_user.password' => md5($fields['password'])])->findAll();
                if ($data) {
                    $response['success'] = true;
                    $response['mesagge'] = "Berhasil login";
                    $response['data'] = $data;
                } else {
                    $response['success'] = false;
                    $response['mesagge'] = "username atau password salah";
                    $response['data'] = $data;
                }
            }
        }

        return $this->response->setJSON($response);
    }

    public function getOneUser()
    {
        $response = array();

        $type = $this->request->getPostGet('type');
        $user = $this->request->getPostGet('username');
        if($type == 'photo'){
            $id   = $this->request->getPostGet('id_user_bio');
            $data = $this->userModel->select('imguser')->where('id_user_bio',$id)->findAll();
        }else if($type == 'ubah'){
            $data = $this->user->join('tbl_user_biodata tub', 'tbl_user.email_user = tub.email_user AND tbl_user.nik_user = tub.nik_user')->where('tbl_user.username', $user)->findAll();
        }
        else {
            $data = $this->user->select('tbl_user.username,tbl_user.nik_user,tbl_user.email_user,tub.gender,tub.tanggal_lahir,tub.tempat_lahir,tub.telpon')->join('tbl_user_biodata tub', 'tbl_user.email_user = tub.email_user AND tbl_user.nik_user = tub.nik_user')->where('tbl_user.username', $user)->findAll();
        }

        if ($data) {
            $response['success'] = true;
            $response['messages'] = "Berhasil Mendapatakan Data";
            $response['data'] = $data;
        }else{
            $response['success'] = false;
            $response['messages'] = "Gagal Mendapatkan Data";
            $response['data'] = $data;
        }

        // print_r($data);
        return $this->response->setJSON($response);
    }

    public function register()
    {
        $response = array();
        $create = date('Y-m-d H:i:s');
        $fields['id_user_bio'] = $this->request->getPostGet('id_user_bio');
        $fields['nik_user'] = $this->request->getPostGet('nik_user');
        $fields['nm_user'] = $this->request->getPostGet('nm_user');
        $fields['email_user'] = $this->request->getPostGet('email_user');
        $fields['gender'] = $this->request->getPostGet('gender');
        $tgl_lahir = $this->request->getPostGet('tanggal_lahir');
        $fields['tempat_lahir'] = $this->request->getPostGet('tempat_lahir');
        $fields['telpon'] = $this->request->getPostGet('telpon');
        // $fields['alamat'] = $this->request->getPostGet('alamat');
        // $fields['imguser'] = $this->request->getFile('imguser');
        $fields['username'] = $this->request->getPostGet('username');
        $fields['pass'] = $this->request->getPostGet('pass');
        // $fields['role'] = $this->request->getPostGet('role');
        $fields['tanggal_lahir']  = date('Y-m-d', strtotime($tgl_lahir));

        $userbiodata = array(
            'nik_user' => $fields['nik_user'],
            'nm_user' => $fields['nm_user'],
            'email_user' => $fields['email_user'],
            'gender' => $fields['gender'],
            'tanggal_lahir' => $fields['tanggal_lahir'],
            'tempat_lahir' => $fields['tempat_lahir'],
            'telpon' => $fields['telpon'],
            'alamat' => $fields['alamat'],
            'created_at' => $create
        );

        $user = array(
            'username' => str_replace(" ", "", $fields['username']),
            'nik_user' => $fields['nik_user'],
            'email_user' => $fields['email_user'],
            'status' => 10,
            'password' => md5($fields['pass']),
            'role' => $fields['role'],
            'created_at' => $create
        );

        if ($this->userModel->insert($userbiodata)) {

            $this->user->insert($user);
            $response['success'] = true;
            $response['messages'] = "Data berhasil ditambah";
        } else {

            $response['success'] = false;
            $response['messages'] = "Data gagal ditambah";
        }

        return $this->response->setJSON($response);
    }

    public function upload(){
        
        $response = array();
		$fields['id_user_bio'] = $this->request->getPostGet('id_user_bio');
		$fields['imguser'] = $this->request->getFile('imguser');

        $data = $this->userModel->where('id_user_bio', $fields['id_user_bio'])->first();

        if ($fields['imguser']->getName() != '') {
            if($data->imguser != null || $data->imguser != '') {
                unlink('../public/img/user/' . $data->imguser);
            }
            $fileName = 'profile-' . $fields['imguser']->getRandomName();
            $userbiodata['imguser'] = $fileName;
            $fields['imguser']->move(WRITEPATH . '../public/img/user', $fileName);
        }
        if ($this->userModel->update($fields['id_user_bio'], $userbiodata)) {

            $response['success'] = true;
            $response['messages'] = "Data berhasil ditambah";
        } else {

            $response['success'] = false;
            $response['messages'] = "Data gagal ditambah";
        }
        
        return $this->response->setJSON($response);
    }

    public function edit(){
        $response = array();
        $create = date('Y-m-d H:i:s');
        $fields['id_user_bio'] = $this->request->getPostGet('id_user_bio');
        $fields['id_user'] = $this->request->getPostGet('id_user');
        $fields['nik_user'] = $this->request->getPostGet('nik_user');
        $fields['nm_user'] = $this->request->getPostGet('nm_user');
        $fields['email_user'] = $this->request->getPostGet('email_user');
        $fields['gender'] = $this->request->getPostGet('gender');
        $tgl_lahir = $this->request->getPostGet('tanggal_lahir');
        $fields['tempat_lahir'] = $this->request->getPostGet('tempat_lahir');
        $fields['telpon'] = $this->request->getPostGet('telpon');
        // $fields['alamat'] = $this->request->getPostGet('alamat');
        // $fields['imguser'] = $this->request->getFile('imguser');
        $fields['username'] = $this->request->getPostGet('username');
        $fields['pass'] = $this->request->getPostGet('pass');
        // $fields['role'] = $this->request->getPostGet('role');
        $fields['tanggal_lahir']  = date('Y-m-d', strtotime($tgl_lahir));

        $userbiodata = array(
            // 'nik_user' => $fields['nik_user'],
            'nm_user' => $fields['nm_user'],
            // 'email_user' => $fields['email_user'],
            'gender' => $fields['gender'],
            'tanggal_lahir' => $fields['tanggal_lahir'],
            'tempat_lahir' => $fields['tempat_lahir'],
            'telpon' => $fields['telpon'],
            // 'alamat' => $fields['alamat'],
            'updated_at' => $create
        );

        $user = array(
            // 'status' => 10,
            'password' => md5($fields['pass']),
            'updated_at' => $create
        );

        // $id = $this->user->where('id_user',$fields['id_user'])->first();


        if ($this->userModel->update($fields['id_user_bio'], $userbiodata)) {
            if($fields['pass'] != null || $fields['pass'] != ''){
                // $id = $this->user->where('nik_user',$fields['nik_user'])->first();
                $this->user->update( $fields['id_user'],$user);
            }
            $response['success'] = true;
            $response['messages'] = "Data berhasil diubah";
        } else {

            $response['success'] = false;
            $response['messages'] = "Data gagak diubah";
        }
        return $this->response->setJSON($response);
    }
}
