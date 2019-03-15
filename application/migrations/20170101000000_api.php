<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_api extends CI_Migration {

        public function up()
        {

			// api_error_log table
			$this->dbforge->add_field(array(
				'el_idx' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					 'auto_increment' => TRUE,
				),
				'el_ip' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'el_self' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'el_vars' => array(
					'type' => 'TEXT',
					'null' => true,
				),
				'el_result' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'el_regdate' => array(
					'type' => 'DATETIME',
                    'null' => true,
				),
			));
            $this->dbforge->add_key('el_idx', TRUE);
            $this->dbforge->create_table('api_error_log');					


			// api_input table
			$this->dbforge->add_field(array(
				'ai_idx' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					 'auto_increment' => TRUE,
				),
				'api_idx' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'default' => '0',
				),
				'ai_name' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'ai_type' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'ai_value' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'ai_ness' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'ai_exp' => array(
					'type' => 'TEXT',
					'null' => true,
				),
				'ai_sort' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'default' => '0',
				),
				'ai_bigo' => array(
					'type' => 'TEXT',
					'null' => true,
				),
			));
            $this->dbforge->add_key('ai_idx', TRUE);
            $this->dbforge->create_table('api_input');					



			// api_list table
			$this->dbforge->add_field(array(
				'api_idx' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					 'auto_increment' => TRUE,
				),
				'api_name' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'api_exp' => array(
					'type' => 'TEXT',
					'null' => true,
				),
				'api_url' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'api_method' => array(
					'type' => 'VARCHAR',
					'constraint' => '10',
					'default' => '',
				),
				'api_use' => array(
					'type' => 'TINYINT',
					'constraint' => 3,
					'unsigned' => TRUE,
					'default' => '1',
				),
				'api_bigo' => array(
					'type' => 'TEXT',
					'null' => true,
				),
			));
            $this->dbforge->add_key('api_idx', TRUE);
            $this->dbforge->create_table('api_list');					


			// api_output table
			$this->dbforge->add_field(array(
				'ai_idx' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					 'auto_increment' => TRUE,
				),
				'api_idx' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'default' => '0',
				),
				'ai_name' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'ai_type' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'ai_value' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'ai_ness' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'ai_exp' => array(
					'type' => 'TEXT',
					'null' => true,
				),
				'ai_sort' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'default' => '0',
				),
				'ai_bigo' => array(
					'type' => 'TEXT',
					'null' => true,
				),
			));
            $this->dbforge->add_key('ai_idx', TRUE);
            $this->dbforge->create_table('api_output');					
		
		}

        public function down()
        {
            $this->dbforge->drop_table('api_error_log');
            $this->dbforge->drop_table('api_input');
            $this->dbforge->drop_table('api_list');
            $this->dbforge->drop_table('api_output');
		}
}