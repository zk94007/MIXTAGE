<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portfolio_clip model class
 */

class Portfolio_clip_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'portfolio_clip';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'clip_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }

    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'portfolio_clip.*, portfolio.por_content, portfolio.por_category, portfolio.por_datetime, user.user_id, user.user_userid, user.user_username, user.user_is_admin, user.user_photo';
        $join[] = array('table' => 'user', 'on' => 'portfolio_clip.user_id = user.user_id', 'type' => 'inner');
        $join[] = array('table' => 'portfolio', 'on' => 'portfolio_clip.por_id = portfolio.por_id', 'type' => 'inner');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }

    public function get_clip_count($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
        $left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
        if (strtolower($orderby) !== 'desc') $orderby = 'asc';

        $this->db->select('count(*) as cnt, left(clip_datetime, ' . $left . ') as day ', false);
        $this->db->where('left(clip_datetime, 10) >=', $start_date);
        $this->db->where('left(clip_datetime, 10) <=', $end_date);

        $this->db->group_by('day');
        $this->db->order_by('clip_datetime', $orderby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;

    }

	public function count_by_category($user_id, $is_collaboration = '')
	{
        $this->db->select('count(*) as cnt, por_category ', false);
		$this->db->join('portfolio', 'portfolio_clip.por_id = portfolio.por_id', 'inner');
		$this->db->where('portfolio_clip.user_id', $user_id);
		$this->db->where('portfolio.por_open', '1');
		if ($is_collaboration == 'Y') {
			$this->db->where('portfolio.col_id >', 0);
		}
		if ($is_collaboration == 'N') {
			$this->db->where('portfolio.col_id', 0);
		}
		$this->db->group_by('por_category');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();
		
		return $result;
	}

	public function count_mentor($user_id)
	{
        $this->db->where('target_user_id', $user_id);
        $this->db->where('user_level', '5');
        $this->db->join('user', 'user.user_id = portfolio_clip.user_id', 'inner');

		$this->db->from($this->_table);

		return $this->db->count_all_results();
	}

	public function count_mentor_clip($por_id)
	{
        $this->db->where('portfolio_clip.por_id', $por_id);
        $this->db->where('user_level', '5');
        $this->db->join('user', 'user.user_id = portfolio_clip.user_id', 'inner');

		$this->db->from($this->_table);

		return $this->db->count_all_results();
	}
}
