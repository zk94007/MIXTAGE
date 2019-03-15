<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 포트폴리오 주소, 페이스북 공유용
 */
if ( ! function_exists('portfolio_url')) {
    function portfolio_url($por_id = '')
    {
		if ( ! $por_id) {
			return '';
		}

        return site_url('p/' . $por_id);
    }
}



/**
 * 포트폴리오 사진 가져오기
 */
if ( ! function_exists('portfolio_image_url')) {
    function portfolio_image_url($img = '', $width = '', $height = '')
    {
        $CI = & get_instance();
        if (empty($img)) {
            return site_url(config_item('uploads_dir') . '/noimage.gif');
        }
        is_numeric($width) OR $width = 0;
        is_numeric($height) OR $height = 0;

        return thumb_url('portfolio', $img, $width, $height);
    }
}


/**
 * 포트폴리오 사진 가져오기
 */
if ( ! function_exists('collaboration_image_url')) {
    function collaboration_image_url($img = '', $width = '', $height = '')
    {
        $CI = & get_instance();
        if (empty($img)) {
            return site_url(config_item('uploads_dir') . '/noimage.gif');
        }
        is_numeric($width) OR $width = 0;
        is_numeric($height) OR $height = 0;

        return thumb_url('collaboration', $img, $width, $height);
    }
}


/**
 * 세미나 사진 가져오기
 */
if ( ! function_exists('seminar_image_url')) {
    function seminar_image_url($img = '', $width = '', $height = '')
    {
        $CI = & get_instance();
        if (empty($img)) {
            return site_url(config_item('uploads_dir') . '/noimage.gif');
        }
        is_numeric($width) OR $width = 0;
        is_numeric($height) OR $height = 0;

        return thumb_url('seminar', $img, $width, $height);
    }
}


/**
 * 문자나라 문자 발송
 */
function SendSMSMesg($url) {
    $fp = fsockopen("211.233.20.184", 80, $errno, $errstr, 10);
    if(!$fp) echo "$errno : $errstr";

    fwrite($fp, "GET $url HTTP/1.0\r\nHost: 211.233.20.184\r\n\r\n"); 
    $flag = 0; 
   
    $out = '';
	while(!feof($fp)){
        $row = fgets($fp, 1024);

        if($flag) $out .= $row;
        if($row=="\r\n") $flag = 1;
    }
    fclose($fp);
    return $out;
}

function send_sms($phone, $msg) {
		$userid = config_item('sms_user_id');
		$passwd = config_item('sms_user_pw');
		$hpSender = config_item('sms_send_phone');
		$hpReceiver = $phone;

		//$hpMesg = $msg;
		$hpMesg = iconv("UTF-8", "EUC-KR", $msg);

		$hpMesg = urlencode($hpMesg);
		$endAlert = 0;  // 전송완료알림창 ( 1:띄움, 0:안띄움 )

		// 한줄로 이어쓰기 하세요.
		$sendresult = SendSMSMesg("/MSG/send/web_admin_send.htm?userid=$userid&passwd=$passwd&sender=$hpSender&receiver=$hpReceiver&encode=1&end_alert=$endAlert&message=$hpMesg&allow_mms=1");

		return $sendresult;
}