<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portfolio_modify
 */


class Portfolio_modify extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 포트폴리오를 수정할 수 있습니다.");
		}

		$this->CI->load->model(array('Portfolio_model', 'Collaboration_model', 'Collaboration_user_model', 'Portfolio_file_model', 'Portfolio_tag_model'));

		
        $por_id = (int) trim($this->CI->input->post('por_id'));
        if (empty($por_id)) {
			$this->CI->apilib->make_error("포트폴리오 고유 PK 가 입력되지 않았습니다.");
        }

		$portfolio = $this->CI->Portfolio_model->get_one($por_id);
		if ( ! element('por_id', $portfolio)) {
			$this->CI->apilib->make_error("존재하지 않는 포트폴리오입니다.");
		}
		if (element('user_id', $portfolio) != element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("본인의 포트폴리오가 아니므로 수정하실 수 없습니다.");
		}

        $por_content = trim($this->CI->input->post('por_content'));
        $por_open = (int) trim($this->CI->input->post('por_open'));
        $por_tag = trim($this->CI->input->post('por_tag'));

        if (empty($por_content)) {
			$this->CI->apilib->make_error("포트폴리오 상세내용이 입력되지 않았습니다.");
        }

		$this->CI->load->library('upload');

		$uploadfiledata = '';

		if (isset($_FILES) && isset($_FILES['por_file']) && isset($_FILES['por_file']['name']) && is_array($_FILES['por_file']['name'])) {
			$filecount = count($_FILES['por_file']['name']);
			$upload_path = config_item('uploads_dir') . '/portfolio/';
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

			foreach ($_FILES['por_file']['name'] as $i => $value) {
				if ($value) {
					$uploadconfig = '';
					$uploadconfig['upload_path'] = $upload_path;
					$uploadconfig['allowed_types'] = 'jpg|jpeg|png|gif';
					$uploadconfig['max_size'] = 1024 * 20;
					$uploadconfig['encrypt_name'] = true;

					$this->CI->upload->initialize($uploadconfig);
					$_FILES['userfile']['name'] = $_FILES['por_file']['name'][$i];
					$_FILES['userfile']['type'] = $_FILES['por_file']['type'][$i];
					$_FILES['userfile']['tmp_name'] = $_FILES['por_file']['tmp_name'][$i];
					$_FILES['userfile']['error'] = $_FILES['por_file']['error'][$i];
					$_FILES['userfile']['size'] = $_FILES['por_file']['size'][$i];
					if ($this->CI->upload->do_upload()) {
						$filedata = $this->CI->upload->data();

						$uploadfiledata[$i]['pfi_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
						$uploadfiledata[$i]['pfi_originname'] = element('orig_name', $filedata);
						$uploadfiledata[$i]['pfi_filesize'] = intval(element('file_size', $filedata) * 1024);
						$uploadfiledata[$i]['pfi_width'] = element('image_width', $filedata) ? element('image_width', $filedata) : 0;
						$uploadfiledata[$i]['pfi_height'] = element('image_height', $filedata) ? element('image_height', $filedata) : 0;
						$uploadfiledata[$i]['pfi_type'] = str_replace('.', '', element('file_ext', $filedata));
					} else {
						$file_error = $this->CI->upload->display_errors();
						$this->CI->apilib->make_error($file_error);
						break;
					}
				}
			}
 		}

		$uploadcoverfiledata = '';

		if (isset($_FILES) && isset($_FILES['por_cover_file']) && isset($_FILES['por_cover_file']['name']) && $_FILES['por_cover_file']['name']) {
			$upload_path = config_item('uploads_dir') . '/portfolio/';
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
			$_FILES['userfile']['name'] = $_FILES['por_cover_file']['name'];
			$_FILES['userfile']['type'] = $_FILES['por_cover_file']['type'];
			$_FILES['userfile']['tmp_name'] = $_FILES['por_cover_file']['tmp_name'];
			$_FILES['userfile']['error'] = $_FILES['por_cover_file']['error'];
			$_FILES['userfile']['size'] = $_FILES['por_cover_file']['size'];
			if ($this->CI->upload->do_upload('por_cover_file')) {
				$filedata = $this->CI->upload->data();

				$uploadcoverfiledata['pfi_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
				$uploadcoverfiledata['pfi_originname'] = element('orig_name', $filedata);
				$uploadcoverfiledata['pfi_filesize'] = intval(element('file_size', $filedata) * 1024);
				$uploadcoverfiledata['pfi_width'] = element('image_width', $filedata) ? element('image_width', $filedata) : 0;
				$uploadcoverfiledata['pfi_height'] = element('image_height', $filedata) ? element('image_height', $filedata) : 0;
				$uploadcoverfiledata['pfi_type'] = str_replace('.', '', element('file_ext', $filedata));

			} else {
				$file_error = $this->CI->upload->display_errors();
				$this->CI->apilib->make_error($file_error);
 			}
		}



		$updatedata = array(
			'por_content' => $por_content,
			'por_updated_datetime' => cdate('Y-m-d H:i:s'),
			'por_open' => $por_open,
		);
		$this->CI->Portfolio_model->update($por_id, $updatedata);


		if ($uploadfiledata && is_array($uploadfiledata) && count($uploadfiledata) > 0) {
			if ( ! $this->CI->input->post('por_keep')) {
				$filedeletewhere = array(
					'por_id' => $por_id,
					'pfi_is_cover' => '0',
				);
				$this->CI->Portfolio_file_model->delete('', $filedeletewhere);
			}
			foreach ($uploadfiledata as $pkey => $pval) {
				if ($pval) {
					$fileupdate = array(
						'por_id' => $por_id,
						'pfi_is_cover' => '',
						'user_id' => element('user_id', $sessionuser),
						'pfi_originname' => element('pfi_originname', $pval),
						'pfi_filename' => element('pfi_filename', $pval),
						'pfi_filesize' => element('pfi_filesize', $pval),
						'pfi_width' => element('pfi_width', $pval),
						'pfi_height' => element('pfi_height', $pval),
						'pfi_type' => element('pfi_type', $pval),
						'pfi_datetime' => cdate('Y-m-d H:i:s'),
					);
					$file_id = $this->CI->Portfolio_file_model->insert($fileupdate);
				}
			}
		}

		if ($uploadcoverfiledata) {

			$filedeletewhere = array(
				'por_id' => $por_id,
				'pfi_is_cover' => '1',
			);
			$this->CI->Portfolio_file_model->delete('', $filedeletewhere);
			$fileupdate = array(
				'por_id' => $por_id,
				'pfi_is_cover' => '1',
				'user_id' => element('user_id', $sessionuser),
				'pfi_originname' => element('pfi_originname', $uploadcoverfiledata),
				'pfi_filename' => element('pfi_filename', $uploadcoverfiledata),
				'pfi_filesize' => element('pfi_filesize', $uploadcoverfiledata),
				'pfi_width' => element('pfi_width', $uploadcoverfiledata),
				'pfi_height' => element('pfi_height', $uploadcoverfiledata),
				'pfi_type' => element('pfi_type', $uploadcoverfiledata),
				'pfi_datetime' => cdate('Y-m-d H:i:s'),
			);
			$file_id = $this->CI->Portfolio_file_model->insert($fileupdate);

			$updatedata = array(
				'por_cover_image_id' => $file_id,
				'por_cover_image_name' => element('pfi_filename', $uploadcoverfiledata),
			);
			$this->CI->Portfolio_model->update($por_id, $updatedata);

		}


		$tagwhere = array(
			'por_id' => $por_id,
		);
		$this->CI->Portfolio_tag_model->delete('', $tagwhere);
		if ($por_tag) {
			$extag = explode(',', $por_tag);
			if ($extag) {
				foreach ($extag as $tag) {
					$taginsert = array(
						'por_id' => $por_id,
						'tag_name' => $tag,
					);
					$this->CI->Portfolio_tag_model->insert($taginsert);
				}
			}
		}

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'por_id' => $por_id,
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;

	}


}
