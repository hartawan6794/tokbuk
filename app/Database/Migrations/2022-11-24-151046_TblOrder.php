<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblOrder extends Migration
{
    public function up()
    {
        $fields = [
            'id_order' => [
                'type' => 'SMALLINT',
                // 'constraint' => 5,
                'auto_increment' => true
            ],
            'id_product' => [
                'type' => 'SMALLINT',
                // 'constraint' => 5,
                // 'auto_increment' => true
            ],
            'id_user_bio' => [
                'type' => 'SMALLINT',
                // 'constraint' => 5,
                // 'auto_increment' => true
            ],
            'jml_order' => [
                'type' => 'TINYINT',
                'constraint' => 3,
            ],
            'bukti_order' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'tgl_order' => [
                'type'    => 'TIMESTAMP',
                'null' => true,
            ],
            // 'id_alamat' => [
            //     'type' => 'SMALLINT',
            // ],
            'validasi' => [
                'type' => 'TINYINT',
                'constraint' => 3,
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
        $this->forge->addPrimaryKey('id_order');
        $this->forge->addForeignKey('id_user_bio','tbl_user_biodata','id_user_bio','CASCADE','RESTRICT');
        $this->forge->addForeignKey('id_product','tbl_product','id_product','CASCADE','RESTRICT');
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('tbl_order', false, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_product');
    }
}
