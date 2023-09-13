<?php

class Presences extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('user_model', 'master/jam_kerja_m', 'presences_m', 'workday_plan_model'));
		$this->load->library('template');
	}

	public function index()
	{
		$data = array();
		$user_id = $this->session->userdata('user_id');
		$data['user'] = $this->user_model->get_by_id($user_id)->row_array();
		$data['jam_kerja'] = $this->jam_kerja_m->get_by_id($data['user']['id_jam_kerja'])->row_array();

		$selisih_hari = 30; //jumlah hari yg akan ditampilkan

		$today = date('Y-m-d');
		$minus = mktime(0, 0, 0, date('m'), date('d') - $selisih_hari, date('Y'));
		$pastmonth = date('Y-m-d', $minus);

		$data['date_start'] = $this->tanggal->tanggal_indo($pastmonth);
		$data['date_end'] = $this->tanggal->tanggal_indo($today);
		if ($_POST) {
			$today = $this->tanggal->tanggal_simpan_db($this->input->post('presences_date_end'));
			$pastmonth = $this->tanggal->tanggal_simpan_db($this->input->post('presences_date_start'));

			$selisih_hari = $this->tanggal->get_selisih($today, $pastmonth);

			$data['date_start'] = $this->input->post('presences_date_start');
			$data['date_end'] = $this->input->post('presences_date_end');
		}

		$data['kehadiran'] = array();
		for ($i = $selisih_hari; $i >= 0; $i--) {
			$temp = mktime(0, 0, 0, $this->tanggal->get_only_month($today), $this->tanggal->get_only_date($today) - $i, $this->tanggal->get_only_year($today));
			$tanggal = date('Y-m-d', $temp);

			$data['kehadiran'][$i]['tanggal'] = $this->tanggal->tanggal_indo_monthtext($tanggal);
			$data['kehadiran'][$i]['hari'] = $this->tanggal->get_hari($tanggal);

			//get data kehadiran
			if ($this->presences_m->get_by_date($tanggal, $user_id)->num_rows() > 0) {
				$present = $this->presences_m->get_by_date($tanggal, $user_id)->row_array();
				$data['kehadiran'][$i]['datang'] = $this->tanggal->get_jam($present['jam_masuk']);
				$data['kehadiran'][$i]['pulang'] = $this->tanggal->get_jam($present['jam_keluar']);
				$data['kehadiran'][$i]['alasan'] = $present['nama_alasan'] . '(' . $present['keterangan'] . ')';
				if ($present['id_alasan'] == '5') {
					$data['kehadiran'][$i]['alasan'] = '-';                                              
				}
			} else {
				$data['kehadiran'][$i]['datang'] = '-';
				$data['kehadiran'][$i]['pulang'] = '-';
				$data['kehadiran'][$i]['alasan'] = '-';
			}

			//checking work day
			$workday_count = $this->workday_plan_model->get_by_date(intval($this->tanggal->get_only_date($tanggal)), $this->tanggal->get_only_month($tanggal), $this->tanggal->get_only_year($tanggal))->num_rows();
			if ($workday_count > 0) {
				$workday = $this->workday_plan_model->get_by_date(intval($this->tanggal->get_only_date($tanggal)), $this->tanggal->get_only_month($tanggal), $this->tanggal->get_only_year($tanggal))->row_array();
				if ($workday['status'] == '0') {
					$data['kehadiran'][$i]['datang'] = $workday['keterangan'];
					$data['kehadiran'][$i]['pulang'] = $workday['keterangan'];
					$data['kehadiran'][$i]['alasan'] = $workday['keterangan'];
				}
			}
		}

		$this->template->display('presences/presences', $data);
	}

	public function input()
	{
		date_default_timezone_set('Asia/Jakarta');

		$this->form_validation->set_rules('nik', 'NIK', 'required');
		$this->form_validation->set_rules('pwd', 'Password', 'required');
		$this->form_validation->set_rules('type_absen', 'Type', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->template->display('presences/input_view');
		} else {
			if ($this->user_model->check_absen($this->input->post('nik'), $this->input->post('pwd'))) {
				//absen success, save to database
				$data_user = $this->user_model->get_by_nik($this->input->post('nik'))->row_array();

				if ($this->input->post('type_absen') == '1') {
					//jam masuk

					//pertama cek dulu absen tanggal sekarang di db
					$cek = $this->presences_m->get_by_date(date('Y-m-d'), $data_user['id_karyawan'])->num_rows();
					if ($cek > 0) {
						//jika sudah ada, beri pesan ke user bahwa jam masuk telah di input
						$this->session->set_flashdata('message_alert', '<div class="alert alert-danger">Data absen has been passed.</div>');
						redirect('presences/input');
					} else {
						//jika belum ada, save to database
						$data_kehadiran = array(
							'id_karyawan' => $data_user['id_karyawan'],
							'tanggal' => date('Y-m-d'),
							'jam_masuk' => date('Y-m-d H:i:s'),
							// 'jam_keluar' => date('Y-m-d H:i:s'),
							'hadir' => '1',
							'id_alasan' => '5',
							'created_date' => date('Y-m-d'),
							'created_user' => $this->session->userdata('user_id'),
							'active' => '1'
						);
						$this->presences_m->save($data_kehadiran);

						$this->session->set_flashdata('message_alert', '<div class="alert alert-success">Data absen is saved.</div>');
						redirect('presences/input');
					}
				} else {
					//jam keluar
					//pertama cek dulu absen tanggal sekarang di db
					$cek = $this->presences_m->get_by_date(date('Y-m-d'), $data_user['id_karyawan'])->num_rows();
					if ($cek > 0) {
						//jika sudah ada, data jam keluar bisa dimasukan
						$tmp = $this->presences_m->get_by_date(date('Y-m-d'), $data_user['id_karyawan'])->row_array();

						$data_kehadiran = array(
							'jam_keluar' => date('Y-m-d H:i:s'),
							'updated_date' => date('Y-m-d'),
							'updated_user' => $this->session->userdata('user_id')
						);
						$this->presences_m->update($tmp['id_kehadiran'], $data_kehadiran);

						$this->session->set_flashdata('message_alert', '<div class="alert alert-success">Data absen is saved.</div>');
						redirect('presences/input');
					} else {
						//jika belum ada, beri pesan ke user bahwa user harus menginput jam masuk terlebih dahulu
						$this->session->set_flashdata('message_alert', '<div class="alert alert-danger">Data absen is not available. Please input the in time first.</div>');
						redirect('presences/input');
					}
				}
			} else {
				$this->session->set_flashdata('message_alert', '<div class="alert alert-danger">Username or password is not registered.</div>');
				redirect('presences/input');
			}
		}
	}

	public function input_absen_masuk()
	{
		date_default_timezone_set('Asia/Jakarta');

		// $this->form_validation->set_rules('nik','NIK','required');
		$this->form_validation->set_rules('rfid', 'RFID', 'required');
		// $this->form_validation->set_rules('pwd','Password','required');
		$this->form_validation->set_rules('type_absen', 'Type', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->template->display('presences/input_absen_masuk_view');
		} else {
			if ($this->user_model->check_rfid($this->input->post('rfid'))) {
				//absen success, save to database
				$data_user = $this->user_model->get_by_rfid($this->input->post('rfid'))->row_array();

				$nama_user = $data_user['nama'];
				if ($this->input->post('type_absen') == '1') {
					//jam masuk

					//pertama cek dulu absen tanggal sekarang di db
					$cek = $this->presences_m->get_by_date(date('Y-m-d'), $data_user['id_karyawan'])->num_rows();
					if ($cek > 0) {
						//jika sudah ada, beri pesan ke user bahwa jam masuk telah di input
						$this->session->set_flashdata('message_alert', '<div class="alert alert-danger"> ' . $nama_user . ' Sudah melakukan absen masuk.</div>');
						redirect('presences/input_absen_masuk');
					} else {
						//jika belum ada, save to database
						$data_kehadiran = array(
							'id_karyawan' => $data_user['id_karyawan'],
							'tanggal' => date('Y-m-d'),
							'jam_masuk' => date('Y-m-d H:i:s'),
							// 'jam_keluar' => date('Y-m-d H:i:s'),
							'hadir' => '1',
							'id_alasan' => '5',
							'created_date' => date('Y-m-d'),
							'created_user' => $this->session->userdata('user_id'),
							'active' => '1'
						);
						$this->presences_m->save($data_kehadiran);

						$this->session->set_flashdata('message_alert', '<div class="alert alert-success"> Selamat datang ' . $nama_user . '</div>');
						redirect('presences/input_absen_masuk');
					}
				} else {
					//jam keluar
					//pertama cek dulu absen tanggal sekarang di db
					$cek = $this->presences_m->get_by_date(date('Y-m-d'), $data_user['id_karyawan'])->num_rows();
					if ($cek > 0) {
						//jika sudah ada, data jam keluar bisa dimasukan
						$tmp = $this->presences_m->get_by_date(date('Y-m-d'), $data_user['id_karyawan'])->row_array();

						$data_kehadiran = array(
							'jam_keluar' => date('Y-m-d H:i:s'),
							'updated_date' => date('Y-m-d'),
							'updated_user' => $this->session->userdata('user_id')
						);
						$this->presences_m->update($tmp['id_kehadiran'], $data_kehadiran);

						$this->session->set_flashdata('message_alert', '<div class="alert alert-success">Data absen is saved.</div>');
						redirect('presences/input_absen_masuk');
					} else {
						//jika belum ada, beri pesan ke user bahwa user harus menginput jam masuk terlebih dahulu
						$this->session->set_flashdata('message_alert', '<div class="alert alert-danger">Data absen is not available. Please input the in time first.</div>');
						redirect('presences/input_absen_masuk');
					}
				}
			} else {
				$this->session->set_flashdata('message_alert', '<div class="alert alert-danger">Username or password is not registered.</div>');
				redirect('presences/input_absen_masuk');
			}
		}
	}

	public function input_absensi()
	{
		date_default_timezone_set('Asia/Jakarta');

		// $this->form_validation->set_rules('nik','NIK','required');
		$this->form_validation->set_rules('rfid', 'RFID', 'required');
		// $this->form_validation->set_rules('pwd','Password','required');
		// $this->form_validation->set_rules('type_absen', 'Type', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->template->display('presences/absen_view');
		} else {
			if ($this->user_model->check_rfid($this->input->post('rfid'))) {
				//absen success, save to database
				$data_user = $this->user_model->get_by_rfid($this->input->post('rfid'))->row_array();

				$nama_user = $data_user['nama'];
				// if ($this->input->post('type_absen') == '1') {
				//jam masuk

				//pertama cek dulu absen tanggal sekarang di db
				$cek = $this->presences_m->get_by_date(date('Y-m-d'), $data_user['id_karyawan'])->num_rows();
				if ($cek > 0) {
					//jika sudah ada, beri pesan ke user bahwa jam masuk telah di input
					$tmp = $this->presences_m->get_by_date(date('Y-m-d'), $data_user['id_karyawan'])->row_array();

					$data_kehadiran = array(
						'jam_keluar' => date('Y-m-d H:i:s'),
						'updated_date' => date('Y-m-d'),
						'updated_user' => $this->session->userdata('user_id')
					);
					$this->presences_m->update($tmp['id_kehadiran'], $data_kehadiran);

					$this->session->set_flashdata('message_alert', '<div class="alert alert-success"> ' . $nama_user . ' Berhasil absen Pulang</div>');
					redirect('presences/input_absensi');
				} else {
					//jika belum ada, save to database
					$data_kehadiran = array(
						'id_karyawan' => $data_user['id_karyawan'],
						'tanggal' => date('Y-m-d'),
						'jam_masuk' => date('Y-m-d H:i:s'),
						// 'jam_keluar' => date('Y-m-d H:i:s'),
						'hadir' => '1',
						'id_alasan' => '5',
						'created_date' => date('Y-m-d'),
						'created_user' => $this->session->userdata('user_id'),
						'active' => '1'
					);
					$this->presences_m->save($data_kehadiran);

					$this->session->set_flashdata('message_alert', '<div class="alert alert-success"> Selamat datang ' . $nama_user . '</div>');
					redirect('presences/input_absensi');
				}
				// } else {
				//jam keluar
				//pertama cek dulu absen tanggal sekarang di db
				// $cek = $this->presences_m->get_by_date(date('Y-m-d'), $data_user['id_karyawan'])->num_rows();
				// if ($cek > 0) {
				// 	//jika sudah ada, data jam keluar bisa dimasukan
				// 	$tmp = $this->presences_m->get_by_date(date('Y-m-d'), $data_user['id_karyawan'])->row_array();

				// 	$data_kehadiran = array(
				// 		'jam_keluar' => date('Y-m-d H:i:s'),
				// 		'updated_date' => date('Y-m-d'),
				// 		'updated_user' => $this->session->userdata('user_id')
				// 	);
				// 	$this->presences_m->update($tmp['id_kehadiran'], $data_kehadiran);

				// 	$this->session->set_flashdata('message_alert', '<div class="alert alert-success"> ' . $nama_user . ' Berhasil absen Pulang</div>');
				// 	redirect('presences/input_absen_keluar');
				// } else {
				// 	//jika belum ada, beri pesan ke user bahwa user harus menginput jam masuk terlebih dahulu
				// 	$this->session->set_flashdata('message_alert', '<div class="alert alert-danger">Data absen is not available. Please input the in time first.</div>');
				// 	redirect('presences/input_absen_keluar');
				// }
				// }
			} else {
				$this->session->set_flashdata('message_alert', '<div class="alert alert-danger">Username or password is not registered.</div>');
				redirect('presences/input_absensi');
			}
		}
	}

	public function list_rekap()
	{
		$data = array();
		$selisih_hari = 30; //jumlah hari yg akan ditampilkan
		
		$today = date('Y-m-d');
		$minus = mktime(0, 0, 0, date('m'), date('d') - $selisih_hari, date('Y'));
		$pastmonth = date('Y-m-d', $minus);

		$data['date_start'] = $this->tanggal->tanggal_indo($pastmonth);
		$data['date_end'] = $this->tanggal->tanggal_indo($today);

		$data['kehadiran'] = array();

		if ($_POST) {
			$today = $this->tanggal->tanggal_simpan_db($this->input->post('presences_date_end'));
			$pastmonth = $this->tanggal->tanggal_simpan_db($this->input->post('presences_date_start'));

			$selisih_hari = $this->tanggal->get_selisih($today, $pastmonth);

			$data['date_start'] = $this->input->post('presences_date_start');
			$data['date_end'] = $this->input->post('presences_date_end');
		}
		// $data['kehadiran'] = $this->presences_m->get_by_just_date('22/06/2023','22/07/2023')->row_array();

		$jumlah_row = $this->presences_m->get_by_just_date('2023/08/20')->num_rows();
		// var_dump($jumlah_row);

		for ($i = $jumlah_row; $i >= 0; $i--) {

			if ($this->presences_m->get_by_just_date('2023/08/20')->num_rows() > 0) {
				$data['kehadiran'] = $this->presences_m->get_by_just_date('2023/08/20')->row_array();
				$data['kehadiran'][$i]['datang'] = $this->tanggal->get_jam($data['jam_masuk']);
				$data['kehadiran'][$i]['pulang'] = $this->tanggal->get_jam($data['jam_keluar']);
				$data['kehadiran'][$i]['alasan'] = $data['nama_alasan'];
				if ($data['id_alasan'] == '5') {
					$data['kehadiran'][$i]['alasan'] = '-';                                              
				}
			} else {
				$data['kehadiran'][$i]['datang'] = '-';
				$data['kehadiran'][$i]['pulang'] = '-';
				$data['kehadiran'][$i]['alasan'] = '-';
			}

			

			// $data['kehadiran'][$i]['datang'] = $this->tanggal->get_jam($data['kehadiran']['jam_masuk']);
			// $data['kehadiran'][$i]['pulang'] = $this->tanggal->get_jam($data['kehadiran']['jam_keluar']);
			// $data['kehadiran'][$i]['alasan'] = $data['kehadiran']['nama_alasan'];
		}

		
		// $data['kehadiran'] = array();
		//get data kehadiran

		// $data['kehadiran']['datang'] = $this->tanggal->get_jam($data['kehadiran']['jam_masuk']);
		// $data['kehadiran']['pulang'] = $this->tanggal->get_jam($data['kehadiran']['jam_keluar']);
		// $data['kehadiran']['alasan'] = $data['kehadiran']['nama_alasan'];

		// $data['kehadiran'][$i]['datang'] = $this->tanggal->get_jam($present['jam_masuk']);
		// $data['kehadiran'][$i]['pulang'] = $this->tanggal->get_jam($present['jam_keluar']);

		// if ($data['kehadiran']->num_rows() > 0) {
		// 	// $present = $this->presences_m->get_by_date($tanggal, $user_id)->row_array();
		// 	$data['kehadiran']['datang'] = $data['kehadiran']['jam_masuk'];
		// 	$data['kehadiran']['pulang'] = $data['kehadiran']['jam_keluar'];
		// 	$data['kehadiran']['alasan'] = $data['kehadiran']['nama_alasan'] . '(' .$data['kehadiran']['keterangan'] . ')';
		// 	if ($$data['kehadiran']['id_alasan'] == '5') {
		// 		$data['kehadiran']['alasan'] = '-';                                              
		// 	}
		// } else {
		// 	$data['kehadiran']['datang'] = '-';
		// 	$data['kehadiran']['pulang'] = '-';
		// 	$data['kehadiran']['alasan'] = '-';
		// }

		// var_dump($data['kehadiran']);
		// var_dump($data['kehadiran']['pulang']);
		// var_dump($this->input->post('presences_date_start'));
		
		// $temp = mktime(0, 0, 0, $this->tanggal->get_only_month($today), $this->tanggal->get_only_date($today) - 1, $this->tanggal->get_only_year($today));
		// $tanggal = date('Y-m-d');


		// $present = $this->presences_m->get_by_just_date($tanggal1,$tanggal2)->row_array();
		// 	$data['kehadiran']['datang'] = $this->tanggal->get_jam($present['jam_masuk']);
		// 	$data['kehadiran']['pulang'] = $this->tanggal->get_jam($present['jam_keluar']);
		// 	$data['kehadiran']['alasan'] = $present['nama_alasan'] . '(' . $present['keterangan'] . ')';
		// 	if ($present['id_alasan'] == '5') {
		// 		$data['kehadiran']['alasan'] = '-';
		// 	}

		// $this->template->display('presences/rekap_absen', $data);
		$this->template->display('presences/rekap_absen', $data);

	}

	// download rekap excel
	public function download_excel($id_kelas, $id_semester)
	{
		// load to_excel_helper (untuk membuat laporan dengan format excel)
		$this->load->helper('to_excel');

		// parameter OK
		if (!empty($id_kelas) && !empty($id_semester)) {
			// kelas
			// $kelas = $this->db->select('t_kehadiran')->where('id_kelas', $id_kelas)->get('kelas')->row()->kelas;
			$kelas = $this->db->select('t_kehadiran');
			$tanggal = date('Y-m-d');
			$user_id = 6;

			$query = $this->presences_m->get_by_date($tanggal, $user_id)->num_rows() > 0;

			$nama_file = 'REKAP_ABSEN_KELAS_' . $tanggal . '_SEMESTER_' . $user_id;

			to_excel($query, $nama_file);
		}
		// parameter tidak lengkap
		else {
			$this->session->set_flashdata('pesan', 'Proses pembuatan data rekap (Excel) gagal. Parameter tidak lengkap.');
			redirect('rekap');
		}
	}
}
