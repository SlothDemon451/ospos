<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Receiving class
 */

class Receiving extends CI_Model
{
	public function get_info($receiving_id)
	{
		$this->db->from('receivings');
		$this->db->join('people', 'people.person_id = receivings.supplier_id', 'LEFT');
		$this->db->join('suppliers', 'suppliers.person_id = receivings.supplier_id', 'LEFT');
		$this->db->where('receiving_id', $receiving_id);

		return $this->db->get();
	}

	public function get_receiving_info($receiving_id)
	{
		// Get receiving with calculated total including taxes and discounts
		$dec = totals_decimals();
		$total_expr = "ROUND(SUM(CASE WHEN ri.discount_type = " . PERCENT . " THEN ri.item_unit_price * ri.quantity_purchased * ri.receiving_quantity - ri.item_unit_price * ri.quantity_purchased * ri.receiving_quantity * ri.discount / 100 ELSE ri.item_unit_price * ri.quantity_purchased * ri.receiving_quantity - ri.discount END), $dec)";
		
		$this->db->select("r.*, $total_expr AS subtotal", FALSE);
		$this->db->from('receivings r');
		$this->db->join('receivings_items ri', 'ri.receiving_id = r.receiving_id');
		$this->db->where('r.receiving_id', $receiving_id);
		$this->db->group_by('r.receiving_id');
		
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$row = $query->row();
			// Calculate total including taxes
			$total_with_taxes = $this->get_receiving_total_with_taxes($receiving_id);
			$row->total = $total_with_taxes;
			return $row;
		}
		return FALSE;
	}

	public function get_receiving_total_with_taxes($receiving_id)
	{
		// Get receiving total including taxes and discounts
		$dec = totals_decimals();
		
		$this->db->select("
			ROUND(SUM(
				CASE 
					WHEN ri.discount_type = " . PERCENT . " 
					THEN ri.item_unit_price * ri.quantity_purchased * ri.receiving_quantity - ri.item_unit_price * ri.quantity_purchased * ri.receiving_quantity * ri.discount / 100 
					ELSE ri.item_unit_price * ri.quantity_purchased * ri.receiving_quantity - ri.discount 
				END
			), $dec) AS subtotal,
			ROUND(SUM(
				CASE 
					WHEN ri.tax_type = " . PERCENT . " 
					THEN (CASE 
						WHEN ri.discount_type = " . PERCENT . " 
						THEN (ri.item_unit_price * ri.quantity_purchased * ri.receiving_quantity - ri.item_unit_price * ri.quantity_purchased * ri.receiving_quantity * ri.discount / 100) * ri.tax / 100 
						ELSE (ri.item_unit_price * ri.quantity_purchased * ri.receiving_quantity - ri.discount) * ri.tax / 100 
					END)
					ELSE ri.tax 
				END
			), $dec) AS tax_total", FALSE);
		
		$this->db->from('receivings_items ri');
		$this->db->where('ri.receiving_id', $receiving_id);
		
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$row = $query->row();
			return $row->subtotal + $row->tax_total;
		}
		return 0;
	}

	public function get_receiving_outstanding_amount($receiving_id)
	{
		$receiving_info = $this->get_receiving_info($receiving_id);
		$total_payments = $this->get_receiving_total_payments($receiving_id);
		
		return max(0, $receiving_info->total - $total_payments);
	}
	
	public function get_receiving_payments($receiving_id)
	{
		$this->db->select('*');
		$this->db->from('receivings_payments');
		$this->db->where('receiving_id', $receiving_id);
		$this->db->order_by('payment_time', 'ASC');
		
		return $this->db->get();
	}
	
	public function get_receiving_total_payments($receiving_id)
	{
		$this->db->select('COALESCE(SUM(payment_amount), 0) as total_payments', FALSE);
		$this->db->from('receivings_payments');
		$this->db->where('receiving_id', $receiving_id);
		
		$result = $this->db->get()->row();
		return $result ? $result->total_payments : 0;
	}

	public function get_receiving_by_reference($reference)
	{
		$this->db->from('receivings');
		$this->db->where('reference', $reference);

		return $this->db->get();
	}

	public function is_valid_receipt($receipt_receiving_id)
	{
		if(!empty($receipt_receiving_id))
		{
			//RECV #
			$pieces = explode(' ', $receipt_receiving_id);

			if(count($pieces) == 2 && preg_match('/(RECV|KIT)/', $pieces[0]))
			{
				return $this->exists($pieces[1]);
			}
			else
			{
				return $this->get_receiving_by_reference($receipt_receiving_id)->num_rows() > 0;
			}
		}

		return FALSE;
	}

	public function exists($receiving_id)
	{
		$this->db->from('receivings');
		$this->db->where('receiving_id', $receiving_id);

		return ($this->db->get()->num_rows() == 1);
	}

	public function update($receiving_data, $receiving_id)
	{
		$this->db->where('receiving_id', $receiving_id);

		return $this->db->update('receivings', $receiving_data);
	}

	public function save($items, $supplier_id, $employee_id, $comment, $reference, $payment_type, $receiving_id = FALSE)
	{
		if(count($items) == 0)
		{
			return -1;
		}

		$receivings_data = array(
			'receiving_time' => date('Y-m-d H:i:s'),
			'supplier_id' => $this->Supplier->exists($supplier_id) ? $supplier_id : NULL,
			'employee_id' => $employee_id,
			'payment_type' => $payment_type,
			'comment' => $comment,
			'reference' => $reference
		);

		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->db->insert('receivings', $receivings_data);
		$receiving_id = $this->db->insert_id();

		$item_subtotal_total = 0; // for dues calculation
		foreach($items as $line=>$item)
		{
			$cur_item_info = $this->Item->get_info($item['item_id']);

            $receivings_items_data = array(
				'receiving_id' => $receiving_id,
				'item_id' => $item['item_id'],
				'line' => $item['line'],
				'description' => $item['description'],
				'serialnumber' => $item['serialnumber'],
				'quantity_purchased' => $item['quantity'],
				'receiving_quantity' => $item['receiving_quantity'],
				'discount' => $item['discount'],
				'discount_type' => $item['discount_type'],
                'tax' => isset($item['tax']) ? $item['tax'] : 0,
                'tax_type' => isset($item['tax_type']) ? $item['tax_type'] : 0,
				'item_cost_price' => $cur_item_info->cost_price,
				'item_unit_price' => $item['price'],
				'item_location' => $item['item_location']
			);

			$this->db->insert('receivings_items', $receivings_items_data);
			$item_subtotal_total += $receivings_items_data['item_unit_price'] * $receivings_items_data['quantity_purchased'] * ($receivings_items_data['receiving_quantity'] ?: 1);

			$items_received = $item['receiving_quantity'] != 0 ? $item['quantity'] * $item['receiving_quantity'] : $item['quantity'];

			// update cost price, if changed AND is set in config as wanted
			if($cur_item_info->cost_price != $item['price'] && $this->config->item('receiving_calculate_average_price') != FALSE)
			{
				$this->Item->change_cost_price($item['item_id'], $items_received, $item['price'], $cur_item_info->cost_price);
			}

			//Update stock quantity
			$item_quantity = $this->Item_quantity->get_item_quantity($item['item_id'], $item['item_location']);
			$this->Item_quantity->save(array('quantity' => $item_quantity->quantity + $items_received, 'item_id' => $item['item_id'],
											  'location_id' => $item['item_location']), $item['item_id'], $item['item_location']);

			$recv_remarks = 'RECV ' . $receiving_id;
			$inv_data = array(
				'trans_date' => date('Y-m-d H:i:s'),
				'trans_items' => $item['item_id'],
				'trans_user' => $employee_id,
				'trans_location' => $item['item_location'],
				'trans_comment' => $recv_remarks,
				'trans_inventory' => $items_received
			);

			$this->Inventory->insert($inv_data);

			$this->Attribute->copy_attribute_links($item['item_id'], 'receiving_id', $receiving_id);

			$supplier = $this->Supplier->get_info($supplier_id);
		}


		$this->db->insert('receivings_payments', array(
			'receiving_id' => $receiving_id,
			'payment_type' => $payment_type,
			'payment_amount' => $this->input->post('amount_tendered') !== NULL ? parse_decimals($this->input->post('amount_tendered')) : $item_subtotal_total,
			'employee_id' => $employee_id,
			'payment_time' => date('Y-m-d H:i:s')
		));

		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE)
		{
			return -1;
		}

		return $receiving_id;
	}

	public function delete_list($receiving_ids, $employee_id, $update_inventory = TRUE)
	{
		$success = TRUE;

		// start a transaction to assure data integrity
		$this->db->trans_start();

		foreach($receiving_ids as $receiving_id)
		{
			$success &= $this->delete($receiving_id, $employee_id, $update_inventory);
		}

		// execute transaction
		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	public function delete($receiving_id, $employee_id, $update_inventory = TRUE)
	{
		// start a transaction to assure data integrity
		$this->db->trans_start();

		if($update_inventory)
		{
			
			$items = $this->get_receiving_items($receiving_id)->result_array();
			foreach($items as $item)
			{
				// create query to update inventory tracking
				$inv_data = array(
					'trans_date' => date('Y-m-d H:i:s'),
					'trans_items' => $item['item_id'],
					'trans_user' => $employee_id,
					'trans_comment' => 'Deleting receiving ' . $receiving_id,
					'trans_location' => $item['item_location'],
					'trans_inventory' => $item['quantity_purchased'] * (-$item['receiving_quantity'])
				);
				// update inventory
				$this->Inventory->insert($inv_data);

				// update quantities
				$this->Item_quantity->change_quantity($item['item_id'], $item['item_location'], $item['quantity_purchased'] * (-$item['receiving_quantity']));
			}
		}

		// delete all items
		$this->db->delete('receivings_items', array('receiving_id' => $receiving_id));
		// delete sale itself
		$this->db->delete('receivings', array('receiving_id' => $receiving_id));

		// execute transaction
		$this->db->trans_complete();
	
		return $this->db->trans_status();
	}

	public function get_receiving_items($receiving_id)
	{
		$this->db->from('receivings_items');
		$this->db->where('receiving_id', $receiving_id);

		return $this->db->get();
	}
	
	public function get_supplier($receiving_id)
	{
		$this->db->from('receivings');
		$this->db->where('receiving_id', $receiving_id);

		return $this->Supplier->get_info($this->db->get()->row()->supplier_id);
	}

	public function get_payment_options()
	{
		// Use the global helper function for consistent payment options across the system
		return get_payment_options();
	}



	public function add_payment_to_receiving($receiving_id, $payment_data)
	{
		$payment_data['receiving_id'] = $receiving_id;
		$payment_data['payment_time'] = date('Y-m-d H:i:s');
		return $this->db->insert('receivings_payments', $payment_data);
	}

    public function get_receivings_manage($start_date, $end_date, $location_id = 'all', $due_only = FALSE, $rows = 0, $limit_from = 0, $sort = 'receiving_time', $order = 'desc', $count_only = FALSE)
    {
        // Robust direct aggregation without temp tables
        $dec = totals_decimals();
        $subtotal_expr = "ROUND(SUM(CASE WHEN ri.discount_type = " . PERCENT . " THEN ri.item_unit_price * ri.quantity_purchased * ri.receiving_quantity - ri.item_unit_price * ri.quantity_purchased * ri.receiving_quantity * ri.discount / 100 ELSE ri.item_unit_price * ri.quantity_purchased * ri.receiving_quantity - ri.discount END), $dec)";
        
        $tax_expr = "ROUND(SUM(CASE WHEN ri.tax_type = " . PERCENT . " THEN (CASE WHEN ri.discount_type = " . PERCENT . " THEN (ri.item_unit_price * ri.quantity_purchased * ri.receiving_quantity - ri.item_unit_price * ri.quantity_purchased * ri.receiving_quantity * ri.discount / 100) * ri.tax / 100 ELSE (ri.item_unit_price * ri.quantity_purchased * ri.receiving_quantity - ri.discount) * ri.tax / 100 END) ELSE ri.tax END), $dec)";
        
        $total_expr = "($subtotal_expr + $tax_expr)";

        $this->db->select("r.receiving_id AS receiving_id, MAX(r.receiving_time) AS receiving_time, MAX(CONCAT(e.first_name,' ',e.last_name)) AS employee_name, MAX(s.company_name) AS supplier_name, $total_expr AS total, IFNULL(rp.payments_total,0) AS payments_total, ($total_expr - IFNULL(rp.payments_total,0)) AS amount_due", FALSE);
        $this->db->from('receivings r');
        $this->db->join('receivings_items ri', 'ri.receiving_id = r.receiving_id');
        $this->db->join('people e', 'r.employee_id = e.person_id');
        $this->db->join('suppliers s', 'r.supplier_id = s.person_id', 'left');
        // Join a pre-aggregated payments subquery to avoid duplicating item rows per payment row
        $payments_subquery = '(SELECT receiving_id, SUM(payment_amount) AS payments_total FROM ' . $this->db->dbprefix('receivings_payments') . ' GROUP BY receiving_id) rp';
        $this->db->join($payments_subquery, 'rp.receiving_id = r.receiving_id', 'left', FALSE);

        if(empty($this->config->item('date_or_time_format')))
        {
            $this->db->where('DATE(r.receiving_time) BETWEEN ' . $this->db->escape($start_date) . ' AND ' . $this->db->escape($end_date));
        }
        else
        {
            $this->db->where('r.receiving_time BETWEEN ' . $this->db->escape(rawurldecode($start_date)) . ' AND ' . $this->db->escape(rawurldecode($end_date)));
        }

        if($location_id != 'all')
        {
            $this->db->where('ri.item_location', $location_id);
        }

        $this->db->group_by('r.receiving_id');
        $this->db->order_by($sort, $order);

        if($due_only)
        {
            $this->db->having('amount_due > 0');
        }

        if($count_only)
        {
            return $this->db->get()->num_rows();
        }

        if($rows > 0)
        {
            $this->db->limit($rows, $limit_from);
        }

        $query = $this->db->get();
        if($query === FALSE)
        {
            log_message('error', 'get_receivings_manage (direct) query failed: ' . print_r($this->db->error(), TRUE));
            return array();
        }
        return $query->result();
    }

	/*
	We create a temp table that allows us to do easy report/receiving queries
	*/
	public function create_temp_table(array $inputs)
	{
		if(empty($inputs['receiving_id']))
		{
			if(empty($this->config->item('date_or_time_format')))
			{
				$where = 'WHERE DATE(receiving_time) BETWEEN ' . $this->db->escape($inputs['start_date']) . ' AND ' . $this->db->escape($inputs['end_date']);
			}
			else
			{
				$where = 'WHERE receiving_time BETWEEN ' . $this->db->escape(rawurldecode($inputs['start_date'])) . ' AND ' . $this->db->escape(rawurldecode($inputs['end_date']));
			}
		}
		else
		{
			$where = 'WHERE receivings_items.receiving_id = ' . $this->db->escape($inputs['receiving_id']);
		}

		$this->db->query('CREATE TEMPORARY TABLE IF NOT EXISTS ' . $this->db->dbprefix('receivings_items_temp') .
			' (INDEX(receiving_date), INDEX(receiving_time), INDEX(receiving_id))
			(
				SELECT 
					MAX(DATE(receiving_time)) AS receiving_date,
					MAX(receiving_time) AS receiving_time,
					receivings_items.receiving_id AS receiving_id,
					MAX(comment) AS comment,
					MAX(item_location) AS item_location,
					MAX(reference) AS reference,
					MAX(payment_type) AS payment_type,
					MAX(employee_id) AS employee_id, 
					items.item_id AS item_id,
					MAX(receivings.supplier_id) AS supplier_id,
					MAX(quantity_purchased) AS quantity_purchased,
					MAX(receivings_items.receiving_quantity) AS item_receiving_quantity,
					MAX(item_cost_price) AS item_cost_price,
					MAX(item_unit_price) AS item_unit_price,
					MAX(discount) AS discount,
					MAX(discount_type) AS discount_type,
					receivings_items.line AS line,
					MAX(serialnumber) AS serialnumber,
					MAX(receivings_items.description) AS description,
					MAX(CASE WHEN receivings_items.discount_type = ' . PERCENT . ' THEN item_unit_price * quantity_purchased * receivings_items.receiving_quantity - item_unit_price * quantity_purchased * receivings_items.receiving_quantity * discount / 100 ELSE item_unit_price * quantity_purchased * receivings_items.receiving_quantity - discount END) AS subtotal,
					MAX(CASE WHEN receivings_items.discount_type = ' . PERCENT . ' THEN item_unit_price * quantity_purchased * receivings_items.receiving_quantity - item_unit_price * quantity_purchased * receivings_items.receiving_quantity * discount / 100 ELSE item_unit_price * quantity_purchased * receivings_items.receiving_quantity - discount END) AS total,
					MAX((CASE WHEN receivings_items.discount_type = ' . PERCENT . ' THEN item_unit_price * quantity_purchased * receivings_items.receiving_quantity - item_unit_price * quantity_purchased * receivings_items.receiving_quantity * discount / 100 ELSE item_unit_price * quantity_purchased * receivings_items.receiving_quantity - discount END) - (item_cost_price * quantity_purchased)) AS profit,
					MAX(item_cost_price * quantity_purchased * receivings_items.receiving_quantity ) AS cost
				FROM ' . $this->db->dbprefix('receivings_items') . ' AS receivings_items
				INNER JOIN ' . $this->db->dbprefix('receivings') . ' AS receivings
					ON receivings_items.receiving_id = receivings.receiving_id
				INNER JOIN ' . $this->db->dbprefix('items') . ' AS items
					ON receivings_items.item_id = items.item_id
				LEFT JOIN ' . $this->db->dbprefix('item_categories') . ' AS item_categories
					ON items.item_category_id = item_categories.id
				' . "
				$where
				" . '
				GROUP BY receivings_items.receiving_id, items.item_id, receivings_items.line
			)'
		);
	}
}
?>
