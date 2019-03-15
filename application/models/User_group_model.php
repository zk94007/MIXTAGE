<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Group model class
 */

class User_group_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'user_group';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'ugr_id'; // 사용되는 테이블의 프라이머리키

    public $cache_name = 'user_group/user-group-model-get'; // 캐시 사용시 프리픽스

    public $cache_time = 86400; // 캐시 저장시간

    function __construct()
    {
        parent::__construct();

        check_cache_dir('user_group');
    }


    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $result = $this->_get_list_common($select = '', $join = '', $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }


    public function get_all_group()
    {
        $cachename = $this->cache_name;
        if ( ! $result = $this->cache->get($cachename)) {
            $result = array();
            $res = $this->get($primary_value = '', $select = '', $where = '', $limit = '', $offset = 0, $findex = 'ugr_order', $forder = 'ASC');
            if ($res && is_array($res)) {
                foreach ($res as $val) {
                    $result[$val['ugr_id']] = $val;
                }
            }
            $this->cache->save($cachename, $result, $this->cache_time);
        }
        return $result;
    }


    public function item($groupid = 0)
    {
        $groupid = (int) $groupid;
        if (empty($groupid) OR $groupid < 1) {
            return false;
        }

        $data = $this->get_all_group();
        $result = isset($data[ $groupid ]) ? $data[ $groupid ] : false;

        return $result;
    }


    public function update_group($data = '')
    {
        $order = 1;
        if (element('ugr_id', $data) && is_array(element('ugr_id', $data))) {
            foreach (element('ugr_id', $data) as $key => $value) {
                if ( ! element($key, element('ugr_title', $data))) {
                    continue;
                }
                if ($value) {
                    $is_default = isset($data['ugr_is_default'][$key]) && $data['ugr_is_default'][$key] ? 1 : 0;
                    $updatedata = array(
                        'ugr_title' => $data['ugr_title'][$key],
                        'ugr_is_default' => $is_default,
                        'ugr_datetime' => cdate('Y-m-d H:i:s'),
                        'ugr_order' => $order,
                        'ugr_description' => $data['ugr_description'][$key],
                    );
                    $this->update($value, $updatedata);
                } else {
                    $is_default = isset($data['ugr_is_default'][$key]) && $data['ugr_is_default'][$key] ? 1 : 0;
                    $insertdata = array(
                        'ugr_title' => $data['ugr_title'][$key],
                        'ugr_is_default' => $is_default,
                        'ugr_datetime' => cdate('Y-m-d H:i:s'),
                        'ugr_order' => $order,
                        'ugr_description' => $data['ugr_description'][$key],
                    );
                    $this->insert($insertdata);
                }
            $order++;
            }
        }
        $deletewhere = array(
            'ugr_datetime !=' => cdate('Y-m-d H:i:s'),
        );
        $this->delete_where($deletewhere);
        $this->cache->delete($this->cache_name);
    }
}
