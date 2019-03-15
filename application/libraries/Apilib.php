<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Apilib
 */


class Apilib extends CI_Controller
{

    private $CI;

	public $returntype;

    function __construct()
    {
        $this->CI = & get_instance();

    }

	function callapi($data ='', $returntype='xml')
	{

		$libraryname = element('api_name', $data);
		$this->returntype = $returntype;
		
		if (strtolower(element('api_method', $data)) == 'get' && strtolower($this->CI->input->method()) != 'get')
		{
			$this->CI->apilib->make_error("GET 방식으로 넘어오지 않았습니다");
		}
		if (strtolower(element('api_method', $data)) == 'post' && strtolower($this->CI->input->method()) != 'post')
		{
			$this->CI->apilib->make_error("POST 방식으로 넘어오지 않았습니다");
		}

		if ($returntype == 'xml') {
			$result = $this->xml($libraryname);
		}
		else if ($returntype == 'json') {
			$result = $this->json($libraryname);
		}
		else if ($returntype == 'json2') {
			$result = $this->json2($libraryname);
		}
		return $result;
	}


	function array2XML($arr, $root)
	{
		$xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\" ?><{$root}></{$root}>");
		if(is_array($arr)){
			$this->array2XML2($arr, $xml);
		}
		return $xml->asXML();
	}

	function array2XML2($arr, $obj)
	{
		if(is_array($arr)){
			foreach($arr as $k => $v){
				if(is_array($v)){
					if(is_numeric($k)){
						${$k} = $obj->addChild("data");
						$this->array2XML2($v, ${$k});
					}else{
						${$k} = $obj->addChild($k);
						$this->array2XML2($v, ${$k});
					}
				}else{
					$node = $obj->addChild($k);
					$node = dom_import_simplexml($node); 
					$no = $node->ownerDocument; 
					$node->appendChild($no->createCDATASection($v));
				}
			}
		}
	}

	function urlencode_data($arg)
	{
		if(is_array($arg)){
			foreach($arg as $k => $v){
				$arg[$k] = $this->urlencode_data($v);
			}
		}else{
			if(gettype($arg) == "string")
				$arg = urlencode($arg);
			if(is_numeric($arg))
				$arg = (float)$arg;
			if(is_int($arg))
				$arg = (int)$arg;
		}

		return $arg;
	}

	function make_error($result_text)
	{
		$arr = array();
		$arr['result'] = "error";
		$arr['result_text'] = $result_text;

		$this->make_error_log($result_text);

		$returntype = 'show_' . $this->returntype;
		echo $this->$returntype($arr);
		exit;
	}

	function make_error_log($el_result)
	{
		$strlen = strlen(admin_url());
		if(substr($this->CI->input->SERVER('PHP_SELF'), 0, $strlen) == admin_url()) {
			return;
		}
 
		$vars = ($this->CI->input->method() == "post") ? $_POST : $_GET;
		$el_vars_arr = array();
		foreach($vars as $k => $v){
			$el_vars_arr[] = $k . '=' . $v;
		}

		$el_vars = implode("&", $el_vars_arr);
		$this->CI->load->model('Api_error_log_model');
		$insertdata = array(
			'el_ip' => $this->CI->input->ip_address(),
			'el_result' => $el_result,
			'el_self' => $this->CI->input->SERVER('PHP_SELF'),
			'el_vars' => $el_vars,
			'el_regdate' => cdate('Y-m-d H:i:s'),
		);
		$this->CI->Api_error_log_model->insert($insertdata);
	}

	function xml($libraryname)
	{
		header("Content-Type: text/xml;charset=utf-8");
		$this->CI->load->library('apis/' . $libraryname);
		$result = $this->CI->{$libraryname}->main();
		return $this->array2XML($result, 'info');
	}

	function json($libraryname)
	{
		header("Content-Type: text/html;charset=utf-8");
		$this->CI->load->library('apis/' . $libraryname);
		$result = $this->CI->{$libraryname}->main();
		// $result = $this->urlencode_data($result);
		return json_encode($result);
	}

	function json2($libraryname)
	{
		header("Content-Type: text/html;charset=utf-8");
		$this->CI->load->library('apis/' . $libraryname);
		$result = $this->CI->{$libraryname}->main();
		$result = $this->urlencode_data($result);
		return urldecode(json_encode($result));
	}

	function show_xml($arr)
	{
		header("Content-Type: text/xml;charset=utf-8");
		return $this->array2XML($arr, 'info');
	}

	function show_json($arr)
	{
		header("Content-Type: text/html;charset=utf-8");
		// $result = $this->urlencode_data($arr);
		//return json_encode($result);
    return json_encode($arr);
	}

	function show_json2($arr)
	{
		header("Content-Type: text/html;charset=utf-8");
		$result = $this->urlencode_data($arr);
		return urldecode(json_encode($result));
	}
}

