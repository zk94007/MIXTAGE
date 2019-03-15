<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Login
 */


class Login extends CI_Controller
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

        if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

		if ( ! $this->CI->input->post('userid')) {
			$this->CI->apilib->make_error("회원 아이디가 입력되지 않았습니다.");
		}
		if ( ! $this->CI->input->post('userpw')) {
			$this->CI->apilib->make_error("회원 패스워드가 입력되지 않았습니다.");
		}

		$userid = trim($this->CI->input->post('userid'));
		$userpw = trim($this->CI->input->post('userpw'));

		if ( ! ctype_alnum($userid) ) {
			$this->CI->apilib->make_error("회원 아이디는 알파벳과 숫자로만 구성되어야 합니다.");
		}
		if ( $desc = $this->_user_password_check($userpw) ) {
			$this->CI->apilib->make_error($desc);
		}
		if (mb_strlen($userid) < 3 OR mb_strlen($userid) > 25) {
			$this->CI->apilib->make_error("회원 아이디는 25 자 이내여야 합니다.");
		}
		if (mb_strlen($userpw) < 7 OR mb_strlen($userpw) > 12) {
			$this->CI->apilib->make_error("회원 비밀번호는 7~12자 사이여야 합니다.");
		}

        $userinfo = $this->CI->User_model->get_by_userid($userid, 'user_id, user_userid, user_denied, user_password');
        $hash = password_hash($this->CI->input->post('userpw'), PASSWORD_BCRYPT);

        if ( ! element('user_id', $userinfo) OR ! element('user_password', $userinfo)) {
			$this->CI->apilib->make_error("회원 아이디와 패스워드가 일치하지 않습니다.");
        } elseif ( ! password_verify($userpw, element('user_password', $userinfo))) {
			$this->CI->apilib->make_error("회원 아이디와 패스워드가 일치하지 않습니다.");
        } elseif (element('user_denied', $userinfo)) {
			$this->CI->apilib->make_error("회원님의 아이디는 접근이 금지된 아이디입니다");
        }

		$updatedata = array(
			'user_token' => '',
		);
		$upwhere = array(
			'user_token' => $token,
		);
		$this->CI->User_model->update('', $updatedata, $upwhere);

		$updatedata = array(
			'user_token' => $token,
		);
		$this->CI->User_model->update(element('user_id', $userinfo), $updatedata);


		/*
		$this->CI->session->set_userdata(
			'user_id',
			element('user_id', $userinfo)
		);
		*/

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'user_id' => element('user_id', $userinfo),
		);
		return $arr;

	}


    /**
     * 회원가입시 패스워드가 올바른 규약에 의해 입력되었는지를 체크하는 함수입니다
     */
    public function _user_password_check($str)
    {
        $uppercase = $this->CI->configlib->item('password_uppercase_length');
        $number = $this->CI->configlib->item('password_numbers_length');
        $specialchar = $this->CI->configlib->item('password_specialchars_length');

        $this->CI->load->helper('chkstring');
        $str_uc = count_uppercase($str);
        $str_num = count_numbers($str);
        $str_spc = count_specialchars($str);

        if ($str_uc < $uppercase OR $str_num < $number OR $str_spc < $specialchar) {

            $description = '비밀번호는 ';
            if ($str_uc < $uppercase) {
                $description .= ' ' . $uppercase . '개 이상의 대문자';
            }
            if ($str_num < $number) {
                $description .= ' ' . $number . '개 이상의 숫자';
            }
            if ($str_spc < $specialchar) {
                $description .= ' ' . $specialchar . '개 이상의 특수문자';
            }
            $description .= '를 포함해야 합니다';

            return $description;

        }

        return false;
    }
}
