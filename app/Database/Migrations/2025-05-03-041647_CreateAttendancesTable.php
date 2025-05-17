<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateAttendancesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'proposal_id' => ['type' => 'INT', 'unsigned' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'date' => ['type' => 'DATE'],
            'check_in' => ['type' => 'TIME', 'null' => true],
            'check_out' => ['type' => 'TIME', 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['unverified', 'verified', 'rejected']],
            'verified_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
                'on_update' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('proposal_id', 'proposals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('verified_by', 'users', 'id', 'CASCADE', 'CASCADE', 'verified_by_id');
        $this->forge->createTable('attendances');
    }
    
    public function down()
    {
        $this->forge->dropTable('attendances');
    }
}
