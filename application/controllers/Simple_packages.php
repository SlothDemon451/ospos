<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Simple_packages extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('simple_packages');
		
		$this->load->model('Simple_package');
		$this->load->model('Simple_package_items');
	}
	
	public function index()
	{
		$data['table_headers'] = $this->xss_clean(get_simple_packages_manage_table_headers());
		$data['controller_name'] = $this->uri->segment(1);

		$this->load->view('simple_packages/manage', $data);
	}

	/*
	Returns packages table data rows. This will be called with AJAX.
	*/
	public function search()
	{
		$search = $this->input->get('search');
		$limit  = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort   = $this->input->get('sort');
		$order  = $this->input->get('order');

		$packages = $this->Simple_package->search($search, $limit, $offset, $sort, $order);
		$total_rows = $this->Simple_package->get_found_rows($search);

		$data_rows = array();
		foreach($packages->result() as $package)
		{
			$data_rows[] = $this->xss_clean(get_simple_package_data_row($package));
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
	}

	public function suggest_search()
	{
		$suggestions = $this->xss_clean($this->Simple_package->get_search_suggestions($this->input->post('term')));

		echo json_encode($suggestions);
	}

	public function item_search()
	{
		$search = $this->input->get('term') != '' ? $this->input->get('term') : NULL;
		
		// Get item suggestions with unit_price included
		$this->db->select('items.item_id, items.name, items.item_number, items.unit_price');
		$this->db->from('items');
		$this->db->where('items.deleted', 0);
		$this->db->where_in('items.item_type', array(ITEM, ITEM_AMOUNT_ENTRY));
		$this->db->group_start();
		$this->db->like('items.name', $search);
		$this->db->or_like('items.item_number', $search);
		$this->db->group_end();
		$this->db->order_by('items.name', 'asc');
		$this->db->limit(25);
		
		$query = $this->db->get();
		$suggestions = array();
		
		foreach($query->result() as $row)
		{
			$suggestions[] = array(
				'value' => $row->item_id,
				'label' => $row->name . ' (' . $row->item_number . ')',
				'unit_price' => to_currency($row->unit_price)
			);
		}
		
		$suggestions = $this->xss_clean($suggestions);
		echo json_encode($suggestions);
	}

	public function get_row($row_id)
	{
		$package = $this->Simple_package->get_info($row_id);

		echo json_encode(get_simple_package_data_row($package));
	}
	
	public function view($package_id = -1)
	{
		if($package_id == -1)
		{
			// Create a new package object with default values
			$info = new stdClass();
			$info->package_id = '';
			$info->name = '';
			$info->package_number = '';
			$info->description = '';
			$info->total_price = '';
			$info->package_price = '';
			$info->discount = '0';
			$info->active = 1;
		}
		else
		{
			$info = $this->Simple_package->get_info($package_id);
		}

		foreach(get_object_vars($info) as $property => $value)
		{
			$info->$property = $this->xss_clean($value);
		}

		$data['package_info'] = $info;

		$items = array();
		if($package_id != -1)
		{
			$package_items_result = $this->Simple_package_items->get_info($package_id);
			if($package_items_result !== FALSE)
			{
				foreach($package_items_result as $package_item)
				{
					$item['name'] = $this->xss_clean($package_item['item_name']);
					$item['item_id'] = $this->xss_clean($package_item['item_id']);
					$item['quantity'] = $this->xss_clean($package_item['quantity']);
					$item['unit_price'] = $this->xss_clean($package_item['unit_price']);

					$items[] = $item;
				}
			}
		}

		$data['package_items'] = $items;

		$this->load->view("simple_packages/form", $data);
	}
	
	public function save($package_id = -1)
	{
		$package_data = array(
			'name' => $this->input->post('name'),
			'package_number' => $this->input->post('package_number'),
			'description' => $this->input->post('description'),
			'package_price' => $this->input->post('package_price'),
			'discount' => $this->input->post('discount'),
			'active' => $this->input->post('active') == NULL ? 1 : $this->input->post('active')
		);
		
		if($this->Simple_package->save($package_data, $package_id))
		{
			$new_package = FALSE;
			//New package
			if($package_id == -1)
			{
				$package_id = $package_data['package_id'];
				$new_package = TRUE;
			}

			if($this->input->post('package_qty') != NULL)
			{
				$package_items = array();
				foreach($this->input->post('package_qty') as $item_id => $quantity)
				{
					$package_items[] = array(
						'item_id' => $item_id,
						'quantity' => $quantity
					);
				}
			}

			$success = $this->Simple_package_items->save($package_items, $package_id);

			// Calculate and update total price
			$total_price = $this->Simple_package_items->calculate_total_price($package_id);
			$update_data = array('total_price' => $total_price);
			$this->Simple_package->save($update_data, $package_id);

			$package_data = $this->xss_clean($package_data);

			if($new_package)
			{
				echo json_encode(array('success' => $success,
					'message' => 'Package successfully added: '.$package_data['name'], 'id' => $package_id));
			}
			else
			{
				echo json_encode(array('success' => $success,
					'message' => 'Package successfully updated: '.$package_data['name'], 'id' => $package_id));
			}
		}
		else
		{
			$package_data = $this->xss_clean($package_data);

			echo json_encode(array('success' => FALSE, 
								'message' => 'Error adding/updating package: '.$package_data['name'], 'id' => -1));
		}
	}
	
	public function delete()
	{
		$packages_to_delete = $this->xss_clean($this->input->post('ids'));

		if($this->Simple_package->delete_list($packages_to_delete))
		{
			echo json_encode(array('success' => TRUE,
								'message' => 'Package(s) successfully deleted'));
		}
		else
		{
			echo json_encode(array('success' => FALSE,
								'message' => 'Package(s) cannot be deleted'));
		}
	}

	public function check_package_number()
	{
		$exists = $this->Simple_package->item_number_exists($this->input->post('package_number'), $this->input->post('package_id'));
		echo !$exists ? 'true' : 'false';
	}

}
?>
