<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblKategori extends Migration
{
    public function up()
    {
        $fields = [
            'id_kategori' => [
                'type' => 'TINYINT',
                'auto_increment' => true,
            ],
            'nama_kategori' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => '',
            ]
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id_kategori');
        $this->forge->createTable('tbl_kategori',false);
    }

    public function down()
    {
        //
    }
}
