<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Join_respond_to_artist
 */


class Join_respond_to_artist extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 요청에 응답할 수 있습니다.");
		}
		
        $cre_id = (int) trim($this->CI->input->post('cre_id'));
        if (empty($cre_id)) {
			$this->CI->apilib->make_error("요청 고유 PK 가 입력되지 않았습니다.");
        }

        $cre_response = trim($this->CI->input->post('cre_response'));
        if ($cre_response != '1' && $cre_response != '2') {
			$this->CI->apilib->make_error("응답 방법(cre_response)이 제대로 입력되지 않았습니다.");
        }

		$this->CI->load->model(array('Collaboration_model', 'Collaboration_user_model', 'Collaboration_request_model'));
		$request = $this->CI->Collaboration_request_model->get_one($cre_id);
		if ( ! element('cre_id', $request)) {
			$this->CI->apilib->make_error("존재하지 않는 요청입니다.");
		}
		if (element('cre_response', $request)) {
			$this->CI->apilib->make_error("이미 응답하신 요청입니다.");
		}
		if (element('cre_type', $request) != '2') {
			$this->CI->apilib->make_error("응답할 수 없는 요청입니다.");
		}

		$col_id = element('col_id', $request);
		$collaboration = $this->CI->Collaboration_model->get_one($col_id);
		if ( ! element('col_id', $collaboration)) {
			$this->CI->apilib->make_error("존재하지 않는 콜라보레이션입니다.");
		}
		if (element('col_user_id', $collaboration) != element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("본인이 생성한 콜라보레이션이 아닙니다.");
		}


		$userwhere = array(
			'col_id' => $col_id,
			'user_id' => element('artist_user_id', $request),
		);
		$user = $this->CI->Collaboration_user_model->get_one('', '', $userwhere);
		if (element('cou_id', $user)) {
			$this->CI->Collaboration_request_model->delete($cre_id);
			$this->CI->apilib->make_error("이미 이 콜라보레이션에 참여하고 있는 회원입니다.");
		}

		$updatedata = array(
			'cre_response' => $cre_response,
			'cre_response_datetime' => cdate('Y-m-d H:i:s'),
		);
		$this->CI->Collaboration_request_model->update($cre_id, $updatedata);

		if ($cre_response == '1') {
			$insertdata = array(
				'col_id' => $col_id,
				'user_id' => element('artist_user_id', $request),
			);
			$this->CI->Collaboration_user_model->insert($insertdata);
		}

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'col_id' => $col_id,
			'cre_response' => $cre_response,
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;

	}
}
