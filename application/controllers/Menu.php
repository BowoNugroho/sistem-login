<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_model', 'menu');
    }
    public function index()
    {
        $data['title'] = 'Menu Management';
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();

        $data['menu'] = $this->db->get('user_menu')->result_array();

        //$this->form_validation->set_rules('menu', 'Menu', 'required');

        //if ($this->form_validation->run() == false) {
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('templates/footer');
        // } else {
        //     $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
        //     $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        //     New Menu Added!</div>');
        //     redirect('menu');
        // }
    }
    // mengubah bentuk data ke bentuk json.
    public function getMenu()
    {
        $data = $this->menu->getMenu();
        // var_dump($data);
        // die;
        echo json_encode($data);
    }

    // validasi dengan ajax jquery
    public function saveMenu()
    {
        $this->form_validation->set_rules('menu', 'Menu', 'required');

        if ($this->form_validation->run()) {
            $data = [
                'success' => 1,

            ];
            $menu = $this->input->post();
            // save data
            $this->db->insert('user_menu', $menu);
            // mengembalikan dalam bentuk json
            echo json_encode($data);
        } else {
            // validasi 
            $data = [
                'error' => true,
                'menu_error' => form_error('menu')

            ];
            echo json_encode($data);
        }
    }

    public function getMenuById($id)
    {
        // mengambil data by id menu
        $data = $this->menu->getMenuById($id);
        echo json_encode($data);
    }
    public function updateMenu()
    {
        $this->form_validation->set_rules('menu_edit', 'Menu', 'required');

        if ($this->form_validation->run()) {
            $data = [
                'success' => 1

            ];
            $id = $this->input->post('id');
            $menu = $this->input->post('menu_edit');
            // update data
            $this->db->set('menu', $menu);
            $this->db->where('id', $id);
            $this->db->update('user_menu');
            // mengembalikan dalam bentuk json
            echo json_encode($data);
        } else {
            // validasi 
            $data = [
                'error' => true,
                'menu_edit_error' => form_error('menu_edit')

            ];
            echo json_encode($data);
        }
    }
    public function delete($id)
    {
        $data = $this->menu->deleteMenuById($id);
        echo json_encode($data);
    }
    public function subMenu()
    {
        $data['title'] = 'Submenu Management';
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();

        $data['subMenu'] = $this->menu->getSubmenu();
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'Url', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/subMenu', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active')
            ];
            $this->db->insert('user_sub_menu', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            New Submenu Added!</div>');
            redirect('menu/submenu');
        }
    }
}
