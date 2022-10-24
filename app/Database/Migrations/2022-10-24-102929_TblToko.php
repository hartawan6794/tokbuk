<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblToko extends Migration
{
    public function up()
    {
        $fields = [
            'id_toko' => [
                'type' => 'SMALLINT',
                // 'constraint' => 5,
                'auto_increment' => true
            ],
            'id_user_bio' => [
                'type' => 'SMALLINT',
                // 'constraint' => 5,
                // 'auto_increment' => true
            ],
            'nm_toko' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],
            'alamat_toko' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'email_user' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'telpon' => [
                'type' => 'varchar',
                'constraint' => 15,
                'null' => true,
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
        $this->forge->addPrimaryKey('id_toko');
        $this->forge->addForeignKey('id_user_bio','tbl_user_biodata','id_user_bio','CASCADE','RESTRICT');
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('tbl_toko', false, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_toko',false);
    }
}
