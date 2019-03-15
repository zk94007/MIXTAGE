<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portfolio_comment_blame model class
 */

class Portfolio_comment_blame_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'portfolio_comment_blame';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'pcb_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }

    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'portfolio_comment_blame.*, portfolio.por_content, portfolio.por_category, portfolio_comment.pco_content, portfolio_comment.pco_datetime, user.user_id, user.user_userid, user.user_username, user.user_is_admin, user.user_photo';
        $join[] = array('table' => 'user', 'on' => 'portfolio_comment_blame.user_id = user.user_id', 'type' => 'inner');
        $join[] = array('table' => 'portfolio', 'on' => 'portfolio_comment_blame.por_id = portfolio.por_id', 'type' => 'inner');
        $join[] = array('table' => 'portfolio_comment', 'on' => 'portfolio_comment_blame.pco_id = portfolio_comment.pco_id', 'type' => 'inner');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }

    public function get_blame_count($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
        $left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
        if (strtolower($orderby) !== 'desc') $orderby = 'asc';

        $this->db->select('count(*) as cnt, left(pcb_datetime, ' . $left . ') as day ', false);
        $this->db->where('left(pcb_datetime, 10) >=', $start_date);
        $this->db->where('left(pcb_datetime, 10) <=', $end_date);

        $this->db->group_by('day');
        $this->db->order_by('pcb_datetime', $orderby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;

    }
}
