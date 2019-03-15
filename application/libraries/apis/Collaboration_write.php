<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Collaboration_write
 */


class Collaboration_write extends CI_Controller
{

    private $CI;

    function __construct()
    {
        $this->CI = & get_instance();

    }

	function main()
	{

		if ( ! $this->CI->input->post_get('token')) {
			$this->CI->apilib->make_error("토큰 값이 넘어오지 않았습니다.");
        }
		$token = $this->CI->input->post_get('token');

		$sessionuser = $this->CI->User_model->get_by_token($token);
		if ( ! element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("로그인 한 회원만 콜라보레이션을 생성할 수 있습니다.");
		}

		$this->CI->load->model(array('Collaboration_model', 'Collaboration_user_model'));

		
        $col_desc = trim($this->CI->input->post('col_desc'));

        if (empty($col_desc)) {
			$this->CI->apilib->make_error("콜라보레이션 상세내용이 입력되지 않았습니다.");
        }

		$this->CI->load->library('upload');

		$uploadfiledata = '';

		$col_image_name = '';
		if (isset($_FILES) && isset($_FILES['col_file']) && isset($_FILES['col_file']['name']) && $_FILES['col_file']['name']) {
			$upload_path = config_item('uploads_dir') . '/collaboration/';
			if (is_dir($upload_path) === false) {
				mkdir($upload_path, 0707);
				$file = $upload_path . 'index.php';
				$f = @fopen($file, 'w');
				@fwrite($f, '');
				@fclose($f);
				@chmod($file, 0644);
			}
			$upload_path .= cdate('Y') . '/';
			if (is_dir($upload_path) === false) {
				mkdir($upload_path, 0707);
				$file = $upload_path . 'index.php';
				$f = @fopen($file, 'w');
				@fwrite($f, '');
				@fclose($f);
				@chmod($file, 0644);
			}
			$upload_path .= cdate('m') . '/';
			if (is_dir($upload_path) === false) {
				mkdir($upload_path, 0707);
				$file = $upload_path . 'index.php';
				$f = @fopen($file, 'w');
				@fwrite($f, '');
				@fclose($f);
				@chmod($file, 0644);
			}

			$uploadconfig = '';
			$uploadconfig['upload_path'] = $upload_path;
			$uploadconfig['allowed_types'] = 'jpg|jpeg|png|gif';
			$uploadconfig['max_size'] = 1024 * 20;
			$uploadconfig['encrypt_name'] = true;

			$this->CI->upload->initialize($uploadconfig);
			$_FILES['userfile']['name'] = $_FILES['col_file']['name'];
			$_FILES['userfile']['type'] = $_FILES['col_file']['type'];
			$_FILES['userfile']['tmp_name'] = $_FILES['col_file']['tmp_name'];
			$_FILES['userfile']['error'] = $_FILES['col_file']['error'];
			$_FILES['userfile']['size'] = $_FILES['col_file']['size'];
			if ($this->CI->upload->do_upload('col_file')) {
				$filedata = $this->CI->upload->data();
				$col_image_name = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
			} else {
				$file_error = $this->CI->upload->display_errors();
				$this->CI->apilib->make_error($file_error);
			}
		} else {
			$this->CI->apilib->make_error("파일이 업로드되지 않았습니다.");
		}



		$insertdata = array(
			'col_desc' => $col_desc,
			'col_datetime' => cdate('Y-m-d H:i:s'),
			'col_user_id' => element('user_id', $sessionuser),
			'col_user_count' => '1',
			'col_image_name' => $col_image_name,
		);
		$col_id = $this->CI->Collaboration_model->insert($insertdata);

		$userinsert = array(
			'col_id' => $col_id,
			'user_id' => element('user_id', $sessionuser),
			'cou_datetime' => cdate('Y-m-d H:i:s'),
		);
		$this->CI->Collaboration_user_model->insert($userinsert);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'col_id' => $col_id,
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;

	}


}
