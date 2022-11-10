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
        $data = $this->userModel->select()->findAll();
        return $this->response->setJson($data);
    }

    public function getOne()
    {

        $id = $this->request->getPostGet('id_user');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->user->where('id_user', $id)->first();

            return $this->response->setJSON($data);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {

        $response = array();

        $fields['nik_user'] = $this->request->getPostGet('nik_user');
        $fields['nm_user'] = $this->request->getPostGet('nm_user');
        $fields['email_user'] = $this->request->getPostGet('email_user');
        if ($fields['nik_user'] == '' || $fields['nik_user'] == null) {
            $response['success'] = false;
            $response['messages'] = "Data nik tidak boleh kosong";
        } else if ($fields['nm_user'] == '' || $fields['nm_user'] == null) {
            $response['success'] = false;
            $response['messages'] = "Data user tidak boleh kosong";
        } else if ($fields['email_user'] == '' || $fields['email_user'] == null) {
            $response['success'] = false;
            $response['messages'] = "Data email tidak boleh kosong";
        } else {
            if ($this->userModel->insert($fields)) {
                $response['success'] = true;
                $response['messages'] = "Data berhasil ditambah";
            } else {
                $response['success'] = false;
                $response['messages'] = "Data gagal ditambah";
            }
        }

        return $this->response->setJSON($response);
    }
}
