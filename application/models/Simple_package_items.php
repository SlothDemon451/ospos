<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Simple_package_items class - Manages items within simple packages
 */

class Simple_package_items extends CI_Model
{
	/*
	Gets package items for a particular package
	*/
	public function get_info($package_id)
	{
		$this->db->select('simple_package_items.package_id, simple_package_items.item_id, quantity, items.name as item_name, items.unit_price');
		$this->db->from('simple_package_items as simple_package_items');
		$this->db->join('items as items', 'simple_package_items.item_id = items.item_id');
		$this->db->where('simple_package_items.package_id', $package_id);
		$this->db->order_by('items.name', 'asc');

		$query = $this->db->get();
		
		if ($query === FALSE) {
			return FALSE;
		}

		return $query->result_array();
	}

	/*
	Inserts or updates a package's items
	*/
	public function save(&$package_items_data, $package_id)
	{
		$success = TRUE;

		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->delete($package_id);

		if($package_items_data != NULL)
		{
			foreach($package_items_data as $row)
			{
				$row['package_id'] = $package_id;
				$success &= $this->db->insert('simple_package_items', $row);
			}
		}

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	/*
	Deletes package items given a package
	*/
	public function delete($package_id)
	{
		return $this->db->delete('simple_package_items', array('package_id' => $package_id));
	}

	/*
	Calculate total price for a package based on its items
	*/
	public function calculate_total_price($package_id)
	{
		$items = $this->get_info($package_id);
		$total = 0;
		
		if($items !== FALSE)
		{
			foreach($items as $item)
			{
				$total += $item['unit_price'] * $item['quantity'];
			}
		}
		
		return $total;
	}
}
?>
