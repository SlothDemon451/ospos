<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once("Persons.php");

/**
 * Customer Types controller
 */

class Customer_types extends Persons
{
	public function __construct()
	{
		parent::__construct('customers');
		
		$this->load->model('Customer_type');
		$this->load->library('form_validation');
		$this->lang->load('customer_types');
		
		if(!$this->Employee->has_grant('customers', $this->session->userdata('person_id')))
		{
			redirect('no_access/customers');
		}
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	public function suggest()
	{
		// Temporarily comment out helper function to test basic functionality
		$suggestions = $this->xss_clean($this->Customer_type->get_search_suggestions($this->input->get('term')));

		echo json_encode($suggestions);
	}

	/*
	 * Shows the customer types screen
	 */
	public function index()
	{
		$data['table_headers'] = $this->xss_clean(get_customer_type_manage_table_headers());
		$data['controller_name'] = $this->router->fetch_class();

		$this->load->view('customer_types/manage', $data);
	}

	/*
	Gets one customer type row for a customer types table. This is called using AJAX to update one row.
	*/
	public function get_row($row_id)
	{
		$data_row = get_customer_type_data_row($this->Customer_type->get_info($row_id), $this);
		echo json_encode($data_row);
	}

	/*
	 * Returns Customer Types table data rows. This will be called with AJAX.
	 */
	public function search()
	{
		$search = $this->input->get('search');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort = $this->input->get('sort');
		$order = $this->input->get('order');

		$customer_types = $this->Customer_type->search($search, $limit, $offset, $sort, $order);
		$total_rows = $this->Customer_type->get_found_rows($search);
		$manage_table_data_rows = array();

		foreach($customer_types->result() as $customer_type)
		{
			$manage_table_data_rows[] = get_customer_type_data_row($customer_type, $this);
		}

		$data_rows = $this->xss_clean($manage_table_data_rows);

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
	}

	/*
	 * Loads the customer type edit form (for modal)
	 */
	public function view($customer_type_id = -1)
	{
		$data['customer_type_info'] = $this->Customer_type->get_info($customer_type_id);
		$data['controller_name'] = $this->router->fetch_class();

		$this->load->view("customer_types/form", $data);
	}

	/*
	Inserts/updates a customer type
	*/
	public function save($customer_type_id = -1)
	{
		$customer_type_data = array(
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description')
		);

		if($this->Customer_type->save($customer_type_data, $customer_type_id))
		{
			$customer_type_data = $this->xss_clean($customer_type_data);

			// New customer type
			if($customer_type_id == -1)
			{
				echo json_encode(array('success' => TRUE,
								'message' => $this->lang->line('customer_types_successful_adding') . ' ' . $customer_type_data['name'],
								'id' => $customer_type_data['customer_type_id']));
			}
			else // Existing customer type
			{
				echo json_encode(array('success' => TRUE,
								'message' => $this->lang->line('customer_types_successful_updating') . ' ' . $customer_type_data['name'],
								'id' => $customer_type_id));
			}
		}
		else // Failure
		{
			echo json_encode(array('success' => FALSE,
							'message' => $this->lang->line('customer_types_error_adding_updating') . ' ' . $customer_type_data['name'],
							'id' => -1));
		}
	}

	/*
	This deletes customer types from the customer_types table
	*/
	public function delete()
	{
		$customer_types_to_delete = $this->input->post('ids');

		if($this->Customer_type->delete_list($customer_types_to_delete))
		{
			echo json_encode(array('success' => TRUE,
							'message' => $this->lang->line('customer_types_successful_deleted') . ' ' .
							count($customer_types_to_delete) . ' ' . $this->lang->line('customer_types_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success' => FALSE,
							'message' => $this->lang->line('customer_types_cannot_be_deleted')));
		}
	}

	/*
	Gets customer type suggestions for autocomplete
	*/
	public function suggest_customer_type()
	{
		$suggestions = $this->xss_clean($this->Customer_type->get_search_suggestions($this->input->get('term')));

		echo json_encode($suggestions);
	}

	/*
	Gets customer type info for display
	*/
	public function get_info($customer_type_id)
	{
		$customer_type = $this->Customer_type->get_info($customer_type_id);
		echo json_encode($customer_type);
	}

	/*
	Gets total rows for pagination
	*/
	public function get_total_rows()
	{
		$total_rows = $this->Customer_type->get_total_rows();
		echo json_encode(array('total_rows' => $total_rows));
	}

	/*
	 * Handles sorting for the customer types table
	 */
	public function sort()
	{
		$search = $this->input->get('search');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort = $this->input->get('sort');
		$order = $this->input->get('order');

		$customer_types = $this->Customer_type->search($search, $limit, $offset, $sort, $order);
		$total_rows = $this->Customer_type->get_found_rows($search);
		$manage_table_data_rows = array();

		foreach($customer_types->result() as $customer_type)
		{
			$manage_table_data_rows[] = get_customer_type_data_row($customer_type, $this);
		}

		$data_rows = $this->xss_clean($manage_table_data_rows);

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
	}

	/*
	 * Handles table state remembering
	 */
	public function remember()
	{
		// This method is called by the table support but not actually used
		echo json_encode(array('success' => TRUE));
	}


}
?>
