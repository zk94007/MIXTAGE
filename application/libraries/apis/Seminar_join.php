<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Seminar_join
 */


class Seminar_join extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 세미나를 참석하실 수 있습니다.");
		}
		if (element('user_level', $sessionuser) < 3) {
			$this->CI->apilib->make_error("아티스트가 아닌 회원은 세미나를 참석하실 수 있습니다.");
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

		if ($i_attend) {
			$this->CI->apilib->make_error("회원님은 이미 세미나 참석 신청을 하셨습니다.");
		}

		if ($attend_user >= element('sem_total_user', $seminar))
		{
			$this->CI->apilib->make_error("참석 인원이 초과되어 더 이상 참석 신청을 하실 수 없습니다.");
		}

		$insertdata = array(
			'sem_id' => element('sem_id', $seminar),
			'user_id' => element('user_id', $sessionuser),
			'seu_datetime' => cdate('Y-m-d H:i:s'),
		);
		$this->CI->Seminar_user_model->insert($insertdata);

 		$where = array(
			'sem_id' => element('sem_id', $seminar),
		);
		$attend_user = $this->CI->Seminar_user_model->count_by($where);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'datetime' => cdate('Y-m-d H:i:s'),
			'sem_attend_user' => $attend_user,
		);

		return $arr;

	}


}
