<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Group User model class
 */

class User_group_user_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'user_group_user';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'ugu_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }
}
