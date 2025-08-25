<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Item_kit class
 */

class Item_kit extends CI_Model
{
	/*
	Determines if a given item_id is an item kit
	*/
	public function exists($item_kit_id)
	{
		$this->db->from('item_kits');
		$this->db->where('item_kit_id', $item_kit_id);

		$query = $this->db->get();
		
		// Check if query was successful
		if ($query === FALSE) {
			log_message('error', 'Database query failed in Item_kit::exists() for item_kit_id: ' . $item_kit_id);
			return FALSE;
		}

		return ($query->num_rows() == 1);
	}

	/*
	Check if a given item_id is an item kit
	*/
	public function is_valid_item_kit($item_kit_id)
	{
		if(!empty($item_kit_id))
		{
			//KIT #
			$pieces = explode(' ', $item_kit_id);

			if(count($pieces) == 2 && preg_match('/(KIT)/i', $pieces[0]))
			{
				return $this->exists($pieces[1]);
			}
			else
			{
				return $this->item_number_exists($item_kit_id);
			}
		}

		return FALSE;
	}

	/*
	Determines if a given item_number exists
	*/
	public function item_number_exists($item_kit_number, $item_kit_id = '')
	{
		if($this->config->item('allow_duplicate_barcodes') != FALSE)
		{
			return FALSE;
		}

		$this->db->where('item_kit_number', (string) $item_kit_number);
		// check if $item_id is a number and not a string starting with 0
		// because cases like 00012345 will be seen as a number where it is a barcode
		if(ctype_digit($item_kit_id) && substr($item_kit_id, 0, 1) !== '0')
		{
			$this->db->where('item_kit_id !=', (int) $item_kit_id);
		}

		$query = $this->db->get('item_kits');
		
		// Check if query was successful
		if ($query === FALSE) {
			log_message('error', 'Database query failed in Item_kit::item_number_exists() for item_kit_number: ' . $item_kit_number);
			return FALSE;
		}

		return ($query->num_rows() >= 1);
	}

	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('item_kits');

