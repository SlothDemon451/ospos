<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Receivings extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('receivings');

		$this->load->library('receiving_lib');
		$this->load->library('token_lib');
		$this->load->library('barcode_lib');
		$this->load->helper('locale');
	}

	public function index()
	{
		$this->_reload();
	}

	// Manage view for receivings (list with due filter and actions)
    public function manage()
	{
        $default_start = date('Y-m-d', strtotime('-30 days'));
        $start_date = $this->input->get('start_date') ?: $default_start;
        $end_date = $this->input->get('end_date') ?: date('Y-m-d');
		$due_only = $this->input->get('due_only') ? TRUE : FALSE;

		$location_id = 'all';
		$rows = 0; $limit_from = 0; // simple view without paging for now

		$receivings = $this->Receiving->get_receivings_manage($start_date, $end_date, $location_id, $due_only, $rows, $limit_from);

		$data = array(
			'receivings' => $receivings,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'due_only' => $due_only,
			'payment_options' => $this->Receiving->get_payment_options()
		);

		$this->load->view('receivings/manage', $data);
	}

	public function apply_payment($receiving_id)
	{
		// Validate receiving exists and user has permission
		if (!$this->Receiving->exists($receiving_id)) {
			echo json_encode(array('success' => FALSE, 'message' => 'Receiving not found'));
			return;
		}

		// Validate input
		$amount = parse_decimals($this->input->post('amount'));
		if ($amount <= 0) {
			echo json_encode(array('success' => FALSE, 'message' => 'Invalid amount'));
			return;
		}

		// Get receiving details to calculate outstanding amount
		$outstanding_amount = $this->Receiving->get_receiving_outstanding_amount($receiving_id);
		
		// Prevent overpayment
		if ($amount > $outstanding_amount) {
			echo json_encode(array('success' => FALSE, 'message' => 'Payment amount cannot exceed outstanding amount of ' . to_currency($outstanding_amount)));
			return;
		}

		$type = $this->input->post('payment_type') ?: $this->lang->line('sales_cash');
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		
		// Debug: Log the payment data being processed
		log_message('debug', '=== RECEIVING PAYMENT DEBUG ===');
		log_message('debug', 'Receiving ID: ' . $receiving_id);
		log_message('debug', 'Amount: ' . $amount);
		log_message('debug', 'Payment Type: ' . $type);
		log_message('debug', 'Employee ID: ' . $employee_id);
		log_message('debug', 'Outstanding Amount: ' . $outstanding_amount);
		
		// Add payment to receiving
		$result = $this->Receiving->add_payment_to_receiving($receiving_id, array(
			'payment_type' => $type,
			'payment_amount' => $amount,
			'employee_id' => $employee_id
		));

		if ($result) {
			// Get updated outstanding amount
			$new_outstanding = $this->Receiving->get_receiving_outstanding_amount($receiving_id);
			
			// Debug: Verify the payment was saved
			$payments = $this->Receiving->get_receiving_payments($receiving_id);
			log_message('debug', 'Payment saved successfully. Total payments found: ' . ($payments ? $payments->num_rows() : 0));
			if($payments && $payments->num_rows() > 0) {
				foreach($payments->result() as $payment) {
					log_message('debug', 'Payment record - ID: ' . $payment->payment_id . ', Type: ' . $payment->payment_type . ', Amount: ' . $payment->payment_amount);
				}
			}
			
			echo json_encode(array(
				'success' => TRUE, 
				'message' => 'Payment applied successfully',
				'amount_paid' => $amount,
				'outstanding_amount' => $new_outstanding
			));
		} else {
			log_message('error', 'Failed to save payment to database');
			echo json_encode(array('success' => FALSE, 'message' => 'Failed to add payment'));
		}
	}

	public function item_search()
	{
		$suggestions = $this->Item->get_search_suggestions($this->input->get('term'), array('search_custom' => FALSE, 'is_deleted' => FALSE), TRUE);
		$suggestions = array_merge($suggestions, $this->Item_kit->get_search_suggestions($this->input->get('term')));

		$suggestions = $this->xss_clean($suggestions);

		echo json_encode($suggestions);
	}

	public function stock_item_search()
	{
		$suggestions = $this->Item->get_stock_search_suggestions($this->input->get('term'), array('search_custom' => FALSE, 'is_deleted' => FALSE), TRUE);
		$suggestions = array_merge($suggestions, $this->Item_kit->get_search_suggestions($this->input->get('term')));

		$suggestions = $this->xss_clean($suggestions);

		echo json_encode($suggestions);
	}

	public function select_supplier()
	{
		$supplier_id = $this->input->post('supplier');
		if($this->Supplier->exists($supplier_id))
		{
			$this->receiving_lib->set_supplier($supplier_id);
		}

		$this->_reload();
	}

	public function change_mode()
	{
		$stock_destination = $this->input->post('stock_destination');
		$stock_source = $this->input->post('stock_source');

		if((!$stock_source || $stock_source == $this->receiving_lib->get_stock_source()) &&
			(!$stock_destination || $stock_destination == $this->receiving_lib->get_stock_destination()))
		{
			$this->receiving_lib->clear_reference();
			$mode = $this->input->post('mode');
			$this->receiving_lib->set_mode($mode);
		}
		elseif($this->Stock_location->is_allowed_location($stock_source, 'receivings'))
		{
			$this->receiving_lib->set_stock_source($stock_source);
			$this->receiving_lib->set_stock_destination($stock_destination);
		}

		$this->_reload();
	}
	
	public function set_comment()
	{
		$this->receiving_lib->set_comment($this->input->post('comment'));
	}

	public function set_print_after_sale()
	{
		$this->receiving_lib->set_print_after_sale($this->input->post('recv_print_after_sale'));
	}
	
	public function set_reference()
	{
		$this->receiving_lib->set_reference($this->input->post('recv_reference'));
	}

	// Multiple Payments for Receivings
	public function add_payment()
	{
		$data = array();
		$payment_type = $this->input->post('payment_type');
		$this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'trim|required|callback_numeric');
		if($this->form_validation->run() == FALSE)
		{
			$data['error'] = $this->lang->line('sales_must_enter_numeric');
		}
		else
		{
			$amount_tendered = $this->input->post('amount_tendered');
			$this->receiving_lib->add_payment($payment_type, $amount_tendered);
		}
		$this->_reload($data);
	}

	public function delete_payment($payment_index)
	{
		$this->receiving_lib->delete_payment($payment_index);
		$this->_reload();
	}
	
	public function add()
	{
		$data = array();

		$mode = $this->receiving_lib->get_mode();
		$item_id_or_number_or_item_kit_or_receipt = $this->input->post('item');
		$this->token_lib->parse_barcode($quantity, $price, $item_id_or_number_or_item_kit_or_receipt);
		$quantity = ($mode == 'receive' || $mode == 'requisition') ? $quantity : -$quantity;
		$item_location = $this->receiving_lib->get_stock_source();
		$discount = $this->config->item('default_receivings_discount');
		$discount_type = $this->config->item('default_receivings_discount_type');

		if($mode == 'return' && $this->Receiving->is_valid_receipt($item_id_or_number_or_item_kit_or_receipt))
		{
			$this->receiving_lib->return_entire_receiving($item_id_or_number_or_item_kit_or_receipt);
		}
		elseif($this->Item_kit->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt))
		{
			$this->receiving_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt, $item_location, $discount, $discount_type);
		}
		elseif(!$this->receiving_lib->add_item($item_id_or_number_or_item_kit_or_receipt, $quantity, $item_location, $discount,  $discount_type))
		{
			$data['error'] = $this->lang->line('receivings_unable_to_add_item');
		}

		$this->_reload($data);
	}

	public function edit_item($item_id)
	{
		$data = array();

		$this->form_validation->set_rules('price', 'lang:items_price', 'required|callback_numeric');
		$this->form_validation->set_rules('quantity', 'lang:items_quantity', 'required|callback_numeric');
        $this->form_validation->set_rules('discount', 'lang:items_discount', 'required|callback_numeric');
        $this->form_validation->set_rules('tax', 'Tax', 'callback_numeric');

		$description = $this->input->post('description');
		$serialnumber = $this->input->post('serialnumber');
		$price = parse_decimals($this->input->post('price'));
		$quantity = parse_quantity($this->input->post('quantity'));
		$discount_type = $this->input->post('discount_type');
        $discount = $discount_type ? parse_quantity($this->input->post('discount')) : parse_decimals($this->input->post('discount'));
        $tax_type = $this->input->post('tax_type');
        $tax = $tax_type ? parse_quantity($this->input->post('tax')) : parse_decimals($this->input->post('tax'));

		$receiving_quantity = $this->input->post('receiving_quantity');

		if($this->form_validation->run() != FALSE)
		{
            $this->receiving_lib->edit_item($item_id, $description, $serialnumber, $quantity, $discount, $discount_type, $price, $receiving_quantity, $tax, $tax_type);
		}
		else
		{
			$data['error']=$this->lang->line('receivings_error_editing_item');
		}

		$this->_reload($data);
	}
	
	public function edit($receiving_id)
	{
		$data = array();

		$data['suppliers'] = array('' => 'No Supplier');
		foreach($this->Supplier->get_all()->result() as $supplier)
		{
			$data['suppliers'][$supplier->person_id] = $this->xss_clean($supplier->first_name . ' ' . $supplier->last_name);
		}
	
		$data['employees'] = array();
		foreach($this->Employee->get_all()->result() as $employee)
		{
			$data['employees'][$employee->person_id] = $this->xss_clean($employee->first_name . ' '. $employee->last_name);
		}
	
		$receiving_info = $this->xss_clean($this->Receiving->get_info($receiving_id)->row_array());
		$data['selected_supplier_name'] = !empty($receiving_info['supplier_id']) ? $receiving_info['company_name'] : '';
		$data['selected_supplier_id'] = $receiving_info['supplier_id'];
		$data['receiving_info'] = $receiving_info;
	
		$this->load->view('receivings/form', $data);
	}

	public function delete_item($item_number)
	{
		$this->receiving_lib->delete_item($item_number);

		$this->_reload();
	}
	
	public function delete($receiving_id = -1, $update_inventory = TRUE) 
	{
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		$receiving_ids = $receiving_id == -1 ? $this->input->post('ids') : array($receiving_id);
	
		if($this->Receiving->delete_list($receiving_ids, $employee_id, $update_inventory))
		{
			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('receivings_successfully_deleted') . ' ' .
							count($receiving_ids) . ' ' . $this->lang->line('receivings_one_or_multiple'), 'ids' => $receiving_ids));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('receivings_cannot_be_deleted')));
		}
	}

	public function remove_supplier()
	{
		$this->receiving_lib->clear_reference();
		$this->receiving_lib->remove_supplier();

		$this->_reload();
	}

	public function complete()
	{
		$data = array();
		
		$data['cart'] = $this->receiving_lib->get_cart();
		$data['total'] = $this->receiving_lib->get_total();
		$data['transaction_time'] = to_datetime(time());
		$data['mode'] = $this->receiving_lib->get_mode();
		$data['comment'] = $this->receiving_lib->get_comment();
		$data['reference'] = $this->receiving_lib->get_reference();
		$data['payment_type'] = $this->input->post('payment_type');
		// Single payment flow only; amount_tendered optional
		$amount_tendered_post = $this->input->post('amount_tendered');
		$data['amount_tendered'] = ($amount_tendered_post !== NULL && $amount_tendered_post !== '') ? parse_decimals($amount_tendered_post) : 0;
		$data['amount_change'] = to_currency($data['amount_tendered'] - $data['total']);
		$data['show_stock_locations'] = $this->Stock_location->show_locations('receivings');
		$data['stock_location'] = $this->receiving_lib->get_stock_source();
		if($this->input->post('amount_tendered') != NULL)
		{
			$data['amount_tendered'] = $this->input->post('amount_tendered');
			$data['amount_change'] = to_currency($data['amount_tendered'] - $data['total']);
		}
		
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		$employee_info = $this->Employee->get_info($employee_id);
		$data['employee'] = $employee_info->first_name . ' ' . $employee_info->last_name;

		$supplier_info = '';
		$supplier_id = $this->receiving_lib->get_supplier();
		if($supplier_id != -1)
		{
			$supplier_info = $this->Supplier->get_info($supplier_id);
			$data['supplier'] = $supplier_info->company_name;
			$data['first_name'] = $supplier_info->first_name;
			$data['last_name'] = $supplier_info->last_name;
			$data['supplier_email'] = $supplier_info->email;
			$data['supplier_address'] = $supplier_info->address_1;
			if(!empty($supplier_info->zip) or !empty($supplier_info->city))
			{
				$data['supplier_location'] = $supplier_info->zip . ' ' . $supplier_info->city;				
			}
			else
			{
				$data['supplier_location'] = '';
			}
		}

		// SAVE receiving to database (model inserts one payment row based on posted amount)
		$data['receiving_id'] = 'RECV ' . $this->Receiving->save($data['cart'], $supplier_id, $employee_id, $data['comment'], $data['reference'], $data['payment_type'], $data['stock_location']);

		$data = $this->xss_clean($data);

		if($data['receiving_id'] == 'RECV -1')
		{
			$data['error_message'] = $this->lang->line('receivings_transaction_failed');
		}
		else
		{
			$data['barcode'] = $this->barcode_lib->generate_receipt_barcode($data['receiving_id']);
			
			// Get the correct total from the database after saving
			$receiving_id_num = str_replace('RECV ', '', $data['receiving_id']);
			$receiving_total_info = $this->Receiving->get_receiving_info($receiving_id_num);
			if($receiving_total_info) {
				$data['total'] = $receiving_total_info->total;
			}
		}

		$data['print_after_sale'] = $this->receiving_lib->is_print_after_sale();

		$this->load->view("receivings/receipt",$data);

		$this->receiving_lib->clear_all();
	}

	public function requisition_complete()
	{
		if($this->receiving_lib->get_stock_source() != $this->receiving_lib->get_stock_destination()) 
		{
			foreach($this->receiving_lib->get_cart() as $item)
			{
				$this->receiving_lib->delete_item($item['line']);
				$this->receiving_lib->add_item($item['item_id'], $item['quantity'], $this->receiving_lib->get_stock_destination(), $item['discount_type']);
				$this->receiving_lib->add_item($item['item_id'], -$item['quantity'], $this->receiving_lib->get_stock_source(), $item['discount_type']);
			}
			
			$this->complete();
		}
		else 
		{
			$data['error'] = $this->lang->line('receivings_error_requisition');

			$this->_reload($data);	
		}
	}
	
	public function receipt($receiving_id)
	{
		$receiving_info = $this->Receiving->get_info($receiving_id)->row_array();
		$this->receiving_lib->copy_entire_receiving($receiving_id);
		$data['cart'] = $this->receiving_lib->get_cart();
		
		// Use the correct total from the database that includes taxes and discounts
		$receiving_total_info = $this->Receiving->get_receiving_info($receiving_id);
		$data['total'] = $receiving_total_info ? $receiving_total_info->total : $this->receiving_lib->get_total();
		
		$data['mode'] = $this->receiving_lib->get_mode();
		$data['transaction_time'] = to_datetime(strtotime($receiving_info['receiving_time']));
		$data['show_stock_locations'] = $this->Stock_location->show_locations('receivings');
		$data['payment_type'] = $receiving_info['payment_type'];
		$data['reference'] = $this->receiving_lib->get_reference();
		$data['receiving_id'] = 'RECV ' . $receiving_id;
		$data['barcode'] = $this->barcode_lib->generate_receipt_barcode($data['receiving_id']);
		$employee_info = $this->Employee->get_info($receiving_info['employee_id']);
		$data['employee'] = $employee_info->first_name . ' ' . $employee_info->last_name;

		// Debug: Check what payment data is available for this receiving
		log_message('debug', '=== RECEIPT GENERATION DEBUG ===');
		log_message('debug', 'Receiving ID: ' . $receiving_id);
		log_message('debug', 'Original payment_type from receiving: ' . $receiving_info['payment_type']);
		
		$payments = $this->Receiving->get_receiving_payments($receiving_id);
		log_message('debug', 'Payments found in receivings_payments table: ' . ($payments ? $payments->num_rows() : 0));
		if($payments && $payments->num_rows() > 0) {
			foreach($payments->result() as $payment) {
				log_message('debug', 'Payment - ID: ' . $payment->payment_id . ', Type: ' . $payment->payment_type . ', Amount: ' . $payment->payment_amount . ', Time: ' . $payment->payment_time);
			}
		}

		$supplier_id = $this->receiving_lib->get_supplier();
		if($supplier_id != -1)
		{
			$supplier_info = $this->Supplier->get_info($supplier_id);
			$data['supplier'] = $supplier_info->company_name;
			$data['first_name'] = $supplier_info->first_name;
			$data['last_name'] = $supplier_info->last_name;
			$data['supplier_email'] = $supplier_info->email;
			$data['supplier_address'] = $supplier_info->address_1;
			if(!empty($supplier_info->zip) or !empty($supplier_info->city))
			{
				$data['supplier_location'] = $supplier_info->zip . ' ' . $supplier_info->city;				
			}
			else
			{
				$data['supplier_location'] = '';
			}
		}

		$data['print_after_sale'] = FALSE;

		$data = $this->xss_clean($data);
		
		$this->load->view("receivings/receipt", $data);

		$this->receiving_lib->clear_all();
	}

	private function _reload($data = array())
	{
		$data['cart'] = $this->receiving_lib->get_cart();
		$data['modes'] = array('receive' => $this->lang->line('receivings_receiving'), 'return' => $this->lang->line('receivings_return'));
		$data['mode'] = $this->receiving_lib->get_mode();
		$data['stock_locations'] = $this->Stock_location->get_allowed_locations('receivings');
		$data['show_stock_locations'] = count($data['stock_locations']) > 1;
		if($data['show_stock_locations']) 
		{
			$data['modes']['requisition'] = $this->lang->line('receivings_requisition');
			$data['stock_source'] = $this->receiving_lib->get_stock_source();
			$data['stock_destination'] = $this->receiving_lib->get_stock_destination();
		}

		$data['total'] = $this->receiving_lib->get_total();
		$data['items_module_allowed'] = $this->Employee->has_grant('items', $this->Employee->get_logged_in_employee_info()->person_id);
		$data['comment'] = $this->receiving_lib->get_comment();
		$data['reference'] = $this->receiving_lib->get_reference();
		$data['payment_options'] = $this->Receiving->get_payment_options();

		$supplier_id = $this->receiving_lib->get_supplier();
		$supplier_info = '';
		if($supplier_id != -1)
		{
			$supplier_info = $this->Supplier->get_info($supplier_id);
			$data['supplier'] = $supplier_info->company_name;
			$data['first_name'] = $supplier_info->first_name;
			$data['last_name'] = $supplier_info->last_name;
			$data['supplier_email'] = $supplier_info->email;
			$data['supplier_address'] = $supplier_info->address_1;
			if(!empty($supplier_info->zip) or !empty($supplier_info->city))
			{
				$data['supplier_location'] = $supplier_info->zip . ' ' . $supplier_info->city;				
			}
			else
			{
				$data['supplier_location'] = '';
			}
		}
		
		$data['print_after_sale'] = $this->receiving_lib->is_print_after_sale();

		$data = $this->xss_clean($data);

		$this->load->view("receivings/receiving", $data);
	}
	
	public function save($receiving_id = -1)
	{
		$newdate = $this->input->post('date');
		
		$date_formatter = date_create_from_format($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), $newdate);
		$receiving_time = $date_formatter->format('Y-m-d H:i:s');

		$receiving_data = array(
			'receiving_time' => $receiving_time,
			'supplier_id' => $this->input->post('supplier_id') ? $this->input->post('supplier_id') : NULL,
			'employee_id' => $this->input->post('employee_id'),
			'comment' => $this->input->post('comment'),
			'reference' => $this->input->post('reference') != '' ? $this->input->post('reference') : NULL
		);

		$this->Inventory->update('RECV '.$receiving_id, ['trans_date' => $receiving_time]);
		if($this->Receiving->update($receiving_data, $receiving_id))
		{
			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('receivings_successfully_updated'), 'id' => $receiving_id));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('receivings_unsuccessfully_updated'), 'id' => $receiving_id));
		}
	}

	public function cancel_receiving()
	{
		$this->receiving_lib->clear_all();

		$this->_reload();
	}
}
?>
