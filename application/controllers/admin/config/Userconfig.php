<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Userconfig class
 */

/**
 * 관리자>환경설정>회원가입설정 controller 입니다.
 */
class Userconfig extends MY_Controller
{

    /**
     * 관리자 페이지 상의 현재 디렉토리입니다
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = 'config/userconfig';

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Config');

    /**
     * 이 컨트롤러의 메인 모델 이름입니다
     */
    protected $modelname = 'Config_model';

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 회원가입설정>기본정보 페이지입니다
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
                'label' => '전송',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_login_account',
                'label' => '로그인시 사용할 계정',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'use_register_block',
                'label' => '회원가입차단',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_register_email_auth',
                'label' => '회원가입시 메일인증사용',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'password_length',
                'label' => '비밀번호 길이',
                'rules' => 'trim|required|numeric|is_natural_no_zero',
            ),
            array(
                'field' => 'password_uppercase_length',
                'label' => '비밀번호 대문자 포함 개수',
                'rules' => 'trim|required|numeric',
            ),
            array(
                'field' => 'password_numbers_length',
                'label' => '비밀번호 수자 포함 개수',
                'rules' => 'trim|required|numeric',
            ),
            array(
                'field' => 'password_specialchars_length',
                'label' => '비밀번호 특수문자 포함 개수',
                'rules' => 'trim|required|numeric',
            ),
            array(
                'field' => 'use_user_photo',
                'label' => '회원프로필사진',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'user_photo_width',
                'label' => '회원프로필사진가로길이',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'user_photo_height',
                'label' => '회원프로필사진세로길이',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_user_icon',
                'label' => '회원아이콘',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'user_icon_width',
                'label' => '회원아이콘가로길이',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'user_icon_height',
                'label' => '회원아이콘세로길이',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'denied_nickname_list',
                'label' => '금지 닉네임',
                'rules' => 'trim',
            ),
            array(
                'field' => 'denied_userid_list',
                'label' => '금지 아이디',
                'rules' => 'trim',
            ),
            array(
                'field' => 'denied_email_list',
                'label' => '금지 이메일',
                'rules' => 'trim',
            ),
            array(
                'field' => 'user_register_policy1',
                'label' => '회원가입약관',
                'rules' => 'trim',
            ),
            array(
                'field' => 'user_register_policy2',
                'label' => '개인정보취급방침',
                'rules' => 'trim',
            ),
            array(
                'field' => 'register_level',
                'label' => '회원가입시레벨',
                'rules' => 'trim|required|is_natural_no_zero|less_than_equal_to[1000]',
            ),
        );
        $this->form_validation->set_rules($config);

        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {


        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */


            $array = array('use_login_account', 'use_register_block', 'use_register_email_auth',
                'password_length', 'password_uppercase_length', 'password_numbers_length',
                'password_specialchars_length', 'use_user_photo', 'user_photo_width',
                'user_photo_height', 'use_user_icon', 'user_icon_width', 'user_icon_height',
                'denied_nickname_list', 'denied_userid_list', 'denied_email_list', 'user_register_policy1',
                'user_register_policy2', 'register_level');
            foreach ($array as $value) {
                $savedata[$value] = $this->input->post($value, null, '');
            }
            $this->Config_model->save($savedata);
            $view['view']['alert_message'] = '회원가입 기본정보설정이 저장되었습니다';
        }

        $getdata = $this->Config_model->get_all_meta();
        $view['view']['data'] = $getdata;


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
     * 회원가입설정>가입폼관리 페이지입니다
     */
    public function registerform()
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
                'field' => 's',
                'label' => '회원가입폼',
                'rules' => 'trim',
            ),
        );
        $this->form_validation->set_rules($config);

        $default_form = array(
            'user_userid' => array(
                'name' => '아이디', 'field_type' => 'text', 'use' => true, 'disable_use' => true, 'open' => true, 'disable_open' => true, 'required' => true, 'disable_required' => true
            ),
            'user_email' => array(
                'name' => '이메일주소', 'field_type' => 'email', 'use' => true, 'disable_use' => true, 'open' => false, 'disable_open' => true, 'required' => true, 'disable_required' => true
            ),
            'user_password' => array(
                'name' => '비밀번호', 'field_type' => 'password', 'use' => true, 'disable_use' => true, 'open' => false, 'disable_open' => true, 'required' => true, 'disable_required' => true
            ),
            'user_username' => array(
                'name' => '이름', 'field_type' => 'text', 'use' => true, 'disable_use' => false, 'open' => false, 'disable_open' => false, 'required' => false, 'disable_required' => false
            ),
            'user_nickname' => array(
                'name' => '닉네임', 'field_type' => 'text', 'use' => true, 'disable_use' => true, 'open' => true, 'disable_open' => true, 'required' => true, 'disable_required' => true
            ),
            'user_homepage' => array(
                'name' => '홈페이지', 'field_type' => 'url', 'use' => true, 'disable_use' => false, 'open' => false, 'disable_open' => false, 'required' => false, 'disable_required' => false
            ),
            'user_phone' => array(
                'name' => '전화번호', 'field_type' => 'phone', 'use' => true, 'disable_use' => false, 'open' => false, 'disable_open' => false, 'required' => false, 'disable_required' => false
            ),
            'user_birthday' => array(
                'name' => '생년월일', 'field_type' => 'date', 'use' => true, 'disable_use' => false, 'open' => false, 'disable_open' => false, 'required' => false, 'disable_required' => false
            ),
            'user_sex' => array(
                'name' => '성별', 'field_type' => 'radio', 'use' => true, 'disable_use' => false, 'open' => false, 'disable_open' => false, 'required' => false, 'disable_required' => false
            ),
            'user_address' => array(
                'name' => '주소', 'field_type' => 'address', 'use' => true, 'disable_use' => false, 'open' => false, 'disable_open' => false, 'required' => false, 'disable_required' => false
            ),
            'user_profile_content' => array(
                'name' => '자기소개', 'field_type' => 'textarea', 'use' => true, 'disable_use' => false, 'open' => true, 'disable_open' => false, 'required' => false, 'disable_required' => false
            ),
            'user_recommend' => array(
                'name' => '추천인', 'field_type' => 'text', 'use' => false, 'disable_use' => false, 'open' => false, 'disable_open' => true, 'required' => false, 'disable_required' => false
            ),
        );

        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {


        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */


            $updatedata = $this->input->post();

            $array_count_values = array_count_values($updatedata['field_name']);
            $fail = false;
            if ($fail === false) {
                foreach ($array_count_values as $akey => $aval) {
                    if ($aval > 1) {
                        $view['view']['warning_message'] = $akey . ' 값이 ' . $aval . ' 회 중복 입력되었습니다. ID 값이 중복되지 않게 입력해주세요';
                        $fail = true;
                        break;
                    }
                }
            }
            if ($fail === false) {
                foreach (element('field_name', $updatedata) as $fkey => $fval) {
                    if (empty($fval)) {
                        $view['view']['warning_message'] = '비어있는 ID 값이 있습니다. ID 값을 빠뜨리지 말고 입력해주세요';
                        $fail = true;
                        break;
                    }
                }
            }
            if ($fail === false) {
                foreach (element('display_name', $updatedata) as $fkey => $fval) {
                    if (empty($fval)) {
                        $view['view']['warning_message'] = '비어있는 입력항목제목이 있습니다. 입력항목제목 값을 빠뜨리지 말고 입력해주세요';
                        $fail = true;
                        break;
                    }
                }
            }
            if ($fail === false) {
                $order = 0;
                $update = array();
                $extra_vars_field = array();

                foreach (element('key', $updatedata) as $key => $value) {
                    if ($value) {
                        if (isset($updatedata['basic'][$value]) && $updatedata['basic'][$value]) {
                            $update[$value]['field_name'] = $value;
                            $update[$value]['func'] = 'basic';
                            $update[$value]['display_name'] = element('name', element($value, $default_form));
                            $update[$value]['field_type'] = element('field_type', element($value, $default_form));
                            $update[$value]['use'] = element('disable_use', element($value, $default_form))
                                ? (element('use', element($value, $default_form)) ? '1' : '')
                                : element($value, element('use', $updatedata));
                            $update[$value]['open'] = element('disable_open', element($value, $default_form))
                                ? (element('open', element($value, $default_form)) ? '1' : '')
                                : element($value, element('open', $updatedata));
                            $update[$value]['required'] = element('disable_required', element($value, $default_form))
                                ? (element('required', element($value, $default_form)) ? '1' : '')
                                : element($value, element('required', $updatedata));

                        } else {
                            $update[$value] = array(
                                'field_name' => element($order, element('field_name', $updatedata)),
                                'func' => 'added',
                                'display_name' => element($order, element('display_name', $updatedata)),
                                'field_type' => element($value, element('field_type', $updatedata)),
                                'use' => element($value, element('use', $updatedata)),
                                'open' => element($value, element('open', $updatedata)),
                                'required' => element($value, element('required', $updatedata)),
                                'options' => element($value, element('options', $updatedata)),
                            );
                            $extra_vars_field[] = element($order, element('field_name', $updatedata));
                        }
                    } else {
                        $update[$updatedata['field_name'][$order]] = array(
                            'field_name' => element($order, element('field_name', $updatedata)),
                            'func' => 'added',
                            'display_name' => element($order, element('display_name', $updatedata)),
                            'use' => element($key, element('use', $updatedata)),
                            'field_type' => element($key, element('field_type', $updatedata)),
                            'open' => element($key, element('open', $updatedata)),
                            'required' => element($key, element('required', $updatedata)),
                            'options' => element($key, element('options', $updatedata)),
                        );
                        $extra_vars_field[] = element($order, element('field_name', $updatedata));
                    }
                    $order++;
                }

                if ($default_form && is_array($default_form)) {
                    foreach ($default_form as $key => $value) {
                        if (isset($update[$key]) && $update[$key]) {
                            continue;
                        }

                        $update[$key]['field_name'] = $key;
                        $update[$key]['func'] = 'basic';
                        $update[$key]['display_name'] = element('name', element($key, $default_form));
                        $update[$key]['field_type'] = element('field_type', element($key, $default_form));
                        $update[$key]['use'] = element('disable_use', element($key, $default_form))
                            ? (element('use', element($key, $default_form)) ? '1' : '')
                            : element($key, element('use', $updatedata));
                        $update[$key]['open'] = element('disable_open', element($key, $default_form))
                            ? (element('open', element($key, $default_form)) ? '1' : '')
                            : element($key, element('open', $updatedata));
                        $update[$key]['required'] = element('disable_required', element($key, $default_form))
                            ? (element('required', element($key, $default_form)) ? '1' : '')
                            : element($key, element('required', $updatedata));
                    }
                }

                $old_registerform = $this->configlib->item('registerform');
                $old_data = json_decode($old_registerform, true);
                if ($old_data) {
                    foreach ($old_data as $oldkey => $oldvalue) {
                        if (element('func', $oldvalue) === 'basic') {
                            continue;
                        }
                        if ( ! in_array($oldkey, $extra_vars_field)) {
                            $this->load->model('User_extra_vars_model');
                            $this->User_extra_vars_model->deletemeta_item($oldkey);
                        }
                    }
                }
                $savedata = array(
                    'registerform' => json_encode($update),
                );
                $this->Config_model->save($savedata);
                $view['view']['alert_message'] = '정상적으로 저장되었습니다';
            }
        }

        $getdata = $this->Config_model->get_all_meta();

        if ( ! element('registerform', $getdata)) {
            foreach ($default_form as $key => $value) {
                $initdata[$key]['field_name'] = $key;
                $initdata[$key]['func'] = 'basic';
                $initdata[$key]['display_name'] = element('name', element($key, $default_form));
                $initdata[$key]['field_type'] = element('field_type', element($key, $default_form));
                $initdata[$key]['use'] = element('disable_use', element($key, $default_form))
                    ? (element('use', element($key, $default_form)) ? '1' : '') : '';
                $initdata[$key]['open'] = element('disable_open', element($key, $default_form))
                    ? (element('open', element($key, $default_form)) ? '1' : '') : '';
                $initdata[$key]['required'] = element('disable_required', element($key, $default_form))
                    ? (element('required', element($key, $default_form)) ? '1' : '') : '';
            }
            $getdata['registerform'] = json_encode($initdata);
        }

        $getdata['result'] = json_decode($getdata['registerform'], true);
        $getdata['default_form'] = $default_form;

        $view['view']['data'] = $getdata;


        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout', 'skin' => 'registerform');
        $view['layout'] = $this->layoutlib->admin($layoutconfig, $this->configlib->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

    /**
     * 회원가입설정>정보수정시 페이지입니다
     */
    public function usermodify()
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
                'label' => '전송',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'change_nickname_date',
                'label' => '닉네임수정가능',
                'rules' => 'trim|numeric|is_natural',
            ),
            array(
                'field' => 'change_open_profile_date',
                'label' => '정보공개수정',
                'rules' => 'trim|numeric|is_natural',
            ),
            array(
                'field' => 'change_use_note_date',
                'label' => '쪽지사용수정',
                'rules' => 'trim|numeric|is_natural',
            ),
        );
        $this->form_validation->set_rules($config);

        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {


        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */


            $array = array('change_nickname_date', 'change_open_profile_date', 'change_use_note_date');
            foreach ($array as $value) {
                $savedata[$value] = $this->input->post($value, null, '');
            }

            $this->Config_model->save($savedata);
            $view['view']['alert_message'] = '정보수정시 설정이 저장되었습니다';
        }

        $getdata = $this->Config_model->get_all_meta();
        $view['view']['data'] = $getdata;


        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout', 'skin' => 'usermodify');
        $view['layout'] = $this->layoutlib->admin($layoutconfig, $this->configlib->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

    /**
     * 회원가입설정>로그인 페이지입니다
     */
    public function login()
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
                'label' => '전송',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'change_password_date',
                'label' => '비밀번호 갱신주기',
                'rules' => 'trim|numeric|is_natural',
            ),
            array(
                'field' => 'max_login_try_count',
                'label' => '로그인시도 회수제한',
                'rules' => 'trim|numeric|is_natural',
            ),
            array(
                'field' => 'max_login_try_limit_second',
                'label' => '로그인시도제한시간',
                'rules' => 'trim|numeric|is_natural',
            ),
            array(
                'field' => 'url_after_login',
                'label' => '로그인후이동할주소',
                'rules' => 'trim',
            ),
            array(
                'field' => 'url_after_logout',
                'label' => '로그아웃후이동할주소',
                'rules' => 'trim',
            ),
        );
        $this->form_validation->set_rules($config);

        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {


        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */


            $array = array('change_password_date', 'max_login_try_count',
                'max_login_try_limit_second', 'url_after_login', 'url_after_logout');
            foreach ($array as $value) {
                $savedata[$value] = $this->input->post($value, null, '');
            }
            $this->Config_model->save($savedata);
            $view['view']['alert_message'] = '로그인 설정이 저장되었습니다';
        }

        $getdata = $this->Config_model->get_all_meta();
        $view['view']['data'] = $getdata;


        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout', 'skin' => 'login');
        $view['layout'] = $this->layoutlib->admin($layoutconfig, $this->configlib->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

}
