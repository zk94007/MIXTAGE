<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mypage class
 */

/**
 * 마이페이지와 관련된 controller 입니다.
 */
class Mypage extends MY_Controller
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
        $this->load->library(array('pagination', 'querystring'));

    }


    /**
     * 마이페이지입니다
     */
    public function index()
    {

        /**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();

        $view = array();
        $view['view'] = array();


        $registerform = $this->configlib->item('registerform');
        $view['view']['userform'] = json_decode($registerform, true);

        $view['view']['user_group_name'] = '';
        $user_group = $this->userlib->group();
        if ($user_group && is_array($user_group)) {

            $this->load->model('User_group_model');

            foreach ($user_group as $gkey => $gval) {
                $item = $this->User_group_model->item(element('ugr_id', $gval));
                if ($view['view']['user_group_name']) {
                    $view['view']['user_group_name'] .= ', ';
                }
                $view['view']['user_group_name'] .= element('ugr_title', $item);
            }
        }


        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->configlib->item('site_meta_title_mypage');
        $meta_description = $this->configlib->item('site_meta_description_mypage');
        $meta_keywords = $this->configlib->item('site_meta_keywords_mypage');
        $meta_author = $this->configlib->item('site_meta_author_mypage');
        $page_name = $this->configlib->item('site_page_name_mypage');

        $layoutconfig = array(
            'path' => 'mypage',
            'layout' => 'layout',
            'skin' => 'main',
            'layout_dir' => $this->configlib->item('layout_mypage'),
            'mobile_layout_dir' => $this->configlib->item('mobile_layout_mypage'),
            'use_sidebar' => $this->configlib->item('sidebar_mypage'),
            'use_mobile_sidebar' => $this->configlib->item('mobile_sidebar_mypage'),
            'skin_dir' => $this->configlib->item('skin_mypage'),
            'mobile_skin_dir' => $this->configlib->item('mobile_skin_mypage'),
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
}
