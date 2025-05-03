<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProposalsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'title'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'institution'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'is_group'     => ['type' => 'TINYINT', 'constraint' => 1],
            'status'       => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'rejected']],
            'leader_id'    => ['type' => 'INT', 'unsigned' => true],
            'created_at'   => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'updated_at'   => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
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
