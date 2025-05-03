<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInternshipActivitiesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'proposal_id'  => ['type' => 'INT', 'unsigned' => true],
            'date'         => ['type' => 'DATE'],
            'description'  => ['type' => 'TEXT'],
            'created_at'   => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'updated_at'   => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('proposal_id', 'proposals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('internship_activities');
    }
    
    public function down()
    {
        $this->forge->dropTable('internship_activities');
    }
}