		return $this->db->count_all_results();
	}

	/*
	Check if the item_kits table exists
	*/
	public function table_exists()
	{
		$tables = $this->db->list_tables();
		$table_name = $this->db->dbprefix . 'item_kits';
		
		if (ENVIRONMENT === 'development') {
			log_message('debug', 'Available tables: ' . json_encode($tables));
			log_message('debug', 'Looking for table: ' . $table_name);
		}
		
		return in_array($table_name, $tables);
	}

	/*
	Gets information about a particular item kit
	*/
	public function get_info($item_kit_id)
	{
		// Debug: Log the table name being used
		if (ENVIRONMENT === 'development') {
			log_message('debug', 'Item_kit::get_info() called with item_kit_id: ' . $item_kit_id);
			log_message('debug', 'Database prefix: ' . $this->db->dbprefix);
			log_message('debug', 'Full table name: ' . $this->db->dbprefix . 'item_kits');
		}

		// Check if table exists first
		if (!$this->table_exists()) {
			log_message('error', 'Table item_kits does not exist');
			return FALSE;
		}

		$this->db->select('
		item_kit_id,
		item_kits.name as name,
		item_kit_number,
		item_kits.description,
		item_kits.item_id as kit_item_id,
		kit_discount_percent as kit_discount,
		price_option,
		print_option');

		$this->db->from('item_kits');
		$this->db->where('item_kit_id', $item_kit_id);
		$this->db->or_where('item_kit_number', $item_kit_id);

		// Debug: Log the final query
		if (ENVIRONMENT === 'development') {
			log_message('debug', 'Final SQL query: ' . $this->db->get_compiled_select());
		}

		$query = $this->db->get();

		// Check if query was successful
		if ($query === FALSE) {
			log_message('error', 'Database query failed in Item_kit::get_info() for item_kit_id: ' . $item_kit_id);
			log_message('error', 'Database error: ' . json_encode($this->db->error()));
			return FALSE;
		}

		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_kit_id is NOT an item kit
			$item_obj = new stdClass();

			//Get all the fields from items table
			try {
				$fields = $this->db->list_fields('item_kits');
				if ($fields !== FALSE) {
					foreach($fields as $field)
					{
						$item_obj->$field = '';
					}
				} else {
					// If list_fields fails, create a basic object with common fields
					$item_obj->item_kit_id = '';
					$item_obj->name = '';
					$item_obj->item_kit_number = '';
					$item_obj->description = '';
					$item_obj->kit_discount = '';
					$item_obj->kit_discount_type = '';
					$item_obj->price_option = '';
					$item_obj->print_option = '';
					$item_obj->item_id = '';
				}
			} catch (Exception $e) {
				log_message('error', 'Failed to get table fields in Item_kit::get_info(): ' . $e->getMessage());
				// Create a basic object with common fields
				$item_obj->item_kit_id = '';
				$item_obj->name = '';
				$item_obj->item_kit_number = '';
				$item_obj->description = '';
				$item_obj->kit_discount = '';
				$item_obj->kit_discount_type = '';
				$item_obj->price_option = '';
				$item_obj->print_option = '';
				$item_obj->item_id = '';
			}

			return $item_obj;
		}
	}

	/*
	Gets information about multiple item kits
	*/
	public function get_multiple_info($item_kit_ids)
	{
		$this->db->from('item_kits');
		$this->db->where_in('item_kit_id', $item_kit_ids);
		$this->db->order_by('name', 'asc');

		return $this->db->get();
	}

	/*
	Inserts or updates an item kit
	*/
	public function save(&$item_kit_data, $item_kit_id = FALSE)
	{
		if(!$item_kit_id || !$this->exists($item_kit_id))
		{
			if($this->db->insert('item_kits', $item_kit_data))
			{
				$item_kit_data['item_kit_id'] = $this->db->insert_id();

				return TRUE;
			}

			return FALSE;
		}

		$this->db->where('item_kit_id', $item_kit_id);

		return $this->db->update('item_kits', $item_kit_data);
	}

	/*
	Deletes one item kit
	*/
	public function delete($item_kit_id)
	{
		return $this->db->delete('item_kits', array('item_kit_id' => $item_kit_id));
	}

	/*
	Deletes a list of item kits
	*/
	public function delete_list($item_kit_ids)
	{
		$this->db->where_in('item_kit_id', $item_kit_ids);

		return $this->db->delete('item_kits');
	}

	public function get_search_suggestions($search, $limit = 25)
	{
		$suggestions = array();

		$this->db->from('item_kits');

		//KIT #
		if(stripos($search, 'KIT ') !== FALSE)
		{
			$this->db->like('item_kit_id', str_ireplace('KIT ', '', $search));
			$this->db->order_by('item_kit_id', 'asc');

			$query = $this->db->get();
			if ($query !== FALSE) {
				foreach($query->result() as $row)
				{
					$suggestions[] = array('value' => 'KIT '. $row->item_kit_id, 'label' => 'KIT ' . $row->item_kit_id);
				}
			}
		}
		else
		{
			$this->db->like('name', $search);
			$this->db->or_like('item_kit_number', $search);
			$this->db->order_by('name', 'asc');

			$query = $this->db->get();
			if ($query !== FALSE) {
				foreach($query->result() as $row)
				{
					$suggestions[] = array('value' => 'KIT ' . $row->item_kit_id, 'label' => $row->name);
				}
			}
		}

		//only return $limit suggestions
		if(count($suggestions) > $limit)
		{
			$suggestions = array_slice($suggestions, 0, $limit);
		}

		return $suggestions;
	}

 	/*
	Gets rows
	*/
	public function get_found_rows($search)
	{
		return $this->search($search, 0, 0, 'name', 'asc', TRUE);
	}

	/*
	Perform a search on items
	*/
	public function search($search, $rows = 0, $limit_from = 0, $sort = 'name', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(item_kits.item_kit_id) as count');
		}

		$this->db->from('item_kits AS item_kits');
		$this->db->like('name', $search);
		$this->db->or_like('description', $search);
		$this->db->or_like('item_kit_number', $search);

		//KIT #
		if(stripos($search, 'KIT ') !== FALSE)
		{
			$this->db->or_like('item_kit_id', str_ireplace('KIT ', '', $search));
		}

		// get_found_rows case
		if($count_only == TRUE)
		{
			$query = $this->db->get();
			if ($query === FALSE) {
				log_message('error', 'Database query failed in Item_kit::search() count_only for search: ' . $search);
				return 0;
			}
			return $query->row()->count;
		}

		$this->db->order_by($sort, $order);

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}

		$query = $this->db->get();
		if ($query === FALSE) {
			log_message('error', 'Database query failed in Item_kit::search() for search: ' . $search);
			return FALSE;
		}
		return $query;
	}
}
?>
