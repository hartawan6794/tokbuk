<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblUserBiodata extends Migration
{
    public function up()
    {
        $fields = [
            'id_user_bio' => [
                'type' => 'SMALLINT',
                // 'constraint' => 5,
                'auto_increment' => true
            ],
            'nik_user' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],
            'nm_user' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'email_user' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'gender' => [
                'type' => 'ENUM("1","2")',
                'null' => true,
            ],
            'tanggal_lahir' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'tempat_lahir' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'telpon' => [
                'type' => 'varchar',
                'constraint' => 15,
                'null' => true,
            ],
            'alamat' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'imguser' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
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
        $this->forge->addPrimaryKey('id_user_bio');
        // $this->forge->addForeignKey('id_alamat','tbl_alamat','id_alamat','CASCADE','RESTRICT');
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('tbl_user_biodata', false, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_user_biodata');
    }
}
