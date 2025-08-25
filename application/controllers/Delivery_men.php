<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Persons.php");

class Delivery_men extends Persons
{
	public function __construct()
	{
		parent::__construct('delivery_men');
	}

	public function index()
	{
		$data['table_headers'] = $this->xss_clean(get_delivery_men_manage_table_headers());

		$this->load->view('people/manage', $data);
	}

	/*
	Gets one row for a delivery man manage table. This is called using AJAX to update one row.
	*/
	public function get_row($row_id)
	{
		$person = $this->Employee->get_info($row_id);

		$data_row = $this->xss_clean(get_delivery_man_data_row($person));

		echo json_encode($data_row);
	}

	/*
	Returns delivery men table data rows. This will be called with AJAX.
	*/
	public function search()
	{
		$search = $this->input->get('search');
		$limit  = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort   = $this->input->get('sort');
		$order  = $this->input->get('order');

		$delivery_men = $this->Employee->search_delivery_men($search, $limit, $offset, $sort, $order);
		$total_rows = $this->Employee->get_found_delivery_men_rows($search);

		$data_rows = array();
		foreach($delivery_men->result() as $person)
		{
			$data_rows[] = $this->xss_clean(get_delivery_man_data_row($person));
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	public function suggest()
	{
		$suggestions = $this->xss_clean($this->Employee->get_delivery_man_search_suggestions($this->input->get('term'), TRUE));

		echo json_encode($suggestions);
	}

	public function suggest_search()
	{
		$suggestions = $this->xss_clean($this->Employee->get_delivery_man_search_suggestions($this->input->post('term')));

		echo json_encode($suggestions);
	}

	/*
	Loads the delivery man edit form
	*/
	public function view($delivery_man_id = -1)
	{
		$person_info = $this->Employee->get_info($delivery_man_id);
		foreach(get_object_vars($person_info) as $property => $value)
		{
			$person_info->$property = $this->xss_clean($value);
		}
		$data['person_info'] = $person_info;
		$data['delivery_man_id'] = $delivery_man_id;

		$this->load->view('delivery_men/form', $data);
	}

	/*
	Inserts/updates a delivery man
	*/
	public function save($delivery_man_id = -1)
	{
		$first_name = $this->xss_clean($this->input->post('first_name'));
		$last_name = $this->xss_clean($this->input->post('last_name'));
		$email = $this->xss_clean(strtolower($this->input->post('email')));

		// format first and last name properly
		$first_name = $this->nameize($first_name);
		$last_name = $this->nameize($last_name);

		$person_data = array(
			'first_name' => $first_name,
			'last_name' => $last_name,
			'gender' => $this->input->post('gender'),
			'email' => $email,
			'phone_number' => $this->input->post('phone_number'),
			'address_1' => $this->input->post('address_1'),
			'address_2' => $this->input->post('address_2') ?: '', // Ensure empty string if null
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state') ?: '', // Ensure empty string if null
			'zip' => $this->input->post('zip'),
			'country' => $this->input->post('country'),
			'comments' => $this->input->post('comments'),
		);

		//Password has been changed OR first time password set
		log_message('debug', 'Password check - raw password: "' . $this->input->post('password') . '"');
		log_message('debug', 'Password check - password length: ' . strlen($this->input->post('password')));
		log_message('debug', 'Password check - password empty check: ' . (empty($this->input->post('password')) ? 'TRUE' : 'FALSE'));
		log_message('debug', 'Password check - ENVIRONMENT: ' . ENVIRONMENT);
		
		if($this->input->post('password') != '' && ENVIRONMENT != 'testing')
		{
			log_message('debug', 'Password condition TRUE - setting password');
			$employee_data = array(
				'username' 	=> $this->input->post('username'),
				'password' 	=> password_hash($this->input->post('password'), PASSWORD_DEFAULT),
				'hash_version' 	=> 2,
				'language_code' => 'en-US',
				'language' 	=> 'English (US)'
			);
		}
		else //Password not changed
		{
			log_message('debug', 'Password condition FALSE - NOT setting password');
			$employee_data = array(
				'username' 	=> $this->input->post('username'),
				'language_code'	=> 'en-US',
				'language' 	=> 'English (US)'
			);
		}

		// Set as delivery man
		$employee_data['is_delivery_man'] = 1;

		// Empty grants array for delivery men (they don't need system access)
		$grants_array = array();

		// Debug: Log all the data being submitted
		log_message('debug', '=== DELIVERY MAN SAVE DEBUG START ===');
		log_message('debug', 'POST data received: ' . json_encode($this->input->post()));
		log_message('debug', 'Person data: ' . json_encode($person_data));
		log_message('debug', 'Employee data: ' . json_encode($employee_data));
		log_message('debug', 'Delivery man ID: ' . $delivery_man_id);
		log_message('debug', 'Password field value: "' . $this->input->post('password') . '"');
		log_message('debug', 'Password field length: ' . strlen($this->input->post('password')));

		if($this->Employee->save_employee($person_data, $employee_data, $grants_array, $delivery_man_id))
		{
			log_message('debug', 'SUCCESS: save_employee returned TRUE');
			// New delivery man
			if($delivery_man_id == -1)
			{
				echo json_encode(array('success' => TRUE,
								'message' => $this->lang->line('delivery_men_successful_adding') . ' ' . $first_name . ' ' . $last_name,
								'id' => $this->xss_clean($person_data['person_id'])));
			}
			else // Existing delivery man
			{
				echo json_encode(array('success' => TRUE,
								'message' => $this->lang->line('delivery_men_successful_updating') . ' ' . $first_name . ' ' . $last_name,
								'id' => $delivery_man_id));
			}
		}
		else // Failure
		{
			log_message('debug', 'FAILURE: save_employee returned FALSE');
			// Get the last database error
			$db_error = $this->db->error();
			log_message('error', 'Database error: ' . json_encode($db_error));
			log_message('error', 'Last query: ' . $this->db->last_query());
			
			echo json_encode(array('success' => FALSE,
							'message' => $this->lang->line('delivery_men_error_adding_updating') . ' ' . $first_name . ' ' . $last_name,
							'id' => -1));
		}
		
		log_message('debug', '=== DELIVERY MAN SAVE DEBUG END ===');
	}

	/*
	This deletes delivery men from the employees table
	*/
	public function delete()
	{
		$delivery_men_to_delete = $this->xss_clean($this->input->post('ids'));

		// Check if delivery men can be deleted first
		if(!$this->Employee->can_delete_delivery_men($delivery_men_to_delete))
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('delivery_men_cannot_be_deleted')));
			return;
		}

