<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Receiving library
 *
 * Library with utilities to manage receivings
 */

class Receiving_lib
{
	private $CI;
    private $payments = array();

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('ItemAttribute');
        // initialize payments from session
        if(!$this->CI->session->userdata('recv_payments'))
        {
            $this->CI->session->set_userdata('recv_payments', array());
        }
	}

	public function get_cart()
	{
		if(!$this->CI->session->userdata('recv_cart'))
		{
			$this->set_cart(array());
		}

		return $this->CI->session->userdata('recv_cart');
	}

	public function set_cart($cart_data)
	{
		$this->CI->session->set_userdata('recv_cart', $cart_data);
	}

	public function empty_cart()
	{
		$this->CI->session->unset_userdata('recv_cart');
	}

	public function get_supplier()
	{
		if(!$this->CI->session->userdata('recv_supplier'))
		{
			$this->set_supplier(-1);
		}

		return $this->CI->session->userdata('recv_supplier');
	}

	public function set_supplier($supplier_id)
	{
		$this->CI->session->set_userdata('recv_supplier', $supplier_id);
	}

	public function remove_supplier()
	{
		$this->CI->session->unset_userdata('recv_supplier');
	}

	public function get_mode()
	{
		if(!$this->CI->session->userdata('recv_mode'))
		{
			$this->set_mode('receive');
		}

		return $this->CI->session->userdata('recv_mode');
	}

	public function set_mode($mode)
	{
		$this->CI->session->set_userdata('recv_mode', $mode);
	}
	
	public function clear_mode()
	{
		$this->CI->session->unset_userdata('recv_mode');
	}

	public function get_stock_source()
	{
		if(!$this->CI->session->userdata('recv_stock_source'))
		{
			$this->set_stock_source($this->CI->Stock_location->get_default_location_id('receivings'));
		}

		return $this->CI->session->userdata('recv_stock_source');
	}
	
	public function get_comment()
	{
		// avoid returning a NULL that results in a 0 in the comment if nothing is set/available
		$comment = $this->CI->session->userdata('recv_comment');

		return empty($comment) ? '' : $comment;
	}
	
	public function set_comment($comment)
	{
		$this->CI->session->set_userdata('recv_comment', $comment);
	}
	
	public function clear_comment()
	{
		$this->CI->session->unset_userdata('recv_comment');
	}
   
	public function get_reference()
	{
		return $this->CI->session->userdata('recv_reference');
	}
	
	public function set_reference($reference)
	{
		$this->CI->session->set_userdata('recv_reference', $reference);
	}
	
	public function clear_reference()
	{
		$this->CI->session->unset_userdata('recv_reference');
	}
	
	public function is_print_after_sale()
	{
		return $this->CI->session->userdata('recv_print_after_sale') == 'true' ||
				$this->CI->session->userdata('recv_print_after_sale') == '1';
	}
	
	public function set_print_after_sale($print_after_sale)
	{
		return $this->CI->session->set_userdata('recv_print_after_sale', $print_after_sale);
	}
	
	public function set_stock_source($stock_source)
	{
		$this->CI->session->set_userdata('recv_stock_source', $stock_source);
	}
	
	public function clear_stock_source()
	{
		$this->CI->session->unset_userdata('recv_stock_source');
	}
	
	public function get_stock_destination()
	{
		if(!$this->CI->session->userdata('recv_stock_destination'))
		{
			$this->set_stock_destination($this->CI->Stock_location->get_default_location_id('receivings'));
		}

		return $this->CI->session->userdata('recv_stock_destination');
	}

	public function set_stock_destination($stock_destination)
	{
		$this->CI->session->set_userdata('recv_stock_destination', $stock_destination);
	}
	
	public function clear_stock_destination()
	{
		$this->CI->session->unset_userdata('recv_stock_destination');
	}

    public function add_item($item_id, $quantity = 1, $item_location = NULL, $discount = 0, $discount_type = 0, $price = NULL, $description = NULL, $serialnumber = NULL, $receiving_quantity = NULL, $receiving_id = NULL, $include_deleted = FALSE, $tax = 0, $tax_type = 0)
	{
		//make sure item exists in database.
		if(!$this->CI->Item->exists($item_id, $include_deleted))
		{
			//try to get item id given an item_number
			$item_id = $this->CI->Item->get_item_id($item_id, $include_deleted);

			if(!$item_id)
			{
				return FALSE;
			}
		}

		//Get items in the receiving so far.
		$items = $this->get_cart();

		//We need to loop through all items in the cart.
		//If the item is already there, get it's key($updatekey).
		//We also need to get the next key that we are going to use in case we need to add the
		//item to the list. Since items can be deleted, we can't use a count. we use the highest key + 1.

		$maxkey = 0;					//Highest key so far
		$itemalreadyinsale = FALSE;		//We did not find the item yet.
		$insertkey = 0;					//Key to use for new entry.
		$updatekey = 0;					//Key to use to update(quantity)

		foreach($items as $item)
		{
			//We primed the loop so maxkey is 0 the first time.
			//Also, we have stored the key in the element itself so we can compare.
			//There is an array public function to get the associated key for an element, but I like it better
			//like that!

			if($maxkey <= $item['line'])
			{
				$maxkey = $item['line'];
			}

			if($item['item_id'] == $item_id && $item['item_location'] == $item_location)
			{
				$itemalreadyinsale = TRUE;
				$updatekey = $item['line'];
			}
		}

		$insertkey = $maxkey+1;
		$item_info = $this->CI->Item->get_info($item_id,$item_location);
		//array records are identified by $insertkey and item_id is just another field.
		$price = $price != NULL ? $price : $item_info->cost_price;

		if($this->CI->config->item('multi_pack_enabled') == '1')
		{
			$item_info->name .= NAME_SEPARATOR . $item_info->pack_name;
		}

		if ($item_info->receiving_quantity == 0 || $item_info->receiving_quantity == 1)
		{
			$receiving_quantity_choices = array(1  => 'x1');
		}
		else
		{
			$receiving_quantity_choices = array(
				to_quantity_decimals($item_info->receiving_quantity) => 'x' . to_quantity_decimals($item_info->receiving_quantity),
				1  => 'x1');
		}

		// Always use form value for receiving_quantity (ship pack) - default to 1
		if(is_null($receiving_quantity) || $receiving_quantity == 0)
		{
			$receiving_quantity = 1; // Force default to 1 instead of using database value
		}

		$attribute_links = $this->CI->ItemAttribute->get_link_values($item_id, 'receiving_id', $receiving_id, ItemAttribute::SHOW_IN_RECEIVINGS)->row_object();

        $item = array($insertkey => array(
				'item_id' => $item_id,
				'item_location' => $item_location,
				'item_number' => $item_info->item_number,
				'stock_name' => $this->CI->Stock_location->get_location_name($item_location),
				'line' => $insertkey,
				'name' => $item_info->name,
				'description' => $description != NULL ? $description: $item_info->description,
				'serialnumber' => $serialnumber != NULL ? $serialnumber: '',
				'attribute_values' => $attribute_links->attribute_values,
				'attribute_dtvalues' => $attribute_links->attribute_dtvalues,
				'allow_alt_description' => $item_info->allow_alt_description,
				'is_serialized' => $item_info->is_serialized,
				'quantity' => $quantity,
				'discount' => $discount,
				'discount_type' => $discount_type,
                'tax' => $tax,
                'tax_type' => $tax_type,
				'in_stock' => $this->CI->Item_quantity->get_item_quantity($item_id, $item_location)->quantity,
				'price' => $price,
				'receiving_quantity' => $receiving_quantity,
				'receiving_quantity_choices' => $receiving_quantity_choices,
				'total' => $this->get_item_total($quantity, $price, $discount, $discount_type, $receiving_quantity)
			)
		);

		//Item already exists
		if($itemalreadyinsale)
		{
			$items[$updatekey]['quantity'] += $quantity;
            $items[$updatekey]['total'] = $this->get_item_total($items[$updatekey]['quantity'], $price, $discount, $discount_type, $items[$updatekey]['receiving_quantity'], isset($items[$updatekey]['tax']) ? $items[$updatekey]['tax'] : 0, isset($items[$updatekey]['tax_type']) ? $items[$updatekey]['tax_type'] : 0);
		}
		else
		{
			//add to existing array
			$items += $item;
		}

		$this->set_cart($items);

		return TRUE;
	}

    public function edit_item($line, $description, $serialnumber, $quantity, $discount, $discount_type, $price, $receiving_quantity, $tax = 0, $tax_type = 0)
	{
		$items = $this->get_cart();
		if(isset($items[$line]))
		{
			$line = &$items[$line];
			$line['description'] = $description;
			$line['serialnumber'] = $serialnumber;
			$line['quantity'] = $quantity;
			$line['receiving_quantity'] = $receiving_quantity;
			$line['discount'] = $discount;
			if(!is_null($discount_type))
			{
				$line['discount_type'] = $discount_type;
			}
			$line['price'] = $price;
            $line['tax'] = $tax;
            if(!is_null($tax_type))
            {
                $line['tax_type'] = $tax_type;
            }
            $line['total'] = $this->get_item_total($quantity, $price, $discount, $discount_type, $receiving_quantity, $line['tax'], isset($line['tax_type']) ? $line['tax_type'] : 0);
			$this->set_cart($items);
		}

		return FALSE;
	}

	public function delete_item($line)
	{
		$items = $this->get_cart();
		unset($items[$line]);
		$this->set_cart($items);
	}

	public function return_entire_receiving($receipt_receiving_id)
	{
		//RECV #
		$pieces = explode(' ', $receipt_receiving_id);
		if(preg_match("/(RECV|KIT)/", $pieces[0]))
		{
			$receiving_id = $pieces[1];
		} 
		else 
		{
			$receiving_id = $this->CI->Receiving->get_receiving_by_reference($receipt_receiving_id)->row()->receiving_id;
		}

		$this->empty_cart();
		$this->remove_supplier();
		$this->clear_comment();

        foreach($this->CI->Receiving->get_receiving_items($receiving_id)->result() as $row)
        {
            $this->add_item($row->item_id, -$row->quantity_purchased, $row->item_location, $row->discount, $row->discount_type, $row->item_unit_price, $row->description, $row->serialnumber, $row->receiving_quantity, $receiving_id, TRUE, isset($row->tax) ? $row->tax : 0, isset($row->tax_type) ? $row->tax_type : 0);
        }

		$this->set_supplier($this->CI->Receiving->get_supplier($receiving_id)->person_id);
	}

	public function add_item_kit($external_item_kit_id, $item_location, $discount, $discount_type)
	{
		//KIT #
		$pieces = explode(' ',$external_item_kit_id);
		$item_kit_id = count($pieces) > 1 ? $pieces[1] : $external_item_kit_id;
		
		foreach($this->CI->Item_kit_items->get_info($item_kit_id) as $item_kit_item)
		{
			$this->add_item($item_kit_item['item_id'], $item_kit_item['quantity'], $item_location, $discount, $discount_type);
		}
	}

	public function copy_entire_receiving($receiving_id)
	{
		$this->empty_cart();
		$this->remove_supplier();

        foreach($this->CI->Receiving->get_receiving_items($receiving_id)->result() as $row)
        {
            $this->add_item($row->item_id, $row->quantity_purchased, $row->item_location, $row->discount, $row->discount_type, $row->item_unit_price, $row->description, $row->serialnumber, $row->receiving_quantity, $receiving_id, TRUE, isset($row->tax) ? $row->tax : 0, isset($row->tax_type) ? $row->tax_type : 0);
        }

		$this->set_supplier($this->CI->Receiving->get_supplier($receiving_id)->person_id);
		//$this->set_reference($this->CI->Receiving->get_info($receiving_id)->row()->reference);
	}

	public function clear_all()
	{
		$this->clear_mode();
		$this->empty_cart();
		$this->remove_supplier();
		$this->clear_comment();
		$this->clear_reference();
        $this->empty_payments();
	}

    public function get_item_total($quantity, $price, $discount, $discount_type, $receiving_quantity, $tax = 0, $tax_type = 0)
	{
		$extended_quantity = bcmul($quantity, $receiving_quantity);
		$total = bcmul($extended_quantity, $price);
		$discount_amount = $discount;
		if($discount_type == PERCENT)
		{
			$discount_fraction = bcdiv($discount, 100);
			$discount_amount = bcmul($total, $discount_fraction);
		}
        $discounted_total = bcsub($total, $discount_amount);

        // Tax calculation on discounted total
        $tax_amount = $tax;
        if($tax_type == PERCENT)
        {
            $tax_fraction = bcdiv($tax, 100);
            $tax_amount = bcmul($discounted_total, $tax_fraction);
        }

        return bcadd($discounted_total, $tax_amount);
	}

	public function get_total()
	{
		$total = 0;
		foreach($this->get_cart() as $item)
		{
			$total = bcadd($total, $this->get_item_total(($item['quantity']), $item['price'], $item['discount'], $item['discount_type'], $item['receiving_quantity'], isset($item['tax'])?$item['tax']:0, isset($item['tax_type'])?$item['tax_type']:0));
		}
		
        return $total;
	}

    // Payments API (modeled after Sale_lib)
    public function get_payments()
    {
        return $this->CI->session->userdata('recv_payments');
    }

    public function get_payments_total()
    {
        $sum = 0.0;
        foreach($this->get_payments() as $payment)
        {
            $amount = isset($payment['payment_amount']) ? $payment['payment_amount'] : 0;
            $sum += (float)$amount;
        }
        return $sum;
    }

    public function add_payment($payment_type, $payment_amount)
    {
        $payments = $this->get_payments();
        $payments[] = array('payment_type' => $payment_type, 'payment_amount' => parse_decimals($payment_amount));
        $this->CI->session->set_userdata('recv_payments', $payments);
    }

    public function delete_payment($index)
    {
        $payments = $this->get_payments();
        if(isset($payments[$index]))
        {
            unset($payments[$index]);
            $this->CI->session->set_userdata('recv_payments', array_values($payments));
        }
    }

    public function empty_payments()
    {
        $this->CI->session->set_userdata('recv_payments', array());
    }

    public function get_amount_due()
    {
        return round($this->get_total() - $this->get_payments_total(), totals_decimals(), PHP_ROUND_HALF_UP);
    }
}

?>
