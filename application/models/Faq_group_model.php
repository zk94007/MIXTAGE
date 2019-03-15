<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Faq group model class
 */

class Faq_group_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'faq_group';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'fgr_id'; // 사용되는 테이블의 프라이머리키

    public $cache_prefix = 'faq_group/faq-group-model-get-'; // 캐시 사용시 프리픽스

    public $cache_time = 86400; // 캐시 저장시간

    function __construct()
    {
        parent::__construct();

        check_cache_dir('faq_group');
    }


    public function get_one($primary_value = '', $select = '', $where = '')
    {
        $use_cache = false;
        if ($primary_value && empty($select) && empty($where)) {
            $use_cache = true;
        }

        if ($use_cache) {
            $cachename = $this->cache_prefix . $primary_value;
            if ( ! $result = $this->cache->get($cachename)) {
                $result = parent::get_one($primary_value);
                $this->cache->save($cachename, $result, $this->cache_time);
            }
        } else {
            $result = parent::get_one($primary_value, $select, $where);
        }
        return $result;
    }


    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'faq_group.*, user.user_id, user.user_userid, user.user_nickname, user.user_is_admin, user.user_icon';
        $join[] = array('table' => 'user', 'on' => 'faq_group.user_id = user.user_id', 'type' => 'left');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }


    public function delete($primary_value = '', $where = '')
    {
        if (empty($primary_value)) {
            return false;
        }
        $result = parent::delete($primary_value);
        $this->cache->delete($this->cache_prefix . $primary_value);

        return $result;
    }


    public function update($primary_value = '', $updatedata = '', $where = '')
    {
        if (empty($primary_value)) {
            return false;
        }
        $result = parent::update($primary_value, $updatedata);
        if ($result) {
            $this->cache->delete($this->cache_prefix . $primary_value);
        }

        return $result;
    }
}
