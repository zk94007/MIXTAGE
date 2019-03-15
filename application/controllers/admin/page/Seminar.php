<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Seminar class
 */

/**
 * 관리자>페이지설정>세미나 controller 입니다.
 */
class Seminar extends MY_Controller
{

    /**
     * 관리자 페이지 상의 현재 디렉토리입니다
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = 'page/seminar';

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Seminar', 'Seminar_user');

    /**
     * 이 컨트롤러의 메인 모델 이름입니다
     */
    protected $modelname = 'Seminar_model';

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array', 'dhtml_editor');

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
        $findex = $this->input->get('findex') ? $this->input->get('findex') : $this->{$this->modelname}->primary_key;
        $forder = $this->input->get('forder', null, 'desc');
        $sfield = $this->input->get('sfield', null, '');
        $skeyword = $this->input->get('skeyword', null, '');

        $per_page = admin_listnum();
        $offset = ($page - 1) * $per_page;

        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */
        $this->{$this->modelname}->allow_search_field = array('sem_id', 'sem_title', 'sem_content'); // 검색이 가능한 필드
        $this->{$this->modelname}->search_field_equal = array('sem_id'); // 검색중 like 가 아닌 = 검색을 하는 필드
        $this->{$this->modelname}->allow_order_field = array('sem_id'); // 정렬이 가능한 필드
        $result = $this->{$this->modelname}
            ->get_admin_list($per_page, $offset, '', '', $findex, $forder, $sfield, $skeyword);

        $list_num = $result['total_rows'] - ($page - 1) * $per_page;
        if (element('list', $result)) {
            foreach (element('list', $result) as $key => $val) {
				$uwhere = array(
					'sem_id' => element('sem_id', $val)
				);
                $result['list'][$key]['sem_attend_user'] = $this->Seminar_user_model->count_by($uwhere);
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
        $search_option = array('sem_title' => 'Title', 'sem_content' => 'Content');
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
     * 목록을 가져오는 메소드입니다
     */
    public function view($pid)
    {

        $view = array();
        $view['view'] = array();

		if ($pid) {
            $pid = (int) $pid;
            if (empty($pid) OR $pid < 1) {
                show_404();
            }
        } else {
            show_404();
		}


        $result = $this->Seminar_user_model->get_list($pid);
		if ($result) {
			foreach($result as $key => $val) {
				$result[$key]['display_name'] = display_username(
					element('user_userid', $val),
					element('user_username', $val),
					element('user_photo', $val)
				);
			}
		}

        $view['view']['data'] = $result;
        $view['view']['listall_url'] = admin_url($this->pagedir);

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout', 'skin' => 'view');
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
                'field' => 'sem_title',
                'label' => '제목',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'sem_content',
                'label' => '내용',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'sem_total_user',
                'label' => '총 참여인원',
                'rules' => 'trim|required|numeric',
            ),
            array(
                'field' => 'sem_approve',
                'label' => '승인여부',
                'rules' => 'trim',
            ),
        );
        $this->form_validation->set_rules($config);
        $form_validation = $this->form_validation->run();
        $file_error = '';
        $updatephoto = '';

        if ($form_validation) {
            $this->load->library('upload');
            if (isset($_FILES) && isset($_FILES['sem_file']) && isset($_FILES['sem_file']['name']) && $_FILES['sem_file']['name']) {
                $upload_path = config_item('uploads_dir') . '/seminar/';
                if (is_dir($upload_path) === false) {
                    mkdir($upload_path, 0707);
                    $file = $upload_path . 'index.php';
                    $f = @fopen($file, 'w');
                    @fwrite($f, '');
                    @fclose($f);
                    @chmod($file, 0644);
                }
                $upload_path .= cdate('Y') . '/';
                if (is_dir($upload_path) === false) {
                    mkdir($upload_path, 0707);
                    $file = $upload_path . 'index.php';
                    $f = @fopen($file, 'w');
                    @fwrite($f, '');
                    @fclose($f);
                    @chmod($file, 0644);
                }
                $upload_path .= cdate('m') . '/';
                if (is_dir($upload_path) === false) {
                    mkdir($upload_path, 0707);
                    $file = $upload_path . 'index.php';
                    $f = @fopen($file, 'w');
                    @fwrite($f, '');
                    @fclose($f);
                    @chmod($file, 0644);
                }

                $uploadconfig = '';
                $uploadconfig['upload_path'] = $upload_path;
                $uploadconfig['allowed_types'] = 'jpg|jpeg|png|gif';
                $uploadconfig['max_size'] = '2000';
                $uploadconfig['max_width'] = '1000';
                $uploadconfig['max_height'] = '1000';
                $uploadconfig['encrypt_name'] = true;

                $this->upload->initialize($uploadconfig);

                if ($this->upload->do_upload('sem_file')) {
                    $img = $this->upload->data();
                    $updatephoto = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $img);
                } else {
                    $file_error = $this->upload->display_errors();

                }
            }

        }

        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($form_validation === false OR $file_error !== '') {

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
                'sem_title' => $this->input->post('sem_title', null, ''),
                'sem_content' => $this->input->post('sem_content', null, ''),
                'sem_total_user' => $this->input->post('sem_total_user', null, ''),
                'sem_approve' => $this->input->post('sem_approve', null, ''),
            );

			if ($updatephoto) {
				$updatedata['sem_image_name'] = $updatephoto;
			}

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
                    $this->{$this->modelname}->delete($val);
                    $this->Seminar_user_model->delete('', array('sem_id' => $val));
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
}
