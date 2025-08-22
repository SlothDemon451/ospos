<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_item_categories_and_subcategories extends CI_Migration {

    public function up()
    {
        // Create item_categories table
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'deleted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('item_categories', TRUE);

        // Create item_subcategories table
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'deleted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (category_id) REFERENCES item_categories(id)');
        $this->dbforge->create_table('item_subcategories', TRUE);

        // Add columns to items table
        $fields = [
            'item_category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'after' => 'category' // Place after the old category field
            ],
            'item_subcategory_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'after' => 'item_category_id'
            ]
        ];
        $this->dbforge->add_column('items', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_table('item_subcategories', TRUE);
        $this->dbforge->drop_table('item_categories', TRUE);
        $this->dbforge->drop_column('items', 'item_category_id');
        $this->dbforge->drop_column('items', 'item_subcategory_id');
    }
} 