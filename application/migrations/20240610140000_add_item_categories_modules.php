<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_item_categories_modules extends CI_Migration {
    public function up()
    {
        // Add modules
        $modules = [
            [
                'name_lang_key' => 'module_item_categories',
                'desc_lang_key' => 'module_item_categories_desc',
                'sort' => 25,
                'module_id' => 'item_categories',
            ],
            [
                'name_lang_key' => 'module_item_subcategories',
                'desc_lang_key' => 'module_item_subcategories_desc',
                'sort' => 26,
                'module_id' => 'item_subcategories',
            ],
        ];
        foreach ($modules as $module) {
            $exists = $this->db->where('module_id', $module['module_id'])->get('modules')->row();
            if (!$exists) {
                $this->db->insert('modules', $module);
            }
        }
        // Add permissions
        foreach ($modules as $module) {
            $perm_exists = $this->db->where('permission_id', $module['module_id'])->get('permissions')->row();
            if (!$perm_exists) {
                $this->db->insert('permissions', [
                    'permission_id' => $module['module_id'],
                    'module_id' => $module['module_id'],
                ]);
            }
        }
        // Add grants for admin (person_id 1) for both home and office
        foreach ($modules as $module) {
            foreach (['home', 'office'] as $menu_group) {
                $grant_exists = $this->db->where(['permission_id' => $module['module_id'], 'person_id' => 1, 'menu_group' => $menu_group])->get('grants')->row();
                if (!$grant_exists) {
                    $this->db->insert('grants', [
                        'permission_id' => $module['module_id'],
                        'person_id' => 1,
                        'menu_group' => $menu_group,
                    ]);
                }
            }
        }
    }
    public function down()
    {
        $module_ids = ['item_categories', 'item_subcategories'];
        $this->db->where_in('module_id', $module_ids)->delete('modules');
        $this->db->where_in('permission_id', $module_ids)->delete('permissions');
        $this->db->where_in('permission_id', $module_ids)->delete('grants');
    }
} 