		if($this->Employee->delete_delivery_men_list($delivery_men_to_delete))
		{
			echo json_encode(array('success' => TRUE,'message' => $this->lang->line('delivery_men_successful_deleted') . ' ' .
							count($delivery_men_to_delete) . ' ' . $this->lang->line('delivery_men_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('delivery_men_cannot_be_deleted')));
		}
	}

	public function check_username($delivery_man_id)
	{
		$exists = $this->Employee->username_exists($delivery_man_id, $this->input->get('username'));
		echo !$exists ? 'true' : 'false';
	}

	public function sales($delivery_man_id)
	{
		$this->load->model('Sale');
		$this->load->helper('tabular');

		// Set controller_name to 'sales' for correct URL generation
		$this->uri->segment(1, 'sales');
		
		// Get delivery man info
		$delivery_man_info = $this->Employee->get_info($delivery_man_id);

		// Date filter - default to today if not provided
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');
		if (empty($start_date) || empty($end_date))
		{
			$today = date('Y-m-d');
			$start_date = $today;
			$end_date = $today;
		}

		// Get all sales for this delivery man, optionally filtered by date
		$sales = $this->Sale->get_sales_by_delivery_man($delivery_man_id, $start_date, $end_date);

		$data = array();
		$data['controller_name'] = 'sales';
		$data['table_headers'] = get_sales_manage_table_headers();
		$data['sales'] = $sales;
		$data['delivery_man_info'] = $delivery_man_info;
		$data['title'] = 'Sales for ' . $delivery_man_info->first_name . ' ' . $delivery_man_info->last_name;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;

		$this->load->view('sales/manage', $data);
	}

    // Removed print_invoices: use batch receipts instead

	public function print_receipts($delivery_man_id)
	{
		$this->load->model('Sale');

		// Date filter - default to today if not provided
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');
		if (empty($start_date) || empty($end_date))
		{
			$today = date('Y-m-d');
			$start_date = $today;
			$end_date = $today;
		}

		$sales = $this->Sale->get_sales_by_delivery_man($delivery_man_id, $start_date, $end_date);
		$sale_ids = array();
		foreach ($sales as $sale)
		{
			$sale_ids[] = (int)$sale->sale_id;
		}

		$data = array(
			'sale_ids' => $sale_ids,
			'delivery_man_id' => $delivery_man_id,
			'start_date' => $start_date,
			'end_date' => $end_date
		);

		$this->load->view('sales/print_all_receipts', $data);
	}

	public function print_all_items($delivery_man_id)
	{
		$this->load->model('Sale');
		$this->load->model('Employee');

		// Date filter - default to today if not provided
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');
		if (empty($start_date) || empty($end_date))
		{
			$today = date('Y-m-d');
			$start_date = $today;
			$end_date = $today;
		}

		// Get delivery man info
		$delivery_man_info = $this->Employee->get_info($delivery_man_id);
		
		// Get all sales for this delivery man
		$sales = $this->Sale->get_sales_by_delivery_man($delivery_man_id, $start_date, $end_date);
		
		// Consolidate all items from all sales
		$consolidated_items = array();
		$total_items = 0;
		$total_sales = count($sales);
		
		foreach ($sales as $sale) {
			// Get items for this sale with proper item names from items table
			$sale_items = $this->Sale->get_sale_items_ordered($sale->sale_id)->result();
			
			foreach ($sale_items as $item) {
				// Use item name from items table, fallback to description if name is empty
				$item_name = !empty($item->name) ? $item->name : ($item->description ?: 'Item #' . $item->item_id);
				
				// Create a unique key that includes item_id, price, and discount
				$item_key = $item->item_id . '_' . $item->item_unit_price . '_' . $item->discount . '_' . $item->discount_type;
				
				if (!isset($consolidated_items[$item_key])) {
					$consolidated_items[$item_key] = array(
						'item_id' => $item->item_id,
						'name' => $item_name,
						'sales' => array()
					);
				}
				
				$consolidated_items[$item_key]['sales'][] = array(
					'sale_id' => $sale->sale_id,
					'customer_name' => $sale->customer_name,
					'quantity' => $item->quantity_purchased,
					'line' => $item->line
				);
				
				$total_items += $item->quantity_purchased;
			}
		}
		
		// Sort items by name for better organization
		usort($consolidated_items, function($a, $b) {
			return strcmp($a['name'], $b['name']);
		});

		$data = array(
			'delivery_man_info' => $delivery_man_info,
			'consolidated_items' => $consolidated_items,
			'total_items' => $total_items,
			'total_sales' => $total_sales,
			'start_date' => $start_date,
			'end_date' => $end_date
		);

		$this->load->view('delivery_men/print_all_items', $data);
	}


}
?>
