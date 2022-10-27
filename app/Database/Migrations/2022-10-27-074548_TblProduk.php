<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblProduk extends Migration
{
    public function up()
    {
        $fields = [
            'id_product' => [
                'type' => 'SMALLINT',
                // 'constraint' => 5,
                'auto_increment' => true
            ],
            'id_toko' => [
                'type' => 'SMALLINT',
                // 'constraint' => '20'
            ],
            'judul_buku' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'nm_penerbit' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'nm_penulis' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'tahun_terbit' => [
                'type' => 'Varchar',
                'constraint' => '4',
                'null' => true,
            ],
            'jml_halaman' => [
                'type' => 'SMALLINT',
                'null' => true,
            ],
            'deskripsi_buku' => [
                'type' => 'varchar',
                'constraint' => 255,
                'null' => true,
            ],
            'id_kategori' => [
                'type' => 'TINYINT',
            ],
            'stok' => [
                'type' => 'SMALLINT',
                'null' => true
            ],
            'harga_buku' => [
                'type' => 'DECIMAL',
                'null' => true
            ],
            'imgproduct1' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ], 'imgproduct2' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ], 'imgproduct3' => [
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
        $this->forge->addPrimaryKey('id_product');
        $this->forge->addForeignKey('id_toko', 'tbl_toko', 'id_toko', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_kategori', 'tbl_kategori', 'id_kategori', 'CASCADE', 'CASCADE');
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('tbl_product', true, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_buku', true);
    }
}
