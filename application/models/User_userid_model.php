<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Userid model class
 */

class User_userid_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'user_userid';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'user_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }
}
