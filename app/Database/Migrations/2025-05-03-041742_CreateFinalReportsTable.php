<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFinalReportsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'proposal_id'   => ['type' => 'INT', 'unsigned' => true],
            'file_path'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'status'        => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'rejected'], 'default' => 'pending'],
            'created_at'    => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'updated_at'    => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('proposal_id', 'proposals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('final_reports');
    }
    
    public function down()
    {
        $this->forge->dropTable('final_reports');
    }
}
