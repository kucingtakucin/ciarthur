<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Datatables
{
    private $_table;
    private $_column_order;
    private $_column_search;
    private $_order;
    private $_ci;

    public function __construct()
    {
        $this->_ci = &get_instance();
    }

    public function setTable($table)
    {
        $this->_table = $table;
    }

    public function setColumnOrder($column_order)
    {
        $this->_column_order = $column_order;
    }

    public function setColumnSearch($column_search)
    {
        $this->_column_search = $column_search;
    }

    public function setOrder($order)
    {
        $this->_order = $order;
    }

    private function _get_datatables_query($callback_query = null)
    {
        if (is_callable($callback_query)) {
            $callback_query($this->_table);
        }
        $this->_ci->db->from($this->_table);

        $i = 0;
        foreach ($this->_column_search as $item) :
            if ($this->_ci->input->get('search')['value']) : // jika datatable mengirimkan pencarian dengan metode GET
                if ($i === 0) : // looping awal
                    $this->_ci->db->group_start();
                    $this->_ci->db->like($item, $this->_ci->input->get('search')['value']);
                else :
                    $this->_ci->db->or_like($item, $this->_ci->input->get('search')['value']);
                endif;

                if (count($this->_column_search) - 1 == $i)
                    $this->_ci->db->group_end();
            endif;
            $i++;
        endforeach;

        if ($this->_ci->input->get('order')) {
            $this->_ci->db->order_by($this->_column_order[$this->_ci->input->get('order')['0']['column']], $this->_ci->input->get('order')['0']['dir']);
        } else if (isset($this->_order)) {
            $order = $this->_order;
            $this->_ci->db->order_by(key($order), $order[key($order)]);
        }
    }

    private function _get_datatables($callback_query = null)
    {
        $this->_get_datatables_query($callback_query);
        if ($this->_ci->input->get('length') != -1)
            $this->_ci->db->limit($this->_ci->input->get('length'), $this->_ci->input->get('start'));
        return $this->_ci->db->get()->result();
    }

    private function _count_filtered()
    {
        $this->_get_datatables_query();
        return $this->_ci->db->get()->num_rows();
    }

    private function _count_all()
    {
        $this->_get_datatables_query();
        return $this->_ci->db->count_all_results();
    }

    public function generateTable($callback_query = null)
    {
        return $this->_ci->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                "draw" => $this->_ci->input->get('draw'),
                "recordsTotal" => $this->_count_all(),
                "recordsFiltered" => $this->_count_filtered(),
                "data" => $this->_get_datatables($callback_query),
            ]));
    }
}
