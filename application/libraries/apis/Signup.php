<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Signup
 */


class Signup extends CI_Controller
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

		$this->CI->load->model(array('User_phone_certify_model'));

		$sessionuser = $this->CI->User_model->get_by_token($token);
		if (element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("이미 로그인 중이셔서 회원가입이 불가능합니다");
		}
		

        if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

		if ( ! $this->CI->input->post('user_userid')) {
			$this->CI->apilib->make_error("회원 아이디가 입력되지 않았습니다.");
		}
		if ( ! $this->CI->input->post('user_password')) {
			$this->CI->apilib->make_error("회원 패스워드가 입력되지 않았습니다.");
		}
		if ($this->CI->input->post('user_password') != $this->CI->input->post('user_password2')) {
			$this->CI->apilib->make_error("패스워드가 일치하지 않습니다.");
		}

		$user_userid = trim($this->CI->input->post('user_userid'));
		$user_password = trim($this->CI->input->post('user_password'));

		if ( ! ctype_alnum($user_userid) ) {
			$this->CI->apilib->make_error("회원 아이디는 알파벳과 숫자로만 구성되어야 합니다.");
		}
		if (mb_strlen($user_userid) < 3 OR mb_strlen($user_userid) > 25) {
			$this->CI->apilib->make_error("회원 아이디는 3~25 자 사이여야 합니다.");
		}
		$userwhere = array(
			'user_userid' => $user_userid,
		);
		$row = $this->CI->User_model->get_one('', 'user_id', $userwhere);
		if (element('user_id', $row)) {
			$this->CI->apilib->make_error("이미 다른 회원이 사용하고 있는 아이디이므로 이 아이디는 사용하실 수 없습니다.");
		}

		if (mb_strlen($user_password) < 7 OR mb_strlen($user_password) > 12) {
			$this->CI->apilib->make_error("회원 패스워드는 7~12 자 사이여야 합니다.");
		}


		$this->CI->load->helper('chkstring');
        $str_uc = count_uppercase($user_password);
        $str_num = count_numbers($user_password);

        if ( ! $str_uc OR ! $str_num) {
			$this->CI->apilib->make_error("회원 패스워드는 대문자와 숫자를 각각 하나 이상씩 포함하여야 합니다.");
        }

		$user_artist_category = trim($this->CI->input->post('user_artist_category'));

		$portfolio_category = config_item('portfolio_category');
		if ( ! $user_artist_category OR ! element($user_artist_category, $portfolio_category)) {
			$this->CI->apilib->make_error("아티스트 분야를 올바르게 선택하여주세요.");
		}

		$user_username = trim($this->CI->input->post('user_username'));
		if ( ! $user_username) {
			$this->CI->apilib->make_error("회원명이 입력되지 않았습니다.");
		}
		
		$user_email = trim($this->CI->input->post('user_email'));
		if ( ! $user_email) {
			$this->CI->apilib->make_error("이메일이 입력되지 않았습니다.");
		}
		
		$this->CI->load->helper('email');
		if ( ! valid_email($user_email) ) {
			$this->CI->apilib->make_error("이메일 형식에 맞지 않습니다.");
		}

		$userwhere = array(
			'user_email' => $user_email,
		);
		$row = $this->CI->User_model->get_one('', 'user_id', $userwhere);
		if (element('user_id', $row)) {
			$this->CI->apilib->make_error("이미 다른 회원이 사용하고 있는 이메일입니다.");
		}

		$user_phone = trim($this->CI->input->post('user_phone'));
		if ( ! $user_phone) {
			$this->CI->apilib->make_error("휴대폰 번호가 입력되지 않았습니다.");
		}

		$pce_phone = str_replace('-', '', $user_phone);
		$getwhere = array(
			'pce_phone' => $pce_phone,
			'pce_activated' => 1,
		);
		$result = $this->CI->User_phone_certify_model->get_one('', '', $getwhere);
		if ( ! element('pce_id', $result)) {
			$this->CI->apilib->make_error("휴대폰 번호를 인증받으신 후에 회원가입을 진행하여주세요.");
		}
		$phonewhere = array('pce_phone' => $pce_phone);
		$this->CI->User_phone_certify_model->delete('', $phonewhere);

		$insertdata = array(
			'user_userid' => $user_userid,
			'user_password' => password_hash($user_password, PASSWORD_BCRYPT),
			'user_email' => $user_email,
			'user_level' => '1',
			'user_username' => $user_username,
			'user_phone' => $user_phone,
			'user_register_datetime' => cdate('Y-m-d H:i:s'),
			'user_token' => $token,
		);

        $user_id = $this->CI->User_model->insert($insertdata);

		//$this->CI->session->set_userdata('user_id', $user_id);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'user_id' => $user_id,
			'user_userid' => $user_userid,
			'user_email' => $user_email,
			'user_username' => $user_username,
			'user_artist_category' => $user_artist_category,
			'user_phone' => $user_phone . " ",
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;

	}


}
