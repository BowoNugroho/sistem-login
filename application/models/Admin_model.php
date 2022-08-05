<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function getUsers($number, $offset)
    {
        $query = "SELECT * 
                    FROM `user` JOIN `role`
                    ON `user`.`role_id` = `role`.`id`";

        return $this->db->query($query, $number, $offset)->result_array();
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
}
