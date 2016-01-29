<?php

class Admin_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $state = $this->session->userdata('state');
        if ($state != "1") {
            redirect('index', 'refresh');
        }
    }

    function promote($id) {
        $this->db->select('state');
        $this->db->from('user');
        $this->db->where('user_id', $id);
        $query = $this->db->get();

        if ($query->result()[0]->state == 0) {
            $data['state'] = 3;
            $this->db->where('user_id', $id);
            $this->db->update('user', $data);
        } elseif ($query->result()[0]->state == 2) {
            $data['state'] = 1;
            $this->db->where('user_id', $id);
            $this->db->update('user', $data);
        } elseif ($query->result()[0]->state == 3) {
            $data['state'] = 2;
            $this->db->where('user_id', $id);
            $this->db->update('user', $data);
        }
    }

    function demote($id) {
        $this->db->select('state');
        $this->db->from('user');
        $this->db->where('user_id', $id);
        $query = $this->db->get();

        if ($query->result()[0]->state == 1) {
            $data['state'] = 2;
            $this->db->where('user_id', $id);
            $this->db->update('user', $data);
        } elseif ($query->result()[0]->state == 2) {
            $data['state'] = 3;
            $this->db->where('user_id', $id);
            $this->db->update('user', $data);
        } elseif ($query->result()[0]->state == 3) {
            $data['state'] = 0;
            $this->db->where('user_id', $id);
            $this->db->update('user', $data);
        }
    }

    function getAllUsers() {
        $this->db->select('user_id, name, mail, state, city');
        $this->db->from('user');
        $this->db->order_by("state", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    function deleteUserById($id) {
        $this->db->where('user_id', $id)->delete('user');
    }

    /*
     *
     * Contact functions ...
     *
     */

    function getAllMessages() {
        $this->db->select('id, name, mail, title, message');
        $this->db->from('ci_contact');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();

        return $query->result();
    }

    function getMessage($id) {
        $this->db->select('id, name, mail, title, message');
        $this->db->from('ci_contact');
        $this->db->where('id', $id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result()[0];
        }
    }

    function deleteMessageById($id) {
        $this->db->where('id', $id)->delete('ci_contact');
    }

}
