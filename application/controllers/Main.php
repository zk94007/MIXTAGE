<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Main class
 */

/**
 * 메인 페이지를 담당하는 controller 입니다.
 */
class Main extends MY_Controller
{

    /**
     * 모델을 로딩합니다
     */
    protected $models = array();

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring'));
    }


    /**
     * 전체 메인 페이지입니다
     */
    public function index()
    {

		if ($this->userlib->is_admin()) {
			redirect(admin_url());
		} else if ( ! $this->userlib->item('user_id')) {
			redirect(site_url('login'));
		} else {
			redirect(site_url('login/nopage'));
		}
    }
}
