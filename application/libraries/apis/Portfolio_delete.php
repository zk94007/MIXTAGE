<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portfolio_delete
 */


class Portfolio_delete extends CI_Controller
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

		$this->CI->load->model(array('Portfolio_model'));
		
		$sessionuser = $this->CI->User_model->get_by_token($token);
		if ( ! element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("로그인 한 회원만 포트폴리오를 삭제할 수 있습니다.");
		}
		
        $por_id = (int) trim($this->CI->input->post('por_id'));
        if (empty($por_id)) {
			$this->CI->apilib->make_error("포트폴리오 고유 PK 가 입력되지 않았습니다.");
        }
		$portfolio = $this->CI->Portfolio_model->get_one($por_id);
		if ( ! element('por_id', $portfolio)) {
			$this->CI->apilib->make_error("존재하지 않는 포트폴리오입니다.");
		}
		if (element('user_id', $portfolio) != element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("본인의 포트폴리오만 삭제가 가능합니다.");
		}
		
		$this->CI->mixtage->delete_portfolio($por_id);
		if (element('col_id', $portfolio)) {
			$colwhere = array(
				'col_id' => element('col_id', $portfolio),
			);
			$coluser = $this->CI->Collaboration_user_model->get('', '', $colwhere);
			if ($coluser) {
				foreach ($coluser as $colval) {
					if (element('user_id', $colval) == element('user_id', $sessionuser)) continue;
					$this->CI->mixtage->deletenoti(element('user_id', $sessionuser), element('user_id', $colval), 'portfolio', $por_id);
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
