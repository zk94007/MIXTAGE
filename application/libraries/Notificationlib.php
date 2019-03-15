<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Notificationlib class
 */

/**
 * 알림 기능을 관리하는 class 입니다.
 */
class Notificationlib extends CI_Controller
{

    private $CI;

    function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->model( array('Notification_model'));
        $this->CI->load->helper( array('array'));
    }


    /**
     * 알림 내용을 인서트하는 함수입니다
     */
    public function set_noti($user_id = 0, $target_user_id = 0, $not_type = '', $not_content_id = '', $not_message = '', $not_url = '')
    {
        $user_id = (int) $user_id;
        $target_user_id = (int) $target_user_id;

        if (empty($user_id) OR $user_id < 1) {
            $result = json_encode( array('error' => 'user_id 가 존재하지 않습니다'));
            return $result;
        }
        if (empty($not_type)) {
            $result = json_encode( array('error' => 'not_type 가 존재하지 않습니다'));
            return $result;
        }
        if (empty($not_content_id)) {
            $result = json_encode( array('error' => 'not_content_id 가 존재하지 않습니다'));
            return $result;
        }
        if ($user_id === $target_user_id) {
            $result = json_encode( array('error' => 'user_id 와 target_user_id 이 같으므로 알림을 저장하지 않습니다'));
            return $result;
        }
        if (empty($not_message)) {
            $result = json_encode( array('error' => '알림 내용이 존재하지 않습니다'));
            return $result;
        }
        if (empty($not_url)) {
            $result = json_encode( array('error' => '알림 URL이 존재하지 않습니다'));
            return $result;
        }

        // 알림 기능을 사용을 하지 않는다면 return
        if ( ! $this->CI->configlib->item('use_notification')) {
            $result = json_encode( array('error' => '알림을 사용하지 않는 사이트입니다'));
            return $result;
        }
        switch ($not_type) {
            case 'reply':
                if ( ! $this->CI->configlib->item('notification_reply')) {
                    $result = json_encode( array('error' => '답변글에 알림 기능을 사용하지 않습니다'));
                    return $result;
                }
                break;
            case 'comment':
                if ( ! $this->CI->configlib->item('notification_comment')) {
                    $result = json_encode( array('error' => '댓글에 알림 기능을 사용하지 않습니다'));
                    return $result;
                }
                break;
            case 'comment_comment':
                if ( ! $this->CI->configlib->item('notification_comment_comment')) {
                    $result = json_encode( array('error' => '댓글의 댓글에 알림 기능을 사용하지 않습니다'));
                    return $result;
                }
                break;
            case 'note':
                if ( ! $this->CI->configlib->item('notification_note')) {
                    $result = json_encode( array('error' => '쪽지에 알림 기능을 사용하지 않습니다'));
                    return $result;
                }
                break;
            default :
                $result = json_encode( array('error' => 'TYPE 이 잘못되었습니다'));
                return $result;

        }
        $insertdata = array(
            'user_id' => $user_id,
            'target_user_id' => $target_user_id,
            'not_type' => $not_type,
            'not_content_id' => $not_content_id,
            'not_message' => $not_message,
            'not_url' => $not_url,
            'not_datetime' => cdate('Y-m-d H:i:s'),
        );
        $not_id = $this->CI->Notification_model->insert($insertdata);

        $result = json_encode( array('success' => '알림이 저장되었습니다'));

        return $result;
    }
}
