<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dhtml Editor helper
 */

if ( ! function_exists('display_dhtml_editor')) {
    // Dhtml Editor 띄우기
    function display_dhtml_editor($name = '', $content = '', $classname = '', $is_dhtml_editor = true, $editor_type = 'smarteditor')
    {
        $editorclassname = '';
        $style = '';
        if ($editor_type === 'smarteditor' && $is_dhtml_editor) {
            $editor_url = site_url('plugin/editor/smarteditor');
            $editorclassname = 'smarteditor';
            $style = 'style="width:98%;"';
        }
        if ($editor_type === 'ckeditor' && $is_dhtml_editor) {
            $editor_url = site_url('plugin/editor/ckeditor');
            $editorclassname = 'ckeditor';
            $style = 'style="width:98%;"';
        }

        $html = '';

        if ($editor_type === 'smarteditor' && $is_dhtml_editor
            && ! defined('LOAD_DHTML_EDITOR_JS')) {

            $html .= "\n" . '<script src="' . $editor_url . '/js/HuskyEZCreator.js"></script>';
            $html .= "\n" . '<script type="text/javascript">var editor_url = "' . $editor_url . '", oEditors = [];</script>';
            $html .= "\n" . '<script src="' . $editor_url . '/editor_config.js"></script>';
            define('LOAD_DHTML_EDITOR_JS', true);

        }
        if ($editor_type === 'ckeditor' && $is_dhtml_editor
            && ! defined('LOAD_DHTML_EDITOR_JS')) {

            $html .= "\n" . '<script src="' . $editor_url . '/ckeditor.js"></script>';
            $html .= "\n" . '<script type="text/javascript">var editor_url = "' . $editor_url . '";</script>';
            $html .= "\n" . '<script src="' . $editor_url . '/config.js"></script>';
            define('LOAD_DHTML_EDITOR_JS', true);
        }
        $html .= "\n<textarea id=\"" . $name . "\" name=\"" . $name . "\" class=\"" . $editorclassname . ' ' . $classname . "\" " . $style . ">" . $content . "</textarea>";

        return $html;
    }
}
