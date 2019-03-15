<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Main class
 */

/**
 * 관리자 메인 controller 입니다.
 */
class Main extends MY_Controller
{

    /**
     * 관리자 페이지 상의 현재 디렉토리입니다
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = '';

    /**
     * 모델을 로딩합니다
     */
    protected $models = array();

    /**
     * 이 컨트롤러의 메인 모델 이름입니다
     */
    protected $modelname = '';

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
     * 관리자 메인 페이지입니다
     */
    public function index()
    {

		redirect(admin_url('portfolio/portfolio'));

    }
}
