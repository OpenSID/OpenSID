<?php

class Pembangunan_model extends CI_Model
{
    protected $table = 'pembangunan';

    const ENABLE = 1;
    CONST DISABLE = 0;

    const ORDER_ABLE = [
        3  => 'p.judul',
        4  => 'p.sumber_dana',
        6  => 'p.volume',
        7  => 'p.tahun_anggaran',
        8  => 'p.pelaksana_kegiatan',
        9  => 'p.lokasi',
        10 => 'p.keterangan',
        11 => 'p.created_at'
    ];

    public function get_data(string $search = '', $tahun = '')
    {
        $builder = $this->db->select([
            'p.*',
            '(CASE WHEN p.id_lokasi IS NOT NULL THEN CONCAT("RT ", w.rt, " / RW ", w.rw, " - ", w.dusun) ELSE p.lokasi END) AS alamat',
            '(CASE WHEN MAX(d.persentase) IS NOT NULL THEN MAX(d.persentase) ELSE CONCAT("belum ada progres") END) AS max_persentase',
        ])
        ->from("{$this->table} p")
        ->join('pembangunan_ref_dokumentasi d', 'd.id_pembangunan = p.id', 'left')
        ->join('tweb_wil_clusterdesa w', 'p.id_lokasi = w.id', 'left')
        ->group_by('p.id');

        if (empty($search)) {
            $search = $builder;
        } else {
            $search = $builder->group_start()
                ->like('p.sumber_dana', $search)
                ->or_like('p.judul', $search)
                ->or_like('p.keterangan', $search)
                ->or_like('p.volume', $search)
                ->or_like('p.tahun_anggaran', $search)
                ->or_like('p.pelaksana_kegiatan', $search)
                ->or_like('p.lokasi', $search)
                ->group_end();
        }

        $condition = $tahun === 'semua'
            ? $search
            : $search->where('p.tahun_anggaran', $tahun);

        return $condition;
    }

    public function insert(array $request)
    {
        return $this->db->insert($this->table, [
            'sumber_dana'        => $request['sumber_dana'],
            'judul'              => $request['judul'],
            'volume'             => $request['volume'],
            'tahun_anggaran'     => $request['tahun_anggaran'],
            'pelaksana_kegiatan' => $request['pelaksana_kegiatan'],
            'id_lokasi'          => $request['id_lokasi'] ?: null,
            'lokasi'             => $request['lokasi'] ?: null,
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
            'id_lokasi'          => $request['id_lokasi'] ?: null,
            'lokasi'             => $request['lokasi'] ?: null,
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
            '(CASE WHEN p.id_lokasi IS NOT NULL THEN CONCAT("RT ", w.rt, " / RW ", w.rw, " - ", w.dusun) ELSE p.lokasi END) AS alamat',
        ])
        ->from("{$this->table} p")
        ->join('tweb_wil_clusterdesa w', 'p.id_lokasi = w.id', 'left')
        ->where('p.id', $id)
        ->get()
        ->row();
    }

    public function list_dusun_rt_rw()
    {
        return $this->db->select(['id', 'rt', 'rw', 'dusun'])
            ->where('rt >', 0)
            ->order_by('dusun')
            ->get('tweb_wil_clusterdesa')
            ->result_array();
    }

    public function unlock($id)
    {
        return $this->db->set('status', static::ENABLE)
            ->where('id', $id)
            ->update($this->table);
    }

    public function lock($id)
    {
        return $this->db->set('status', static::DISABLE)
            ->where('id', $id)
            ->update($this->table);
    }
}