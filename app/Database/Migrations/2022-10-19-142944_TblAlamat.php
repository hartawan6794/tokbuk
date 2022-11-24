<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblAlamat extends Migration
{
    public function up()
    {
        $fields= [
            'id_alamat'=>[
                'type'=>'SMALLINT',
                // 'constraint' => 5,
                'auto_increment' => true
            ],
            'id_user_bio'=>[
                'type'=>'SMALLINT',
                // 'constraint' => 5,
                // 'auto_increment' => true
                'null' => true,
            ],
            'nm_penerima' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'telp_penerima' => [
                'type' => 'VARCHAR',
                'constraint' => '15'
            ],
            'id_provinsi'=>[
                'type' => 'TINYINT',
                'constraint' => 3
            ],
            'provinsi'=>[
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'id_kabupaten'=>[
                'type' => 'TINYINT',
                'constraint' => 3
            ],
            'kabupaten'=>[
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'kecamatan'=>[
                'type' => 'VARCHAR',
                'constraint'=> 100
            ],
            'kelurahan' => [
                'type' => 'VARCHAR',
                'constraint'=> 150,
                // 'null' => true,
            ],
            'alamat_rumah'=> [ 
                'type' => 'VARCHAR',
                'constraint'=> 200,
                // 'null' => true,
            ],
            'postalcode' =>[
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 2,
                'null' => true
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null' => true
                // 'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'null' => true
                // 'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id_alamat');
        $this->forge->addForeignKey('id_user_bio','tbl_user_biodata','id_user_bio','CASCADE','RESTRICT');
        // $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('tbl_alamat',false);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_alamat', false);
    }
}
