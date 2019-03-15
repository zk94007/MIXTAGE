<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Collaboration_request model class
 */

class Collaboration_request_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'collaboration_request';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'cre_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }

    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'collaboration_request.*, collaboration.col_desc, collaboration.col_blame, collaboration.col_datetime, collaboration.col_user_id, collaboration.col_image_name, req.user_userid, req.user_username, req.user_is_admin, req.user_photo, res.user_userid target_user_userid, res.user_username target_user_username, res.user_is_admin target_user_is_admin, res.user_photo target_user_photo';
        $join[] = array('table' => 'user as req', 'on' => 'collaboration_request.col_user_id = req.user_id', 'type' => 'inner');
        $join[] = array('table' => 'user as res', 'on' => 'collaboration_request.artist_user_id = res.user_id', 'type' => 'inner');
        $join[] = array('table' => 'collaboration', 'on' => 'collaboration_request.col_id = collaboration.col_id', 'type' => 'inner');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }

    public function get_list($col_id = '', $type = '')
    {
        $this->db->select('collaboration_request.cre_id, collaboration_request.artist_user_id, collaboration_request.cre_datetime, collaboration_request.cre_response, collaboration_request.cre_response_datetime, user.user_userid, user.user_username, user.user_photo');
		$this->db->join('user', 'collaboration_request.artist_user_id = user.user_id', 'inner');
		$this->db->where('col_id', $col_id);
		$this->db->where('cre_type', '2');
		if ($type == 'Y') {
			$this->db->where('cre_response', '1');
		} else if ($type == 'N') {
			$this->db->where('cre_response', '2');
		} else if ($type == 'R') {
			$this->db->where('cre_response', '0');
		}
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();
        return $result;
    }


    public function get_requested_list($user_id = '', $type = '')
    {
        $this->db->select('collaboration_request.cre_id, collaboration_request.artist_user_id, collaboration_request.cre_datetime, collaboration_request.cre_response, collaboration_request.cre_response_datetime, collaboration.col_desc, collaboration.col_datetime, collaboration.col_user_id, collaboration.col_image_name, user.user_userid, user.user_username, user.user_photo, user.user_level, user.user_artist_category');
		$this->db->join('user', 'collaboration_request.artist_user_id = user.user_id', 'inner');
		$this->db->where('collaboration_request.col_user_id', $user_id);
		$this->db->join('collaboration', 'collaboration_request.col_id = collaboration.col_id', 'inner');
		$this->db->where('cre_type', '2');
		if ($type == 'Y') {
			$this->db->where('cre_response', '1');
		} else if ($type == 'N') {
			$this->db->where('cre_response', '2');
		} else if ($type == 'R') {
			$this->db->where('cre_response', '0');
		}
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();
        return $result;
    }


    public function get_requesting_list($user_id = '', $type = '')
    {
        $this->db->select('collaboration_request.cre_id, collaboration_request.artist_user_id, collaboration_request.cre_datetime, collaboration_request.cre_response, collaboration_request.cre_response_datetime, collaboration.col_desc, collaboration.col_datetime, collaboration.col_user_id, collaboration.col_image_name, user.user_userid, user.user_username, user.user_photo, user.user_level, user.user_artist_category');
		$this->db->join('user', 'collaboration_request.col_user_id = user.user_id', 'inner');
		$this->db->where('collaboration_request.artist_user_id', $user_id);
		$this->db->join('collaboration', 'collaboration_request.col_id = collaboration.col_id', 'inner');
		$this->db->where('cre_type', '1');
		if ($type == 'Y') {
			$this->db->where('cre_response', '1');
		} else if ($type == 'N') {
			$this->db->where('cre_response', '2');
		} else if ($type == 'R') {
			$this->db->where('cre_response', '0');
		}
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();
        return $result;
    }

}
