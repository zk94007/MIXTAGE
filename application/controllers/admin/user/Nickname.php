<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Nickname class
 */

/**
 * 관리자>회원설정>닉네임변경이력 controller 입니다.
 */
class Nickname extends MY_Controller
{

    /**
     * 관리자 페이지 상의 현재 디렉토리입니다
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = 'user/nickname';

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('User_nickname');

    /**
     * 이 컨트롤러의 메인 모델 이름입니다
     */
    protected $modelname = 'User_nickname_model';

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
        $view['view']['sort'] = array(
            'uni_nickname' => $param->sort('uni_nickname', 'asc'),
            'uni_start_datetime' => $param->sort('uni_start_datetime', 'asc'),
            'uni_end_datetime' => $param->sort('uni_end_datetime', 'asc'),
        );
        $findex = $this->input->get('findex') ? $this->input->get('findex') : $this->{$this->modelname}->primary_key;
        $forder = $this->input->get('forder', null, 'desc');
        $sfield = $this->input->get('sfield', null, '');
        $skeyword = $this->input->get('skeyword', null, '');

        $per_page = admin_listnum();
        $offset = ($page - 1) * $per_page;

        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */
        $this->{$this->modelname}->allow_search_field = array('uni_id', 'user_nickname.user_id', 'user.user_userid', 'user.user_nickname', 'uni_nickname'); // 검색이 가능한 필드
        $this->{$this->modelname}->search_field_equal = array('uni_id', 'user_nickname.user_id'); // 검색중 like 가 아닌 = 검색을 하는 필드
        $this->{$this->modelname}->allow_order_field = array('uni_id', 'uni_nickname', 'uni_start_datetime', 'uni_end_datetime'); // 정렬이 가능한 필드

        $where = array();
        if ($this->input->get('use') === 'Y') {
            $where['uni_end_datetime'] = null;
        }
        if ($this->input->get('use') === 'N') {
            $where['uni_end_datetime >'] = '0000-00-00 00:00:00';
        }
        
        $result = $this->{$this->modelname}
            ->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
        $list_num = $result['total_rows'] - ($page - 1) * $per_page;
        if (element('list', $result)) {
            foreach (element('list', $result) as $key => $val) {
                $result['list'][$key]['display_name'] = display_username(
                    element('user_userid', $val),
                    element('user_nickname', $val),
                    element('user_icon', $val)
                );
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
        $search_option = array('user.user_userid' => '회원아이디', 'user.user_nickname' => '현재닉네임', 'uni_nickname' => '닉네임');
        $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        $view['view']['search_option'] = search_option($search_option, $sfield);
        $view['view']['listall_url'] = admin_url($this->pagedir);
        $view['view']['write_url'] = admin_url($this->pagedir . '/write');
        $view['view']['list_update_url'] = admin_url($this->pagedir . '/listupdate/?' . $param->output());
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
}
