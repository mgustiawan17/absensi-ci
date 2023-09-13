<?php

class User_model extends CI_Model{
	private $table_name = 't_karyawan';
	private $table_pk = 'id_karyawan';
	private $table_status = 't_karyawan.active';
	private $table_uc = 't_user_type';
	private $table_uc_pk = 'id_jabatan';

	public function __construct(){
		parent::__construct();
	}

	public function get_by_id($user_id){
		$this->db->select('id_karyawan,
							nik,
							rfid,
							password,
							nama,
							tempat_lahir,
							tanggal_lahir,
							jenis_kelamin,
							alamat,
							no_telp,
							no_handphone,
							email,
							status_perkawinan,
							id_golongan,
							id_divisi,
							id_jam_kerja,
							tanggal_masuk,
							t_karyawan.active,
							nama_user_type,
							t_karyawan.id_jabatan,
							foto');
		$this->db->from($this->table_name);
		$this->db->join($this->table_uc,$this->table_name.'.id_jabatan = '.$this->table_uc.'.'.$this->table_uc_pk,'INNER');
		$this->db->where($this->table_pk,$user_id);
		$this->db->where($this->table_status,'1');
		return $this->db->get();
	}

	public function get_by_nik($nik){
		$this->db->select('id_karyawan,
							nik,
							rfid,
							password,
							nama,
							tempat_lahir,
							tanggal_lahir,
							jenis_kelamin,
							alamat,
							no_telp,
							no_handphone,
							email,
							status_perkawinan,
							id_golongan,
							id_divisi,
							id_jam_kerja,
							tanggal_masuk,
							t_karyawan.active,
							nama_user_type,
							t_karyawan.id_jabatan,
							foto');
		$this->db->from($this->table_name);
		$this->db->join($this->table_uc,$this->table_name.'.id_jabatan = '.$this->table_uc.'.'.$this->table_uc_pk,'INNER');
		$this->db->where('nik',$nik);
		$this->db->where($this->table_status,'1');
		return $this->db->get();	
	}

	public function get_by_rfid($rfid){
		$this->db->select('id_karyawan,
							nik,
							rfid,
							password,
							nama,
							tempat_lahir,
							tanggal_lahir,
							jenis_kelamin,
							alamat,
							no_telp,
							no_handphone,
							email,
							status_perkawinan,
							id_golongan,
							id_divisi,
							id_jam_kerja,
							tanggal_masuk,
							t_karyawan.active,
							nama_user_type,
							t_karyawan.id_jabatan,
							foto');
		$this->db->from($this->table_name);
		$this->db->join($this->table_uc,$this->table_name.'.id_jabatan = '.$this->table_uc.'.'.$this->table_uc_pk,'INNER');
		$this->db->where('rfid',$rfid);
		$this->db->where($this->table_status,'1');
		return $this->db->get();	
	}

	public function get_all($paging=true,$start=0,$limit=10){
		$this->db->select('id_karyawan,nik,nama,nama_jabatan,nama_divisi,t_karyawan.active,nama_user_type,foto');
		$this->db->from($this->table_name);
		$this->db->join($this->table_uc,$this->table_name.'.id_jabatan = '.$this->table_uc.'.'.$this->table_uc_pk,'INNER');
		$this->db->join('t_jabatan','t_jabatan.id_jabatan = t_karyawan.id_jabatan','INNER');
		$this->db->join('t_divisi','t_divisi.id_divisi = t_karyawan.id_divisi','INNER');
		$this->db->join('t_golongan','t_golongan.id_golongan = t_karyawan.id_golongan','INNER');
		if($paging==true){
			$this->db->limit($limit,$start);
		}
		$this->db->where($this->table_status,'1');
		$this->db->order_by($this->table_pk,'DESC');
		return $this->db->get();	
	}

	public function get_last_id(){
		$this->db->select($this->table_pk);
		$this->db->from($this->table_name);
		$this->db->order_by($this->table_pk,'DESC');
		$this->db->limit(1);

		return $this->db->get();
	}

	public function search_user($nik){
		$this->db->select('id_karyawan,
							nik,
							rfid,
							password,
							nama,
							tempat_lahir,
							tanggal_lahir,
							jenis_kelamin,
							alamat,
							no_telp,
							no_handphone,
							email,
							status_perkawinan,
							id_golongan,
							id_divisi,
							id_jam_kerja,
							tanggal_masuk,
							t_karyawan.active,
							nama_user_type,
							t_karyawan.id_jabatan,
							foto');
		$this->db->from($this->table_name);
		$this->db->join($this->table_uc,$this->table_name.'.id_jabatan = '.$this->table_uc.'.'.$this->table_uc_pk,'INNER');
		$this->db->where("nik LIKE '%".$nik."%'");
		$this->db->where($this->table_status,'1');
		return $this->db->get();
	}

	public function save($data_user){
		$this->db->insert($this->table_name,$data_user);
	}

	public function update($user_id,$data_user){
		$this->db->where($this->table_pk,$user_id);
		$this->db->update($this->table_name,$data_user);
	}

	public function delete($user_id){
		$this->db->where($this->table_pk,$user_id);
		$this->db->delete($this->table_name);
	}

	public function check_login(){
		$username = $this->input->post('username');
		$password = sha1($this->input->post('password'));


		//first checking username
		if($this->get_by_nik($username)->num_rows() > 0){
			//username exist
			$data_user = $this->get_by_nik($username)->row_array();
			if(($data_user['password'] == $password) && ($data_user['active'] == '1')){
				//login success
				return true;
			}else{
				//login failed, password is not match
				return false;
			}
		}else{
			//login failed, username is not registered
			return false;
		}
	}

	public function check_absen($uname,$pwd){
		$username = $uname;
		$password = sha1($pwd);


		//first checking username
		if($this->get_by_nik($username)->num_rows() > 0){
			//username exist
			$data_user = $this->get_by_nik($username)->row_array();
			if(($data_user['password'] == $password) && ($data_user['active'] == '1')){
				//login success
				return true;
			}else{
				//login failed, password is not match
				return false;
			}
		}else{
			//login failed, username is not registered
			return false;
		}
	}

	public function check_rfid($rfid){
		$rfid = $rfid;
		// $password = sha1($pwd);


		//first checking RFID
		if($this->get_by_rfid($rfid)->num_rows() > 0){
			return true;

			// //username exist
			// $data_user = $this->get_by_rfid($rfid)->row_array();
			// if(($data_user['rfid'] == $rfid) && ($data_user['active'] == '1')){
			// 	//login success
			// 	return true;
			// }else{
			// 	//login failed, password is not match
			// 	return false;
			// }
		}else{
			//login failed, username is not registered
			return false;
		}
	}
}