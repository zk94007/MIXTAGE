<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Editorfileupload class
 */

/**
 * 에디터를 통해 파일을 업로드하는 controller 입니다.
 */
class Editorfileupload extends MY_Controller
{

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Editor_image');

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('array');

    function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
    }


    /**
     * 스마트 에디터를 통해 이미지를 업로드하는 컨트롤러입니다.
     */
    public function smarteditor()
    {

		$this->_init();

        $user_id = (int) $this->userlib->item('user_id');

        $upload_path = config_item('uploads_dir') . '/editor/' . cdate('Y') . '/' . cdate('m') . '/';

        if (isset($_FILES)
            && isset($_FILES['files'])
            && isset($_FILES['files']['name'])
            && isset($_FILES['files']['name'][0])) {

            $uploadconfig = array(
                'upload_path' => $upload_path,
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size' => 10 * 1024,
                'encrypt_name' => true,
            );

            $this->upload->initialize($uploadconfig);
            $_FILES['userfile']['name'] = $_FILES['files']['name'][0];
            $_FILES['userfile']['type'] = $_FILES['files']['type'][0];
            $_FILES['userfile']['tmp_name'] = $_FILES['files']['tmp_name'][0];
            $_FILES['userfile']['error'] = $_FILES['files']['error'][0];
            $_FILES['userfile']['size'] = $_FILES['files']['size'][0];

            if ($this->upload->do_upload()) {

                $filedata = $this->upload->data();
                $fileupdate = array(
                    'user_id' => $user_id,
                    'eim_originname' => element('orig_name', $filedata),
                    'eim_filename' => cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata),
                    'eim_filesize' => intval(element('file_size', $filedata) * 1024),
                    'eim_width' => element('image_width', $filedata) ? element('image_width', $filedata) : 0,
                    'eim_height' => element('image_height', $filedata) ? element('image_height', $filedata) : 0,
                    'eim_type' => str_replace('.', '', element('file_ext', $filedata)),
                    'eim_datetime' => cdate('Y-m-d H:i:s'),
                    'eim_ip' => $this->input->ip_address(),
                );
                $image_id = $this->Editor_image_model->insert($fileupdate);

                $image_url = site_url(config_item('uploads_dir') . '/editor/' . cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata));
                $info = new stdClass();
                $info->oriname = element('orig_name', $filedata);
                $info->name = element('file_name', $filedata);
                $info->size = intval(element('file_size', $filedata) * 1024);
                $info->type = 'image/' . str_replace('.', '', element('file_ext', $filedata));
                $info->url = $image_url;
                $info->width = element('image_width', $filedata)
                    ? element('image_width', $filedata) : 0;
                $info->height = element('image_height', $filedata)
                    ? element('image_height', $filedata) : 0;

                $return['files'][0] = $info;

                exit(json_encode($return));

            } else {
                exit($this->upload->display_errors());
            }
        } elseif ($this->input->get('file') && $user_id) {

            $where = array(
                'user_id' => $user_id,
                'eim_filename' => cdate('Y') . '/' . cdate('m') . '/' . $this->input->get('file'),
                'eim_ip' => $this->input->ip_address(),
            );
            $image = $this->Editor_image_model->get_one('', '', $where);
            if (element('eim_filename', $image)) {

                unlink($upload_path . $this->input->get('file'));
                $this->Editor_image_model->delete_where($where);
            }
        }
    }


    /**
     * CK 에디터를 통해 이미지를 업로드하는 컨트롤러입니다.
     */
    public function ckeditor()
    {

        $this->_init();

        $user_id = (int) $this->userlib->item('user_id');

        $upload_path = config_item('uploads_dir') . '/editor/' . cdate('Y') . '/' . cdate('m') . '/';

        $uploadconfig = array(
            'upload_path' => $upload_path,
            'allowed_types' => 'jpg|jpeg|png|gif',
            'max_size' => 10 * 1024,
            'encrypt_name' => true,
        );

        if (isset($_FILES)
            && isset($_FILES['upload'])
            && isset($_FILES['upload']['name'])) {

            $this->upload->initialize($uploadconfig);
            $_FILES['userfile']['name'] = $_FILES['upload']['name'];
            $_FILES['userfile']['type'] = $_FILES['upload']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['upload']['tmp_name'];
            $_FILES['userfile']['error'] = $_FILES['upload']['error'];
            $_FILES['userfile']['size'] = $_FILES['upload']['size'];

            if ($this->upload->do_upload()) {

                $filedata = $this->upload->data();
                $fileupdate = array(
                    'user_id' => $user_id,
                    'eim_originname' => element('orig_name', $filedata),
                    'eim_filename' => cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata),
                    'eim_filesize' => intval(element('file_size', $filedata) * 1024),
                    'eim_width' => element('image_width', $filedata) ? element('image_width', $filedata) : 0,
                    'eim_height' => element('image_height', $filedata) ? element('image_height', $filedata) : 0,
                    'eim_type' => str_replace('.', '', element('file_ext', $filedata)),
                    'eim_datetime' => cdate('Y-m-d H:i:s'),
                    'eim_ip' => $this->input->ip_address(),
                );
                $this->Editor_image_model->insert($fileupdate);
                $image_url = site_url(config_item('uploads_dir') . '/editor/' . cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata));

                echo "<script>window.parent.CKEDITOR.tools.callFunction("
                    . $this->input->get('CKEditorFuncNum', null, '') . ", '"
                    . $image_url . "', '업로드완료');</script>";
            } else {
                echo $this->upload->display_errors();
            }
        }
    }


    public function _init()
    {
        $upload_path = config_item('uploads_dir') . '/editor/';
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
    }
}
