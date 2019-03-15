<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Baisc helper
 */

/**
 * Alert ???
 */
if ( ! function_exists('alert')) {
    function alert($msg = '', $url = '')
    {
        if (empty($msg)) {
            $msg = '??? ?????';
        }
        echo '<meta http-equiv="content-type" content="text/html; charset=' . config_item('charset') . '">';
        echo '<script type="text/javascript">alert("' . $msg . '");';
        if (empty($url)) {
            echo 'history.go(-1);';
        }
        if ($url) {
            echo 'document.location.href="' . $url . '"';
        }
        echo '</script>';
        exit;
    }
}


/**
 * Alert ? ? ??
 */
if ( ! function_exists('alert_close')) {
    function alert_close($msg = '')
    {
        if (empty($msg)) {
            $msg = '??? ?????';
        }
        echo '<meta http-equiv="content-type" content="text/html; charset=' . config_item('charset') . '">';
        echo '<script type="text/javascript"> alert("' . $msg . '"); window.close(); </script>';
        exit;
    }
}


/**
 * Alert ? ??? ???? ? ? ??
 */
if ( ! function_exists('alert_refresh_close')) {
    function alert_refresh_close($msg = '')
    {
        if (empty($msg)) {
            $msg = '??? ?????';
        }
        echo '<meta http-equiv="content-type" content="text/html; charset=' . config_item('charset') . '">';
        echo '<script type="text/javascript"> alert("' . $msg . '"); window.opener.location.reload();window.close(); </script>';
        exit;
    }
}


/**
 * DATE ??? ?? ??
 */
if ( ! function_exists('cdate')) {
    function cdate($date, $timestamp = '')
    {
        defined('TIMESTAMP') or define('TIMESTAMP', time());
        return $timestamp ? date($date, $timestamp) : date($date, TIMESTAMP);
    }
}


/**
 * TIMESTAMP ????
 */
if ( ! function_exists('ctimestamp')) {
    function ctimestamp()
    {
        defined('TIMESTAMP') or define('TIMESTAMP', time());
        return TIMESTAMP;
    }
}


/**
 * ?? ??? ?? ?? ? ?? ???? ????
 */
if ( ! function_exists('seconds2human')) {
    function seconds2human($second = 0)
    {
        $second = (int) $second;
        $s = $second%60;
        $m = floor(($second % 3600)/60);
        $h = floor(($second % 86400)/3600);
        $d = floor($second / 86400);

        $return = '';
        if ($d) {
            $return .= $d . " ? ";
        }
        if ($h) {
            $return .= $h . " ?? ";
        }
        if ($m) {
            $return .= $m . " ? ";
        }
        if ($s) {
            $return .= $s . " ?";
        }
        $return = trim($return);

        return $return;
    }
}


if ( ! function_exists('array_to_keys')) {
    function array_to_keys($array = '')
    {
        $result = array();
        if ( ! is_array($array)) {
            return false;
        }
        foreach ($array as $key) {
            $result[$key] = false;
        }
        return $result;
    }
}


/**
 * ?? select option
 */
if ( ! function_exists('search_option')) {
    function search_option($options = '', $selected = '')
    {
        if (empty($options) OR ! is_array($options)) {
            return false;
        }

        $result = '';
        foreach ($options as $key => $val) {
            $result .= '<option value="' . $key . '" ';
            if ($selected === $key) {
                $result .= ' selected="selected" ';
            }
            $result .= ' >' . $val . '</option>';
        }
        return $result;
    }
}


/**
 * ?????
 */
if ( ! function_exists('cut_str')) {
    function cut_str($str = '', $len = '', $suffix = '?')
    {
        $arr_str = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
        $str_len = count($arr_str);

        if ($str_len >= $len) {
            $slice_str = array_slice($arr_str, 0, $len);
            $str = join('', $slice_str);
            return $str . ($str_len > $len ? $suffix : '');
        } else {
            $str = join('', $arr_str);
            return $str;
        }
    }
}


/**
 * ALERT MESSAGE ? ?? ?? html ?? ??? ????
 */
if ( ! function_exists('show_alert_message')) {
    function show_alert_message($message = '', $html1 = '', $html2 = '')
    {
        if (empty($message)) {
            return false;
        }

        $result = $html1 . $message . $html2;
        return $result;
    }
}


/**
 * ?? ???? ??
 */
