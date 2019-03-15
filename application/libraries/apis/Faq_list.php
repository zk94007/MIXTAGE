<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Faq_list
 */


class Faq_list extends CI_Controller
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


        $fgr_id = (int) trim($this->CI->input->get('group_id'));
        if (empty($fgr_id)) {
			$this->CI->apilib->make_error("그룹 아이디가 입력되지 않았습니다.");
        }

		$this->CI->load->model(array('Faq_model'));

        $where = array(
			'fgr_id' => $fgr_id,
		);
		$select = 'faq_id, faq_title, faq_content, faq_order, faq_datetime';
		$result = $this->CI->Faq_model->get('', $select, $where, '', '', 'faq_order', 'asc');
		if ($result) {
			foreach ($result as $key => $value) {
				$result[$key]['faq_title'] = html_escape(element('faq_title', $value));
				$result[$key]['faq_content'] = html_escape(element('faq_content', $value));
			}
		}
		$count = $this->CI->Faq_model->count_by($where);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'count' => $count,
			'list' => $result,
		);
		return $arr;

	}


}
