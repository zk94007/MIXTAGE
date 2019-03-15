<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Send_reset_password_email
 */


class Send_reset_password_email extends CI_Controller
{

    private $CI;

    function __construct()
    {
        $this->CI = & get_instance();

        $this->CI->load->library(array('email'));
    }

	function main()
	{

		if ( ! $this->CI->input->post_get('token')) {
			$this->CI->apilib->make_error("토큰 값이 넘어오지 않았습니다.");
        }
		$token = $this->CI->input->post_get('token');

		$sessionuser = $this->CI->User_model->get_by_token($token);

		$email = trim($this->CI->input->post('email'));
		if ( ! $email) {
			$this->CI->apilib->make_error("이메일이 입력되지 않았습니다.");
		}

        $this->CI->load->helper('string');
		if ( ! function_exists('password_hash')) {
            $this->CI->load->helper('password');
        }

		$this->CI->load->model(array('User_auth_email_model'));

        $userinfo = $this->CI->User_model->get_by_email($email, 'user_id, user_userid, user_email, user_denied, user_username, user_password');

        if (element('user_denied', $userinfo)) {
			$this->CI->apilib->make_error("회원님의 아이디는 접근이 금지된 아이디입니다");
        }
        if ( ! element('user_id', $userinfo)) {
			$this->CI->apilib->make_error("일치하는 회원정보가 없습니다.");
        }

		$user_id = element('user_id', $userinfo);

		$vericode = array('$', '/', '.');
		$verificationcode = str_replace(
			$vericode,
			'',
			password_hash($user_id . '-' . $email . '-' . random_string('alnum', 10), PASSWORD_BCRYPT)
		);

		$beforeauthdata = array(
			'user_id' => $user_id,
			'uae_type' => 6,
		);
		$this->CI->User_auth_email_model->delete_where($beforeauthdata);
		$authdata = array(
			'user_id' => $user_id,
			'uae_key' => $verificationcode,
			'uae_type' => 6,
			'uae_generate_datetime' => cdate('Y-m-d H:i:s'),
		);
		$this->CI->User_auth_email_model->insert($authdata);


        $verify_url = site_url('mixtageverify/resetpassword?user=' . element('user_userid', $userinfo) . '&code=' . $verificationcode);

		$email_title = '[믹스테이지] 패스워드를 변경할 수 있도록 도와드립니다.';
		$email_content = '<table width="100%" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tbody><tr><td width="200" style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;">믹스테이지</td><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">안녕하세요 ' . html_escape(element('user_username', $userinfo)) . '님,</span><br>패스워드를 변경할 수 있는 링크를 보내드립니다.</td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td colspan="2" style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><p>' . html_escape(element('user_userid', $userinfo)) . '님, 안녕하세요!</p><p>&nbsp;</p><p>패스워드를 변경할 수 있도록 도와드리겠습니다.</p><p><a href="' . $verify_url . '" target="_blank" style="font-weight:bold;">패스워드 변경하기</a></p><p>&nbsp;</p><p>감사합니다.</p></td></tr></tbody></table>';

		include_once(FCPATH . 'plugin/PHPMailer/PHPMailerAutoload.php');
		
		$mail = new PHPMailer;
		$mail->SMTPDebug = 0;                               // Enable verbose debug output
		$mail->CharSet = "euc-kr";
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = config_item('email_smtp_host');  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = config_item('email_smtp_user');                 // SMTP username
		$mail->Password = config_item('email_smtp_pass');                           // SMTP password
		$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 465;                                    // TCP port to connect to
		$mail->setFrom(config_item('email_smtp_user'), iconv("UTF-8", "EUC-KR", '믹스테이지'));
		$mail->addAddress($email, element('user_username', $userinfo));     // Add a recipient
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = iconv("UTF-8", "EUC-KR", $email_title);
		$mail->Body    = iconv("UTF-8", "EUC-KR", $email_content);

		if(!$mail->send()) {
			$this->CI->apilib->make_error("메일을 발송할 수가 없습니다.");
			//echo 'Mailer Error: ' . $mail->ErrorInfo;
		} 

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'user_id' => element('user_id', $userinfo),
			'user_email' => element('user_email', $userinfo),
		);
		return $arr;

	}
}
