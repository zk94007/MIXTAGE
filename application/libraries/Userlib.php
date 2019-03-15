<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Userlib class
 */

/**
 * user table 을 관리하는 class 입니다.
 */
class Userlib
{

    private $CI;

    private $mb;

    private $user_group;


    function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->model( array('User_model'));
        $this->CI->load->helper( array('array'));
    }


    /**
     * 접속한 유저가 회원인지 아닌지를 판단합니다
     */
    public function is_user()
    {
        if ($this->CI->session->userdata('user_id')) {
            return $this->CI->session->userdata('user_id');
        } else {
            return false;
        }
    }


    /**
     * 접속한 유저가 관리자인지 아닌지를 판단합니다
     */
    public function is_admin($check = array())
    {
        if ($this->item('user_is_admin')) {
            return 'super';
        }
		return false;
    }


    /**
     * user, user_extra_vars, user_meta 테이블에서 정보를 가져옵니다
     */
    public function get_user()
    {
        if ($this->is_user()) {
            if (empty($this->mb)) {
                $user = $this->CI->User_model->get_by_id($this->is_user());
                $extras = $this->get_all_extras(element('user_id', $user));
                if (is_array($extras)) {
                    $user = array_merge($user, $extras);
                }
                $metas = $this->get_all_meta(element('user_id', $user));
                if (is_array($metas)) {
                    $user = array_merge($user, $metas);
                }
                $this->mb = $user;
            }
            return $this->mb;
        } else {
            return false;
        }
    }


    /**
     * get_user 에서 가져온 데이터의 item 을 보여줍니다
     */
    public function item($column = '')
    {
        if (empty($column)) {
            return false;
        }
        if (empty($this->mb)) {
            $this->get_user();
        }
        if (empty($this->mb)) {
            return false;
        }
        $user = $this->mb;

        return isset($user[$column]) ? $user[$column] : false;
    }


    /**
     * 회원이 속한 그룹 정보를 가져옵니다
     */
    public function group()
    {
        if (empty($this->user_group)) {
            $where = array(
                'user_id' => $this->item('user_id'),
            );
            $this->CI->load->model('User_group_user_model');
            $this->user_group = $this->CI->User_group_user_model->get('', '', $where, '', 0, 'ugu_id', 'ASC');
        }
        return $this->user_group;
    }


    /**
     * user_extra_vars 테이블에서 가져옵니다
     */
    public function get_all_extras($user_id = 0)
    {
        if (empty($user_id)) {
            return false;
        }

        $this->CI->load->model('User_extra_vars_model');
        $result = $this->CI->User_extra_vars_model->get_all_meta($user_id);
        return $result;
    }


    /**
     * user_meta 테이블에서 가져옵니다
     */
    public function get_all_meta($user_id = 0)
    {
        $user_id = (int) $user_id;
        if (empty($user_id) OR $user_id < 1) {
            return false;
        }

        $this->CI->load->model('User_meta_model');
        $result = $this->CI->User_meta_model->get_all_meta($user_id);
        
        return $result;
    }


    /**
     * 로그인 기록을 남깁니다
     */
    public function update_login_log($user_id= 0, $userid = '', $success= 0, $reason = '')
    {
        $success = $success ? 1 : 0;
        $user_id = (int) $user_id ? (int) $user_id : 0;
        $reason = isset($reason) ? $reason : '';
        $referer = $this->CI->input->get_post('url', null, '');
        $loginlog = array(
            'ull_success' => $success,
            'user_id' => $user_id,
            'ull_userid' => $userid,
            'ull_datetime' => cdate('Y-m-d H:i:s'),
            'ull_ip' => $this->CI->input->ip_address(),
            'ull_reason' => $reason,
            'ull_useragent' => $this->CI->agent->agent_string(),
            'ull_url' => current_full_url(),
            'ull_referer' => $referer,
        );
        $this->CI->load->model('User_login_log_model');
        $this->CI->User_login_log_model->insert($loginlog);

        return true;
    }

    /**
     * 회원삭제 남깁니다
     */
    public function delete_user($user_id = 0)
    {
        $user_id = (int) $user_id;
        if (empty($user_id) OR $user_id < 1) {
            return false;
        }

        $this->CI->load->model(
            array(
                'Follow_model', 'User_model', 'User_auth_email_model',
                'User_extra_vars_model', 'User_group_user_model',
                'User_level_history_model', 'User_login_log_model', 'User_meta_model',
                'User_register_model', 'Notification_model',
                'User_userid_model',
            )
        );

        $deletewhere = array(
            'user_id' => $user_id,
        );
        $this->CI->Follow_model->delete_where($deletewhere);
        $this->CI->User_model->delete_where($deletewhere);
        $this->CI->User_auth_email_model->delete_where($deletewhere);
        $this->CI->User_extra_vars_model->delete_where($deletewhere);
        $this->CI->User_group_user_model->delete_where($deletewhere);
        $this->CI->User_level_history_model->delete_where($deletewhere);
        $this->CI->User_login_log_model->delete_where($deletewhere);
        $this->CI->User_meta_model->delete_where($deletewhere);
        $this->CI->User_register_model->delete_where($deletewhere);
        $this->CI->Notification_model->delete_where($deletewhere);
        $this->CI->User_userid_model->update($user_id, array('user_status' => 1));

        return true;
    }
}
