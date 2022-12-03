<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblOrderDetail extends Migration
{
    public function up()
    {
        $fields = [
            'id_order_detail' => [
                'type' => 'SMALLINT',
                // 'constraint' => 5,
                'auto_increment' => true
            ],
            'id_order' => [
                'type' => 'SMALLINT',
                // 'constraint' => 5,
                // 'auto_increment' => true
            ],
            'id_product' => [
                'type' => 'SMALLINT',
                // 'constraint' => 5,
                // 'auto_increment' => true
            ],
            'harga_product' => [
                'type' => 'Double',
            ],
            'qty' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'total' => [
                'type'    => 'DECIMAL',
                // 'null' => true,
            ]
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id_order_detail');
        $this->forge->addForeignKey('id_order','tbl_order','id_order','CASCADE','CASCADE');
        $this->forge->addForeignKey('id_product','tbl_product','id_product','CASCADE','CASCADE');
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('tbl_order_detail', false, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_order_detail');
    }
}
