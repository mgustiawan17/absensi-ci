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
		$data['kehadiran'] = array();

		$data['date_start'] = ''; // Inisialisasi variabel date_start
		$data['date_end'] = ''; // Inisialisasi variabel date_end

		if ($_POST) {
			// Ambil tanggal dari form
			$date_start = $this->input->post('presences_date_start');
			$date_end = $this->input->post('presences_date_end');

			$this->session->set_userdata('date_start', $date_start);
        	$this->session->set_userdata('date_end', $date_end);

			// Konversi tanggal ke format yang sesuai dengan database (YYYY-MM-DD)
			$date_start_db = date('Y-m-d', strtotime(str_replace('/', '-', $date_start)));
			$date_end_db = date('Y-m-d', strtotime(str_replace('/', '-', $date_end)));

			// Panggil model untuk mendapatkan data berdasarkan rentang tanggal
			$data['kehadiran'] = $this->presences_m->get_by_date_range($date_start_db, $date_end_db);

			// Mengisi nilai date_start dan date_end untuk dikirimkan ke view
			$data['date_start'] = $date_start;
			$data['date_end'] = $date_end;

			// Loop melalui hasil dan tambahkan datang, pulang, dan alasan
			foreach ($data['kehadiran'] as &$row) {
				$row['nama'] = $this->presences_m->get_name_by_id($row['id_karyawan']); // Ganti dengan model dan metode yang sesuai
				$row['tanggal'] = $row['tanggal'];
				$row['datang'] = date('H:i', strtotime($this->tanggal->get_jam($row['jam_masuk'])));
				$row['pulang'] = date('H:i', strtotime($this->tanggal->get_jam($row['jam_keluar'])));
				$row['alasan'] = ($row['id_alasan'] == '5') ? '-' : $row['nama_alasan'];
			}
		}

		$this->template->display('presences/rekap_absen', $data);
	}

	// download rekap excel
	// public function download_excel($id_kelas, $id_semester)
	// {
	// 	// load to_excel_helper (untuk membuat laporan dengan format excel)
	// 	$this->load->helper('to_excel');

	// 	// parameter OK
	// 	if (!empty($id_kelas) && !empty($id_semester)) {
	// 		// kelas
	// 		// $kelas = $this->db->select('t_kehadiran')->where('id_kelas', $id_kelas)->get('kelas')->row()->kelas;
	// 		$kelas = $this->db->select('t_kehadiran');
	// 		$tanggal = date('Y-m-d');
	// 		$user_id = 6;

	// 		$query = $this->presences_m->get_by_date($tanggal, $user_id)->num_rows() > 0;

	// 		$nama_file = 'REKAP_ABSEN_KELAS_' . $tanggal . '_SEMESTER_' . $user_id;

	// 		to_excel($query, $nama_file);
	// 	}
	// 	// parameter tidak lengkap
	// 	else {
	// 		$this->session->set_flashdata('pesan', 'Proses pembuatan data rekap (Excel) gagal. Parameter tidak lengkap.');
	// 		redirect('rekap');
	// 	}
	// }

	public function export_to_excel() {
		$date_start = $this->session->userdata('date_start');
    	$date_end = $this->session->userdata('date_end');

		// Validasi tanggal
		if (empty($date_start) || empty($date_end)) {
			// Handle kesalahan jika tanggal awal atau akhir kosong
			redirect('presences/list_rekap'); // Redirect ke halaman sebelumnya atau tampilkan pesan kesalahan
		}

		// Konversi tanggal ke format yang sesuai dengan database (YYYY-MM-DD)
		$date_start_db = date('Y-m-d', strtotime(str_replace('/', '-', $date_start)));
		$date_end_db = date('Y-m-d', strtotime(str_replace('/', '-', $date_end)));

		$this->load->model('presences_m');
		$data['kehadiran'] = $this->presences_m->get_by_date_range($date_start_db, $date_end_db);

		$data['date_start'] = $date_start;
		$data['date_end'] = $date_end;

		require(APPPATH. 'PHPExcel-1.8/Classes/PHPExcel.php');
		require(APPPATH. 'PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');

		$object = new PHPExcel();

		$object->getProperties()->setCreator("Framework Indonesia");
		$object->getProperties()->setLastModifiedBy("Framework Indonesia");
		$object->getProperties()->setTitle("Daftar Mahasiswa");

		$object->setActiveSheetIndex(0);

		$object->getActiveSheet()->setCellValue('A1', 'NO');
		$object->getActiveSheet()->setCellValue('B1', 'Nama');
		$object->getActiveSheet()->setCellValue('C1', 'Tanggal');
		$object->getActiveSheet()->setCellValue('D1', 'Jam Masuk');
		$object->getActiveSheet()->setCellValue('E1', 'Jam Pulang');
		$object->getActiveSheet()->setCellValue('F1', 'Alasan');

		$baris = 2;
		$no = 1;
		
		foreach ($data['kehadiran'] as $hdr) {
			// if (is_object($hdr) && property_exists($hdr, 'nama')) {
				$object->getActiveSheet()->setCellValue('A'.$baris, $no++);
				$object->getActiveSheet()->setCellValue('B'.$baris, $hdr['nama']);
				$object->getActiveSheet()->setCellValue('C'.$baris, $hdr['tanggal']);
				$object->getActiveSheet()->setCellValue('D' . $baris, date('H:i', strtotime($hdr['jam_masuk'])));
    			$object->getActiveSheet()->setCellValue('E' . $baris, date('H:i', strtotime($hdr['jam_keluar'])));
				$object->getActiveSheet()->setCellValue('F'.$baris, $hdr['nama_alasan']);
			// }
			$baris++;
		}

		$filename="Laporan_kehadiran_" . $date_start_db . "_" . $date_end_db . '.xlsx';

		$object->getActiveSheet()->setTitle("Data Mahasiswa");

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$writer=PHPExcel_IOFactory::createWriter($object, 'Excel2007');
		$writer->save('php://output');

		exit;
	}

	// public function tampil_data() {
    //     $this->load->model('presences_m');
    //     return $this->presences_m->tampil_data('t_kehadiran');
    // }
}
