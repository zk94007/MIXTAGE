<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Recommend class
 */

/**
 * 관리자>회원관리>Recommend controller 입니다.
 */
class Recommend extends MY_Controller
{

    /**
     * 관리자 페이지 상의 현재 디렉토리입니다
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = 'user/recommend';

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Recommend_artist');

    /**
     * 이 컨트롤러의 메인 모델 이름입니다
     */
    protected $modelname = 'Recommend_artist_model';

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
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'is_submit',
                'label' => '저장',
                'rules' => 'trim|required',
            ),
        );
        $this->form_validation->set_rules($config);

        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {
		
			$list = $this->Recommend_artist_model->get_list();
			$view['view']['list'] = $list;

			/**
			 * 어드민 레이아웃을 정의합니다
			 */
			$layoutconfig = array('layout' => 'layout', 'skin' => 'index');
			$view['layout'] = $this->layoutlib->admin($layoutconfig, $this->configlib->get_device_view_type());
			$this->data = $view;
			$this->layout = element('layout_skin_file', element('layout', $view));
			$this->view = element('view_skin_file', element('layout', $view));
    
        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */

			$list = $this->Recommend_artist_model->get_list();

			$ids = $this->input->post('ids');
			$userids = $this->input->post('userids');
			$usernames = $this->input->post('usernames');
			$photos = $this->input->post('photos');
			foreach ($list as $key => $result) {
			 
				$content = '';
				$rec_content = '';
				
				for ( $i=1 ; $i <= 10 ; $i++ ) {
					$content[$i] = array(
						'id' => $ids[$result['rec_id']][$i],
						'userid' => $userids[$result['rec_id']][$i],
						'username' => $usernames[$result['rec_id']][$i],
						'photo' => $photos[$result['rec_id']][$i],
					);
				}
				$rec_content = json_encode($content, JSON_UNESCAPED_UNICODE);

				$updatedata = array(
					'rec_content' => $rec_content,
					'rec_updated_datetime' => cdate('Y-m-d H:i:s'),
				);
                $this->Recommend_artist_model->update(element('rec_id', $result), $updatedata);

			}

			$this->session->set_flashdata(
				'message',
				'정상적으로 수정되었습니다'
			);

            /**
             * 게시물의 신규입력 또는 수정작업이 끝난 후 목록 페이지로 이동합니다
             */
            $redirecturl = admin_url($this->pagedir);

            redirect($redirecturl);
        }

	
	}

	public function artistlist($artist_category = '')
	{

        $view = array();
        $view['view'] = array();


        /**
         * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
         */
        $param =& $this->querystring;
        $page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
        $findex = $this->input->get('findex', null, 'user.user_id');
        $forder = $this->input->get('forder', null, 'desc');
        $sfield = $this->input->get('sfield', null, '');
        $skeyword = $this->input->get('skeyword', null, '');

        $per_page = admin_listnum();
        $offset = ($page - 1) * $per_page;

        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */
        $this->User_model->allow_search_field = array('user_id', 'user_userid', 'user_email', 'user_username', 'user_level', 'user_homepage', 'user_register_datetime', 'user_register_ip', 'user_lastlogin_datetime', 'user_lastlogin_ip', 'user_is_admin'); // 검색이 가능한 필드
        $this->User_model->search_field_equal = array('user_id', 'user_level', 'user_is_admin'); // 검색중 like 가 아닌 = 검색을 하는 필드
        $this->User_model->allow_order_field = array('user.user_id', 'user_userid', 'user_username', 'user_email', 'user_register_datetime', 'user_lastlogin_datetime', 'user_level'); // 정렬이 가능한 필드

		$category = config_item('portfolio_category');

        $where = array();
        if ($this->input->get('user_is_admin')) {
            $where['user_is_admin'] = 1;
        }

		$where['user_level'] = 3;
        if ($artist_category) {
            $where['user_artist_category'] = $artist_category;
			$view['view']['artist_category'] = element($artist_category, $category);
        }
        if ($this->input->get('user_denied')) {
            $where['user_denied'] = 1;
        }
        $result = $this->User_model
            ->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
        $list_num = $result['total_rows'] - ($page - 1) * $per_page;

        if (element('list', $result)) {
            foreach (element('list', $result) as $key => $val) {

                $where = array(
                    'user_id' => element('user_id', $val),
                );
                $result['list'][$key]['meta'] = $this->User_meta_model->get_all_meta(element('user_id', $val));

                $result['list'][$key]['num'] = $list_num--;
            }
        }

        $view['view']['data'] = $result;

        /**
         * primary key 정보를 저장합니다
         */
        $view['view']['primary_key'] = $this->User_model->primary_key;

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
        $search_option = array('user_userid' => 'User ID', 'user_email' => 'Email', 'user_username' => 'User name', 'user_homepage' => 'Homepage', 'user_instagram' => 'Instagram', 'user_facebook' => 'Facebook', 'user_register_datetime' => 'Sign up date', 'user_lastlogin_datetime' => 'Last log in', 'user_adminmemo' => 'Admin memo');
        $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        $view['view']['search_option'] = search_option($search_option, $sfield);
        $view['view']['listall_url'] = admin_url($this->pagedir . '/artistlist/' . $artist_category);


        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout_popup', 'skin' => 'artistlist');
        $view['layout'] = $this->layoutlib->admin($layoutconfig, $this->configlib->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
	

	}
}
