<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTblUser extends Migration
{
    public function up()
    {
        $fields = [
            'nik_user' => [
                'type' => 'varchar',
                'constraint' => 100,
                // 'auto_increment' => true
            ],
            'email_user' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
        ];

        $this->forge->addColumn('tbl_user',$fields);
    }

    public function down()
    {
        //
    }
}
