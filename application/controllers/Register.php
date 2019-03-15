<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Register class
 */

/**
 * 회원 가입과 관련된 controller 입니다.
 */
class Register extends MY_Controller
{

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('User_nickname', 'User_meta', 'User_auth_email', 'User_userid');

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array', 'string');

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring', 'form_validation', 'email'));

        if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }
    }


    /**
     * 회원 약관 동의시 작동하는 함수입니다
     */
    public function index()
    {

		$view = array();
        $view['view'] = array();


        if ($this->userlib->is_user()
            && ! ($this->userlib->is_admin() === 'super' && $this->uri->segment(1) === config_item('uri_segment_admin'))) {
            redirect();
        }

        if ($this->configlib->item('use_register_block')) {

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->configlib->item('site_meta_title_register');
            $meta_description = $this->configlib->item('site_meta_description_register');
            $meta_keywords = $this->configlib->item('site_meta_keywords_register');
            $meta_author = $this->configlib->item('site_meta_author_register');
            $page_name = $this->configlib->item('site_page_name_register');

            $layoutconfig = array(
                'path' => 'register',
                'layout' => 'layout',
                'skin' => 'register_block',
                'layout_dir' => $this->configlib->item('layout_register'),
                'mobile_layout_dir' => $this->configlib->item('mobile_layout_register'),
                'use_sidebar' => $this->configlib->item('sidebar_register'),
                'use_mobile_sidebar' => $this->configlib->item('mobile_sidebar_register'),
                'skin_dir' => $this->configlib->item('skin_register'),
                'mobile_skin_dir' => $this->configlib->item('mobile_skin_register'),
                'page_title' => $page_title,
                'meta_description' => $meta_description,
                'meta_keywords' => $meta_keywords,
                'meta_author' => $meta_author,
                'page_name' => $page_name,
            );
            $view['layout'] = $this->layoutlib->front($layoutconfig, $this->configlib->get_device_view_type());
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view));
            $this->view = element('view_skin_file', element('layout', $view));

            return false;
        }

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'agree',
                'label' => '회원가입약관',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'agree2',
                'label' => '개인정보취급방침',
                'rules' => 'trim|required',
            ),
        );
        $this->form_validation->set_rules($config);

        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {


            $this->session->set_userdata('registeragree', '');
            
            $view['view']['user_register_policy1'] = $this->configlib->item('user_register_policy1');
            $view['view']['user_register_policy2'] = $this->configlib->item('user_register_policy2');
            $view['view']['canonical'] = site_url('register');

        
            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->configlib->item('site_meta_title_register');
            $meta_description = $this->configlib->item('site_meta_description_register');
            $meta_keywords = $this->configlib->item('site_meta_keywords_register');
            $meta_author = $this->configlib->item('site_meta_author_register');
            $page_name = $this->configlib->item('site_page_name_register');

            $layoutconfig = array(
                'path' => 'register',
                'layout' => 'layout',
                'skin' => 'register',
                'layout_dir' => $this->configlib->item('layout_register'),
                'mobile_layout_dir' => $this->configlib->item('mobile_layout_register'),
                'use_sidebar' => $this->configlib->item('sidebar_register'),
                'use_mobile_sidebar' => $this->configlib->item('mobile_sidebar_register'),
                'skin_dir' => $this->configlib->item('skin_register'),
                'mobile_skin_dir' => $this->configlib->item('mobile_skin_register'),
                'page_title' => $page_title,
                'meta_description' => $meta_description,
                'meta_keywords' => $meta_keywords,
                'meta_author' => $meta_author,
                'page_name' => $page_name,
            );
            $view['layout'] = $this->layoutlib->front($layoutconfig, $this->configlib->get_device_view_type());
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view));
            $this->view = element('view_skin_file', element('layout', $view));

        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */


            $this->session->set_userdata('registeragree', '1');
            redirect('register/form');
        }
    }


    /**
     * 회원가입 폼 페이지입니다
     */
    public function form()
    {

        if ($this->userlib->is_user() && ! ($this->userlib->is_admin() === 'super' && $this->uri->segment(1) === config_item('uri_segment_admin'))) {
            redirect();
        }

        $view = array();
        $view['view'] = array();


        if ($this->configlib->item('use_register_block')) {

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->configlib->item('site_meta_title_register_form');
            $meta_description = $this->configlib->item('site_meta_description_register_form');
            $meta_keywords = $this->configlib->item('site_meta_keywords_register_form');
            $meta_author = $this->configlib->item('site_meta_author_register_form');
            $page_name = $this->configlib->item('site_page_name_register_form');

            $layoutconfig = array(
                'path' => 'register',
                'layout' => 'layout',
                'skin' => 'register_block',
                'layout_dir' => $this->configlib->item('layout_register'),
                'mobile_layout_dir' => $this->configlib->item('mobile_layout_register'),
                'use_sidebar' => $this->configlib->item('sidebar_register'),
                'use_mobile_sidebar' => $this->configlib->item('mobile_sidebar_register'),
                'skin_dir' => $this->configlib->item('skin_register'),
                'mobile_skin_dir' => $this->configlib->item('mobile_skin_register'),
                'page_title' => $page_title,
                'meta_description' => $meta_description,
                'meta_keywords' => $meta_keywords,
                'meta_author' => $meta_author,
                'page_name' => $page_name,
            );
            $view['layout'] = $this->layoutlib->front($layoutconfig, $this->configlib->get_device_view_type());
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view));
            $this->view = element('view_skin_file', element('layout', $view));
            return false;
        }

        
        $password_length = $this->configlib->item('password_length');
        $email_description = '';
        if ($this->configlib->item('use_register_email_auth')) {
            $email_description = '회원가입 후 인증메일이 발송됩니다. 인증메일을 확인하신 후에 사이트 이용이 가능합니다';
        }

        $configbasic = array();

        $nickname_description = '';
        if ($this->configlib->item('change_nickname_date')) {
            $nickname_description = '<br />닉네임을 입력하시면 앞으로 '
                . $this->configlib->item('change_nickname_date') . '일 이내에는 변경할 수 없습니다';
        }

        $configbasic['user_userid'] = array(
            'field' => 'user_userid',
            'label' => '아이디',
            'rules' => 'trim|required|alphanumunder|min_length[3]|max_length[20]|is_unique[user_userid.user_userid]|callback__user_userid_check',
            'description' => '영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요',
        );

        $password_description = '비밀번호는 ' . $password_length . '자리 이상이어야 ';
        if ($this->configlib->item('password_uppercase_length')
            OR $this->configlib->item('password_numbers_length')
            OR $this->configlib->item('password_specialchars_length')) {

            $password_description .= '하며 ';
            if ($this->configlib->item('password_uppercase_length')) {
                $password_description .= ', ' . $this->configlib->item('password_uppercase_length') . '개의 대문자';
            }
            if ($this->configlib->item('password_numbers_length')) {
                $password_description .= ', ' . $this->configlib->item('password_numbers_length') . '개의 숫자';
            }
            if ($this->configlib->item('password_specialchars_length')) {
                $password_description .= ', ' . $this->configlib->item('password_specialchars_length') . '개의 특수문자';
            }
            $password_description .= '를 포함해야 ';
        }
        $password_description .= '합니다';

        $configbasic['user_password'] = array(
            'field' => 'user_password',
            'label' => '패스워드',
            'rules' => 'trim|required|min_length[' . $password_length . ']|callback__user_password_check',
            'description' => $password_description,
        );
        $configbasic['user_password_re'] = array(
            'field' => 'user_password_re',
            'label' => '패스워드 확인',
            'rules' => 'trim|required|min_length[' . $password_length . ']|matches[user_password]',
        );
        $configbasic['user_username'] = array(
            'field' => 'user_username',
            'label' => '이름',
            'rules' => 'trim|min_length[2]|max_length[20]',
        );
        $configbasic['user_nickname'] = array(
            'field' => 'user_nickname',
            'label' => '닉네임',
            'rules' => 'trim|required|min_length[2]|max_length[20]|callback__user_nickname_check',
            'description' => '공백없이 한글, 영문, 숫자만 입력 가능 2글자 이상' . $nickname_description,
        );
        $configbasic['user_email'] = array(
            'field' => 'user_email',
            'label' => '이메일',
            'rules' => 'trim|required|valid_email|max_length[50]|is_unique[user.user_email]|callback__user_email_check',
            'description' => $email_description,
        );
        $configbasic['user_homepage'] = array(
            'field' => 'user_homepage',
            'label' => '홈페이지',
            'rules' => 'prep_url|valid_url',
        );
        $configbasic['user_phone'] = array(
            'field' => 'user_phone',
            'label' => '전화번호',
            'rules' => 'trim|valid_phone',
        );
        $configbasic['user_birthday'] = array(
            'field' => 'user_birthday',
            'label' => '생년월일',
            'rules' => 'trim|exact_length[10]',
        );
        $configbasic['user_sex'] = array(
            'field' => 'user_sex',
            'label' => '성별',
            'rules' => 'trim|exact_length[1]',
        );
        $configbasic['user_zipcode'] = array(
            'field' => 'user_zipcode',
            'label' => '우편번호',
            'rules' => 'trim|min_length[5]|max_length[7]',
        );
        $configbasic['user_address1'] = array(
            'field' => 'user_address1',
            'label' => '기본주소',
            'rules' => 'trim',
        );
        $configbasic['user_address2'] = array(
            'field' => 'user_address2',
            'label' => '상세주소',
            'rules' => 'trim',
        );
        $configbasic['user_address3'] = array(
            'field' => 'user_address3',
            'label' => '참고항목',
            'rules' => 'trim',
        );
        $configbasic['user_address4'] = array(
            'field' => 'user_address4',
            'label' => '지번',
            'rules' => 'trim',
        );
        $configbasic['user_profile_content'] = array(
            'field' => 'user_profile_content',
            'label' => '자기소개',
            'rules' => 'trim',
        );
        $configbasic['user_open_profile'] = array(
            'field' => 'user_open_profile',
            'label' => '정보공개',
            'rules' => 'trim|exact_length[1]',
        );
        if ($this->configlib->item('use_note')) {
            $configbasic['user_use_note'] = array(
                'field' => 'user_use_note',
                'label' => '쪽지사용',
                'rules' => 'trim|exact_length[1]',
            );
        }
        $configbasic['user_receive_email'] = array(
            'field' => 'user_receive_email',
            'label' => '이메일수신여부',
            'rules' => 'trim|exact_length[1]',
        );
        $configbasic['user_receive_sms'] = array(
            'field' => 'user_receive_sms',
            'label' => 'SMS 문자수신여부',
            'rules' => 'trim|exact_length[1]',
        );
        $configbasic['user_recommend'] = array(
            'field' => 'user_recommend',
            'label' => '추천인아이디',
            'rules' => 'trim|alphanumunder|min_length[3]|max_length[20]|callback__user_recommend_check',
        );

        if ($this->userlib->is_admin() === false && ! $this->session->userdata('registeragree')) {
            $this->session->set_flashdata(
                'message',
                '회원가입약관동의와 개인정보취급방침동의후 회원가입이 가능합니다'
            );
            redirect('register');
        }

        $registerform = $this->configlib->item('registerform');
        $form = json_decode($registerform, true);

        $config = array();
        if ($form && is_array($form)) {
            foreach ($form as $key => $value) {
                if ( ! element('use', $value)) {
                    continue;
                }
                if (element('func', $value) === 'basic') {

                    if ($key === 'user_address') {
                        if (element('required', $value) === '1') {
                            $configbasic['user_zipcode']['rules'] = $configbasic['user_zipcode']['rules'] . '|required';
                        }
                        $config[] = $configbasic['user_zipcode'];
                        if (element('required', $value) === '1') {
                            $configbasic['user_address1']['rules'] = $configbasic['user_address1']['rules'] . '|required';
                        }
                        $config[] = $configbasic['user_address1'];
                        if (element('required', $value) === '1') {
                            $configbasic['user_address2']['rules'] = $configbasic['user_address2']['rules'] . '|required';
                        }
                        $config[] = $configbasic['user_address2'];
                    } else {
                        if (element('required', $value) === '1') {
                            $configbasic[$value['field_name']]['rules'] = $configbasic[$value['field_name']]['rules'] . '|required';
                        }
                        if (element('field_type', $value) === 'phone') {
                            $configbasic[$value['field_name']]['rules'] = $configbasic[$value['field_name']]['rules'] . '|valid_phone';
                        }
                        $config[] = $configbasic[$value['field_name']];
                        if ($key === 'user_password') {
                            $config[] = $configbasic['user_password_re'];
                        }
                    }
                } else {
                    $required = element('required', $value) ? '|required' : '';
                    if (element('field_type', $value) === 'checkbox') {
                        $config[] = array(
                            'field' => element('field_name', $value) . '[]',
                            'label' => element('display_name', $value),
                            'rules' => 'trim' . $required,
                        );
                    } else {
                        $config[] = array(
                            'field' => element('field_name', $value),
                            'label' => element('display_name', $value),
                            'rules' => 'trim' . $required,
                        );
                    }
                }
            }
        }

        if ($this->configlib->item('use_recaptcha')) {
            $config[] = array(
                'field' => 'g-recaptcha-response',
                'label' => '자동등록방지문자',
                'rules' => 'trim|required|callback__check_recaptcha',
            );
        } else {
            $config[] = array(
                'field' => 'captcha_key',
                'label' => '자동등록방지문자',
                'rules' => 'trim|required|callback__check_captcha',
            );
        }
        $this->form_validation->set_rules($config);

        $form_validation = $this->form_validation->run();
        $file_error = '';
        $updatephoto = '';
        $file_error2 = '';
        $updateicon = '';

        if ($form_validation) {
            $this->load->library('upload');
            if ($this->configlib->item('use_user_photo') && $this->configlib->item('user_photo_width') > 0 && $this->configlib->item('user_photo_height') > 0) {
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
                        $updatephoto = cdate('Y') . '/' . cdate('m') . '/' . $img['file_name'];
                    } else {
                        $file_error = $this->upload->display_errors();

                    }
                }
            }

            if ($this->configlib->item('use_user_icon') && $this->configlib->item('user_icon_width') > 0 && $this->configlib->item('user_icon_height') > 0) {
                if (isset($_FILES) && isset($_FILES['user_icon']) && isset($_FILES['user_icon']['name']) && $_FILES['user_icon']['name']) {
                    $upload_path = config_item('uploads_dir') . '/user_icon/';
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

                    if ($this->upload->do_upload('user_icon')) {
                        $img = $this->upload->data();
                        $updateicon = cdate('Y') . '/' . cdate('m') . '/' . $img['file_name'];
                    } else {
                        $file_error2 = $this->upload->display_errors();
                    }
                }
            }
        }

        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($form_validation === false OR $file_error !== '' OR $file_error2 !== '') {


            $html_content = array();

            $k = 0;
            if ($form && is_array($form)) {
                foreach ($form as $key => $value) {
                    if ( ! element('use', $value)) {
                        continue;
                    }

                    $required = element('required', $value) ? 'required' : '';

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
                            $html_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input datepicker" value="' . set_value(element('field_name', $value)) . '" readonly="readonly" ' . $required . ' />';
                        } elseif (element('field_type', $value) === 'phone') {
                            $html_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input validphone" value="' . set_value(element('field_name', $value)) . '" ' . $required . ' />';
                        } else {
                            $html_content[$k]['input'] .= '<input type="' . element('field_type', $value) . '" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input" value="' . set_value(element('field_name', $value)) . '" ' . $required . '/>';
                        }
                    } elseif (element('field_type', $value) === 'textarea') {
                        $html_content[$k]['input'] .= '<textarea id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input" ' . $required . '>' . set_value(element('field_name', $value)) . '</textarea>';
                    } elseif (element('field_type', $value) === 'radio') {
                        $html_content[$k]['input'] .= '<div class="checkbox">';
                        if (element('field_name', $value) === 'user_sex') {
                            $options = array(
                                '1' => '남성',
                                '2' => '여성',
                            );
                        } else {
                            $options = explode("\n", element('options', $value));
                        }
                        $i =1;
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $radiovalue = (element('field_name', $value) === 'user_sex') ? $okey : $oval;
                                $html_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="radio" name="' . element('field_name', $value) . '" id="' . element('field_name', $value) . '_' . $i . '" value="' . $radiovalue . '" ' . set_radio(element('field_name', $value), $radiovalue) . ' /> ' . $oval . ' </label> ';
                                $i++;
                            }
                        }
                        $html_content[$k]['input'] .= '</div>';
                    } elseif (element('field_type', $value) === 'checkbox') {
                        $html_content[$k]['input'] .= '<div class="checkbox">';
                        $options = explode("\n", element('options', $value));
                        $i =1;
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $html_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="checkbox" name="' . element('field_name', $value) . '[]" id="' . element('field_name', $value) . '_' . $i . '" value="' . $oval . '" ' . set_checkbox(element('field_name', $value), $oval) . ' /> ' . $oval . ' </label> ';
                                $i++;
                            }
                        }
                        $html_content[$k]['input'] .= '</div>';
                    } elseif (element('field_type', $value) === 'select') {
                        $html_content[$k]['input'] .= '<div class="input-group">';
                        $html_content[$k]['input'] .= '<select name="' . element('field_name', $value) . '" class="form-control input" ' . $required . '>';
                        $html_content[$k]['input'] .= '<option value="" >선택하세요</option> ';
                        $options = explode("\n", element('options', $value));
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $html_content[$k]['input'] .= '<option value="' . $oval . '" ' . set_select(element('field_name', $value), $oval) . ' >' . $oval . '</option> ';
                            }
                        }
                        $html_content[$k]['input'] .= '</select>';
                        $html_content[$k]['input'] .= '</div>';
                    } elseif (element('field_name', $value) === 'user_address') {
                        $html_content[$k]['input'] .= '
                            <label for="user_zipcode">우편번호</label>
                            <label>
                                <input type="text" name="user_zipcode" value="' . set_value('user_zipcode') . '" id="user_zipcode" class="form-control input" size="7" maxlength="7" ' . $required . '/>
                            </label>
                            <label>
                                <button type="button" class="btn btn-black btn-sm" style="margin-top:0px;" onclick="win_zip(\'fregisterform\', \'user_zipcode\', \'user_address1\', \'user_address2\', \'user_address3\', \'user_address4\');">주소 검색</button>
                            </label>
                            <div class="addr-line mt10">
                                <label for="user_address1">기본주소</label>
                                <input type="text" name="user_address1" value="' . set_value('user_address1') . '" id="user_address1" class="form-control input" placeholder="기본주소" ' . $required . ' />
                            </div>
                            <div class="addr-line mt10">
                                <label for="user_address2">상세주소</label>
                                <input type="text" name="user_address2" value="' . set_value('user_address2') . '" id="user_address2" class="form-control input" placeholder="상세주소" ' . $required . ' />
                            </div>
                            <div class="addr-line mt10">
                                <label for="user_address3">참고항목</label>
                                <input type="text" name="user_address3" value="' . set_value('user_address3') . '" id="user_address3" class="form-control input" readonly="readonly" placeholder="참고항목" />
                            </div>
                            <input type="hidden" name="user_address4" value="' . set_value('user_address4') . '" />
                        ';
                    } elseif (element('field_name', $value) === 'user_password') {
                        $html_content[$k]['input'] .= '<input type="' . element('field_type', $value) . '" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input" minlength="' . $password_length . '" />';
                    }

                    $html_content[$k]['description'] = '';
                    if (isset($configbasic[$value['field_name']]['description']) && $configbasic[$value['field_name']]['description']) {
                        $html_content[$k]['description'] = $configbasic[$value['field_name']]['description'];
                    }
                    if (element('field_name', $value) === 'user_password') {
                        $k++;
                        $html_content[$k]['field_name'] = 'user_password_re';
                        $html_content[$k]['display_name'] = '비밀번호 확인';
                        $html_content[$k]['input'] = '<input type="password" id="user_password_re" name="user_password_re" class="form-control input" minlength="' . $password_length . '" />';
                    }
                    $k++;
                }
            }

            $view['view']['html_content'] = $html_content;
            $view['view']['open_profile_description'] = '';
            if ($this->configlib->item('change_open_profile_date')) {
                $view['view']['open_profile_description'] = '정보공개 설정은 ' . $this->configlib->item('change_open_profile_date') . '일 이내에는 변경할 수 없습니다';
            }

            $view['view']['use_note_description'] = '';
            if ($this->configlib->item('change_use_note_date')) {
                $view['view']['use_note_description'] = '쪽지 기능 사용 설정은 ' . $this->configlib->item('change_use_note_date') . '일 이내에는 변경할 수 없습니다';
            }

            $view['view']['canonical'] = site_url('register/form');

        
            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->configlib->item('site_meta_title_register_form');
            $meta_description = $this->configlib->item('site_meta_description_register_form');
            $meta_keywords = $this->configlib->item('site_meta_keywords_register_form');
            $meta_author = $this->configlib->item('site_meta_author_register_form');
            $page_name = $this->configlib->item('site_page_name_register_form');

            $layoutconfig = array(
                'path' => 'register',
                'layout' => 'layout',
                'skin' => 'register_form',
                'layout_dir' => $this->configlib->item('layout_register'),
                'mobile_layout_dir' => $this->configlib->item('mobile_layout_register'),
                'use_sidebar' => $this->configlib->item('sidebar_register'),
                'use_mobile_sidebar' => $this->configlib->item('mobile_sidebar_register'),
                'skin_dir' => $this->configlib->item('skin_register'),
                'mobile_skin_dir' => $this->configlib->item('mobile_skin_register'),
                'page_title' => $page_title,
                'meta_description' => $meta_description,
                'meta_keywords' => $meta_keywords,
                'meta_author' => $meta_author,
                'page_name' => $page_name,
            );
            $view['layout'] = $this->layoutlib->front($layoutconfig, $this->configlib->get_device_view_type());
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view));
            $this->view = element('view_skin_file', element('layout', $view));

        } else {

            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */


            $user_level = (int) $this->configlib->item('register_level');
            $insertdata = array();
            $metadata = array();

            $insertdata['user_userid'] = $this->input->post('user_userid');
            $insertdata['user_email'] = $this->input->post('user_email');
            $insertdata['user_password'] = password_hash($this->input->post('user_password'), PASSWORD_BCRYPT);
            $insertdata['user_nickname'] = $this->input->post('user_nickname');
            $metadata['meta_nickname_datetime'] = cdate('Y-m-d H:i:s');
            $insertdata['user_level'] = $user_level;

            if (isset($form['user_username']['use']) && $form['user_username']['use']) {
                $insertdata['user_username'] = $this->input->post('user_username', null, '');
            }
            if (isset($form['user_homepage']['use']) && $form['user_homepage']['use']) {
                $insertdata['user_homepage'] = $this->input->post('user_homepage', null, '');
            }
            if (isset($form['user_phone']['use']) && $form['user_phone']['use']) {
                $insertdata['user_phone'] = $this->input->post('user_phone', null, '');
            }
            if (isset($form['user_birthday']['use']) && $form['user_birthday']['use']) {
                $insertdata['user_birthday'] = $this->input->post('user_birthday', null, '');
            }
            if (isset($form['user_sex']['use']) && $form['user_sex']['use']) {
                $insertdata['user_sex'] = $this->input->post('user_sex', null, '');
            }
            if (isset($form['user_address']['use']) && $form['user_address']['use']) {
                $insertdata['user_zipcode'] = $this->input->post('user_zipcode', null, '');
                $insertdata['user_address1'] = $this->input->post('user_address1', null, '');
                $insertdata['user_address2'] = $this->input->post('user_address2', null, '');
                $insertdata['user_address3'] = $this->input->post('user_address3', null, '');
                $insertdata['user_address4'] = $this->input->post('user_address4', null, '');
            }
            $insertdata['user_receive_email'] = $this->input->post('user_receive_email') ? 1 : 0;
            if ($this->configlib->item('use_note')) {
                $insertdata['user_use_note'] = $this->input->post('user_use_note') ? 1 : 0;
                $metadata['meta_use_note_datetime'] = cdate('Y-m-d H:i:s');
            }
            $insertdata['user_receive_sms'] = $this->input->post('user_receive_sms') ? 1 : 0;
            $insertdata['user_open_profile'] = $this->input->post('user_open_profile') ? 1 : 0;
            $metadata['meta_open_profile_datetime'] = cdate('Y-m-d H:i:s');
            $insertdata['user_register_datetime'] = cdate('Y-m-d H:i:s');
            $insertdata['user_register_ip'] = $this->input->ip_address();
            $metadata['meta_change_pw_datetime'] = cdate('Y-m-d H:i:s');
            if (isset($form['user_profile_content']['use']) && $form['user_profile_content']['use']) {
                $insertdata['user_profile_content'] = $this->input->post('user_profile_content', null, '');
            }

            if ($this->configlib->item('use_register_email_auth')) {
                $insertdata['user_email_cert'] = 0;
                $metadata['meta_email_cert_datetime'] = '';
            } else {
                $insertdata['user_email_cert'] = 1;
                $metadata['meta_email_cert_datetime'] = cdate('Y-m-d H:i:s');
             }

            if ($updatephoto) {
                $insertdata['user_photo'] = $updatephoto;
            }
            if ($updateicon) {
                $insertdata['user_icon'] = $updateicon;
            }

            $user_id = $this->User_model->insert($insertdata);

            $useridinsertdata = array(
                'user_id' => $user_id,
                'user_userid' => $this->input->post('user_userid'),
            );
            $this->User_userid_model->insert($useridinsertdata);
            
            $this->User_meta_model->save($user_id, $metadata);

            $nickinsert = array(
                'user_id' => $user_id,
                'uni_nickname' => $this->input->post('user_nickname'),
                'uni_start_datetime' => cdate('Y-m-d H:i:s'),
            );
            $this->User_nickname_model->insert($nickinsert);

            $extradata = array();
            if ($form && is_array($form)) {
                $this->load->model('User_extra_vars_model');
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

            $levelhistoryinsert = array(
                'user_id' => $user_id,
                'ulh_from' => 0,
                'ulh_to' => $user_level,
                'ulh_datetime' => cdate('Y-m-d H:i:s'),
                'ulh_reason' => '회원가입',
                'ulh_ip' => $this->input->ip_address(),
            );
            $this->load->model('User_level_history_model');
            $this->User_level_history_model->insert($levelhistoryinsert);

            $this->load->model('User_group_model');
            $allgroup = $this->User_group_model->get_all_group();
            if ($allgroup && is_array($allgroup)) {
                $this->load->model('User_group_user_model');
                foreach ($allgroup as $gkey => $gval) {
                    if (element('ugr_is_default', $gval)) {
                        $gminsert = array(
                            'ugr_id' => element('ugr_id', $gval),
                            'user_id' => $user_id,
                            'ugu_datetime' => cdate('Y-m-d H:i:s'),
                        );
                        $this->User_group_user_model->insert($gminsert);
                    }
                }
            }

            $searchconfig = array(
                '{홈페이지명}',
                '{회사명}',
                '{홈페이지주소}',
                '{회원아이디}',
                '{회원닉네임}',
                '{회원실명}',
                '{회원이메일}',
                '{메일수신여부}',
                '{쪽지수신여부}',
                '{문자수신여부}',
                '{회원아이피}',
            );
            $user_userid = $this->input->post('user_userid', null, '');
            $user_nickname = $this->input->post('user_nickname', null, '');
            $user_username = $this->input->post('user_username', null, '');
            $user_email = $this->input->post('user_email', null, '');
            $receive_email = $this->input->post('user_receive_email') ? '동의' : '거부';
            $receive_note = $this->input->post('user_use_note') ? '동의' : '거부';
            $receive_sms = $this->input->post('user_receive_sms') ? '동의' : '거부';
            $replaceconfig = array(
                $this->configlib->item('site_title'),
                $this->configlib->item('company_name'),
                site_url(),
                $user_userid,
                $user_nickname,
                $user_username,
                $user_email,
                $receive_email,
                $receive_note,
                $receive_sms,
                $this->input->ip_address(),
            );
            $replaceconfig_escape = array(
                html_escape($this->configlib->item('site_title')),
                html_escape($this->configlib->item('company_name')),
                site_url(),
                html_escape($user_userid),
                html_escape($user_nickname),
                html_escape($user_username),
                html_escape($user_email),
                $receive_email,
                $receive_note,
                $receive_sms,
                $this->input->ip_address(),
            );

            if ( ! $this->configlib->item('use_register_email_auth')) {
                if (($this->configlib->item('send_email_register_user') && $this->input->post('user_receive_email'))
                    OR $this->configlib->item('send_email_register_alluser')) {
                    $title = str_replace(
                        $searchconfig,
                        $replaceconfig,
                        $this->configlib->item('send_email_register_user_title')
                    );
                    $content = str_replace(
                        $searchconfig,
                        $replaceconfig_escape,
                        $this->configlib->item('send_email_register_user_content')
                    );
                    $this->email->from($this->configlib->item('webmaster_email'), $this->configlib->item('webmaster_name'));
                    $this->email->to($this->input->post('user_email'));
                    $this->email->subject($title);
                    $this->email->message($content);
                    $this->email->send();
                }
            } else {
                $vericode = array('$', '/', '.');
                $verificationcode = str_replace(
                    $vericode,
                    '',
                    password_hash($user_id . '-' . $this->input->post('user_email') . '-' . random_string('alnum', 10), PASSWORD_BCRYPT)
                );

                $beforeauthdata = array(
                    'user_id' => $user_id,
                    'uae_type' => 1,
                );
                $this->User_auth_email_model->delete_where($beforeauthdata);
                $authdata = array(
                    'user_id' => $user_id,
                    'uae_key' => $verificationcode,
                    'uae_type' => 1,
                    'uae_generate_datetime' => cdate('Y-m-d H:i:s'),
                );
                $this->User_auth_email_model->insert($authdata);

                $verify_url = site_url('verify/confirmemail?user=' . $this->input->post('user_userid') . '&code=' . $verificationcode);

                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->configlib->item('send_email_register_user_verifytitle')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->configlib->item('send_email_register_user_verifycontent')
                );

                $title = str_replace('{메일인증주소}', $verify_url, $title);
                $content = str_replace('{메일인증주소}', $verify_url, $content);

                $this->email->from($this->configlib->item('webmaster_email'), $this->configlib->item('webmaster_name'));
                $this->email->to($this->input->post('user_email'));
                $this->email->subject($title);
                $this->email->message($content);
                $this->email->send();

                $email_auth_message = $this->input->post('user_email') . '로 인증메일이 발송되었습니다. <br />발송된 인증메일을 확인하신 후에 사이트 이용이 가능합니다';
                $this->session->set_flashdata(
                    'email_auth_message',
                    $email_auth_message
                );
            }

            $emailsendlistadmin = array();
            $notesendlistadmin = array();
            $smssendlistadmin = array();
            $notesendlistuser = array();
            $smssendlistuser = array();

            $superadminlist = '';
            if ($this->configlib->item('send_email_register_admin')
                OR $this->configlib->item('send_note_register_admin')
                OR $this->configlib->item('send_sms_register_admin')) {
                $mselect = 'user_id, user_email, user_nickname, user_phone';
                $superadminlist = $this->User_model->get_superadmin_list($mselect);
            }

            if ($this->configlib->item('send_email_register_admin') && $superadminlist) {
                foreach ($superadminlist as $key => $value) {
                    $emailsendlistadmin[$value['user_id']] = $value;
                }
            }
            if ($this->configlib->item('send_note_register_admin') && $superadminlist) {
                foreach ($superadminlist as $key => $value) {
                    $notesendlistadmin[$value['user_id']] = $value;
                }
            }
            if (($this->configlib->item('send_note_register_user') && $this->input->post('user_use_note'))) {
                $notesendlistuser['user_id'] = $user_id;
            }
            if ($this->configlib->item('send_sms_register_admin') && $superadminlist) {
                foreach ($superadminlist as $key => $value) {
                    $smssendlistadmin[$value['user_id']] = $value;
                }
            }
            if (($this->configlib->item('send_sms_register_user') && $this->input->post('user_receive_sms'))
                OR $this->configlib->item('send_sms_register_alluser')) {
                if ($this->input->post('user_phone')) {
                    $smssendlistuser['user_id'] = $user_id;
                    $smssendlistuser['user_nickname'] = $this->input->post('user_nickname');
                    $smssendlistuser['user_phone'] = $this->input->post('user_phone');
                }
            }

            if ($emailsendlistadmin) {
                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->configlib->item('send_email_register_admin_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->configlib->item('send_email_register_admin_content')
                );
                foreach ($emailsendlistadmin as $akey => $aval) {
                    $this->email->clear(true);
                    $this->email->from($this->configlib->item('webmaster_email'), $this->configlib->item('webmaster_name'));
                    $this->email->to(element('user_email', $aval));
                    $this->email->subject($title);
                    $this->email->message($content);
                    $this->email->send();
                }
            }
            if ($notesendlistadmin) {
                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->configlib->item('send_note_register_admin_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->configlib->item('send_note_register_admin_content')
                );
                foreach ($notesendlistadmin as $akey => $aval) {
                    $note_result = $this->notelib->send_note(
                        $sender = 0,
                        $receiver = element('user_id', $aval),
                        $title,
                        $content,
                        1
                    );
                }
            }
            if ($notesendlistuser && element('user_id', $notesendlistuser)) {
                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->configlib->item('send_note_register_user_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->configlib->item('send_note_register_user_content')
                );
                $note_result = $this->notelib->send_note(
                    $sender = 0,
                    $receiver = element('user_id', $notesendlistuser),
                    $title,
                    $content,
                    1
                );
            }
            if ($smssendlistadmin) {
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->configlib->item('send_sms_register_admin_content')
                );
                $sender = array(
                    'phone' => $this->configlib->item('sms_admin_phone'),
                );
                $receiver = array();
                foreach ($smssendlistadmin as $akey => $aval) {
                    $receiver[] = array(
                        'user_id' => element('user_id', $aval),
                        'name' => element('user_nickname', $aval),
                        'phone' => element('user_phone', $aval),
                    );
                }
                $this->load->library('smslib');
                $smsresult = $this->smslib->send($receiver, $sender, $content, $date = '', '회원가입알림');
            }
            if ($smssendlistuser) {
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->configlib->item('send_sms_register_user_content')
                );
                $sender = array(
                    'phone' => $this->configlib->item('sms_admin_phone'),
                );
                $receiver = array();
                $receiver[] = $smssendlistuser;
                $this->load->library('smslib');
                $smsresult = $this->smslib->send($receiver, $sender, $content, $date = '', '회원가입알림');
            }

            $user_register_data = array(
                'user_id' => $user_id,
                'urg_ip' => $this->input->ip_address(),
                'urg_datetime' => cdate('Y-m-d H:i:s'),
                'urg_useragent' => $this->agent->agent_string(),
                'urg_referer' => $this->session->userdata('site_referer'),
            );
            $recommended = '';
            if ($this->input->post('user_recommend')) {
                $recommended = $this->User_model->get_by_userid($this->input->post('user_recommend'), 'user_id');
                if (element('user_id', $recommended)) {
                    $user_register_data['urg_recommend_user_id'] = element('user_id', $recommended);
                } else {
                    $recommended['user_id'] = 0;
                }
            }
            $this->load->model('User_register_model');
            $this->User_register_model->insert($user_register_data);

            $this->session->set_flashdata(
                'nickname',
                $this->input->post('user_nickname')
            );

            if ( ! $this->configlib->item('use_register_email_auth')) {
                $this->session->set_userdata(
                    'user_id',
                    $user_id
                );
            }

            redirect('register/result');
        }
    }


    /**
     * 회원가입 결과 페이지입니다
     */
    public function result()
    {

        $view = array();
        $view['view'] = array();


        $this->session->keep_flashdata('nickname');
        $this->session->keep_flashdata('email_auth_message');

        if ( ! $this->session->flashdata('nickname')) {
            redirect();
        }


        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->configlib->item('site_meta_title_register_result');
        $meta_description = $this->configlib->item('site_meta_description_register_result');
        $meta_keywords = $this->configlib->item('site_meta_keywords_register_result');
        $meta_author = $this->configlib->item('site_meta_author_register_result');
        $page_name = $this->configlib->item('site_page_name_register_result');

        $layoutconfig = array(
            'path' => 'register',
            'layout' => 'layout',
            'skin' => 'register_result',
            'layout_dir' => $this->configlib->item('layout_register'),
            'mobile_layout_dir' => $this->configlib->item('mobile_layout_register'),
            'use_sidebar' => $this->configlib->item('sidebar_register'),
            'use_mobile_sidebar' => $this->configlib->item('mobile_sidebar_register'),
            'skin_dir' => $this->configlib->item('skin_register'),
            'mobile_skin_dir' => $this->configlib->item('mobile_skin_register'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );
        $view['layout'] = $this->layoutlib->front($layoutconfig, $this->configlib->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }


    public function ajax_userid_check()
    {

        $result = array();
        $this->output->set_content_type('application/json');


        $userid = trim($this->input->post('userid'));
        if (empty($userid)) {
            $result = array(
                'result' => 'no',
                'reason' => '아이디값이 넘어오지 않았습니다',
            );
            exit(json_encode($result));
        }

        if ( ! preg_match("/^([a-z0-9_])+$/i", $userid)) {
            $result = array(
                'result' => 'no',
                'reason' => '아이디는 숫자, 알파벳, _ 만 입력가능합니다',
            );
            exit(json_encode($result));
        }

        $where = array(
            'user_userid' => $userid,
        );
        $count = $this->User_userid_model->count_by($where);
        if ($count > 0) {
            $result = array(
                'result' => 'no',
                'reason' => '이미 사용중인 아이디입니다',
            );
            exit(json_encode($result));
        }

        if ($this->_user_userid_check($userid) === false) {
            $result = array(
                'result' => 'no',
                'reason' => $userid . '은(는) 예약어로 사용하실 수 없는 회원아이디입니다',
            );
            exit(json_encode($result));
        }


        $result = array(
            'result' => 'available',
            'reason' => '사용 가능한 아이디입니다',
        );
        exit(json_encode($result));
    }


    public function ajax_email_check()
    {

        $result = array();
        $this->output->set_content_type('application/json');


        $email = trim($this->input->post('email'));
        if (empty($email)) {
            $result = array(
                'result' => 'no',
                'reason' => '이메일값이 넘어오지 않았습니다',
            );
            exit(json_encode($result));
        }

        if ($this->userlib->item('user_email')
            && $this->userlib->item('user_email') === $email) {
            $result = array(
                'result' => 'available',
                'reason' => '사용 가능한 이메일입니다',
            );
            exit(json_encode($result));
        }

        $where = array(
            'user_email' => $email,
        );
        $count = $this->User_model->count_by($where);
        if ($count > 0) {
            $result = array(
                'result' => 'no',
                'reason' => '이미 사용중인 이메일입니다',
            );
            exit(json_encode($result));
        }

        if ($this->_user_email_check($email) === false) {
            $result = array(
                'result' => 'no',
                'reason' => $email . '은(는) 예약어로 사용하실 수 없는 이메일입니다',
            );
            exit(json_encode($result));
        }


        $result = array(
            'result' => 'available',
            'reason' => '사용 가능한 이메일입니다',
        );
        exit(json_encode($result));
    }


    public function ajax_password_check()
    {

        $result = array();
        $this->output->set_content_type('application/json');


        $password = trim($this->input->post('password'));
        if (empty($password)) {
            $result = array(
                'result' => 'no',
                'reason' => '패스워드값이 넘어오지 않았습니다',
            );
            exit(json_encode($result));
        }

        if ($this->_user_password_check($password) === false) {
            $result = array(
                'result' => 'no',
                'reason' => '패스워드는 최소 1개 이상의 숫자를 포함해야 합니다',
            );
            exit(json_encode($result));
        }

        $result = array(
            'result' => 'available',
            'reason' => '사용 가능한 패스워드입니다',
        );
        exit(json_encode($result));
    }


    public function ajax_nickname_check()
    {

        $result = array();
        $this->output->set_content_type('application/json');


        $nickname = trim($this->input->post('nickname'));
        if (empty($nickname)) {
            $result = array(
                'result' => 'no',
                'reason' => '닉네임값이 넘어오지 않았습니다',
            );
            exit(json_encode($result));
        }

        if ($this->userlib->item('user_nickname')
            && $this->userlib->item('user_nickname') === $nickname) {
            $result = array(
                'result' => 'available',
                'reason' => '사용 가능한 닉네임입니다',
            );
            exit(json_encode($result));
        }

        $where = array(
            'user_nickname' => $nickname,
        );
        $count = $this->User_model->count_by($where);
        if ($count > 0) {
            $result = array(
                'result' => 'no',
                'reason' => '이미 사용중인 닉네임입니다',
            );
            exit(json_encode($result));
        }

        if ($this->_user_nickname_check($nickname) === false) {
            $result = array(
                'result' => 'no',
                'reason' => '이미 사용중인 닉네임입니다',
            );
            exit(json_encode($result));
        }

        $result = array(
            'result' => 'available',
            'reason' => '사용 가능한 닉네임입니다',
        );
        exit(json_encode($result));
    }


    /**
     * 회원가입시 회원아이디를 체크하는 함수입니다
     */
    public function _user_userid_check($str)
    {
        if (preg_match("/[\,]?{$str}/i", $this->configlib->item('denied_userid_list'))) {
            $this->form_validation->set_message(
                '_user_userid_check',
                $str . ' 은(는) 예약어로 사용하실 수 없는 회원아이디입니다'
            );
            return false;
        }

        return true;
    }


    /**
     * 회원가입시 닉네임을 체크하는 함수입니다
     */
    public function _user_nickname_check($str)
    {
        $this->load->helper('chkstring');
        if (chkstring($str, _HANGUL_ + _ALPHABETIC_ + _NUMERIC_) === false) {
            $this->form_validation->set_message(
                '_user_nickname_check',
                '닉네임은 공백없이 한글, 영문, 숫자만 입력 가능합니다'
            );
            return false;
        }

        if (preg_match("/[\,]?{$str}/i", $this->configlib->item('denied_nickname_list'))) {
            $this->form_validation->set_message(
                '_user_nickname_check',
                $str . ' 은(는) 예약어로 사용하실 수 없는 닉네임입니다'
            );
            return false;
        }
        $countwhere = array(
            'user_nickname' => $str,
        );
        $row = $this->User_model->count_by($countwhere);

        if ($row > 0) {
            $this->form_validation->set_message(
                '_user_nickname_check',
                $str . ' 는 이미 다른 회원이 사용하고 있는 닉네임입니다'
            );
            return false;
        }

        $countwhere = array(
            'uni_nickname' => $str,
        );
        $row = $this->User_nickname_model->count_by($countwhere);

        if ($row > 0) {
            $this->form_validation->set_message(
                '_user_nickname_check',
                $str . ' 는 이미 다른 회원이 사용하고 있는 닉네임입니다'
            );
            return false;
        }

        return true;
    }


    /**
     * 회원가입시 이메일을 체크하는 함수입니다
     */
    public function _user_email_check($str)
    {
        list($emailid, $emaildomain) = explode('@', $str);
        $denied_list = explode(',', $this->configlib->item('denied_email_list'));
        $emaildomain = trim($emaildomain);
        $denied_list = array_map('trim', $denied_list);
        if (in_array($emaildomain, $denied_list)) {
            $this->form_validation->set_message(
                '_user_email_check',
                $emaildomain . ' 은(는) 사용하실 수 없는 이메일입니다'
            );
            return false;
        }

        return true;
    }


    /**
     * 회원가입시 추천인을 체크하는 함수입니다
     */
    public function _user_recommend_check($str)
    {
        if( ! $str) {
            return true;
        }
        
        $countwhere = array(
            'user_userid' => $str,
            'user_denied' => 0,
        );
        $row = $this->User_model->count_by($countwhere);

        if ($row === 0) {
            $this->form_validation->set_message(
                '_user_recommend_check',
                $str . ' 는 존재하지 않는 추천인 회원아이디입니다'
            );
            return false;
        }

        return true;
    }


    /**
     * 회원가입시 captcha 체크하는 함수입니다
     */
    public function _check_captcha($str)
    {
        $captcha = $this->session->userdata('captcha');
        if ( ! is_array($captcha) OR ! element('word', $captcha) OR strtolower(element('word', $captcha)) !== strtolower($str)) {
            $this->session->unset_userdata('captcha');
            $this->form_validation->set_message(
                '_check_captcha',
                '자동등록방지코드가 잘못되었습니다'
            );
            return false;
        }
        return true;
    }


    /**
     * 회원가입시 recaptcha 체크하는 함수입니다
     */
    public function _check_recaptcha($str)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => $this->configlib->item('recaptcha_secret'),
            'response' => $str,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, sizeof($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        $obj = json_decode($result);

        if ((string) $obj->success !== '1') {
            $this->form_validation->set_message(
                '_check_recaptcha',
                '자동등록방지코드가 잘못되었습니다'
            );
            return false;
        }

        return true;
    }


    /**
     * 회원가입시 패스워드가 올바른 규약에 의해 입력되었는지를 체크하는 함수입니다
     */
    public function _user_password_check($str)
    {
        $uppercase = $this->configlib->item('password_uppercase_length');
        $number = $this->configlib->item('password_numbers_length');
        $specialchar = $this->configlib->item('password_specialchars_length');

        $this->load->helper('chkstring');
        $str_uc = count_uppercase($str);
        $str_num = count_numbers($str);
        $str_spc = count_specialchars($str);

        if ($str_uc < $uppercase OR $str_num < $number OR $str_spc < $specialchar) {

            $description = '비밀번호는 ';
            if ($str_uc < $uppercase) {
                $description .= ' ' . $uppercase . '개 이상의 대문자';
            }
            if ($str_num < $number) {
                $description .= ' ' . $number . '개 이상의 숫자';
            }
            if ($str_spc < $specialchar) {
                $description .= ' ' . $specialchar . '개 이상의 특수문자';
            }
            $description .= '를 포함해야 합니다';

            $this->form_validation->set_message(
                '_user_password_check',
                $description
            );
            return false;

        }

        return true;
    }
}
