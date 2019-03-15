<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Profile_upload_photo
 */


class Profile_upload_photo extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 접근이 가능합니다.");
		}

		$user_id = element('user_id', $sessionuser);

		

		$this->CI->load->library('upload');

		$uploadfiledata = '';

		$col_image_name = '';
		if (isset($_FILES) && isset($_FILES['user_photo']) && isset($_FILES['user_photo']['name']) && $_FILES['user_photo']['name']) {
			$upload_path = config_item('uploads_dir') . '/user_photo/';
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
			$_FILES['userfile']['name'] = $_FILES['user_photo']['name'];
			$_FILES['userfile']['type'] = $_FILES['user_photo']['type'];
			$_FILES['userfile']['tmp_name'] = $_FILES['user_photo']['tmp_name'];
			$_FILES['userfile']['error'] = $_FILES['user_photo']['error'];
			$_FILES['userfile']['size'] = $_FILES['user_photo']['size'];
			if ($this->CI->upload->do_upload('user_photo')) {
				$filedata = $this->CI->upload->data();
				$image_name = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
			} else {
				$file_error = $this->CI->upload->display_errors();
				$this->CI->apilib->make_error($file_error);
			}
		} else {
			$this->CI->apilib->make_error("파일이 업로드되지 않았습니다.");
		}

		
		$updatedata = array(
			'user_photo' => $image_name,
		);
		$this->CI->User_model->update($user_id, $updatedata);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
		);
		return $arr;
	}
}
