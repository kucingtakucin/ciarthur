<?php

class M_NamaModel extends CI_Model
{
    private $_table = 'nama_table a'; // nama table
    private $_column_order = ['a.kolom_1', 'a.kolom_2']; // field yang ada di dalam table
    private $_column_search = ['a.kolom_1', 'a.kolom_2']; // field yang diizin untuk pencarian
    private $_order = ['a.kolom_1' => 'asc']; // default order

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->_table);

        $i = 0;
        foreach ($this->column_search as $item) :
            if ($this->input->get('search')['value']) : // jika datatable mengirimkan pencarian dengan metode GET
                if ($i === 0) : // looping awal
                    $this->db->group_start();
                    $this->db->like($item, $this->input->get('search')['value']);
                else :
                    $this->db->or_like($item, $this->input->get('search')['value']);
                endif;

                if (count($this->_column_search) - 1 == $i)
                    $this->db->group_end();
            endif;
            $i++;
        endforeach;

        if ($this->input->get('order')) {
            $this->db->order_by($this->_column_order[$this->input->get('order')['0']['column']], $this->input->get('order')['0']['dir']);
        } else if (isset($this->_order)) {
            $order = $this->_order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    private function _get_datatables()
    {
        $this->_get_datatables_query();
        if ($this->input->get('length') != -1)
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
        return $this->db->get()->result();
    }

    private function _count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    private function _count_all()
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

    public function generate_table()
    {
        $list = $this->_get_datatables();
        $data = [];
        $no = $this->input->get('start');
        foreach ($list as $field) :
            $no++;
            $row = [];

            $row[] = $no;
            $row[] = $field->kolom_1;
            $row[] = $field->kolom_2;
            $row[] = '
                <button type="button" class="btn btn-square btn-sm btn-danger tombol_hapus" data-id="' . $field->id . '">
                    <i class="fa fa-trash"></i>
                </button>
                <button type="button" class="btn btn-square btn-sm btn-success tombol_ubah" data-id="' . $field->id . '">
                    <i class="fa fa-edit"></i>
                </button>
            ';

            $data[] = $row;
        endforeach;

        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                "draw" => $this->input->get('draw'),
                "recordsTotal" => $this->_count_all(),
                "recordsFiltered" => $this->_count_filtered(),
                "data" => $data,
            ]));
    }

    public function insert($table, $data = [])
    {
        return $this->db->insert($table, $data);
    }

    public function get_where($table, $where = [])
    {
        return $this->db->get_where($table, $where)->row();
    }

    public function update($table, $data = [], $id = null)
    {
        return $this->db->update($table, $data, ['id' => $id]);
    }

    // public function delete($table, $id = null)
    // {
    //     return $this->db->delete($table, ['id' => $id]);
    // }
}
