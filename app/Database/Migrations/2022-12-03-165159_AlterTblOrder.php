<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTblOrder extends Migration
{
    public function up()
    {
        $fields = [
            'noresi'
        ];
        $this->forge->dropColumn('tbl_order', $fields);
    }

    public function down()
    {
        //
    }
}
