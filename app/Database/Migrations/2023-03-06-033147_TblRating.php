<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblRating extends Migration
{
    public function up()
    {
        $fields = [
            'id_rating' => [
                'type' => 'SMALLINT',
                // 'constraint' => 5,
                'auto_increment' => true
            ],
            'id_product' => [
                'type' => 'SMALLINT',
            ],
            'id_user_bio' => [
                'type' => 'SMALLINT',
            ],
            'rating' => [
                'type' => 'DOUBLE',
                'null' => true
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
        $this->forge->addPrimaryKey('id_rating');
        $this->forge->addForeignKey('id_product', 'tbl_product', 'id_product', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_user_bio', 'tbl_user_biodata', 'id_user_bio', 'CASCADE', 'CASCADE');
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('tbl_rating', true, $attributes);
    }

    public function down()
    {
        //
    }
}
