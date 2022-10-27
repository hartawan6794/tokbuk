<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterDropEmail extends Migration
{
    public function up()
    {
        $fields = [
            'email_user'
        ];
        $this->forge->dropColumn('tbl_toko', $fields);
    }

    public function down()
    {
        //
    }
}
