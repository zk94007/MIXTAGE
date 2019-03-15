<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Auth Email model class
 */

class User_auth_email_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'user_auth_email';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'uae_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }
}
