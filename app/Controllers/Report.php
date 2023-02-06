<?php

namespace App\Controllers;

use App\Models\TokoModel;
use TCPDF;
use FPDF;

class Report extends BaseController
{

    public function __construct()
    {
        $this->db = db_connect();
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


    public function getData($awal, $akhir, $toko = null)
    {
        if ($toko) {
            $sql = 'SELECT * FROM tbl_order_detail tod 
                    INNER JOIN tbl_order tor ON tor.id_order = tod.id_order
                    INNER JOIN tbl_user_biodata tub ON tub.id_user_bio = tor.id_user_bio 
                    left JOIN tbl_product tp ON tp.id_product = tod.id_product 
                    inner JOIN tbl_toko tt ON tp.id_toko = tt.id_toko 
                    WHERE 
                    tgl_order >= "' . $awal . '" AND tgl_order <= "' . $akhir . '" AND tt.id_toko = ' . $toko . '
                    #and kd_file = 1 AND validasi = 4 ';
            //    $sql .= $toko ? '':  'AND tt.id_toko = '.$toko;
        } else {
            $sql = 'SELECT * FROM tbl_order_detail tod 
                    INNER JOIN tbl_order tor ON tor.id_order = tod.id_order
                    INNER JOIN tbl_user_biodata tub ON tub.id_user_bio = tor.id_user_bio 
                    left JOIN tbl_product tp ON tp.id_product = tod.id_product 
                    inner JOIN tbl_toko tt ON tp.id_toko = tt.id_toko 
                    WHERE 
                    tgl_order >= "' . $awal . '" AND tgl_order <= "' . $akhir . '"
                    #and kd_file = 1 AND validasi = 4 ';
            //    $sql .= $toko ? '':  'AND tt.id_toko = '.$toko;
        }
        return $this->db->query($sql)->getResult();
    }


    // public function cetak()
    // {

    //     // $pdf = new FPDF();
    //     // $pdf->AliasNbPages();
    //     // $pdf->SetAutoPageBreak(1, 13);
    //     // $pdf->AddPage('P', 'A4');
    //     // $this->response->setHeader('Content-Type', 'application/pdf');
    //     // $pdf->Output('test.pdf', 'F');
    // }

    public function cetak()
    {

        $fields['awal'] = $this->request->getPost('date1');
        $fields['akhir'] = $this->request->getPost('date2');
        $fields['toko'] = $this->session->get('username') == 'admin' ? $this->request->getPost('toko') : $this->toko->select('id_toko')->where('id_user_bio', $this->session->get('id_user_bio'))->first()->id_toko;
        //data penjualan
        $fields['data'] = $this->getData($fields['awal'], $fields['akhir'], $fields['toko']);
        $total = 0;
        foreach ($fields['data'] as $data) {
            $total += $data->total;
        }
        //validation
        $this->validation->setRules([
            'awal' => ['label' => 'awal', 'rules' => 'required', 'errors' => ['required' => 'Harap memasukan tanggal dengan benar']],
            'akhir' => ['label' => 'akhir', 'rules' => 'required', 'errors' => ['required' => 'Harap memasukan tanggal akhir']]
        ]);
        if ($this->validation->run($fields) == FALSE) {
            $data = [
                'controller'        => 'Laporan',
                'title'             => 'Laporan',
                'toko'              => $this->toko->select('id_toko,nm_toko')->findAll(),
            ];
            session()->setFlashdata('error', $this->validation->getErrors());
            return view('laporan', $data);
        } else {
            //cek toko
            $toko = $fields['toko'] ? $this->toko->select('nm_toko')->where('id_toko', $fields['toko'])->findAll()[0]->nm_toko : 'Laporan Semua Toko';
            $html = view('cetak', [
                'awal' => $fields['awal'],
                'akhir' => $fields['akhir'],
                'toko' => $toko,
                'data' => $fields['data'],
                'total' => $total
            ]);
    
            $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
    
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Dea Venditama');
            $pdf->SetTitle('Invoice');
            $pdf->SetSubject('Invoice');
    
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
    
            $pdf->addPage();
    
            // output the HTML content
            $pdf->writeHTML($html, true, false, true, false, '');
            //line ini penting
            $this->response->setContentType('application/pdf');
            //Close and output PDF document
            $pdf->Output('invoice.pdf', 'I');

        }
    }
}
