<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Item_subcategories extends Secure_Controller
{
    public function __construct()
    {
        parent::__construct('item_subcategories');
        $this->load->model('Item_subcategory');
        $this->load->model('Item_category');
    }

    public function index()
    {
        $data['table_headers'] = $this->xss_clean(get_item_subcategory_manage_table_headers());
        $this->load->view('item_subcategories/manage', $data);
    }

    public function search()
    {
        $search = $this->input->get('search');
        $limit  = $this->input->get('limit');
        $offset = $this->input->get('offset');
        $sort   = $this->input->get('sort');
        $order  = $this->input->get('order');

        $subcategories = $this->Item_subcategory->search($search, $limit, $offset, $sort, $order);
        $total_rows = $this->Item_subcategory->get_found_rows($search);

        $data_rows = array();
        foreach($subcategories->result() as $subcategory)
        {
            $data_rows[] = $this->xss_clean(get_item_subcategory_data_row($subcategory));
        }

        echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
    }

    public function get_row($row_id)
    {
        $data_row = $this->xss_clean(get_item_subcategory_data_row($this->Item_subcategory->get_info($row_id)));
        echo json_encode($data_row);
    }

    public function view($id = -1)
    {
        $data['subcategory_info'] = $this->Item_subcategory->get_info($id);
        $data['controller_name'] = $this->router->class;
        
        // Load categories for dropdown - pass as array of objects
        $data['categories'] = $this->Item_category->get_all()->result();
        
        $this->load->view("item_subcategories/form", $data);
    }

    public function save($id = -1)
    {
        $subcategory_data = array(
            'category_id' => $this->input->post('category_id'),
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description')
        );

        if($this->Item_subcategory->save($subcategory_data, $id))
        {
            $subcategory_data = $this->xss_clean($subcategory_data);
            if($id == -1)
            {
                echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('item_subcategories_successful_adding'), 'id' => $subcategory_data['id']));
            }
            else
            {
                echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('item_subcategories_successful_updating'), 'id' => $id));
            }
        }
        else
        {
            echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('item_subcategories_error_adding_updating') . ' ' . $subcategory_data['name'], 'id' => -1));
        }
    }

    public function delete()
    {
        $ids_to_delete = $this->input->post('ids');
        if($this->Item_subcategory->delete_list($ids_to_delete))
        {
            echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('item_subcategories_successful_deleted') . ' ' . count($ids_to_delete) . ' ' . $this->lang->line('item_subcategories_one_or_multiple')));
        }
        else
        {
            echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('item_subcategories_cannot_be_deleted')));
        }
    }
} 