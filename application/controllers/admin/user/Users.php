<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Users class
 */

/**
 * 관리자>회원설정>회원관리 controller 입니다.
 */
class Users extends MY_Controller
{

    /**
     * 관리자 페이지 상의 현재 디렉토리입니다
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = 'user/users';

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('User_meta', 'User_extra_vars', 'User_userid');

    /**
     * 이 컨트롤러의 메인 모델 이름입니다
     */
    protected $modelname = 'User_model';

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array', 'chkstring');

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
            'user_id' => $param->sort('user_id', 'asc'),
            'user_userid' => $param->sort('user_userid', 'asc'),
            'user_username' => $param->sort('user_username', 'asc'),
            'user_email' => $param->sort('user_email', 'asc'),
            'user_register_datetime' => $param->sort('user_register_datetime', 'asc'),
            'user_lastlogin_datetime' => $param->sort('user_lastlogin_datetime', 'asc'),
            'user_level' => $param->sort('user_level', 'asc'),
        );
        $findex = $this->input->get('findex', null, 'user.user_id');
        $forder = $this->input->get('forder', null, 'desc');
        $sfield = $this->input->get('sfield', null, '');
        $skeyword = $this->input->get('skeyword', null, '');

        $per_page = admin_listnum();
        $offset = ($page - 1) * $per_page;

        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */
        $this->{$this->modelname}->allow_search_field = array('user_id', 'user_userid', 'user_email', 'user_username', 'user_level', 'user_homepage', 'user_register_datetime', 'user_register_ip', 'user_lastlogin_datetime', 'user_lastlogin_ip', 'user_is_admin'); // 검색이 가능한 필드
        $this->{$this->modelname}->search_field_equal = array('user_id', 'user_level', 'user_is_admin'); // 검색중 like 가 아닌 = 검색을 하는 필드
        $this->{$this->modelname}->allow_order_field = array('user.user_id', 'user_userid', 'user_username', 'user_email', 'user_register_datetime', 'user_lastlogin_datetime', 'user_level'); // 정렬이 가능한 필드

        $where = array();
        if ($this->input->get('user_is_admin')) {
            $where['user_is_admin'] = 1;
        }
        if ($this->input->get('user_level')) {
            $where['user_level'] = $this->input->get('user_level');
        }
        if ($this->input->get('user_artist_category')) {
            $where['user_artist_category'] = $this->input->get('user_artist_category');
        }
        if ($this->input->get('user_denied')) {
            $where['user_denied'] = 1;
        }
        $result = $this->{$this->modelname}
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
        $search_option = array('user_userid' => 'User ID', 'user_email' => 'Email', 'user_username' => 'User name', 'user_homepage' => 'Homepage', 'user_instagram' => 'Instagram', 'user_facebook' => 'Facebook', 'user_register_datetime' => 'Sign up date', 'user_lastlogin_datetime' => 'Last log in', 'user_adminmemo' => 'Admin memo');
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
            $getdata['extras'] = $this->User_extra_vars_model->get_all_meta($pid);
            $getdata['meta'] = $this->User_meta_model->get_all_meta($pid);
            $where = array(
                'user_id' => $pid,
            );
        }
        $getdata['config_max_level'] = $this->configlib->item('max_level');
        $registerform = $this->configlib->item('registerform');
        $form = json_decode($registerform, true);

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

         if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'user_username',
                'label' => '이름',
                'rules' => 'trim|min_length[2]',
            ),
            array(
                'field' => 'user_phone',
                'label' => '전화번호',
                'rules' => 'trim|valid_phone',
            ),
            array(
                'field' => 'user_artist_category',
                'label' => '아티스트 분야',
                'rules' => 'trim|numeric|is_natural_no_zero',
            ),
            array(
                'field' => 'user_level',
                'label' => '레벨',
                'rules' => 'trim|required|numeric|is_natural_no_zero',
            ),
            array(
                'field' => 'user_homepage',
                'label' => '홈페이지',
                'rules' => 'valid_url',
            ),
            array(
                'field' => 'user_instagram',
                'label' => '인스타그램',
                'rules' => 'valid_url',
            ),
            array(
                'field' => 'user_facebook',
                'label' => '페이스북',
                'rules' => 'valid_url',
            ),
        );
        if ($this->input->post($primary_key)) {
            $config[] = array(
                'field' => 'user_userid',
                'label' => '회원아이디',
                'rules' => 'trim|required|alphanumunder|min_length[3]|max_length[20]|is_unique[user_userid.user_userid.user_id.' . element('user_id', $getdata) . ']|callback__user_userid_check',
            );
            $config[] = array(
                'field' => 'user_password',
                'label' => '패스워드',
                'rules' => 'trim|min_length[4]',
            );
            $config[] = array(
                'field' => 'user_email',
                'label' => '회원이메일',
                'rules' => 'trim|required|valid_email|is_unique[user.user_email.user_id.' . element('user_id', $getdata) . ']',
            );
        } else {
            $config[] = array(
                'field' => 'user_userid',
                'label' => '회원아이디',
                'rules' => 'trim|required|alphanumunder|min_length[3]|max_length[20]|is_unique[user_userid.user_userid]',
            );
            $config[] = array(
                'field' => 'user_password',
                'label' => '패스워드',
                'rules' => 'trim|required|min_length[4]',
            );
            $config[] = array(
                'field' => 'user_email',
                'label' => '회원이메일',
                'rules' => 'trim|required|valid_email|is_unique[user.user_email]',
            );
        }
        $this->form_validation->set_rules($config);
        $form_validation = $this->form_validation->run();
        $file_error = '';
        $updatephoto = '';

        if ($form_validation) {
            $this->load->library('upload');
            if (isset($_FILES) && isset($_FILES['user_photo']) && isset($_FILES['user_photo']['name']) && $_FILES['user_photo']['name']) {
                $upload_path = config_item('uploads_dir') . '/user_photo/';
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

                if ($this->upload->do_upload('user_photo')) {
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


            $view['view']['message'] = $file_error;

            $view['view']['data'] = $getdata;

            if (empty($pid)) {
                $view['view']['data']['user_receive_email'] = 1;
                $view['view']['data']['user_use_note'] = 1;
                $view['view']['data']['user_receive_sms'] = 1;
                $view['view']['data']['user_open_profile'] = 1;
            }

            /**
             * primary key 정보를 저장합니다
             */
            $view['view']['primary_key'] = $primary_key;

            $html_content = '';
            $k = 0;
            if ($form && is_array($form)) {
                foreach ($form as $key => $value) {
                    if ( ! element('use', $value)) {
                        continue;
                    }
                    if (element('func', $value) === 'basic') {
                        continue;
                    }
                    $required = element('required', $value) ? 'required' : '';

                    $item = element(element('field_name', $value), element('extras', $getdata));
                    $html_content[$k]['field_name'] = element('field_name', $value);
                    $html_content[$k]['display_name'] = element('display_name', $value);
                    $html_content[$k]['input'] = '';

                    //field_type : text, url, email, phone, textarea, radio, select, checkbox, date
                    if (element('field_type', $value) === 'text'
                        OR element('field_type', $value) === 'url'
                        OR element('field_type', $value) === 'email'
                        OR element('field_type', $value) === 'phone'
                        OR element('field_type', $value) === 'date') {
                        if (element('field_type', $value) === 'date') {
                            $html_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control datepicker" value="' . set_value(element('field_name', $value), $item) . '" readonly="readonly" ' . $required . ' />';
                        } elseif (element('field_type', $value) === 'phone') {
                            $html_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control validphone" value="' . set_value(element('field_name', $value), $item) . '" ' . $required . ' />';
                        } else {
                            $html_content[$k]['input'] .= '<input type="' . element('field_type', $value) . '" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control" value="' . set_value(element('field_name', $value), $item) . '" ' . $required . ' />';
                        }
                    } elseif (element('field_type', $value) === 'textarea') {
                        $html_content[$k]['input'] .= '<textarea id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control" ' . $required . ' >' . set_value(element('field_name', $value), $item) . '</textarea>';
                    } elseif (element('field_type', $value) === 'radio') {
                        $html_content[$k]['input'] .= '<div class="checkbox">';
                        $options = explode("\n", element('options', $value));
                        $i =1;
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $oval = trim($oval);
                                $radiovalue = (element('field_name', $value) === 'user_sex') ? $okey : $oval;
                                $html_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="radio" name="' . element('field_name', $value) . '" id="' . element('field_name', $value) . '_' . $i . '" value="' . $radiovalue . '" ' . set_radio(element('field_name', $value), $radiovalue, ($item === $radiovalue ? true : false)) . ' /> ' . $oval . ' </label> ';
                            $i++;
                            }
                        }
                        $html_content[$k]['input'] .= '</div>';
                    } elseif (element('field_type', $value) === 'checkbox') {
                        $html_content[$k]['input'] .= '<div class="checkbox">';
                        $options = explode("\n", element('options', $value));
                        $item = json_decode($item, true);
                        $i =1;
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $oval = trim($oval);
                                $chkvalue = is_array($item) && in_array($oval, $item) ? $oval : '';
                                $html_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="checkbox" name="' . element('field_name', $value) . '[]" id="' . element('field_name', $value) . '_' . $i . '" value="' . $oval . '" ' . set_checkbox(element('field_name', $value), $oval, ($chkvalue ? true : false)) . ' /> ' . $oval . ' </label> ';
                            $i++;
                            }
                        }
                        $html_content[$k]['input'] .= '</div>';
                    } elseif (element('field_type', $value) === 'select') {
                        $html_content[$k]['input'] .= '<div class="input-group">';
                        $html_content[$k]['input'] .= '<select name="' . element('field_name', $value) . '" class="form-control" ' . $required . '>';
                        $html_content[$k]['input'] .= '<option value="" >선택하세요</option> ';
                        $options = explode("\n", element('options', $value));
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $oval = trim($oval);
                                $html_content[$k]['input'] .= '<option value="' . $oval . '" ' . set_select(element('field_name', $value), $oval, ($item === $oval ? true : false)) . ' >' . $oval . '</option> ';
                            }
                        }
                        $html_content[$k]['input'] .= '</select>';
                        $html_content[$k]['input'] .= '</div>';
                    }
                    $k++;
                }
            }

            $view['view']['html_content'] = $html_content;

        
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


            $user_is_admin = $this->input->post('user_level') == '10' ? 1 : 0;

            $updatedata = array(
                'user_userid' => $this->input->post('user_userid', null, ''),
                'user_email' => $this->input->post('user_email', null, ''),
                'user_username' => $this->input->post('user_username', null, ''),
                'user_phone' => $this->input->post('user_phone', null, ''),
                'user_artist_category' => $this->input->post('user_artist_category', null, ''),
                'user_level' => $this->input->post('user_level', null, ''),
                'user_homepage' => $this->input->post('user_homepage', null, ''),
                'user_instagram' => $this->input->post('user_instagram', null, ''),
                'user_facebook' => $this->input->post('user_facebook', null, ''),
                'user_denied' => $this->input->post('user_denied', null, ''),
                'user_is_admin' => $user_is_admin,
                'user_adminmemo' => $this->input->post('user_adminmemo', null, ''),
            );

            $metadata = array();

            if (empty($getdata['user_denied']) && $this->input->post('user_denied')) {
                $metadata['meta_denied_datetime'] = cdate('Y-m-d H:i:s');
                $metadata['meta_denied_by_user_id'] = $this->userlib->item('user_id');
            }
            if ( ! empty($getdata['user_denied']) && ! $this->input->post('user_denied')) {
                $metadata['meta_denied_datetime'] = '';
                $metadata['meta_denied_by_user_id'] = '';
            }
            if ($this->input->post('user_password')) {
                $updatedata['user_password'] = password_hash($this->input->post('user_password'), PASSWORD_BCRYPT);
            }

            if ($this->input->post('user_photo_del')) {
                $updatedata['user_photo'] = '';
            } elseif ($updatephoto) {
                $updatedata['user_photo'] = $updatephoto;
            }
            if (element('user_photo', $getdata) && ($this->input->post('user_photo_del') OR $updatephoto)) {
                // 기존 파일 삭제
                @unlink(config_item('uploads_dir') . '/user_photo/' . element('user_photo', $getdata));
            }

            /**
             * 게시물을 수정하는 경우입니다
             */
            if ($this->input->post($primary_key)) {
                $user_id = $this->input->post($primary_key);
                $this->{$this->modelname}->update($user_id, $updatedata);
                $this->User_meta_model->save($user_id, $metadata);
                if (element('user_userid', $getdata) !== $this->input->post('user_userid')) {
                    $useriddata = array('user_userid' => $this->input->post('user_userid'));
                    $useridwhere = array('user_id' => element('user_id', $getdata));
                    $this->User_userid_model->update('', $useriddata, $useridwhere);
                }

                $extradata = array();
                if ($form && is_array($form)) {
                    foreach ($form as $key => $value) {
                        if ( ! element('use', $value)) {
                            continue;
                        }
                        if (element('func', $value) === 'basic') {
                            continue;
                        }
                        $extradata[element('field_name', $value)] = $this->input->post(element('field_name', $value), null, '');
                    }
                    $this->User_extra_vars_model->save($user_id, $extradata);
                }

                $this->session->set_flashdata(
                    'message',
                    '정상적으로 수정되었습니다'
                );
            } else {
                /**
                 * 게시물을 새로 입력하는 경우입니다
                 */
                $updatedata['user_register_datetime'] = cdate('Y-m-d H:i:s');
                $updatedata['user_register_ip'] = $this->input->ip_address();

                $user_id = $this->{$this->modelname}->insert($updatedata);

                $useridinsertdata = array(
                    'user_id' => $user_id,
                    'user_userid' => $this->input->post('user_userid'),
                );
                $this->User_userid_model->insert($useridinsertdata);

                $extradata = array();
                if ($form && is_array($form)) {
                    foreach ($form as $key => $value) {
                        if ( ! element('use', $value)) {
                            continue;
                        }
                        if (element('func', $value) === 'basic') {
                            continue;
                        }
                        $extradata[element('field_name', $value)] = $this->input->post(element('field_name', $value), null, '');
                    }
                    $this->User_extra_vars_model->save($user_id, $extradata);
                }

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
                    $this->User_model->update($val, array('user_denied' => '1'));
		            $metadata = array();
					$metadata['meta_denied_datetime'] = cdate('Y-m-d H:i:s');
					$metadata['meta_denied_by_user_id'] = $this->userlib->item('user_id');
	                $this->User_meta_model->save($val, $metadata);
					//$this->userlib->delete_user($val);
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
     * 회원아이디 체크함수입니다
     */
    public function _user_userid_check($str)
    {
        if (preg_match("/[\,]?{$str}/i", $this->configlib->item('prohibit_id'))) {
            $this->form_validation->set_message(
                '_user_userid_check',
                $str . ' 은(는) 예약어로 사용하실 수 없는 회원아이디입니다'
            );
            return false;
        }
        return true;
    }
}
