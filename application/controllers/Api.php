<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Api class
 */

/**
 * Api controller.
 */
class Api extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

    		/**
    		 * Load Models
    		 */
    		$this->load->model(array('Api_error_log_model', 'Api_input_model', 'Api_list_model', 'Api_output_model'));

        /**
         * Load Helpers
         */
        $this->load->helper(array('array'));

        /**
         * Load API Libraries
         */
        $this->load->library(array('apilib'));
    }


    /**
     * API  Function.
     */
    function run($libraryname = '')
    {
		if ( ! $libraryname) show_404();

		$getdata = $this->Api_list_model->get_one('', '', array('api_name' => $libraryname));

		if ( ! element('api_idx', $getdata)) show_404();
		if ( ! element('api_use', $getdata)) show_404();

		$this->load->library('apis/' . $libraryname);
		if ( ! class_exists($libraryname))
		{
			show_404();
		}

		if ($this->input->method() == 'post') {
			$return_type = $this->input->post('return_type');
		} else {
			$return_type = $this->input->get('return_type');
		}
		if ( $return_type != 'json') $return_type = 'xml';
		echo $this->apilib->callapi($getdata, $return_type);

	}
}
