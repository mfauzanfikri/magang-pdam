<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateCertificatesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'       => ['type' => 'INT', 'unsigned' => true],
            'final_report_id'   => ['type' => 'INT', 'unsigned' => true],
            'file_path'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
                'on_update' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('final_report_id', 'final_reports', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('certificates');
    }
    
    public function down()
    {
        $this->forge->dropTable('certificates');
    }
}
