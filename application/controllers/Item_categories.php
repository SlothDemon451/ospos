<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Item_categories extends Secure_Controller
{
    public function __construct()
    {
        parent::__construct('item_categories');
        $this->load->model('Item_category');
    }

    public function index()
    {
        $data['table_headers'] = $this->xss_clean(get_item_category_manage_table_headers());
        $this->load->view('item_categories/manage', $data);
    }

    public function search()
    {
        $search = $this->input->get('search');
        $limit  = $this->input->get('limit');
        $offset = $this->input->get('offset');
        $sort   = $this->input->get('sort');
        $order  = $this->input->get('order');

        $categories = $this->Item_category->search($search, $limit, $offset, $sort, $order);
        $total_rows = $this->Item_category->get_found_rows($search);

        $data_rows = array();
        foreach($categories->result() as $category)
        {
            $data_rows[] = $this->xss_clean(get_item_category_data_row($category));
        }

        echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
    }

    public function get_row($row_id)
    {
        $data_row = $this->xss_clean(get_item_category_data_row($this->Item_category->get_info($row_id)));
        echo json_encode($data_row);
    }

    public function view($id = -1)
    {
        $data['category_info'] = $this->Item_category->get_info($id);
        $data['controller_name'] = $this->router->class;
        $this->load->view("item_categories/form", $data);
    }

    public function save($id = -1)
    {
        $category_data = array(
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description')
        );

        if($this->Item_category->save($category_data, $id))
        {
            $category_data = $this->xss_clean($category_data);
            if($id == -1)
            {
                echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('item_categories_successful_adding'), 'id' => $category_data['id']));
            }
            else
            {
                echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('item_categories_successful_updating'), 'id' => $id));
            }
        }
        else
        {
            echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('item_categories_error_adding_updating') . ' ' . $category_data['name'], 'id' => -1));
        }
    }

    public function delete()
    {
        $ids_to_delete = $this->input->post('ids');
        if($this->Item_category->delete_list($ids_to_delete))
        {
            echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('item_categories_successful_deleted') . ' ' . count($ids_to_delete) . ' ' . $this->lang->line('item_categories_one_or_multiple')));
        }
        else
        {
            echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('item_categories_cannot_be_deleted')));
        }
    }
} 