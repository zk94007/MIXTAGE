<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Gotourl class
 */

/**
 * 다른 페이지로 이동시 중간에 거쳐가는 controller 입니다.
 * admin 페이지에서 외부 페이이지로 이동시 이 페이지를 거쳐가면 referer 가 이 주소로 남기 때문에 admin 주소를 referer 에서 감출 수 있습니다
 */
class Gotourl extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
    }


    /**
     * url 이동 관련 함수입니다
     */
    public function index()
    {

        $url = $this->input->get('url');
        if (empty($url)) {
            $url = '/';
        }
        redirect($url, 'refresh');
    }
}
