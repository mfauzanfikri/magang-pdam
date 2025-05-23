<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateProposalsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'title'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'institution'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'is_group'     => ['type' => 'TINYINT', 'constraint' => 1],
            'leader_id'    => ['type' => 'INT', 'unsigned' => true],
            'file_path'    => ['type' => 'TEXT', 'null' => true],
            'status'       => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'rejected']],
            'note' => ['type' => 'TEXT', 'null' => true],
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
        $this->forge->addForeignKey('leader_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('proposals');
    }
    
    public function down()
    {
        $this->forge->dropTable('proposals');
    }
}
