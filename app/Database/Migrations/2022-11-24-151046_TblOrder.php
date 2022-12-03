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
            'invoice' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true
            ],
            'id_user_bio' => [
                'type' => 'SMALLINT',
                // 'constraint' => 5,
                // 'auto_increment' => true
            ],
            'id_rekening' => [
                'type' => 'TINYINT',
            ],
            'bukti_order' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'noresi' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'tgl_order' => [
                'type'    => 'TIMESTAMP',
                // 'null' => true,
            ],
            'sub_total' => [
                'type'    => 'DECIMAL',
                // 'null' => true,
            ],
            'sub_total_pengiriman' => [
                'type'    => 'DECIMAL',
                // 'null' => true,
            ],
            'total_pembayaran' => [
                'type'    => 'DECIMAL',
                // 'null' => true,
            ],
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
        // $this->forge->addForeignKey('id_product','tbl_product','id_product','CASCADE','RESTRICT');
        $this->forge->addForeignKey('id_rekening','tbl_rekening','id_rekening','CASCADE','RESTRICT');
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('tbl_order', false, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_product');
    }
}
