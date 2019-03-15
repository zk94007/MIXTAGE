<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Api_error_log model class
 */

class Api_error_log_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'api_error_log';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'el_idx'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }
}
