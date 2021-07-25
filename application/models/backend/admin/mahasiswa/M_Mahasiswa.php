<?php

class M_Mahasiswa extends CI_Model
{
    private $_table = 'mahasiswa a'; // nama table
    private $_column_order = ['a.nim', 'a.nama', 'nama_prodi', 'nama_fakultas']; // field yang ada di dalam table
    private $_column_search = ['a.nim', 'a.nama', 'nama_prodi', 'nama_fakultas']; // field yang diizin untuk pencarian
    private $_order = ['a.nama' => 'asc']; // default order

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->select('a.*, b.nama as nama_prodi, c.nama as nama_fakultas');
        $this->db->from($this->_table);
        $this->db->join('prodi b', 'b.id = a.prodi_id');
        $this->db->join('fakultas c', 'c.id = a.fakultas_id');
        $this->db->where('a.is_active', '1');

        if ($this->input->get('fakultas_id')) {
            $this->db->where('a.fakultas_id', $this->input->get('fakultas_id'));
            if ($this->input->get('prodi_id')) {
                $this->db->where('a.prodi_id', $this->input->get('prodi_id'));
            }
        }

        $i = 0;
        foreach ($this->_column_search as $item) :
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
            $row[] = '<img src="' . base_url("uploads/mahasiswa/$field->foto_thumb") . '" alt="' . $field->nama . '" class="img-thumnail rounded-circle">';
            $row[] = $field->nim;
            $row[] = $field->nama;
            $row[] = $field->nama_prodi;
            $row[] = $field->nama_fakultas;
            $row[] = $field->angkatan;
            $row[] = '
                <button type="button" class="btn btn-success tombol_ubah mb-2" data-id="' . $field->id . '">
                    <i class="fa fa-edit"></i>
                    Ubah
                </button>
                <button type="button" class="btn btn-danger tombol_hapus" data-id="' . $field->id . '">
                    <i class="fa fa-trash"></i>
                    Hapus
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
        return $this->db->select('a.*, b.nama as nama_fakultas, c.nama as nama_prodi')
            ->join('fakultas b', 'b.id = a.fakultas_id')
            ->join('prodi c', 'c.id = a.prodi_id')
            ->get_where($table, $where)->row();
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
