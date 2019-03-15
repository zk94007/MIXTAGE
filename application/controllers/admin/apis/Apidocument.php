<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Apidocument class
 */

/**
 * 관리자>Api 관리>Api 문서보기 controller 입니다.
 */
class Apidocument extends MY_Controller
{

    /**
     * 관리자 페이지 상의 현재 디렉토리입니다
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = 'apis/apidocument';

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Api_error_log', 'Api_input', 'Api_list', 'Api_output');

    /**
     * 이 컨트롤러의 메인 모델 이름입니다
     */
    protected $modelname = 'Api_list_model';

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
        $this->load->library(array('pagination', 'querystring', 'apilib'));
    }


    /**
     * 메인페이지입니다 메소드입니다
     */
    public function index()
    {

        $view = array();
        $view['view'] = array();


        $menu = $this->_menu();

        $view['view']['data'] = $menu;


        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout_api', 'skin' => 'index');
        $view['layout'] = $this->layoutlib->admin($layoutconfig, $this->configlib->get_device_view_type());
        $view['layout']['menu'] = $menu;
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

    /**
     * 뷰페이지입니다 메소드입니다
     */
    public function view($pid = 0)
    {

        $view = array();
        $view['view'] = array();

        /**
         * 프라이머리키에 숫자형이 입력되지 않으면 에러처리합니다
         */
        if ($pid) {
            $pid = (int) $pid;
            if (empty($pid) OR $pid < 1) {
                show_404();
            }
        }
        $primary_key = $this->Api_list_model->primary_key;

        /**
         * 수정 페이지일 경우 기존 데이터를 가져옵니다
         */
        $getdata = array();
        if ($pid) {

			$getdata = $this->Api_list_model->get_one($pid);
			
			// input data
			$input = $this->Api_input_model->get('', '', $where = array('api_idx' => $pid), '', 0, 'ai_sort', 'asc');

			// output data
			$output = $this->Api_output_model->get('', '', $where = array('api_idx' => $pid), '', 0, 'ai_sort', 'asc');

		} else {
			show_404();
		}

        $menu = $this->_menu();

		$view['view']['data'] = $getdata;
		$view['view']['input'] = $input;
		$view['view']['output'] = $output;


        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout_api', 'skin' => 'view');
        $view['layout'] = $this->layoutlib->admin($layoutconfig, $this->configlib->get_device_view_type());
        $view['layout']['menu'] = $menu;
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

	public function emptypage()
	{
		echo '실행하여주세요';
	}


	public function xml($api_idx)
	{
		if (! $api_idx) show_404();
		$getdata = $this->Api_list_model->get_one($api_idx);
		if (! element('api_idx', $getdata)) show_404();
		echo $this->apilib->callapi($getdata, 'xml');
	}

	public function json($api_idx)
	{
		if (! $api_idx) show_404();
		$getdata = $this->Api_list_model->get_one($api_idx);
		if (! element('api_idx', $getdata)) show_404();
		echo $this->apilib->callapi($getdata, 'json');
	}

	public function json2($api_idx)
	{
		if (! $api_idx) show_404();
		$getdata = $this->Api_list_model->get_one($api_idx);
		if (! element('api_idx', $getdata)) show_404();
		echo $this->apilib->callapi($getdata, 'json2');
	}

	public function _menu()
	{
		$menu = $this->Api_list_model->get('', '', $where = array('api_use' => '1'), '', 0, 'api_name', 'asc');
		return $menu;
	}
}

