<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tbluser extends Migration
{
    public function up()
    {
        $fields = [
            'id_user' => [
                'type' => 'SMALLINT',
                'auto_increment' => true
            ],
            'nik_user' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => '4'
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => true
            ],
            'role' => [
                'type' => 'TINYINT',
                'constraint' => 2,
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null' => true,
                // 'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'null' => true,
                // 'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id_user');
        // $this->forge->addForeignKey('id_user_bio', 'tbl_user_biodata', 'id_user_bio', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('tbl_user', false);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_user', false);
    }
}