if ( ! function_exists('get_skin_name')) {
    function get_skin_name($skin_path = '', $selected_skin = '', $default_text = '', $dir = VIEW_DIR)
    {
        $result = '';

        if ($dir) {
            $dir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
        if ($default_text) {
            $result .= '<option value="">' . $default_text . '</option>';
        }

        $skin_dir = array();
        $dirname = $dir . $skin_path . '/';

        if (is_dir($dir . $skin_path) === false) {
            return;
        }

        $handle = opendir($dirname);
        while ($file = readdir($handle)) {
            if ($file === '.' OR $file === '..') {
                continue;
            }

            if (is_dir($dirname . $file)) {
                $skin_dir[] = $file;
            }
        }
        closedir($handle);
        sort($skin_dir);

        foreach ($skin_dir as $row) {
            $option = $row;
            if (strlen($option) > 10) {
                $option = substr($row, 0, 18) . '?';
            }

            $slt = ($selected_skin === $row) ? 'selected="selected"' : '';
            $result .= '<option value="' . $row . '" ' . $slt . '>' . $option . '</option>';
        }

        return $result;
    }
}


/**
 * ???? ???? ??? ?????
 */
if ( ! function_exists('get_access_selectbox')) {
    function get_access_selectbox($config = '', $useronly = '')
    {
        if (empty($config)) {
            return false;
        }

        $show_level_array = array('3', '4', '5');
        $show_group_array = array('2', '4', '5');

        $result = '';
        $result .= '<select name="' . element('column_name', $config) . '" class="form-control" >';

        if (empty($useronly)) {
            $result .= '<option value=""';
            $result .= (element('column_value', $config) === '') ? 'selected="selected"' : '';
            $result .= '>?? ???</option>';
        }

        $result .= '<option value="1"';
        $result .= element('column_value', $config) === '1' ? 'selected="selected"' : '';
        $result .= '>??? ???</option>';

        $result .= '<option value="100"';
        $result .= element('column_value', $config) === '100' ? 'selected="selected"' : '';
        $result .= '>???</option>';

        $result .= '<option value="2"';
        $result .= element('column_value', $config) === '2' ? 'selected="selected"' : '';
        $result .= '>???????</option>';

        $result .= '<option value="3"';
        $result .= element('column_value', $config) === '3' ? 'selected="selected"' : '';
        $result .= '>????????</option>';

        $result .= '<option value="4"';
        $result .= element('column_value', $config) === '4' ? 'selected="selected"' : '';
        $result .= '>???? OR ????</option>';

        $result .= '<option value="5"';
        $result .= element('column_value', $config) === '5' ? 'selected="selected"' : '';
        $result .= '>???? AND ????</option>';

        $result .= '</select>';

        $result .= '<span id="' . element('column_level_name', $config) . '" style="';
        $result .= in_array(element('column_value', $config), $show_level_array)
            ? 'display:inline;' : 'display:none;';

        $result .= '">';
        $result .= '<select name="'
            . element('column_level_name', $config)
            . '" class="form-control">';

        for ($level = 1; $level <= element('max_level', $config); $level++) {
            $result .= '<option value="' . $level . '" ';
            $result .= (int) element('column_level_value', $config) === (int) $level ? 'selected="selected"' : '';
            $result .= ' >' . $level . '</option>';
        }
        $result .= '</select> ?? ???? </span>';

        $result .= '<div id="' . element('column_group_name', $config) . '" style="';
        $result .= in_array(element('column_value', $config), $show_group_array)
            ? 'display:block;' : 'display:none;';

        $result .= '">';

        $mgroup = element('mgroup', $config);
        $group_value = json_decode(element('column_group_value', $config), true);
        if (element('list', $mgroup)) {
            foreach (element('list', $mgroup) as $key => $value) {
                $result .= '    <label class="checkbox-inline">
                    <input type="checkbox" name="'
                    . element('column_group_name', $config)
                    . '[]" value="' . element('ugr_id', $value) . '" ';
                $result .= is_array($group_value) && in_array(element('ugr_id', $value), $group_value)
                    ? 'checked="checked"' : '';

                $result .= ' /> ' . element('ugr_title', $value) . '</label>';
            }
        }

        $result .= '</div>';
        $result .= '<script type="text/javascript">';
        $result .= '$(function() {
            $(document).on("change", "select[name=' . element('column_name', $config) . ']", function() {';
                $result .= 'if ($(this).val() == "2" || $(this).val() == "4" || $(this).val() == "5") {';
                    $result .= '$("#' . element('column_group_name', $config) . '").css("display", "block");';
                $result .= '} else {';
                    $result .= '$("#' . element('column_group_name', $config) . '").css("display", "none");';
                $result .= '}';
                $result .= 'if ($(this).val() == "3" || $(this).val() == "4" || $(this).val() == "5") {';
                    $result .= '$("#' . element('column_level_name', $config) . '").css("display", "inline");';
                $result .= '} else {';
                    $result .= '$("#' . element('column_level_name', $config) . '").css("display", "none");';
                $result .= '}';

            $result .= '})
        });';
        $result .= '</script>';

        return $result;
    }
}


/**
 * ???? ??? ??? ?????
 */
if ( ! function_exists('required_user_login')) {
    function required_user_login($type = '')
    {
        $CI =& get_instance();
        if ($CI->userlib->is_user() === false) {
            if ($type === 'alert') {
                alert_close('??? ? ??? ?????');
            } else {
                $CI->session->set_flashdata(
                    'message',
                    '??? ? ??? ?????'
                );
                redirect('login?url=' . urlencode(current_full_url()));
            }
        }
        return true;
    }
}


/**
 * ip ? ?? ??? ?? ????
 */
if ( ! function_exists('display_ipaddress')) {
    function display_ipaddress($ip = '', $type = '0001')
    {
        $len = strlen($type);
        if ($len !== 4) {
            return false;
        }
        if (empty($ip)) {
            return false;
        }

        $regex = '';
        $regex .= ($type[0] === '1') ? '\\1' : '&#9825;';
        $regex .= '.';
        $regex .= ($type[1] === '1') ? '\\2' : '&#9825;';
        $regex .= '.';
        $regex .= ($type[2] === '1') ? '\\3' : '&#9825;';
        $regex .= '.';
        $regex .= ($type[3] === '1') ? '\\4' : '&#9825;';

        return preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", $regex, $ip);
    }
}


/**
 * ????? ???? IP ????
 */
if ( ! function_exists('display_admin_ip')) {
    function display_admin_ip($ip = '')
    {
        if (empty($ip)) {
            return false;
        }
        $CI = & get_instance();
        if ($CI->userlib->is_admin() !== 'super') {
            return;
        }

        return $ip;
    }
}


/**
 * ?????? ????? ?? ??
 */
if ( ! function_exists('display_username')) {
    function display_username($userid = '', $name = '', $photo = '', $use_sideview = '')
    {
        $CI = & get_instance();
        $name = $name ? html_escape($name) : 'Non user';
        $title = $userid ? '[' . $userid . ']' : '[User]';

        $result = '';
        if ($CI->configlib->item('use_user_photo') && $photo) {
            $result .= '<img src="'
                . user_photo_url($photo) . '" alt="photo" class="user-icon"
                width="22" height="22" style="width:22px;height:22px;" /> ';
        }

        $result .= $name;

        return $result;
    }
}


/**
 * ???? ??
 */
if ( ! function_exists('is_adult')) {
    function is_adult($birthday = '')
    {
        $birthday = str_replace('-', '', $birthday);
        if (strlen($birthday) !== 8) return false;
        if ( ! is_numeric($birthday)) return false;

        $adult_day = date("Ymd", strtotime("-19 years", ctimestamp()));
        $is_adult = ($birthday < $adult_day) ? true : false;
        
        return $is_adult;
    }
}


/**
 * ?? ?? ????
 */
if ( ! function_exists('user_photo_url')) {
    function user_photo_url($img = '', $width = '', $height = '')
    {
        $CI = & get_instance();
        if (empty($img)) {
            return site_url(config_item('uploads_dir') . '/noimage.gif');
        }
        is_numeric($width) OR $width = $CI->configlib->item('user_photo_width');
        is_numeric($height) OR $height = $CI->configlib->item('user_photo_height');

        return thumb_url('user_photo', $img, $width, $height);
    }
}


/**
 * ?? ??? ????
 */
if ( ! function_exists('user_icon_url')) {
    function user_icon_url($img = '', $width = '', $height = '')
    {
        $CI = & get_instance();
        if (empty($img)) {
            return '';
        }
        is_numeric($width) OR $width = $CI->configlib->item('user_icon_width');
        is_numeric($height) OR $height = $CI->configlib->item('user_icon_height');

        return thumb_url('user_icon', $img, $width, $height);
    }
}



/**
 * ?? ????
 */
if ( ! function_exists('display_html_content')) {
    function display_html_content($content = '', $html = '', $thumb_width=700, $autolink = false, $popup = false, $writer_is_admin = false)
    {
        if (empty($html)) {
            $content = nl2br(html_escape($content));
            if ($autolink) {
                $content = url_auto_link($content, $popup);
            }
            $content = preg_replace(
                "/\[<a\s*href\=\"(http|https|ftp)\:\/\/([^[:space:]]+)\.(gif|png|jpg|jpeg|bmp).*<\/a>(\s\]|\]|)/i",
                "<img src=\"$1://$2.$3\" alt=\"\" style=\"max-width:100%;border:0;\">",
                $content
            );
            $content = preg_replace_callback(
                "/{??\:([^}]*)}/is",
                create_function('$match', '
                     global $thumb_width;
                     return get_google_map($match[1], $thumb_width);
                '),
                $content
            ); // Google Map


            return $content;
        }

        $source = array();
        $target = array();

        $source[] = '//';
        $target[] = '';

        $source[] = "/<\?xml:namespace prefix = o ns = \"urn:schemas-microsoft-com:office:office\" \/>/";
        $target[] = '';

        // ??? ??? ??? ?? ???? ??? ??? ??.
        $table_begin_count = substr_count(strtolower($content), '<table');
        $table_end_count = substr_count(strtolower($content), '</table');
        for ($i = $table_end_count; $i < $table_begin_count; $i++) {
            $content .= '</table>';
        }

        $content = preg_replace($source, $target, $content);

        if ($autolink) {
            $content = url_auto_link($content, $popup);
        }

        if ($writer_is_admin === false) {
            $content = html_purifier($content);
        }

        $content = get_view_thumbnail($content, $thumb_width);

        $content = preg_replace_callback(
            "/{&#51648;&#46020;\:([^}]*)}/is",
            create_function('$match', '
                 global $thumb_width;
                 return get_google_map($match[1], $thumb_width);
            '),
            $content
        ); // Google Map

        return $content;
    }
}


/*
 * http://htmlpurifier.org/
 * Standards-Compliant HTML Filtering
 * Safe : HTML Purifier defeats XSS with an audited whitelist
 * Clean : HTML Purifier ensures standards-compliant output
 * Open : HTML Purifier is open-source and highly customizable
 */
if ( ! function_exists('html_purifier')) {
    function html_purifier($html)
    {
        $CI = & get_instance();

        $white_iframe = $CI->configlib->item('white_iframe');;
        $white_iframe = preg_replace("/[\r|\n|\r\n]+/", ",", $white_iframe);
        $white_iframe = preg_replace("/\s+/", "", $white_iframe);
        if ($white_iframe) {
            $white_iframe = explode(',', trim($white_iframe, ','));
            $white_iframe = array_unique($white_iframe);
        }
        $domains = array();
        if ($white_iframe) {
            foreach ($white_iframe as $domain) {
                $domain = trim($domain);
                if ($domain) {
                    array_push($domains, $domain);
                }
            }
        }
        // ? ???? ??
        array_push($domains, $CI->input->server('HTTP_HOST') . '/');
        $safeiframe = implode('|', $domains);

        if ( ! defined('INC_HTMLPurifier')) {
            include_once(FCPATH . 'plugin/htmlpurifier/HTMLPurifier.standalone.php');
            define('INC_HTMLPurifier', true);
        }
        $config = HTMLPurifier_Config::createDefault();
        // cache ????? CSS, HTML, URI ???? ?? ???.

        $cache_path = config_item('cache_path') ? config_item('cache_path') : APPPATH . 'cache/';

        $config->set('Cache.SerializerPath', $cache_path);
        $config->set('HTML.SafeEmbed', false);
        $config->set('HTML.SafeObject', false);
        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp','%^(https?:)?//(' . $safeiframe . ')%');
        $config->set('Attr.AllowedFrameTargets', array('_blank'));
        $config->set('Core.Encoding', 'utf-8');
        $config->set('Core.EscapeNonASCIICharacters', true);
        $config->set('HTML.MaxImgLength', null);
        $config->set('CSS.MaxImgLength', null);
        $purifier = new HTMLPurifier($config);

        return $purifier->purify($html);
    }
}


/**
 * URL ?? ?? ??
 */
if ( ! function_exists('url_auto_link')) {
    function url_auto_link($str = '', $popup = false)
    {
        if (empty($str)) {
            return false;
        }
        $target = $popup ? 'target="_blank"' : '';
        $str = str_replace(
            array("&lt;", "&gt;", "&amp;", "&quot;", "&nbsp;", "&#039;"),
            array("\t_lt_\t", "\t_gt_\t", "&", "\"", "\t_nbsp_\t", "'"),
            $str
        );
        $str = preg_replace(
            "/([^(href=\"?'?)|(src=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[?-?\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i",
            "\\1<a href=\"\\2\" {$target}>\\2</A>",
            $str
        );
        $str = preg_replace(
            "/(^|[\"'\s(])(www\.[^\"'\s()]+)/i",
            "\\1<a href=\"http://\\2\" {$target}>\\2</A>",
            $str
        );
        $str = preg_replace(
            "/[0-9a-z_-]+@[a-z0-9._-]{4,}/i",
            "<a href=\"mailto:\\0\">\\0</a>",
            $str
        );
        $str = str_replace(
            array("\t_nbsp_\t", "\t_lt_\t", "\t_gt_\t", "'"),
            array("&nbsp;", "&lt;", "&gt;", "&#039;"),
            $str
        );
        return $str;
    }
}


/**
 * syntax highlight
 */
if ( ! function_exists('content_syntaxhighlighter')) {
    function content_syntaxhighlighter($m)
    {
        $str = $m[3];

        if (empty($str)) {
            return;
        }

        $str = str_replace(
            array("<br>", "<br/>", "<br />", "<div>", "</div>", "<p>", "</p>", "&nbsp;"),
            "",
            $str
        );
        $target = array("/</", "/>/", "/\"/", "/\'/");
        $source = array("&lt;", "&gt;", "&#034;", "&#039;");

        $str = preg_replace($target, $source, $str);

        if (empty($str)) {
            return;
        }

        $brush = strtolower(trim($m[2]));
        $brush_arr = array('css', 'js', 'jscript', 'javascript', 'php', 'xml', 'xhtml', 'xslt', 'html');
        $brush = ($brush && in_array($brush, $brush_arr)) ? $brush : 'html';

        return '<pre class="brush: ' . $brush . ';">' . $str . '</pre>' . PHP_EOL;
    }
}


/**
 * syntax highlight
 */
if ( ! function_exists('content_syntaxhighlighter_html')) {
    function content_syntaxhighlighter_html($m)
    {
        $str = $m[3];

        if (empty($str)) {
            return;
        }

        $str = str_replace(
            array("\n\r", "\r"),
            array("\n"),
            $str
        );
        $str = str_replace("\n", "", $str);
        $str = str_replace(
            array("<br>", "<br/>", "<br />", "<div>", "</div>", "<p>", "</p>", "&nbsp;"),
            array("\n", "\n", "\n", "\n", "", "\n", "", "\t"),
            $str
        );
        $target = array("/<span[^>]+>/i", "/<\/span>/i", "/</", "/>/", "/\"/", "/\'/");
        $source = array("", "", "&lt;", "&gt;", "&#034;", "&#039;");

        $str = preg_replace($target, $source, $str);

        if (empty($str)) {
            return;
        }

        $brush = strtolower(trim($m[2]));
        $brush_arr = array('css', 'js', 'jscript', 'javascript', 'php', 'xml', 'xhtml', 'xslt', 'html');
        $brush = ($brush && in_array($brush, $brush_arr)) ? $brush : 'html';

        return '<pre class="brush: ' . $brush . ';">' . $str . '</pre>' . PHP_EOL;
    }
}


if ( ! function_exists('change_key_case')) {
    function change_key_case($str)
    {
        $str = stripcslashes($str);
        preg_match_all('@(?P<attribute>[^\s\'\"]+)\s*=\s*(\'|\")?(?P<value>[^\s\'\"]+)(\'|\")?@i', $str, $match);
        $value = @array_change_key_case(array_combine($match['attribute'], $match['value']));

        return $value;
    }
}


/**
 * Google Map
 */
if ( ! function_exists('get_google_map')) {
    function get_google_map($geo_data = '', $maxwidth = '')
    {
        if (empty($geo_data)) {
            return;
        }

        $maxwidth = (int) $maxwidth;
        if (empty($maxwidth)) {
            $maxwidth = 700;
        }

        $geo_data = stripslashes($geo_data);
        $geo_data = str_replace('&quot;', '', $geo_data);

        if (empty($geo_data)) {
            return;
        }

        $map = array();
        $map = change_key_case($geo_data);

        if (isset($map['loc'])) {
            list($lat, $lng) = explode(',', element('loc', $map));
            $zoom = element('z', $map);
        } else {
            list($lat, $lng, $zoom) = explode(',', element('geo', $map));
        }

        if (empty($lat) OR empty($lng)) {
            return;
        }

        //Map
        $map['geo'] = $lat . ',' . $lng . ',' . $zoom;

        //Marker
        preg_match("/m=\"([^\"]*)\"/is", $geo_data, $marker);
        $map['m'] = element(1, $marker);

        $google_map = '<div style="width:100%; margin:0 auto 15px; max-width:'
            . $maxwidth . 'px;">' . PHP_EOL;
        $google_map .= '<iframe width="100%" height="480" src="'
            . site_url('helptool/googlemap?geo=' . urlencode($map['geo'])
            . '&marker=' . urlencode($map['m']))
            . '" frameborder="0" scrolling="no"></iframe>' . PHP_EOL;
        $google_map .= '</div>' . PHP_EOL;

        return $google_map;
    }
}


/**
 * ????? ??? ??
 */
if ( ! function_exists('get_view_thumbnail')) {
    function get_view_thumbnail($contents = '', $thumb_width= 0)
    {
        if (empty($contents)) {
            return false;
        }

        $CI = & get_instance();

        if (empty($thumb_width)) {
            $thumb_width = 700;
        }

        // $contents ? img ?? ??
        $matches = get_editor_image($contents, true);

        if (empty($matches)) {
            return $contents;
        }

        $end = count(element(1, $matches));
        for ($i = 0; $i < $end; $i++) {

            $img = $matches[1][$i];
            preg_match("/src=[\'\"]?([^>\'\"]+[^>\'\"]+)/i", $img, $m);
            $src = isset($m[1]) ? $m[1] : '';
            preg_match("/style=[\"\']?([^\"\'>]+)/i", $img, $m);
            $style = isset($m[1]) ? $m[1] : '';
            preg_match("/width:\s*(\d+)px/", $style, $m);
            $width = isset($m[1]) ? $m[1] : '';
            preg_match("/height:\s*(\d+)px/", $style, $m);
            $height = isset($m[1]) ? $m[1] : '';
            preg_match("/alt=[\"\']?([^\"\']*)[\"\']?/", $img, $m);
            $alt = isset($m[1]) ? html_escape($m[1]) : '';
            if (empty($width)) {
                preg_match("/width=[\"\']?([^\"\']*)[\"\']?/", $img, $m);
                $width = isset($m[1]) ? html_escape($m[1]) : '';
            }
            if (empty($height)) {
                preg_match("/height=[\"\']?([^\"\']*)[\"\']?/", $img, $m);
                $height = isset($m[1]) ? html_escape($m[1]) : '';
            }

            // ??? path ??
            $p = parse_url($src);
            if (isset($p['host']) && $p['host'] === $CI->input->server('HTTP_HOST')
                && strpos($p['path'], '/' . config_item('uploads_dir') . '/editor/') !== false) {
                $thumb_tag = '<img src="' . thumb_url('editor', str_replace(site_url(config_item('uploads_dir') . '/editor') . '/', '', $src), $thumb_width) . '" ';
            } else {
                $thumb_tag = '<img src="' . $src . '" ';
            }
            if ($width) {
                $thumb_tag .= ' width="' . $width . '" ';
            }
            $thumb_tag .= 'alt="' . $alt . '" style="max-width:100%;"/>';

            $img_tag = $matches[0][$i];
            $contents = str_replace($img_tag, $thumb_tag, $contents);
            if ($width) {
                $thumb_tag .= ' width="' . $width . '" ';
            }
            $thumb_tag .= 'alt="' . $alt . '" style="max-width:100%;"/>';

            $img_tag = $matches[0][$i];
            $contents = str_replace($img_tag, $thumb_tag, $contents);
        }

        return $contents;
    }
}


/**
 * ??? ??? 1? url ??
 */
if ( ! function_exists('get_post_image_url')) {
    function get_post_image_url($contents = '', $thumb_width = '', $thumb_height = '')
    {
        $CI = & get_instance();

        if (empty($contents)) {
            return;
        }

        $matches = get_editor_image($contents);
        if (empty($matches)) {
            return;
        }

        $img = element(0, element(1, $matches));
        if (empty($img)) {
            return;
        }

        preg_match("/src=[\'\"]?([^>\'\"]+[^>\'\"]+)/i", $img, $m);
        $src = isset($m[1]) ? $m[1] : '';

        $p = parse_url($src);
        if (isset($p['host']) && $p['host'] === $CI->input->server('HTTP_HOST')
            && strpos($p['path'], '/' . config_item('uploads_dir') . '/editor/') !== false) {
            $src = thumb_url(
                'editor',
                str_replace(site_url(config_item('uploads_dir') . '/editor') . '/', '', $src),
                $thumb_width,
                $thumb_height
            );
        }
        return $src;
    }
}


/**
 * ??? ??? ??
 */
if ( ! function_exists('get_editor_image')) {
    function get_editor_image($contents = '', $view = true)
    {
        if (empty($contents)) {
            return false;
        }

        // $contents ? img ?? ??
        if ($view) {
            $pattern = "/<img([^>]*)>/iS";
        } else {
            $pattern = "/<img[^>]*src=[\'\"]?([^>\'\"]+[^>\'\"]+)[\'\"]?[^>]*>/i";
        }
        preg_match_all($pattern, $contents, $matchs);

        return $matchs;
    }
}


/**
 * ?? ????
 */
if ( ! function_exists('display_datetime')) {
    function display_datetime($datetime = '', $type = '', $custom = '')
    {
        if (empty($datetime)) {
            return false;
        }

        if ($type === 'sns') {

            $diff = ctimestamp() - strtotime($datetime);

            $s = 60; //1? = 60?
            $h = $s * 60; //1?? = 60?
            $d = $h * 24; //1? = 24??
            $y = $d * 10; //1? = 1? * 10?

            if ($diff < $s) {
                $result = $diff . '??';
            } elseif ($h > $diff && $diff >= $s) {
                $result = round($diff/$s) . '??';
            } elseif ($d > $diff && $diff >= $h) {
                $result = round($diff/$h) . '???';
            } elseif ($y > $diff && $diff >= $d) {
                $result = round($diff/$d) . '??';
            } else {
                if (substr($datetime,0, 10) === cdate('Y-m-d')) {
                    $result = str_replace('-', '.', substr($datetime,11,5));
                } else {
                    $result = substr($datetime, 5, 5);
                }
            }
        } elseif ($type === 'user' && $custom) {
            return cdate($custom, strtotime($datetime));
        } elseif ($type === 'full') {
            if (substr($datetime,0, 10) === cdate('Y-m-d')) {
                $result = substr($datetime,11,5);
            } elseif (substr($datetime,0, 4) === cdate('Y')) {
                $result = substr($datetime,5,11);
            } else {
                $result = substr($datetime,0,10);
            }
        } else {
            if (substr($datetime,0, 10) === cdate('Y-m-d')) {
                $result = substr($datetime,11,5);
            } else {
                $result = substr($datetime,5,5);
            }
        }

        return $result;
    }
}


/**
 * ?? ??? ??
 */
if ( ! function_exists('get_extension')) {
    function get_extension($filename)
    {
        $file = explode('.', basename($filename));
        $count = count($file);
        if ($count > 1) {
            return strtolower($file[$count-1]);
        } else {
            return '';
        }
    }
}


/**
 * get_sock ?? ??
 */
if ( ! function_exists('get_sock')) {
    function get_sock($url)
    {
        // host ? uri ? ??
        $host = '';
        $get = '';

        if (preg_match("/http:\/\/([a-zA-Z0-9_\-\.]+)([^<]*)/", $url, $res)) {
            $host = $res[1];
            $get = $res[2];
        }

        // 80? ??? ???? ??
        $fp = fsockopen ($host, 80, $errno, $errstr, 30);
        if (empty($fp)) {
            die($errstr . ' (' . $errno . ")\n");
        } else {
            fputs($fp, "GET $get HTTP/1.0\r\n");
            fputs($fp, "Host: $host\r\n");
            fputs($fp, "\r\n");

            $header = '';
            // header ? content ? ????.
            while (trim($buffer = fgets($fp,1024)) !== '') {
                $header .= $buffer;
            }
            while ( ! feof($fp)) {
                $buffer .= fgets($fp,1024);
            }
        }
        fclose($fp);

        // content ? return ??.
        return $buffer;
    }
}


/**
 * ??? ?? ??
 */
if ( ! function_exists('get_phone')) {
    function get_phone($phone, $hyphen=1)
    {
        if (is_phone($phone) === false) {
            return '';
        }
        if ($hyphen) {
            $preg = "$1-$2-$3";
        } else {
            $preg = "$1$2$3";
        }

        $phone = str_replace('-', '', trim($phone));
        $phone = preg_replace(
            "/^(01[016789])([0-9]{3,4})([0-9]{4})$/",
            $preg,
            $phone
        );
        return $phone;
    }
}


/**
 * ??? ???? ??
 */
if ( ! function_exists('is_phone')) {
    function is_phone($phone)
    {
        $phone = str_replace('-', '', trim($phone));
        if (preg_match("/^(01[016789])([0-9]{3,4})([0-9]{4})$/", $phone)) {
            return true;
        } else {
            return false;
        }
    }
}


/**
 * json_encode
 */
if ( ! function_exists('json_encode')) {
    function json_encode($data)
    {
        $CI = & get_instance();
        $CI->load->library('Services_json');
        $json = new Services_JSON();
        return($json->encode($data));
    }
}


/**
 * json_decode
 */
if ( ! function_exists('json_decode')) {
    function json_decode($data, $output_mode = false)
    {
        $CI = & get_instance();
        $CI->load->library('Services_json');
        $param = $output_mode ? 16 : null;
        $json = new Services_JSON($param);
        return($json->decode($data));
    }
}


/**
 * ??? ??? ?? ????? ??? ??? ? ??
 */
if ( ! function_exists('admin_listnum')) {
    function admin_listnum()
    {
        $CI = & get_instance();
        if ($CI->input->get('listnum')
            && is_numeric($CI->input->get('listnum'))
            && $CI->input->get('listnum') > 0
            && $CI->input->get('listnum') <= 1000) {

            $listnum = (int) $CI->input->get('listnum');
            $cookie_name = 'admin_listnum';
            $cookie_value = $listnum;
            $cookie_expire = 8640000;
            set_cookie($cookie_name, $cookie_value, $cookie_expire);

        } else {
            $cookienum = (int) get_cookie('admin_listnum');
            $listnum = $cookienum > 0 ? $cookienum : 20;
        }
        return $listnum;
    }
}


/**
 * ??? ??? ?? ????? ??? ??? ? ???? ??? ??
 */
if ( ! function_exists('admin_listnum_selectbox')) {
    function admin_listnum_selectbox()
    {
        $CI = & get_instance();
        if ($CI->input->get('listnum')
            && is_numeric($CI->input->get('listnum'))
            && $CI->input->get('listnum') > 0
            && $CI->input->get('listnum') <= 1000) {
            $listnum = $CI->input->get('listnum');
        } else {
            $listnum = get_cookie('admin_listnum')
                ? get_cookie('admin_listnum') : '20';
        }
        $array = array('10', '15', '20', '25', '30', '40', '50', '60', '70', '100');

        $html = '<select name="listnum" class="form-control" onchange="location.href=\'' . current_url() . '?listnum=\' + this.value;">';
        $html .= '<option value="">Select</option>';

        foreach ($array as $val) {
            $html .= '<option value="' . $val . '" ';
            $html .= ((int) $listnum === (int) $val) ? ' selected="selected" ' : '';
            $html .= ' >' . $val . '</option>';
        }
        $html .= '</select>';

        return $html;
    }
}


/**
 * http://kr1.php.net/manual/en/function.curl-setopt-array.php ??
 */
if ( ! function_exists('curl_setopt_array')) {
    function curl_setopt_array(&$ch, $curl_options)
    {
        foreach ($curl_options as $option => $value) {
            if ( ! curl_setopt($ch, $option, $value)) {
                return false;
            }
        }
        return true;
    }
}


if ( ! function_exists('get_useragent_info')) {
    function get_useragent_info($useragent = '')
    {
        if (empty($useragent)) {
            return false;
        }

        $result = array();

        if ( ! defined('CONSTANT_GET_USERAGENT_INFO')) {
            $CI = & get_instance();
            $CI->load->library(array('phpuseragentstringparser', 'phpuseragent'));
        }
        $userAgent = new phpUserAgent($useragent);
        $result['browsername'] = $userAgent->getBrowserName();
        $result['browserversion'] = $userAgent->getBrowserVersion();
        $result['os'] = $userAgent->getOperatingSystem();
        $result['engine'] = $userAgent->getEngine();

        defined('CONSTANT_GET_USERAGENT_INFO') OR define('CONSTANT_GET_USERAGENT_INFO', 1);

        return $result;
    }
}


/**
 * cache ????? ?? ????? ???? ??? ??
 */
if ( ! function_exists('check_cache_dir')) {
    function check_cache_dir($dir = '')
    {

        $cache_path = config_item('cache_path') ? config_item('cache_path') : APPPATH . 'cache/';
        if ($dir) $cache_path .= '/' . $dir;

        if ( ! is_dir($cache_path) OR ! is_really_writable($cache_path))
        {
            if (mkdir($cache_path , 0755)) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }
}
