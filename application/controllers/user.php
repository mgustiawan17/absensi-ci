<?php

class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('user_category_model');
		$this->load->model(array('master/divisi_m', 'master/golongan_m', 'master/jabatan_m', 'master/jam_kerja_m'));
		$this->load->library('template');
	}

	public function index()
	{
		$this->loginstatus->check_login();
		redirect('user/listdata');
	}

	public function listdata($start = 0, $perpage = 10)
	{
		$this->loginstatus->check_login();
		$data = array();

		$count = $this->user_model->get_all(false)->num_rows();
		$data['user'] = $this->user_model->get_all(true, $start, $perpage)->result_array();

		$this->load->library('pagination');
		$config['base_url'] = base_url() . 'user/listdata/';
		$config['total_rows'] = $count;
		$config['per_page'] = $perpage;
		$config['uri_segment'] = 3;

		$this->pagination->initialize($config);

		$data['paging'] = $this->pagination->create_links();
		$data['number'] = $start + 1;

		$this->template->display('user/listdata_view', $data);
	}

	public function add()
	{

		$this->loginstatus->check_login();
		$this->form_validation->set_rules('nik', 'NIK', 'required');
		$this->form_validation->set_rules('nama_karyawan', 'Nama Lengkap', 'required');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
		$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		$this->form_validation->set_rules('no_telepon', 'No Telepon', 'numeric');
		$this->form_validation->set_rules('no_handphone', 'No Handphone', 'required|numeric');
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('status_perkawinan', 'Status Perkawinan', 'required');
		$this->form_validation->set_rules('id_divisi', 'Divisi', 'required');
		$this->form_validation->set_rules('id_jabatan', 'Jabatan', 'required');
		$this->form_validation->set_rules('id_golongan', 'Golongan', 'required');
		$this->form_validation->set_rules('id_jam_kerja', 'Jam Kerja', 'required');
		$this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');

		// $config['upload_path']          = './uploads/foto';
		// $config['allowed_types']        = 'gif|jpg|png';
		// $config['max_size']             = 100;
		// $config['max_width']            = 1024;
		// $config['max_height']           = 768;

		// $this->load->library('upload', $config);


		// if ( ! $this->upload->do_upload('berkas')){
		// 	$error = array('error' => $this->upload->display_errors());
		// 	// $this->load->view('user/add_view', $error);
		// }else{
		// 	$data = array('upload_data' => $this->upload->data());
		// 	$this->load->view('user/listdata', $data);
		// }

		if ($this->form_validation->run() == FALSE) {

			$data = array();

			//load select box divisi
			$div = $this->divisi_m->get_all(false)->result_array();
			$data['divisi'] = '<select name="id_divisi" id="id_divisi" class="form-control" required="required">';
			$data['divisi'] .= '<option value="">Pilih Divisi</option>';
			foreach ($div as $d) {
				$data['divisi'] .= '<option value="' . $d['id_divisi'] . '">' . $d['nama_divisi'] . '</option>';
			}
			$data['divisi'] .= '</select>';

			//load select box jabatan
			$jab = $this->jabatan_m->get_all(false)->result_array();
			$data['jabatan'] = '<select name="id_jabatan" id="id_jabatan" class="form-control" required="required">';
			$data['jabatan'] .= '<option value="">Pilih Jabatan</option>';
			foreach ($jab as $j) {
				$data['jabatan'] .= '<option value="' . $j['id_jabatan'] . '">' . $j['nama_jabatan'] . '</option>';
			}
			$data['jabatan'] .= '</select>';

			//load select box golongan
			$gol = $this->golongan_m->get_all(false)->result_array();
			$data['golongan'] = '<select name="id_golongan" id="id_golongan" class="form-control" required="required">';
			$data['golongan'] .= '<option value="">Pilih Golongan</option>';
			foreach ($gol as $g) {
				$data['golongan'] .= '<option value="' . $g['id_golongan'] . '">' . $g['nama_golongan'] . '</option>';
			}
			$data['golongan'] .= '</select>';

			//load select box jam kerja
			$jam = $this->jam_kerja_m->get_all(false)->result_array();
			$data['jam_kerja'] = '<select name="id_jam_kerja" id="id_jam_kerja" class="form-control" required="required">';
			$data['jam_kerja'] .= '<option value="">Pilih Jam Kerja</option>';
			foreach ($jam as $jm) {
				$data['jam_kerja'] .= '<option value="' . $jm['id_jam_kerja'] . '">' . $jm['keterangan'] . '</option>';
			}
			$data['jam_kerja'] .= '</select>';

			$this->template->display('user/add_view', $data);
		} else {

			$file_name = $this->input->post('nik');
			$config['upload_path']          = FCPATH . '/uploads/foto/';
			$config['allowed_types']        = 'gif|jpg|jpeg|png';
			$config['file_name']            = $file_name;
			$config['overwrite']            = true;
			$config['max_size']             = 2024; // 2MB
			// $config['max_width']            = 1080;
			// $config['max_height']           = 1080;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('foto')) {
				$data['error'] = $this->upload->display_errors();
				die();
			} else {
				$uploaded_data = $this->upload->data();
				// $data = array('upload_data' => $this->upload->data());
				$uploaded_data['file_name'];
			}


			//generate NIK
			// $NIK = $this->generateNIK();

			$data_user = array(
				'nik' => $this->input->post('nik'),
				'rfid' => $this->input->post('rfid'),
				'nama' => $this->input->post('nama_karyawan'),
				'tempat_lahir' => $this->input->post('tempat_lahir'),
				'tanggal_lahir' => $this->tanggal->tanggal_simpan_db($this->input->post('tanggal_lahir')),
				'jenis_kelamin' => $this->input->post('jenis_kelamin'),
				'alamat' => $this->input->post('alamat'),
				'no_telp' => $this->input->post('no_telp'),
				'no_handphone' => $this->input->post('no_handphone'),
				'email' => $this->input->post('email'),
				'status_perkawinan' => $this->input->post('status_perkawinan'),
				'id_jabatan' => $this->input->post('id_jabatan'),
				'id_golongan' => $this->input->post('id_golongan'),
				'id_divisi' => $this->input->post('id_divisi'),
				'id_jam_kerja' => $this->input->post('id_jam_kerja'),
				'tanggal_masuk' => $this->tanggal->tanggal_simpan_db($this->input->post('tanggal_masuk')),
				'password' => sha1('password'),
				'created_user' => $this->session->userdata('user_id'),
				'created_date' => date('Y-m-d H:i:s'),
				'active' => '1',
				'foto' => $uploaded_data['file_name']
			);
			$this->user_model->save($data_user);

			$this->session->set_flashdata('message_alert', '<div class="alert alert-success">Data has been saved.</div>');

			redirect('user/listdata');
		}
	}

	public function edit($id)
	{
		$this->loginstatus->check_login();
		if ($id) {

			$this->form_validation->set_rules('nik', 'NIK', 'required');
			$this->form_validation->set_rules('rfid', 'RFID', 'required');
			$this->form_validation->set_rules('nama_karyawan', 'Nama Lengkap', 'required');
			$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
			$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
			$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
			$this->form_validation->set_rules('alamat', 'Alamat', 'required');
			$this->form_validation->set_rules('no_telepon', 'No Telepon', 'numeric');
			$this->form_validation->set_rules('no_handphone', 'No Handphone', 'required|numeric');
			$this->form_validation->set_rules('email', 'Email', 'required');
			$this->form_validation->set_rules('status_perkawinan', 'Status Perkawinan', 'required');
			$this->form_validation->set_rules('id_divisi', 'Divisi', 'required');
			$this->form_validation->set_rules('id_jabatan', 'Jabatan', 'required');
			$this->form_validation->set_rules('id_golongan', 'Golongan', 'required');
			$this->form_validation->set_rules('id_jam_kerja', 'Jam Kerja', 'required');
			$this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');

			if ($this->form_validation->run() == FALSE) {
				$data = array();

				$count = $this->user_model->get_by_id($id)->num_rows();
				if ($count > 0) {
					$data['user'] = $this->user_model->get_by_id($id)->row_array();
					// $data['rfid'] = $this->user_model->get_by_id($id)->row_array();
					// $data = array(
					// $foto = array(
					// 	'foto' => $data['user']['foto']
					// );

					$data['gambar']= $data['user']['foto'];

					//load select box divisi
					$div = $this->divisi_m->get_all(false)->result_array();
					$data['divisi'] = '<select name="id_divisi" id="id_divisi" class="form-control" required="required">';
					$data['divisi'] .= '<option value="">Pilih Divisi</option>';
					foreach ($div as $d) {
						$selected = '';
						if ($data['user']['id_divisi'] == $d['id_divisi']) {
							$selected = 'selected';
						}
						$data['divisi'] .= '<option value="' . $d['id_divisi'] . '" ' . $selected . '>' . $d['nama_divisi'] . '</option>';
					}
					$data['divisi'] .= '</select>';

					//load select box jabatan
					$jab = $this->jabatan_m->get_all(false)->result_array();
					$data['jabatan'] = '<select name="id_jabatan" id="id_jabatan" class="form-control" required="required">';
					$data['jabatan'] .= '<option value="">Pilih Jabatan</option>';
					foreach ($jab as $j) {
						$selected = '';
						if ($data['user']['id_jabatan'] == $j['id_jabatan']) {
							$selected = 'selected';
						}
						$data['jabatan'] .= '<option value="' . $j['id_jabatan'] . '" ' . $selected . '>' . $j['nama_jabatan'] . '</option>';
					}
					$data['jabatan'] .= '</select>';

					//load select box golongan
					$gol = $this->golongan_m->get_all(false)->result_array();
					$data['golongan'] = '<select name="id_golongan" id="id_golongan" class="form-control" required="required">';
					$data['golongan'] .= '<option value="">Pilih Golongan</option>';
					foreach ($gol as $g) {
						$selected = '';
						if ($data['user']['id_golongan'] == $g['id_golongan']) {
							$selected = 'selected';
						}
						$data['golongan'] .= '<option value="' . $g['id_golongan'] . '" ' . $selected . '>' . $g['nama_golongan'] . '</option>';
					}
					$data['golongan'] .= '</select>';

					//load select box jam kerja
					$jam = $this->jam_kerja_m->get_all(false)->result_array();
					$data['jam_kerja'] = '<select name="id_jam_kerja" id="id_jam_kerja" class="form-control" required="required">';
					$data['jam_kerja'] .= '<option value="">Pilih Jam Kerja</option>';
					foreach ($jam as $jm) {
						$selected = '';
						if ($data['user']['id_jam_kerja'] == $jm['id_jam_kerja']) {
							$selected = 'selected';
						}
						$data['jam_kerja'] .= '<option value="' . $jm['id_jam_kerja'] . '" ' . $selected . '>' . $jm['keterangan'] . '</option>';
					}
					$data['jam_kerja'] .= '</select>';

					//generate selectbox perkawinan
					$status_perkawinan_option_0 = '';
					$status_perkawinan_option_1 = '';
					$status_perkawinan_option_2 = '';
					$status_perkawinan_option_3 = '';
					if ($data['user']['status_perkawinan'] == '0') {
						$status_perkawinan_option_1 = 'selected';
					}
					if ($data['user']['status_perkawinan'] == '1') {
						$status_perkawinan_option_1 = 'selected';
					}
					if ($data['user']['status_perkawinan'] == '2') {
						$status_perkawinan_option_2 = 'selected';
					}
					if ($data['user']['status_perkawinan'] == '3') {
						$status_perkawinan_option_3 = 'selected';
					}
					$data['status_perkawinan'] = '<select name="status_perkawinan" id="status_perkawinan" required="required" class="form-control">
													<option value="" ' . $status_perkawinan_option_0 . '>Pilih Status Perkawinan</option>
													<option value="1" ' . $status_perkawinan_option_1 . '>Lajang</option>
													<option value="2" ' . $status_perkawinan_option_2 . '>Menikah</option>
													<option value="3" ' . $status_perkawinan_option_3 . '>Cerai</option>
												</select>';

					//setting up for jenis kelamin
					$jenis_kelamin_checked_1 = '';
					$jenis_kelamin_checked_2 = '';

					if ($data['user']['jenis_kelamin'] == '1') {
						$jenis_kelamin_checked_1 = 'checked';
					}

					if ($data['user']['jenis_kelamin'] == '2') {
						$jenis_kelamin_checked_2 = 'checked';
					}

					$data['jenis_kelamin'][1] = $jenis_kelamin_checked_1;
					$data['jenis_kelamin'][2] = $jenis_kelamin_checked_2;

					$this->template->display('user/edit_view', $data);
				} else {
					$this->session->set_flashdata('message_alert', '<div class="alert alert-danger">The ID you\'ve choosen not registered.</div>');
					redirect('user/listdata');
				}
			} else {

				// $file_name = $this->input->post('nik');
				// $config['upload_path']          = FCPATH . '/uploads/foto/';
				// $config['allowed_types']        = 'gif|jpg|jpeg|png';
				// $config['file_name']            = $file_name;
				// $config['overwrite']            = true;
				// $config['max_size']             = 2024; // 2MB
				// // $config['max_width']            = 1080;
				// // $config['max_height']           = 1080;

				// $this->load->library('upload', $config);

				// if (!$this->upload->do_upload('foto')) {
				// 	$data['error'] = $this->upload->display_errors();
				// 	die();
				// } else {
				// 	$uploaded_data = $this->upload->data();
				// 	// $data = array('upload_data' => $this->upload->data());
				// 	$uploaded_data['file_name'];
				// }



				$data_user = array(
					'nik' => $this->input->post('nik'),
					'rfid' => $this->input->post('rfid'),
					'nama' => $this->input->post('nama_karyawan'),
					'tempat_lahir' => $this->input->post('tempat_lahir'),
					'tanggal_lahir' => $this->tanggal->tanggal_simpan_db($this->input->post('tanggal_lahir')),
					'jenis_kelamin' => $this->input->post('jenis_kelamin'),
					'alamat' => $this->input->post('alamat'),
					'no_telp' => $this->input->post('no_telp'),
					'no_handphone' => $this->input->post('no_handphone'),
					'email' => $this->input->post('email'),
					'status_perkawinan' => $this->input->post('status_perkawinan'),
					'id_jabatan' => $this->input->post('id_jabatan'),
					'id_golongan' => $this->input->post('id_golongan'),
					'id_divisi' => $this->input->post('id_divisi'),
					'id_jam_kerja' => $this->input->post('id_jam_kerja'),
					'tanggal_masuk' => $this->tanggal->tanggal_simpan_db($this->input->post('tanggal_masuk')),
					'password' => sha1('password'),
					'updated_user' => $this->session->userdata('user_id'),
					'updated_date' => date('Y-m-d H:i:s'),
					'foto' => $uploaded_data['file_name']
				);
				$this->user_model->update($id, $data_user);

				$this->session->set_flashdata('message_alert', '<div class="alert alert-success">Data has been updated.</div>');

				redirect('user/listdata');
			}
		} else {
			redirect('user/listdata');
		}
	}

	public function delete($id)
	{
		$this->loginstatus->check_login();
		if ($id) {
			$count = $this->user_model->get_by_id($id)->num_rows();
			if ($count > 0) {
				$data_user = array(
					'active' => '0'
				);

				// $file_name = $SBLN_ROLL_NO."_ssc";
				// $file_ext = pathinfo($_FILES['ASSIGNMENT_FILE']['name'],PATHINFO_EXTENSION);

				// //File upload configuration
				// $config['upload_path'] = $upload_path;
				// $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
				// $config['file_name'] = $file_name.'.'.$file_ext;
				$file_name =

					$config['upload_path']          = FCPATH . '/uploads/foto/';
				$config['allowed_types']        = 'gif|jpg|jpeg|png';
				$config['file_name']            = $file_name;
				//First save the previous path for unlink before update
				$temp = $this->utilities->findByAttribute('SKILL_DEV_ELEMENT', array('APPLICANT_ID' => $STUDENT_PERSONAL_INFO->APPLICANT_ID, 'SD_ID' => $SD_ID));

				//Now Unlink
				if (file_exists($upload_path . '/' . $temp->ELEMENT_URL)) {
					unlink(FCPATH . $upload_path . '/' . $temp->ELEMENT_URL);
				}

				//Then upload a new file
				if ($this->upload->do_upload('file')) {
					// Uploaded file data
					$fileData = $this->upload->data();
					$file_name = $fileData['file_name'];
				}

				$this->user_model->update($id, $data_user);
				$this->session->set_flashdata('message_alert', '<div class="alert alert-success">Data has been deleted.</div>');

				redirect('user/listdata');
			} else {
				$this->session->set_flashdata('message_alert', '<div class="alert alert-danger">The ID you\'ve choosen not registered.</div>');
				redirect('user/listdata');
			}
		} else {
			redirect('user/listdata');
		}
	}

	public function login()
	{
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('user/login_view');
		} else {
			if ($this->user_model->check_login()) {
				//login success, save to session
				$data_user = $this->user_model->get_by_nik($this->input->post('username'))->row_array();
				$user_sess = array(
					'user_id' => $data_user['id_karyawan'],
					'user_nik' => $data_user['nik'],
					'user_fullname' => $data_user['nama'],
					'user_type_id' => $data_user['id_jabatan'],
					'logged_in' => true
				);
				$this->session->set_userdata($user_sess);

				redirect('home');
			} else {
				$this->session->set_flashdata('login_failed', '<div class="alert alert-danger">Username or password is not registered.</div>');
				redirect('user/login');
			}
		}
	}

	public function logout()
	{
		$this->loginstatus->check_login();
		$this->session->sess_destroy();
		redirect('user/login');
	}

	public function check_username_availabilities()
	{
		$nik = $this->input->post('username');
		//$username = 'administratorw';

		$data = array();
		$count = $this->user_model->get_by_nik($nik)->num_rows();

		if ($count > 0) {
			$data['status'] = false;
		} else {
			$data['status'] = true;
		}

		echo json_encode($data);
	}

	public function getUserAPI()
	{
		$nik = $this->input->get('name_startsWith');

		$data = array();
		$count = $this->user_model->search_user($nik)->num_rows();


		$userdata = $this->user_model->search_user($nik)->result_array();

		$i = 0;
		foreach ($userdata as $row) {
			$data['data_karyawan'][$i]['id'] = $row['id_karyawan'];
			$data['data_karyawan'][$i]['nik'] = $row['nik'];
			$data['data_karyawan'][$i]['nama'] = $row['nama'];
			$i++;
		}

		$data['total'] = $count;

		echo json_encode($data);
	}

	public function generateNIK()
	{
		$count = $this->user_model->get_last_id()->num_rows();
		if ($count > 0) {
			$lastdata = $this->user_model->get_last_id()->row_array();

			if ($lastdata['id_karyawan'] > 100000) {
				$NIK = ($lastdata['id_karyawan'] + 1);
			} elseif ($lastdata['id_karyawan'] > 10000) {
				$NIK = '0' . ($lastdata['id_karyawan'] + 1);
			} elseif ($lastdata['id_karyawan'] > 1000) {
				$NIK = '00' . ($lastdata['id_karyawan'] + 1);
			} elseif ($lastdata['id_karyawan'] > 100) {
				$NIK = '000' . ($lastdata['id_karyawan'] + 1);
			} elseif ($lastdata['id_karyawan'] > 10) {
				$NIK = '0000' . ($lastdata['id_karyawan'] + 1);
			} else {
				$NIK = '00000' . ($lastdata['id_karyawan'] + 1);
			}
		} else {
			$NIK = '000001';
		}

		return $NIK;
	}

	public function upload_avatar()
	{
		$this->load->model('profile_model');
		// $data['user'] = $this->user_model->get_by_id($id)->row_array();

		// $data['current_user'] = $this->auth_model->current_user();
		$data['current_user'] = $this->session->userdata('user_id');

		if ($this->input->method() === 'post') {
			// the user id contain dot, so we must remove it
			$file_name = str_replace('.', '', $data['current_user']->id);
			$config['upload_path']          = FCPATH . '/upload/avatar/';
			$config['allowed_types']        = 'gif|jpg|jpeg|png';
			$config['file_name']            = $file_name;
			$config['overwrite']            = true;
			$config['max_size']             = 2024; // 2MB
			// $config['max_width']            = 1080;
			// $config['max_height']           = 1080;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('avatar')) {
				$data['error'] = $this->upload->display_errors();
			} else {
				$uploaded_data = $this->upload->data();

				$new_data = [
					'id' => $data['current_user']->id,
					'avatar' => $uploaded_data['file_name'],
				];

				if ($this->profile_model->update($new_data)) {
					$this->session->set_flashdata('message', 'Avatar updated!');
					// redirect(site_url('admin/setting'));
				}
			}
		}

		$this->load->view('admin/setting_upload_avatar.php', $data);
	}
}
