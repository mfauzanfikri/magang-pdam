<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAttendancesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'proposal_id'  => ['type' => 'INT', 'unsigned' => true],
            'user_id'      => ['type' => 'INT', 'unsigned' => true],
            'date'         => ['type' => 'DATE'],
            'check_in'     => ['type' => 'TIME', 'null' => true],
            'check_out'    => ['type' => 'TIME', 'null' => true],
            'is_verified'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'   => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'updated_at'   => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('proposal_id', 'proposals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('attendances');
    }
    
    public function down()
    {
        $this->forge->dropTable('attendances');
    }
}
