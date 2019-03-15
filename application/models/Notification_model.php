<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Notification model class
 */

class Notification_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'notification';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'not_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function get_notification_list($limit = '', $offset = '', $user_id = 0, $nottype = '')
    {
        $user_id = (int) $user_id;
        if (empty($user_id) OR $user_id < 1) {
            return;
        }

        $this->db->select('notification.*, user.user_id, user.user_userid, user.user_nickname, user.user_is_admin, user.user_icon');
        $this->db->from($this->_table);
        $this->db->join('user', 'notification.target_user_id = user.user_id', 'left');

        $this->db->where(array('notification.user_id' => $user_id));
        if ($nottype === 'Y') {
            $this->db->where(array('not_read_datetime >' => '0000-00-00 00:00:00'));
        }
        if ($nottype === 'N') {
            $this->db->group_start();
            $this->db->where(array('not_read_datetime <=' => '0000-00-00 00:00:00'));
            $this->db->or_where(array('not_read_datetime' => null));
            $this->db->group_end();
        }

        $this->db->order_by('not_id', 'desc');
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        $qry = $this->db->get();
        $result['list'] = $qry->result_array();

        $this->db->select('count(*) as rownum');
        $this->db->from($this->_table);
        $this->db->join('user', 'notification.target_user_id = user.user_id', 'left');
        $this->db->where(array('notification.user_id' => $user_id));
        if ($nottype === 'Y') {
            $this->db->where(array('not_read_datetime >' => '0000-00-00 00:00:00'));
        }
        if ($nottype === 'N') {
            $this->db->group_start();
            $this->db->where(array('not_read_datetime <=' => '0000-00-00 00:00:00'));
            $this->db->or_where(array('not_read_datetime' => null));
            $this->db->group_end();
        }
        $qry = $this->db->get();
        $rows = $qry->row_array();
        $result['total_rows'] = $rows['rownum'];

        return $result;
    }


    public function unread_notification_num($user_id = 0)
    {
        $user_id = (int) $user_id;
        if (empty($user_id) OR $user_id < 1) {
            return;
        }

        $this->db->where(array('user_id' => $user_id ));
        $this->db->group_start();
        $this->db->where(array('not_read_datetime <=' => '0000-00-00 00:00:00'));
        $this->db->or_where(array('not_read_datetime' => null));
        $this->db->group_end();

        return $this->db->count_all_results($this->_table);
    }


    public function mark_read($not_id, $user_id)
    {
        $where = array(
            'not_id' => $not_id,
            'user_id' => $user_id,
        );
        $updatedata = array(
            'not_read_datetime' => cdate('Y-m-d H:i:s'),
        );
        $this->db->where($where);
        $this->db->group_start();
        $this->db->where(array('not_read_datetime <=' => '0000-00-00 00:00:00'));
        $this->db->or_where(array('not_read_datetime' => null));
        $this->db->group_end();
        $this->db->set($updatedata);

        return $this->db->update($this->_table);
    }


    public function mark_allread($user_id)
    {
        $where = array(
            'user_id' => $user_id,
        );
        $updatedata = array(
            'not_read_datetime' => cdate('Y-m-d H:i:s'),
        );
        $this->db->where($where);
        $this->db->group_start();
        $this->db->where(array('not_read_datetime <=' => '0000-00-00 00:00:00'));
        $this->db->or_where(array('not_read_datetime' => null));
        $this->db->group_end();
        $this->db->set($updatedata);

        return $this->db->update($this->_table);
    }
}
