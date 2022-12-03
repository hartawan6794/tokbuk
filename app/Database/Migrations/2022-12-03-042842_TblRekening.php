<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblRekening extends Migration
{
    public function up()
    {
        $fields = [
            'id_rekening' => [
                'type' => 'TINYINT',
                'auto_increment' => true,
            ],
            'nm_bank' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'an_rekening' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => '',
            ],
            'no_rek' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null' => true,
                // 'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                // 'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id_rekening');
        $this->forge->createTable('tbl_rekening', false);
    }

    public function down()
    {
        $this->forge->dropTable('id_rekening',true);
    }
}
