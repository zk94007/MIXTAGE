<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helptool class
 */

/**
 * 각종 헬프페이지에 관련된 controller 입니다.
 */
class Helptool extends MY_Controller
{

    /**
     * 모델을 로딩합니다
     */
    protected $models = array();

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array', 'file', 'string');

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('pagination', 'querystring'));
    }


    /**
     * 이미지 크게 보기
     */
    public function viewimage()
    {

        $view = array();


        $view['view']['imgurl'] = $this->input->get('imgurl', null, '');

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = '이미지 보기';
        $layoutconfig = array(
            'path' => 'helptool',
            'layout' => 'layout_popup',
            'skin' => 'viewimage',
            'layout_dir' => $this->configlib->item('layout_helptool'),
            'mobile_layout_dir' => $this->configlib->item('mobile_layout_helptool'),
            'skin_dir' => $this->configlib->item('skin_helptool'),
            'mobile_skin_dir' => $this->configlib->item('mobile_skin_helptool'),
            'page_title' => $page_title,
        );
        $view['layout'] = $this->layoutlib->front($layoutconfig, $this->configlib->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }


    /**
     * 이모티콘 보기
     */
    public function emoticon()
    {

        $view = array();
        $view['view'] = array();

        $view['view']['emoticon'] = get_filenames(config_item('uploads_dir') . '/emoticon');

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = '이모티콘';
        $layoutconfig = array(
            'path' => 'helptool',
            'layout' => 'layout_popup',
            'skin' => 'emoticon',
            'layout_dir' => $this->configlib->item('layout_helptool'),
            'mobile_layout_dir' => $this->configlib->item('mobile_layout_helptool'),
            'skin_dir' => $this->configlib->item('skin_helptool'),
            'mobile_skin_dir' => $this->configlib->item('mobile_skin_helptool'),
            'page_title' => $page_title,
        );
        $view['layout'] = $this->layoutlib->front($layoutconfig, $this->configlib->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }


    /**
     * 특수문자 보기
     */
    public function specialchars()
    {

        $view = array();
        $view['view'] = array();



        $chars = "、 。 · ‥ … ¨ 〃 ― ∥ ＼ ∼ ‘ ’ “ ” 〔 〕 〈 〉 《 》 「 」 『 』 【 】 ± × ÷ ≠ ≤ ≥ ∞ ∴ ° ′ ″ ℃ Å ￠ ￡ ￥ ♂ ♀ ∠ ⊥ ⌒ ∂ ∇ ≡ ≒ § ※ ☆ ★ ○ ● ◎ ◇ ◆ □ ■ △ ▲ ▽ ▼ → ← ↑ ↓ ↔ 〓 ≪ ≫ √ ∽ ∝ ∵ ∫ ∬ ∈ ∋ ⊆ ⊇ ⊂ ⊃ ∩ ∧ ∨ ￢ ⇒ ⇔ ∀ ∃ ´ ～ ˇ ˘ ˝ ˚ ˙ ¸ ˛ ¡ ¿ ː ∮ ∑ ∏ ¤ ℉ ‰ ◁ ◀ ▷ ▶ ♤ ♠ ♡ ♥ ♧ ♣ ⊙ ◈ ▣ ◐ ◑ ▒ ▤ ▥ ▨ ▧ ▦ ▩ ♨ ☏ ☎ ☜ ☞ ¶ † ‡ ↕ ↗ ↙ ↖ ↘ ♭ ♩ ♪ ♬ ㉿ ㈜ № ㏇ ™ ㏂ ㏘ ℡";

        $view['view']['char'] = explode(' ', $chars);

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = '특수문자';
        $layoutconfig = array(
            'path' => 'helptool',
            'layout' => 'layout_popup',
            'skin' => 'specialchars',
            'layout_dir' => $this->configlib->item('layout_helptool'),
            'mobile_layout_dir' => $this->configlib->item('mobile_layout_helptool'),
            'skin_dir' => $this->configlib->item('skin_helptool'),
            'mobile_skin_dir' => $this->configlib->item('mobile_skin_helptool'),
            'page_title' => $page_title,
        );
        $view['layout'] = $this->layoutlib->front($layoutconfig, $this->configlib->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }


    /**
     * 구글지도
     */
    public function googlemap()
    {

        $view = array();
        $view['view'] = array();


        /**
         * 레이아웃을 정의합니다
         */
        $page_title = '구글지도';
        $layoutconfig = array(
            'path' => 'helptool',
            'layout' => 'layout_popup',
            'skin' => 'googlemap',
            'layout_dir' => $this->configlib->item('layout_helptool'),
            'mobile_layout_dir' => $this->configlib->item('mobile_layout_helptool'),
            'skin_dir' => $this->configlib->item('skin_helptool'),
            'mobile_skin_dir' => $this->configlib->item('mobile_skin_helptool'),
            'page_title' => $page_title,
        );
        $view['layout'] = $this->layoutlib->front($layoutconfig, $this->configlib->get_device_view_type());
        $this->data = $view;
        //$this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }


    /**
     * 구글지도
     */
    public function googlemap_search()
    {

        $view = array();
        $view['view'] = array();


        /**
         * 레이아웃을 정의합니다
         */
        $page_title = '구글지도';
        $layoutconfig = array(
            'path' => 'helptool',
            'layout' => 'layout_popup',
            'skin' => 'googlemap_search',
            'layout_dir' => $this->configlib->item('layout_helptool'),
            'mobile_layout_dir' => $this->configlib->item('mobile_layout_helptool'),
            'skin_dir' => $this->configlib->item('skin_helptool'),
            'mobile_skin_dir' => $this->configlib->item('mobile_skin_helptool'),
            'page_title' => $page_title,
        );
        $view['layout'] = $this->layoutlib->front($layoutconfig, $this->configlib->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }
}
