<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Userappi class
 */

/**
 * 회원정보를 저장하는 클래스입니다.
 */
class Userapi extends MY_Controller
{

    protected $available_keys;

    public function __construct()
    {

        parent::__construct();

        $this->available_keys = [
            'admin-skin',
            'admin-layout-fixed',
            'admin-layout-layout-boxed',
            'admin-layout-sidebar-collapse',
            'admin-layout-control-sidebar-open',
            'admin-layout-sidebarskin',
        ];

    }

    /**
     * get user info
     */
    public function getuserinfo($key = '')
    {

        /**
         * only login user can access
         */
        if (!$this->userlib->is_user()) {
            return;
        }

        if ($this->input->method() != 'post') {
            return;
        }

        if (!in_array($key, $this->available_keys)) {
            return;
        }

        if ($value = $this->userlib->item($key)) {
            echo $value;
        }

    }

    /**
     * set user info
     */
    public function setuserinfo($key = '', $value = '')
    {

        /**
         * only login user can access
         */
        if (!$this->userlib->is_user()) {
            return;
        }

        if ($this->input->method() != 'post') {
            return;
        }

        if (!$key) {
            return;
        }

        if (!in_array($key, $this->available_keys)) {
            return;
        }

        $savedata = [$key => $value];
        $this->User_meta_model->save($this->userlib->item('user_id'), $savedata);

        if ($value = $this->userlib->item($key)) {
            return $value;
        }

        return;

    }
}
