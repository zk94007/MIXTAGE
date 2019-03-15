<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Noti model class
 */

class Noti_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'noti';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'not_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }

	public function get_personal_list($user_id, $not_type = '')
	{
		$this->db->select('noti.*, portfolio.por_content, portfolio.por_category, portfolio.por_datetime, portfolio.por_cover_image_name, user.user_id, user.user_userid, user.user_username, user.user_level, user.user_photo');
		$this->db->join('user', 'noti.user_id = user.user_id', 'inner');
		$this->db->join('portfolio', 'noti.por_id = portfolio.por_id', 'left');
		$this->db->where('target_user_id', $user_id);
		if ($not_type) {
			$this->db->where('noti.not_type', $not_type);
		}
		$this->db->order_by('not_id', 'desc');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}
}
