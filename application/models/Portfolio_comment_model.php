<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portfolio_comment class
 */

class Portfolio_comment_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'portfolio_comment';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'pco_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }

    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'portfolio_comment.*, portfolio.por_content, portfolio.por_category, portfolio.por_datetime, user.user_id, user.user_userid, user.user_username, user.user_is_admin, user.user_photo';
        $join[] = array('table' => 'user', 'on' => 'portfolio_comment.pco_user_id = user.user_id', 'type' => 'inner');
        $join[] = array('table' => 'portfolio', 'on' => 'portfolio_comment.por_id = portfolio.por_id', 'type' => 'inner');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }

    public function get_api_list($por_id, $type)
    {
        $select = 'portfolio_comment.pco_id, portfolio_comment.por_id, pco_content, pco_user_id, pco_reply_user_id, pco_datetime, pco_updated_datetime, pco_blame, user.user_id, user.user_userid, user.user_username, user.user_level, user.user_photo, portfolio_comment_blame.pcb_id';
		$this->db->select($select);
		$this->db->join('user', 'portfolio_comment.pco_user_id = user.user_id', 'inner');
		$this->db->join('portfolio_comment_blame', 'portfolio_comment.pco_id = portfolio_comment_blame.pco_id AND portfolio_comment.pco_user_id = portfolio_comment_blame.user_id', 'left');
		$this->db->where('portfolio_comment.por_id', $por_id);
		if ($type == 'M') {
			$this->db->where('user.user_level >=', '5');
		}
		if ($type == 'G') {
			$this->db->where('user.user_level <', '5');
		}
		$this->db->order_by('pco_id', 'desc');
		//$this->db->order_by('pco_reply', 'asc');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();
        return $result;
    }

    public function get_count($por_id, $type )
    {
		$this->db->where(array('por_id' => $por_id));
		$this->db->join('user', 'portfolio_comment.pco_user_id = user.user_id', 'inner');
		if ($type == 'M') {
			$this->db->where('user.user_level >=', '5');
		}
		if ($type == 'G') {
			$this->db->where('user.user_level <', '5');
		}
		$result = $this->db->count_all_results($this->_table);
        return $result;
    }

    public function next_comment_num()
    {
        $this->db->select_min('pco_num');
        $result = $this->db->get($this->_table);
        $row = $result->row_array();
        $row['pco_num'] = (isset($row['pco_num']) && is_numeric($row['pco_num'])) ? $row['pco_num'] : 0;
        $pco_num = $row['pco_num'] - 1;

        return $pco_num;
    }

	
	public function update_blame($primary_value = '')
    {
        if (empty($primary_value)) {
            return false;
        }

        $this->db->where($this->primary_key, $primary_value);
        $this->db->set('pco_blame', 'pco_blame+1', false);
        $result = $this->db->update($this->_table);

        return $result;
    }

	public function count_mentor($user_id)
	{
        $this->db->where('portfolio.user_id', $user_id);
        $this->db->where('user_level', '5');
        $this->db->join('user', 'user.user_id = portfolio_comment.pco_user_id', 'inner');
        $this->db->join('portfolio', 'portfolio.por_id = portfolio_comment.por_id', 'inner');

		$this->db->from($this->_table);

		return $this->db->count_all_results();
	}

	public function count_mentor_comment($por_id)
	{
        $this->db->where('portfolio_comment.por_id', $por_id);
        $this->db->where('user_level', '5');
        $this->db->join('user', 'user.user_id = portfolio_comment.pco_user_id', 'inner');

		$this->db->from($this->_table);

		return $this->db->count_all_results();
	}
 
}
