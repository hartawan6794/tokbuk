<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Models;
use CodeIgniter\Model;

class OrderModel extends Model {
    
	protected $table = 'tbl_order';
	protected $primaryKey = 'id_order';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['invoice', 'id_user_bio', 'id_rekening', 'bukti_order', 'noresi', 'tgl_order', 'sub_total', 'sub_total_pengiriman', 'total_pembayaran', 'validasi', 'created_at', 'updated_at'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    

	public function invoice_no()
    {
        $sql = "SELECT MAX(MID(invoice,9,4)) AS invoice_no 
                FROM tbl_order
                WHERE MID(invoice,3,6) = DATE_FORMAT(CURDATE(),'%d%m%y')";
        $query = $this->db->query($sql);
        if($query){
            $row = $query->getResult();
            $n = ((int) $row[0]->invoice_no) +1;
            $no = sprintf("%'.04d", $n);
        }else{
            $no = "0001";
        }
        $invoice = "TB".date('dmy').$no;
        return $invoice;
    }
	
}