<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Seminar_view
 */


class Seminar_view extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 세미나를 열람하실 수 있습니다.");
		}
		
		$this->CI->load->model(array('Seminar_model', 'Seminar_user_model'));
		$seminar = $this->CI->Seminar_model->get_approved_data();
		if ( ! element('sem_id', $seminar)) {
			$this->CI->apilib->make_error("현재 진행되는 세미나가 없습니다.");
		}

		$where = array(
			'sem_id' => element('sem_id', $seminar),
		);
		$attend_user = $this->CI->Seminar_user_model->count_by($where);

		$where = array(
			'sem_id' => element('sem_id', $seminar),
			'user_id' => element('user_id', $sessionuser),
		);
		$i_attend = $this->CI->Seminar_user_model->count_by($where);


		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'sem_id' => element('sem_id', $seminar),
			'sem_title' => html_escape(element('sem_title', $seminar)),
			'sem_content' => html_escape(element('sem_content', $seminar)),
			'sem_image' => seminar_image_url(element('sem_image_name', $seminar)),
			'sem_total_user' => element('sem_total_user', $seminar),
			'sem_attend_user' => $attend_user,
			'i_attend' => $i_attend,
		);

		return $arr;

	}


}
