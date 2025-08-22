<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_migrate_item_categories extends CI_Migration {

    public function up()
    {
        // Get all unique category strings from items
        $categories = [];
        $items = $this->db->select('item_id, category')->from('items')->where('category !=', '')->get()->result();
        foreach ($items as $item) {
            $cat = trim($item->category);
            if ($cat !== '' && !in_array($cat, $categories)) {
                $categories[] = $cat;
            }
        }

        // Insert unique categories into item_categories if not already present
        foreach ($categories as $cat) {
            $exists = $this->db->where('name', $cat)->get('item_categories')->row();
            if (!$exists) {
                $this->db->insert('item_categories', [
                    'name' => $cat,
                    'description' => '',
                    'deleted' => 0
                ]);
            }
        }

        // Update items to reference the new item_category_id
        foreach ($items as $item) {
            $cat = trim($item->category);
            $cat_row = $this->db->where('name', $cat)->get('item_categories')->row();
            if ($cat_row) {
                $this->db->where('item_id', $item->item_id)->update('items', [
                    'item_category_id' => $cat_row->id
                ]);
            }
        }
    }

    public function down()
    {
        // Optionally, reverse the migration: set items.category from item_categories.name
        $items = $this->db->select('item_id, item_category_id')->from('items')->where('item_category_id IS NOT NULL', null, false)->get()->result();
        foreach ($items as $item) {
            $cat_row = $this->db->where('id', $item->item_category_id)->get('item_categories')->row();
            if ($cat_row) {
                $this->db->where('item_id', $item->item_id)->update('items', [
                    'category' => $cat_row->name
                ]);
            }
        }
    }
} 