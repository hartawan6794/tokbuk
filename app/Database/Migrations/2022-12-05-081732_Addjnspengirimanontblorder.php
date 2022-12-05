<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Addjnspengirimanontblorder extends Migration
{
    public function up()
    {
        $field = [
            'jns_pengiriman' => [
                'type' => 'varchar',
                'constraint' => 100,
            ]
        ];
        $this->forge->addColumn('tbl_order',$field);
    }

    public function down()
    {
        //
    }
}
