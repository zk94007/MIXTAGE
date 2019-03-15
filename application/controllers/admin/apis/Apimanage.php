<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Apimanage class
 */

/**
 * 관리자>Api 관리>Api 관리 controller 입니다.
 */
class Apimanage extends MY_Controller
{

    /**
     * 관리자 페이지 상의 현재 디렉토리입니다
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = 'apis/apimanage';

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
        $this->load->library(array('pagination', 'querystring'));
    }

    /**
     * 목록을 가져오는 메소드입니다
     */
    public function index()
    {

        $view = array();
        $view['view'] = array();


        /**
         * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
         */
        $param =& $this->querystring;
        $page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
        $findex = $this->input->get('findex') ? $this->input->get('findex') : 'api_name';
        $forder = $this->input->get('forder', null, 'asc');
        $sfield = $this->input->get('sfield', null, '');
        $skeyword = $this->input->get('skeyword', null, '');

        $per_page = 100;
        $offset = ($page - 1) * $per_page;

        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */
        $this->{$this->modelname}->allow_search_field = array('api_idx', 'api_name', 'api_exp', 'api_url', 'api_bigo'); // 검색이 가능한 필드
        $this->{$this->modelname}->search_field_equal = array('api_idx'); // 검색중 like 가 아닌 = 검색을 하는 필드
        $this->{$this->modelname}->allow_order_field = array('api_idx', 'api_name'); // 정렬이 가능한 필드
        $result = $this->{$this->modelname}
            ->get_admin_list($per_page, $offset, '', '', $findex, $forder, $sfield, $skeyword);

        $list_num = $result['total_rows'] - ($page - 1) * $per_page;
        if (element('list', $result)) {
            foreach (element('list', $result) as $key => $val) {
                $result['list'][$key]['num'] = $list_num--;
            }
        }

        $view['view']['data'] = $result;

        /**
         * primary key 정보를 저장합니다
         */
        $view['view']['primary_key'] = $this->{$this->modelname}->primary_key;

        /**
         * 페이지네이션을 생성합니다
         */
        $config['base_url'] = admin_url($this->pagedir) . '?' . $param->replace('page');
        $config['total_rows'] = $result['total_rows'];
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $view['view']['paging'] = $this->pagination->create_links();
        $view['view']['page'] = $page;

        /**
         * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
         */
        $search_option = array('api_name' => '이름', 'api_method' => '호출방식', 'api_exp' => '설명', 'api_bigo' => '비고');
        $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        $view['view']['search_option'] = search_option($search_option, $sfield);
        $view['view']['listall_url'] = admin_url($this->pagedir);
        $view['view']['write_url'] = admin_url($this->pagedir . '/write');
        $view['view']['list_delete_url'] = admin_url($this->pagedir . '/listdelete/?' . $param->output());


        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout', 'skin' => 'index');
        $view['layout'] = $this->layoutlib->admin($layoutconfig, $this->configlib->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

    /**
     * 게시판 글쓰기 또는 수정 페이지를 가져오는 메소드입니다
     */
    public function write($pid = 0)
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
        $primary_key = $this->{$this->modelname}->primary_key;

        /**
         * 수정 페이지일 경우 기존 데이터를 가져옵니다
         */
        $getdata = array();
        if ($pid) {
            $getdata = $this->{$this->modelname}->get_one($pid);
		}

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'api_name',
                'label' => 'API 이름',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'api_exp',
                'label' => '내용',
                'rules' => 'trim',
            ),
            array(
                'field' => 'api_method',
                'label' => '호출방식',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'api_use',
                'label' => '사용여부',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'api_bigo',
                'label' => '비고',
                'rules' => 'trim',
            ),
        );
        $this->form_validation->set_rules($config);


        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {


            $view['view']['data'] = $getdata;

            /**
             * primary key 정보를 저장합니다
             */
            $view['view']['primary_key'] = $primary_key;

        
            /**
             * 어드민 레이아웃을 정의합니다
             */
            $layoutconfig = array('layout' => 'layout', 'skin' => 'write');
            $view['layout'] = $this->layoutlib->admin($layoutconfig, $this->configlib->get_device_view_type());
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view));
            $this->view = element('view_skin_file', element('layout', $view));

        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */


            $updatedata = array(
                'api_name' => $this->input->post('api_name', null, ''),
                'api_exp' => $this->input->post('api_exp', null, ''),
                'api_method' => $this->input->post('api_method', null, ''),
                'api_use' => $this->input->post('api_use', null, ''),
                'api_bigo' => $this->input->post('api_bigo', null, ''),
            );

            /**
             * 게시물을 수정하는 경우입니다
             */
            if ($this->input->post($primary_key)) {
                $this->{$this->modelname}->update($this->input->post($primary_key), $updatedata);
                $this->session->set_flashdata(
                    'message',
                    '정상적으로 수정되었습니다'
                );
            } else {
                /**
                 * 게시물을 새로 입력하는 경우입니다
                 */
                $this->{$this->modelname}->insert($updatedata);
                $this->session->set_flashdata(
                    'message',
                    '정상적으로 입력되었습니다'
                );
            }

            /**
             * 게시물의 신규입력 또는 수정작업이 끝난 후 목록 페이지로 이동합니다
             */
            $param =& $this->querystring;
            $redirecturl = admin_url($this->pagedir . '?' . $param->output());

            redirect($redirecturl);
        }
    }

    /**
     * 목록 페이지에서 선택삭제를 하는 경우 실행되는 메소드입니다
     */
    public function listdelete()
    {

        /**
         * 체크한 게시물의 삭제를 실행합니다
         */
        if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
            foreach ($this->input->post('chk') as $val) {
                if ($val) {
					$outdata = $this->Api_output_model->get_one('', '', array('api_idx', $val));
					if (element('ai_idx', $outdata)) {
						alert('Input 및 Output 변수를 먼저 삭제하신 후에 삭제가 가능합니다.');
					}
					$indata = $this->Api_input_model->get_one('', '', array('api_idx', $val));
					if (element('ai_idx', $indata)) {
						alert('Input 및 Output 변수를 먼저 삭제하신 후에 삭제가 가능합니다.');
					}
                    $this->{$this->modelname}->delete($val);
                }
            }
        }

        /**
         * 삭제가 끝난 후 목록페이지로 이동합니다
         */
        $this->session->set_flashdata(
            'message',
            '정상적으로 삭제되었습니다'
        );
        $param =& $this->querystring;
        $redirecturl = admin_url($this->pagedir . '?' . $param->output());

        redirect($redirecturl);
    }


    /**
     * 목록을 가져오는 메소드입니다
     */
    public function argumentlist($type = '', $api_idx = '')
    {

        $view = array();
        $view['view'] = array();

		if ($type != 'output') $type = 'input';
		$view['view']['type'] = $type;

		if (! $api_idx) show_404();
		if ( ! is_numeric($api_idx)) show_404();

		$view['view']['apidata'] = $apidata = $this->Api_list_model->get_one($api_idx);
		if ( ! element('api_idx', $apidata)) show_404();

		$modelname = $type == 'output' ? 'Api_output_model' : 'Api_input_model';


        /**
         * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
         */
        $param =& $this->querystring;
        $page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
        $findex = $this->input->get('findex') ? $this->input->get('findex') : 'ai_sort';
        $forder = $this->input->get('forder', null, 'asc');
        $sfield = $this->input->get('sfield', null, '');
        $skeyword = $this->input->get('skeyword', null, '');

        $per_page = 100;
        $offset = ($page - 1) * $per_page;

        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */
        $this->{$modelname}->allow_search_field = array('ai_idx', 'ai_name', 'ai_type', 'ai_ness', 'ai_exp', 'ai_sort', 'api_bigo'); // 검색이 가능한 필드
        $this->{$modelname}->search_field_equal = array('ai_idx'); // 검색중 like 가 아닌 = 검색을 하는 필드
        $this->{$modelname}->allow_order_field = array('ai_idx', 'ai_name', 'ai_sort'); // 정렬이 가능한 필드
		$where = array(
			'api_idx' => $api_idx,
		);
        $result = $this->{$modelname}
            ->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);

        $list_num = $result['total_rows'] - ($page - 1) * $per_page;
        if (element('list', $result)) {
            foreach (element('list', $result) as $key => $val) {
                $result['list'][$key]['num'] = $list_num--;
            }
        }

        $view['view']['data'] = $result;

        /**
         * primary key 정보를 저장합니다
         */
        $view['view']['primary_key'] = $this->{$modelname}->primary_key;

        /**
         * 페이지네이션을 생성합니다
         */
        $config['base_url'] = admin_url($this->pagedir) . '?' . $param->replace('page');
        $config['total_rows'] = $result['total_rows'];
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $view['view']['paging'] = $this->pagination->create_links();
        $view['view']['page'] = $page;

        /**
         * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
         */
        $search_option = array('ai_name' => '변수명', 'ai_type' => '타입', 'ai_ness' => '종류', 'ai_exp' => '설명', 'ai_sort' => '순서');
        $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        $view['view']['search_option'] = search_option($search_option, $sfield);
        $view['view']['listall_url'] = admin_url($this->pagedir);
        $view['view']['input_list_url'] = admin_url($this->pagedir . '/argumentlist/input/' . $api_idx);
        $view['view']['output_list_url'] = admin_url($this->pagedir . '/argumentlist/output/' . $api_idx);
        $view['view']['write_url'] = admin_url($this->pagedir . '/argumentwrite/' . $type . '/' . $api_idx);
        $view['view']['list_delete_url'] = admin_url($this->pagedir . '/argumentlistdelete/' . $type . '/' . $api_idx . '?' . $param->output());


        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout', 'skin' => 'argumentlist');
        $view['layout'] = $this->layoutlib->admin($layoutconfig, $this->configlib->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

    /**
     * 게시판 글쓰기 또는 수정 페이지를 가져오는 메소드입니다
     */
    public function argumentwrite($type = '', $api_idx = '', $pid = 0)
    {

        $view = array();
        $view['view'] = array();

		if ($type != 'output') $type = 'input';
		$view['view']['type'] = $type;

		if (! $api_idx) show_404();
		if ( ! is_numeric($api_idx)) show_404();
		$modelname = $type == 'output' ? 'Api_output_model' : 'Api_input_model';

		$view['view']['apidata'] = $apidata = $this->Api_list_model->get_one($api_idx);
		if ( ! element('api_idx', $apidata)) show_404();



        /**
         * 프라이머리키에 숫자형이 입력되지 않으면 에러처리합니다
         */
        if ($pid) {
            $pid = (int) $pid;
            if (empty($pid) OR $pid < 1) {
                show_404();
            }
        }
        $primary_key = $this->{$modelname}->primary_key;

        /**
         * 수정 페이지일 경우 기존 데이터를 가져옵니다
         */
        $getdata = array();
        if ($pid) {
            $getdata = $this->{$modelname}->get_one($pid);
		} else {
			$max_sort = $this->{$modelname}->select_max_sort($api_idx);
			$getdata['ai_sort'] = $max_sort + 1;
		}

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'api_idx',
                'label' => 'API IDX',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'ai_name',
                'label' => '내용',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'ai_type',
                'label' => '타입',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'ai_ness',
                'label' => '종류',
                'rules' => 'trim',
            ),
            array(
                'field' => 'ai_exp',
                'label' => '설명',
                'rules' => 'trim',
            ),
            array(
                'field' => 'ai_sort',
                'label' => '순서',
                'rules' => 'trim|numeric',
            ),
        );
        $this->form_validation->set_rules($config);


        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {


            $view['view']['data'] = $getdata;

            /**
             * primary key 정보를 저장합니다
             */
            $view['view']['primary_key'] = $primary_key;

			$view['view']['input_list_url'] = admin_url($this->pagedir . '/argumentlist/input/' . $api_idx);
			$view['view']['output_list_url'] = admin_url($this->pagedir . '/argumentlist/output/' . $api_idx);
        
            /**
             * 어드민 레이아웃을 정의합니다
             */
            $layoutconfig = array('layout' => 'layout', 'skin' => 'argumentwrite');
            $view['layout'] = $this->layoutlib->admin($layoutconfig, $this->configlib->get_device_view_type());
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view));
            $this->view = element('view_skin_file', element('layout', $view));

        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */


            $updatedata = array(
                'api_idx' => $this->input->post('api_idx', null, ''),
                'ai_name' => $this->input->post('ai_name', null, ''),
                'ai_type' => $this->input->post('ai_type', null, ''),
                'ai_ness' => $this->input->post('ai_ness', null, ''),
                'ai_exp' => $this->input->post('ai_exp', null, ''),
                'ai_sort' => $this->input->post('ai_sort', null, ''),
            );

            /**
             * 게시물을 수정하는 경우입니다
             */
            if ($this->input->post($primary_key)) {
                $this->{$modelname}->update($this->input->post($primary_key), $updatedata);
                $this->session->set_flashdata(
                    'message',
                    '정상적으로 수정되었습니다'
                );
            } else {
                /**
                 * 게시물을 새로 입력하는 경우입니다
                 */
				$this->{$modelname}->update_sort($this->input->post('api_idx', null, ''), $this->input->post('ai_sort', null, ''));
                $this->{$modelname}->insert($updatedata);
                $this->session->set_flashdata(
                    'message',
                    $this->input->post('ai_name', null, '') . '변수명이 등록되었습니다'
                );
            }

            /**
             * 게시물의 신규입력 또는 수정작업이 끝난 후 목록 페이지로 이동합니다
             */
            $param =& $this->querystring;
			if ($this->input->post('reinput')) {
	           $redirecturl = admin_url($this->pagedir . '/argumentwrite/' . $type . '/' . $api_idx . '?' . $param->output());
			} else {
	           $redirecturl = admin_url($this->pagedir . '/argumentlist/' . $type . '/' . $api_idx . '?' . $param->output());
 			}

            redirect($redirecturl);
        }
    }

    /**
     * 목록 페이지에서 선택삭제를 하는 경우 실행되는 메소드입니다
     */
    public function argumentlistdelete($type = '', $api_idx = '')
    {

		if ($type != 'output') $type = 'input';

		if (! $api_idx) show_404();
		if ( ! is_numeric($api_idx)) show_404();
		$modelname = $type == 'output' ? 'Api_output_model' : 'Api_input_model';

        /**
         * 체크한 게시물의 삭제를 실행합니다
         */
        if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
            foreach ($this->input->post('chk') as $val) {
                if ($val) {
                    $this->{$modelname}->delete($val);
                }
            }
        }

        /**
         * 삭제가 끝난 후 목록페이지로 이동합니다
         */
        $this->session->set_flashdata(
            'message',
            '정상적으로 삭제되었습니다'
        );
        $param =& $this->querystring;
        $redirecturl = admin_url($this->pagedir . '/argumentlist/' . $type . '/' . $api_idx . '?' . $param->output());

        redirect($redirecturl);
    }

}

