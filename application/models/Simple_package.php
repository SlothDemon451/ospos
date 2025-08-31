<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Simple_package class - A simplified way to create packages/bundles of items
 */

class Simple_package extends CI_Model
{
	/*
	Determines if a given package_id exists
	*/
	public function exists($package_id)
	{
		$this->db->from('simple_packages');
		$this->db->where('package_id', $package_id);

		$query = $this->db->get();
		
		return ($query->num_rows() == 1);
	}

	/*
	Check if the simple_packages table exists
	*/
	public function table_exists()
	{
		$tables = $this->db->list_tables();
		$table_name = $this->db->dbprefix . 'simple_packages';
		
		return in_array($table_name, $tables);
	}

	/*
	Gets information about a particular package
	*/
	public function get_info($package_id)
	{
		// Check if table exists first
		if (!$this->table_exists()) {
			return FALSE;
		}

		$this->db->select('
		package_id,
		name,
		package_number,
		description,
		package_price,
		discount,
		total_price,
		active');

		$this->db->from('simple_packages');
		$this->db->where('package_id', $package_id);
		$this->db->or_where('package_number', $package_id);

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object
			$package_obj = new stdClass();
			$package_obj->package_id = '';
			$package_obj->name = '';
			$package_obj->package_number = '';
			$package_obj->description = '';
			$package_obj->package_price = '';
			$package_obj->discount = '0';
			$package_obj->total_price = '';
			$package_obj->active = 1;

			return $package_obj;
		}
	}

	/*
	Gets information about multiple packages
	*/
	public function get_multiple_info($package_ids)
	{
		$this->db->from('simple_packages');
		$this->db->where_in('package_id', $package_ids);
		$this->db->order_by('name', 'asc');

		return $this->db->get();
	}

	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('simple_packages');

		return $this->db->count_all_results();
	}

	/*
	Inserts or updates a package
	*/
	public function save(&$package_data, $package_id = FALSE)
	{
		if(!$package_id || !$this->exists($package_id))
		{
			if($this->db->insert('simple_packages', $package_data))
			{
				$package_data['package_id'] = $this->db->insert_id();
				return TRUE;
			}
			return FALSE;
		}

		$this->db->where('package_id', $package_id);
		return $this->db->update('simple_packages', $package_data);
	}

	/*
	Deletes one package
	*/
	public function delete($package_id)
	{
		return $this->db->delete('simple_packages', array('package_id' => $package_id));
	}

	/*
	Deletes a list of packages
	*/
	public function delete_list($package_ids)
	{
		$this->db->where_in('package_id', $package_ids);
		return $this->db->delete('simple_packages');
	}

	/*
	Get search suggestions
	*/
	public function get_search_suggestions($search, $limit = 25)
	{
		$suggestions = array();

		$this->db->select('package_id, name, package_number, package_price, discount, total_price');
		$this->db->from('simple_packages');
		$this->db->like('name', $search);
		$this->db->or_like('package_number', $search);
		$this->db->order_by('name', 'asc');

		$query = $this->db->get();
		if ($query !== FALSE) {
			foreach($query->result() as $row)
			{
				// Calculate final price for display
				$final_price = $row->package_price > 0 ? 
					$row->package_price - ($row->package_price * $row->discount / 100) : 
					$row->total_price;
				
				// Format price without currency symbol for display
				$formatted_price = number_format($final_price, 2);
				
				$suggestions[] = array(
					'value' => $row->package_id, 
					'label' => $row->name . ' (' . $row->package_number . ') - $' . $formatted_price,
					'package_price' => $row->package_price,
					'discount' => $row->discount,
					'total_price' => $row->total_price
				);
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
	Perform a search on packages
	*/
	public function search($search, $rows = 0, $limit_from = 0, $sort = 'name', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(simple_packages.package_id) as count');
		}

		$this->db->from('simple_packages AS simple_packages');
		$this->db->like('name', $search);
		$this->db->or_like('description', $search);
		$this->db->or_like('package_number', $search);

		// get_found_rows case
		if($count_only == TRUE)
		{
			$query = $this->db->get();
			if ($query === FALSE) {
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
			return FALSE;
		}
		return $query;
	}

	/*
	Get all active packages
	*/
	public function get_active_packages()
	{
		$this->db->from('simple_packages');
		$this->db->where('active', 1);
		$this->db->order_by('name', 'asc');

		return $this->db->get();
	}

	/*
	Check if package number exists
	*/
	public function item_number_exists($package_number, $package_id = '')
	{
		$this->db->where('package_number', (string) $package_number);
		// check if $package_id is a number and not a string starting with 0
		if(ctype_digit($package_id) && substr($package_id, 0, 1) !== '0')
		{
			$this->db->where('package_id !=', (int) $package_id);
		}

		$query = $this->db->get('simple_packages');
		
		return ($query->num_rows() >= 1);
	}
}
?>
