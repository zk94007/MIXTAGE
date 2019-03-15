<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_recommend model class
 */

class User_recommend_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'user_recommend';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'rec_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }
}
