<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblPengiriman extends Migration
{
    public function up()
    {
        $fields = [
            'id_pengiriman' => [
                'type' => 'TINYINT',
                'auto_increment' => true,
            ],
            'id_order' => [
                'type' => 'SMALLINT',
            ],
            'layanan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'no_resi' => [
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
        $this->forge->addPrimaryKey('id_pengiriman');
        $this->forge->addForeignKey('id_order','tbl_order','id_order','CASCADE','CASCADE');
        $this->forge->createTable('tbl_pengiriman', false);
    }

    public function down()
    {
        //
    }
}
