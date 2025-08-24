<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Customer Type class
 */

class Customer_type extends CI_Model
{
	/*
	Determines if a given customer_type_id is a customer type
	*/
	public function exists($customer_type_id)
	{
		$this->db->from('customer_types');
		$this->db->where('customer_type_id', $customer_type_id);
		$this->db->where('deleted', 0);

		return ($this->db->get()->num_rows() == 1);
	}

	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('customer_types');
		$this->db->where('deleted', 0);

		return $this->db->count_all_results();
	}

	/*
	Returns all the customer types
	*/
	public function get_all($rows = 0, $limit_from = 0)
	{
		$this->db->from('customer_types');
		$this->db->where('deleted', 0);
		$this->db->order_by('name', 'asc');

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}

	/*
	Gets information about a particular customer type
	*/
	public function get_info($customer_type_id)
	{
		$this->db->from('customer_types');
		$this->db->where('customer_type_id', $customer_type_id);
		$this->db->where('deleted', 0);
		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $customer_type_id is NOT a customer type
			$customer_type_obj = new stdClass();

			//Get all the fields from customer_types table
			//append those fields to base parent object, we we have a complete empty object
			foreach($this->db->list_fields('customer_types') as $field)
			{
				$customer_type_obj->$field = '';
			}

			return $customer_type_obj;
		}
	}

	/*
	Gets customer type name
	*/
	public function get_name($customer_type_id)
	{
		$customer_type = $this->get_info($customer_type_id);
		return $customer_type->name;
	}

	/*
	Inserts or updates a customer type
	*/
	public function save(&$customer_type_data, $customer_type_id = FALSE)
	{
		if(!$customer_type_id || !$this->exists($customer_type_id))
		{
			if($this->db->insert('customer_types', $customer_type_data))
			{
				$customer_type_data['customer_type_id'] = $this->db->insert_id();
				return TRUE;
			}
			return FALSE;
		}

		$this->db->where('customer_type_id', $customer_type_id);
		return $this->db->update('customer_types', $customer_type_data);
	}

	/*
	Deletes one customer type
	*/
	public function delete($customer_type_id)
	{
		$this->db->where('customer_type_id', $customer_type_id);
		return $this->db->update('customer_types', array('deleted' => 1));
	}

	/*
	Deletes a list of customer types
	*/
	public function delete_list($customer_type_ids)
	{
		$this->db->where_in('customer_type_id', $customer_type_ids);
		return $this->db->update('customer_types', array('deleted' => 1));
	}

	/*
	Get search suggestions to find customer types
	*/
	public function get_search_suggestions($search, $unique = FALSE, $limit = 25)
	{
		$suggestions = array();

		$this->db->from('customer_types');
		$this->db->where('deleted', 0);
		$this->db->like('name', $search);
		$this->db->order_by('name', 'asc');

		foreach($this->db->get()->result() as $row)
		{
			$suggestions[] = array('value' => $row->customer_type_id, 'label' => $row->name);
		}

		//only return $limit suggestions
		if(count($suggestions) > $limit)
		{
			$suggestions = array_slice($suggestions, 0, $limit);
		}

		return $suggestions;
	}

	/*
	Performs a search on customer types
	*/
	public function search($search, $rows = 0, $limit_from = 0, $sort = 'name', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(customer_types.customer_type_id) as count');
		}

		$this->db->from('customer_types AS customer_types');
		$this->db->group_start();
			$this->db->like('name', $search);
			$this->db->or_like('description', $search);
		$this->db->group_end();
		$this->db->where('deleted', 0);

		// get_found_rows case
		if($count_only == TRUE)
		{
			return $this->db->get()->row()->count;
		}

		$this->db->order_by($sort, $order);

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}

	/*
	Gets rows
	*/
	public function get_found_rows($search)
	{
		return $this->search($search, 0, 0, 'name', 'asc', TRUE);
	}
}
?>
