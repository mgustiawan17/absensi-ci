<?php

class Presences_m extends CI_Model{
	private $table_name = 't_kehadiran';
	private $table_pk = 'id_kehadiran';
	private $table_status = 't_kehadiran.active';

	private $table_name1 = 't_karyawan';
	private $table_uc = 't_user_type';
	private $table_uc_pk = 'id_jabatan';

	public function __construct(){
		parent::__construct();
	}

	public function get_all($paging=true,$start=0,$limit=10){
		$this->db->select('id_kehadiran,id_karyawan,tanggal,jam_masuk,jam_keluar,hadir,id_alasan,keterangan');
		$this->db->from($this->table_name);
		$this->db->where($this->table_status,'1');
		if($paging==true){
			$this->db->limit($limit,$start);
		}
		return $this->db->get();
	}

	public function get_by_id($id_kehadiran){
		$this->db->select('id_kehadiran,t_kehadiran.id_karyawan,nama,tanggal,jam_masuk,jam_keluar,hadir,t_kehadiran.id_alasan,nama_alasan,keterangan');
		$this->db->from($this->table_name);
		$this->db->join('t_karyawan','t_karyawan.id_karyawan = '.$this->table_name.'.id_karyawan');
		$this->db->join('t_alasan','t_alasan.id_alasan = '.$this->table_name.'.id_alasan');
		$this->db->where($this->table_pk,$id_kehadiran);
		$this->db->where($this->table_status,'1');
		return $this->db->get();
	}

	public function get_by_date($tanggal,$id_karyawan){
		$this->db->select('id_kehadiran,t_kehadiran.id_karyawan,nama,tanggal,jam_masuk,jam_keluar,hadir,t_kehadiran.id_alasan,nama_alasan,keterangan');
		$this->db->from($this->table_name);
		$this->db->join('t_karyawan','t_karyawan.id_karyawan = '.$this->table_name.'.id_karyawan');
		$this->db->join('t_alasan','t_alasan.id_alasan = '.$this->table_name.'.id_alasan');
		$this->db->where($this->table_name.'.id_karyawan',$id_karyawan);
		$this->db->where('tanggal',$tanggal);
		$this->db->where($this->table_name.'.id_karyawan',$id_karyawan);
		$this->db->where($this->table_status,'1');
		return $this->db->get();
	}

	public function get_by_just_date($tanggal_start, $tanggal_end){
		$this->db->select('id_kehadiran,
		t_kehadiran.id_karyawan,
		nama,
		tanggal,
		jam_masuk,
		jam_keluar,
		hadir,
		t_kehadiran.id_alasan,
		t_alasan.nama_alasan,
		keterangan');
		$this->db->from('t_kehadiran');
		$this->db->join('t_karyawan','t_karyawan.id_karyawan = t_kehadiran.id_karyawan');
		$this->db->join('t_alasan','t_alasan.id_alasan = t_kehadiran.id_alasan');
		// $this->db->where($this->table_name.'.id_karyawan',$id_karyawan);
		$this->db->where('tanggal >=', $tanggal_start);
		$this->db->where('tanggal <=', $tanggal_end);
		$this->db->where($this->table_status, '1');
		// $this->db->where("tanggal BETWEEN '.$tanggal1.' AND '.$tanggal2.'");

		// $this->db->where($this->table_name.'.id_karyawan',$id_karyawan);
		// $this->db->where($this->table_status,'1');
		return $this->db->get();

		// $this->db->select('id_karyawan,
		// nik,
		// rfid,
		// password,
		// nama,
		// tempat_lahir,
		// tanggal_lahir,
		// jenis_kelamin,
		// alamat,
		// no_telp,
		// no_handphone,
		// email,
		// status_perkawinan,
		// id_golongan,
		// id_divisi,
		// id_jam_kerja,
		// tanggal_masuk,
		// t_karyawan.active,
		// nama_user_type,
		// t_karyawan.id_jabatan,
		// foto');
		// $this->db->from($this->table_name1);
		// $this->db->join($this->table_uc,$this->table_name1.'.id_jabatan = '.$this->table_uc.'.'.$this->table_uc_pk,'INNER');
		// $this->db->where('rfid',$tanggal1);
		// // $this->db->where($this->table_status,'1');
		// return $this->db->get();	
	}

	public function save($data_kehadiran){
		$this->db->insert($this->table_name,$data_kehadiran);
	}

	public function update($id_kehadiran,$data_kehadiran){
		$this->db->where($this->table_pk,$id_kehadiran);
		$this->db->update($this->table_name,$data_kehadiran);
	}

	public function delete($id_kehadiran){
		$this->db->where($this->table_pk,$id_kehadiran);
		$this->db->delete($this->table_name);
	}

	public function get_by_date_range($date_start, $date_end)
    {
        $this->db->select('id_kehadiran, t_kehadiran.id_karyawan, nama, tanggal, jam_masuk, jam_keluar, hadir, t_kehadiran.id_alasan, t_alasan.nama_alasan, keterangan');
        $this->db->from($this->table_name);
        $this->db->join('t_karyawan', 't_karyawan.id_karyawan = ' . $this->table_name . '.id_karyawan');
        $this->db->join('t_alasan', 't_alasan.id_alasan = ' . $this->table_name . '.id_alasan');
        $this->db->where('tanggal >=', $date_start);
        $this->db->where('tanggal <=', $date_end);
        $this->db->where($this->table_status, '1');
        return $this->db->get()->result_array();
    }

	public function get_name_by_id($id_karyawan)
    {
        $this->db->select('t_karyawan.nama');
		$this->db->from('t_kehadiran');
		$this->db->join('t_karyawan', 't_karyawan.id_karyawan = t_kehadiran.id_karyawan');
		$this->db->where('t_kehadiran.id_karyawan', $id_karyawan);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row->nama;
		}

		return null;
    }

	public function tampil_data()
	{
		$this->db->select('t_karyawan.nama AS nama_karyawan, t_kehadiran.tanggal, t_kehadiran.jam_masuk, t_kehadiran.jam_keluar, t_alasan.nama_alasan');
		$this->db->from('t_kehadiran');
		$this->db->join('t_karyawan', 't_karyawan.id_karyawan = t_kehadiran.id_karyawan');
		$this->db->join('t_alasan', 't_alasan.id_alasan = t_kehadiran.id_alasan');
		return $this->db->get()->result();
	}
}