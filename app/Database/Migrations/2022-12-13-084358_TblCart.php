<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblCart extends Migration
{
    public function up()
    {
        $fields = [
            'id_cart' => [
                'type' => 'SMALLINT',
                // 'constraint' => 5,
                'auto_increment' => true
            ],
            'id_product' => [
                'type' => 'SMALLINT',
            ],
            'qty' => [
                'type' => 'SMALLINT',
                'null' => true
            ],
            'harga_buku' => [
                'type' => 'DECIMAL',
                'null' => true
            ],
            'total_harga' => [
                'type' => 'DECIMAL',
                'null' => true
            ],
            'id_user_bio' => [
                'type' => 'SMALLINT',
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
        $this->forge->addPrimaryKey('id_cart');
        $this->forge->addForeignKey('id_product', 'tbl_product', 'id_product', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_user_bio', 'tbl_user_biodata', 'id_user_bio', 'CASCADE', 'CASCADE');
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('tbl_cart', true, $attributes);

        
    }

    public function down()
    {
        //
    }
}
