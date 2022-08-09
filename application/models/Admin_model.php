<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function get_users()
    {
        $query = "SELECT * 
                    FROM `user` JOIN `role`
                    ON `user`.`role_id` = `role`.`id`";

        return $this->db->query($query)->result_array();
    }
    // pagination
    public function data($number, $offset)
    {
        return $query = $this->db->get('user', $number, $offset)->result_array();
    }
    // menghitung jumlah data
    public function count()
    {
        return $this->db->get('user')->num_rows();
    }
    // searching data user
    public function get_data_user($keyword)
    {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->like('name', $keyword);
        $this->db->or_like('email', $keyword);
        return $this->db->get()->result();
    }
}
