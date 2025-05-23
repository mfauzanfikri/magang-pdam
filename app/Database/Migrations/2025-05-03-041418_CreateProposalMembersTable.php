<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProposalMembersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'proposal_id' => ['type' => 'INT', 'unsigned' => true],
            'user_id'     => ['type' => 'INT', 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('proposal_id', 'proposals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('proposal_members');
    }
    
    public function down()
    {
        $this->forge->dropTable('proposal_members');
    }
}
