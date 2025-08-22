<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Item_subcategory extends CI_Model
{
    public function exists($id)
    {
        $this->db->from('item_subcategories');
        $this->db->where('id', $id);
        return ($this->db->get()->num_rows() == 1);
    }

    public function get_total_rows()
    {
        $this->db->from('item_subcategories');
        $this->db->where('deleted', 0);
        return $this->db->count_all_results();
    }

    public function get_info($id)
    {
        if($id == -1)
        {
            // New item case
            $obj = new stdClass();
            foreach($this->db->list_fields('item_subcategories') as $field)
            {
                $obj->$field = '';
            }
            return $obj;
        }
        
        $this->db->from('item_subcategories');
        $this->db->where('id', $id);
        $this->db->where('deleted', 0);
        $query = $this->db->get();
        if($query->num_rows() == 1)
        {
            return $query->row();
        }
        else
        {
            $obj = new stdClass();
            foreach($this->db->list_fields('item_subcategories') as $field)
            {
                $obj->$field = '';
            }
            return $obj;
        }
    }

    public function get_all($rows = 0, $limit_from = 0, $no_deleted = FALSE)
    {
        $this->db->from('item_subcategories');
        if($no_deleted) $this->db->where('deleted', 0);
        $this->db->order_by('name', 'asc');
        if($rows > 0) $this->db->limit($rows, $limit_from);
        return $this->db->get();
    }

    public function get_multiple_info($ids)
    {
        $this->db->from('item_subcategories');
        $this->db->where_in('id', $ids);
        $this->db->order_by('name', 'asc');
        return $this->db->get();
    }

    public function get_by_category($category_id)
    {
        $this->db->from('item_subcategories');
        $this->db->where('category_id', $category_id);
        $this->db->where('deleted', 0);
        $this->db->order_by('name', 'asc');
        return $this->db->get();
    }

    public function save(&$data, $id = FALSE)
    {
        if(!$id || !$this->exists($id))
        {
            if($this->db->insert('item_subcategories', $data))
            {
                $data['id'] = $this->db->insert_id();
                return TRUE;
            }
            return FALSE;
        }
        $this->db->where('id', $id);
        return $this->db->update('item_subcategories', $data);
    }

    public function delete_list($ids)
    {
        $this->db->where_in('id', $ids);
        return $this->db->update('item_subcategories', array('deleted' => 1));
    }

    public function get_found_rows($search)
    {
        return $this->search($search, 0, 0, 'name', 'asc', TRUE);
    }

    public function search($search, $rows = 0, $limit_from = 0, $sort = 'name', $order = 'asc', $count_only = FALSE)
    {
        if($count_only)
        {
            $this->db->select('COUNT(id) as count');
        }
        $this->db->from('item_subcategories');
        $this->db->group_start();
        $this->db->like('name', $search);
        $this->db->or_like('description', $search);
        $this->db->group_end();
        $this->db->where('deleted', 0);
        if($count_only)
        {
            return $this->db->get()->row()->count;
        }
        $this->db->order_by($sort, $order);
        if($rows > 0) $this->db->limit($rows, $limit_from);
        return $this->db->get();
    }
} 