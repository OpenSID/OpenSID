<?php

class Pembangunan_model extends CI_Model
{
    protected $table = 'pembangunan';

    const ORDER_ABLE = [
        2  => 'j.jenis',
        3  => 's.sumber_dana',
        4  => 'p.judul',
        5  => 'p.volume',
        6  => 'p.tahun_anggaran',
        7  => 'p.pelaksana_kegiatan',
        8  => 'p.status',
        9  => 'p.keterangan',
        10 => 'p.created_at',
    ];

    public function get_data(string $search = '')
    {
        $builder = $this->db->select([
            'p.*',
            'j.jenis',
            's.sumber_dana'
        ])
        ->from("{$this->table} p")
        ->join('pembangunan_ref_jenis j', 'p.id_jenis = j.id')
        ->join('pembangunan_ref_sumber_dana s', 'p.id_sumber_dana = s.id');

        if (empty($search)) {
            $condition = $builder;
        } else {
            $condition = $builder->group_start()
                ->like('j.jenis', $search)
                ->or_like('s.sumber_dana', $search)
                ->or_like('p.judul', $search)
                ->or_like('p.keterangan', $search)
                ->or_like('p.volume', $search)
                ->or_like('p.tahun_anggaran', $search)
                ->or_like('p.pelaksana_kegiatan', $search)
                ->group_end();
        }

        return $condition;
    }

    public function insert(array $request)
    {
        return $this->db->insert($this->table, [
            'id_jenis'           => $request['id_jenis'],
            'id_sumber_dana'     => $request['id_sumber_dana'],
            'judul'              => $request['judul'],
            'volume'             => $request['volume'],
            'tahun_anggaran'     => $request['tahun_anggaran'],
            'pelaksana_kegiatan' => $request['pelaksana_kegiatan'],
            'keterangan'         => $request['keterangan'],
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s'),
        ]);
    }

    public function update($id, array $request)
    {
        return $this->db->where('id', $id)->update($this->table, [
            'id_jenis'           => $request['id_jenis'],
            'id_sumber_dana'     => $request['id_sumber_dana'],
            'judul'              => $request['judul'],
            'volume'             => $request['volume'],
            'tahun_anggaran'     => $request['tahun_anggaran'],
            'pelaksana_kegiatan' => $request['pelaksana_kegiatan'],
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
        return $this->db->select([
                'p.*',
                'j.jenis',
                's.sumber_dana'
            ])
            ->from("{$this->table} p")
            ->join('pembangunan_ref_jenis j', 'p.id_jenis = j.id')
            ->join('pembangunan_ref_sumber_dana s', 'p.id_sumber_dana = s.id')
            ->where('p.id', $id)
            ->get()
            ->row();
    }
}