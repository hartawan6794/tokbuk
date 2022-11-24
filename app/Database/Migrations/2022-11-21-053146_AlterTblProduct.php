<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTblProduct extends Migration
{
    public function up()
    {
        $field = [
            'berat_produk'=> [
                'type' => 'DOUBLE'
            ]
        ];
        $this->forge->addColumn('tbl_product',$field);
    }

    public function down()
    {
        //
    }
}
