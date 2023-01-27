<?php

namespace App\Controllers;

use App\Models\TokoModel;
use TCPDF;

class Report extends BaseController
{

    public function __construct()
    {
		$this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->toko = new TokoModel();
    }

    public function index()
    {
        if ($this->session->get('isLogin')) {

            if ($this->session->get('username') == 'admin') {
                $data = [
                    'controller'        => 'Laporan',
                    'title'             => 'Laporan',
                    'toko'              => $this->toko->select('id_toko,nm_toko')->findAll()
                ];
                // var_dump($toko);die;
            } else {
                $data = [
                    'controller'        => 'Laporan',
                    'title'             => 'Laporan',
                ];
            }
            return view('laporan', $data);
        } else {
            return view('login');
        }
    }

    public function cetak()
    {
        
        $fields['awal'] = $this->request->getPost('date1');
        $fields['akhir'] = $this->request->getPost('date2');
        $fields['toko'] = $this->session->get('username') == 'admin' ? $this->request->getPost('toko') : $this->session->get('id_user_bio');

        $this->validation->setRules([
            'awal' => ['label'=>'awal','rules'=>'required','errors' => ['required' => 'Harap memasukan tanggal dengan benar']],
            'akhir' => ['label'=>'akhir','rules'=>'required','errors' => ['required' => 'Harap memasukan tanggal akhir']]
        ]);

        // var_dump($this->validation->getErrors());die;
        if ($this->validation->run($fields) == FALSE) {
            var_dump($this->validation->getErrors());die;
            $data = [
                'controller'        => 'Laporan',
                'title'             => 'Laporan',
                'toko'              => $this->toko->select('id_toko,nm_toko')->findAll(),
            ];
            session()->setFlashdata('error',$this->validation->getErrors());
            
            return view('laporan',$data);

		} else {
        //cek toko
        $toko = $fields['toko'] ? $this->toko->select('nm_toko')->where('id_toko',$fields['toko'])->findAll() : $this->toko->select('nm_toko')->findAll();
        // return $this->response->setJSON($toko);
        return view('cetak',[
            'awal' => $fields['awal'],
            'akhir' => $fields['akhir'],
            'toko' => $toko[0]->nm_toko
            ]);

            // print_r($html);
        }

		// $pdf = new TCPDF('L', PDF_UNIT, 'A5', true, 'UTF-8', false);

		// $pdf->SetCreator(PDF_CREATOR);
		// $pdf->SetAuthor('Dea Venditama');
		// $pdf->SetTitle('Invoice');
		// $pdf->SetSubject('Invoice');

		// $pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(false);

		// $pdf->addPage();

		// // output the HTML content
		// $pdf->writeHTML($html, true, false, true, false, '');
		// //line ini penting
		// $this->response->setContentType('application/pdf');
		// //Close and output PDF document
		// $pdf->Output('invoice.pdf', 'I');
    }
}
