<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Addkdfiletblorder extends Migration
{
    public function up()
    {
        $field = [
            'kd_file' => [
                'type' => 'TINYINT',
                'constraint' => 2,
            ]
        ];
        $this->forge->addColumn('tbl_order',$field);
    }

    public function down()
    {
        //
    }
}
