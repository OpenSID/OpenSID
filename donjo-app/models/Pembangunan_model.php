<?php

class Pembangunan_model extends CI_Model
{
    protected $table = 'pembangunan';

    const ORDER_ABLE = [
        2 => 'sumber_dana',
        3 => 'judul',
        4 => 'volume',
        5 => 'tahun_anggaran',
        6 => 'pelaksana_kegiatan',
        7 => 'lokasi',
        8 => 'keterangan',
        9 => 'created_at'
    ];

    public function get_data(string $search = '')
    {
        $builder = $this->db->from($this->table);

        if (empty($search)) {
            $condition = $builder;
        } else {
            $condition = $builder->group_start()
                ->like('sumber_dana', $search)
                ->or_like('judul', $search)
                ->or_like('keterangan', $search)
                ->or_like('volume', $search)
                ->or_like('tahun_anggaran', $search)
                ->or_like('pelaksana_kegiatan', $search)
                ->or_like('lokasi', $search)
                ->group_end();
        }

        return $condition;
    }

    public function insert(array $request)
    {
        $this->db->insert($this->table, [
            'sumber_dana'     => $request['sumber_dana'],
            'judul'              => $request['judul'],
            'volume'             => $request['volume'],
            'tahun_anggaran'     => $request['tahun_anggaran'],
            'pelaksana_kegiatan' => $request['pelaksana_kegiatan'],
            'lokasi'             => $request['lokasi'],
            'keterangan'         => $request['keterangan'],
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s'),
        ]);
    }

    public function update($id, array $request)
    {
        return $this->db->where('id', $id)->update($this->table, [
            'sumber_dana'        => $request['sumber_dana'],
            'judul'              => $request['judul'],
            'volume'             => $request['volume'],
            'tahun_anggaran'     => $request['tahun_anggaran'],
            'pelaksana_kegiatan' => $request['pelaksana_kegiatan'],
            'lokasi'             => $request['lokasi'],
            'keterangan'         => $request['keterangan'],
            'updated_at'         => date('Y-m-d H:i:s'),
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