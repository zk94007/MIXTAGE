<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Collaboration_modify
 */


class Collaboration_modify extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 콜라보레이션을 수정할 수 있습니다.");
		}

		$this->CI->load->model(array('Collaboration_model', 'Collaboration_user_model'));

		
        $col_id = trim($this->CI->input->post('col_id'));
        if (empty($col_id)) {
			$this->CI->apilib->make_error("수정하고자 하는 콜라보레이션 고유번호가 입력되지 않았습니다.");
        }
		
        $col_desc = trim($this->CI->input->post('col_desc'));
        if (empty($col_desc)) {
			$this->CI->apilib->make_error("콜라보레이션 상세내용이 입력되지 않았습니다.");
        }

		$collaboration = $this->CI->Collaboration_model->get_one_data($col_id);
		if ( ! element('col_id', $collaboration)) {
			$this->CI->apilib->make_error("존재하지 않는 콜라보레이션입니다.");
		}
		if (element('col_user_id', $collaboration) != element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("본인이 생성한 콜라보레이션이 아니므로 수정하실 수 없습니다.");
		}

		$updatedata = array(
			'col_desc' => $col_desc,
		);

		if (isset($_FILES) && isset($_FILES['col_file']) && isset($_FILES['col_file']['name']) && $_FILES['col_file']['name']) {

			$this->CI->load->library('upload');

			$uploadfiledata = '';

			$col_image_name = '';
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
				$updatedata['col_image_name'] = $col_image_name;
			} else {
				$file_error = $this->CI->upload->display_errors();
				$this->CI->apilib->make_error($file_error);
			}
		}



		$this->CI->Collaboration_model->update($col_id, $updatedata);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'col_id' => $col_id,
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;

	}


}
