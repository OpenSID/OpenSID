<?php

class Pembangunan_sumber_dana_model extends CI_Model
{
    protected $table = 'pembangunan_ref_sumber_dana';

    const ORDER_ABLE = [
        2 => 'sumber_dana',
        4 => 'created_at',
    ];

    public function get_data(string $search = '')
    {
        $builder = $this->db->from($this->table);

        if (empty($search)) {
            $condition = $builder;
        } else {
            $condition = $builder->group_start()
                ->like('sumber_dana', $search)
                ->group_end();
        }

        return $condition;
    }

    public function all()
    {
        return $this->db->get($this->table)->result();
    }

    public function insert(array $request)
    {
        return $this->db->insert($this->table, [
            'sumber_dana' => $request['sumber_dana'],
            'keterangan'  => $request['keterangan'],
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    public function update($id, array $request)
    {
        return $this->db->where('id', $id)->update($this->table, [
            'sumber_dana' => $request['sumber_dana'],
            'keterangan'  => $request['keterangan'],
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function find($id)
    {
        return $this->db->where('id', $id)
            ->get($this->table)
            ->row();
    }
}