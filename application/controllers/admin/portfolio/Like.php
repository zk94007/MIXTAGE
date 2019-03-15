<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Like class
 */

/**
 * 관리자>포트폴리오>Like controller 입니다.
 */
class Like extends MY_Controller
{

    /**
     * 관리자 페이지 상의 현재 디렉토리입니다
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = 'portfolio/like';

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Portfolio_like');

    /**
     * 이 컨트롤러의 메인 모델 이름입니다
     */
    protected $modelname = 'Portfolio_like_model';

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
     * 목록을 가져오는 메소드입니다
     */
    public function index()
    {

        $view = array();
        $view['view'] = array();


        /**
         * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
         */
        $param =& $this->querystring;
        $page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
        $findex = $this->input->get('findex') ? $this->input->get('findex') : $this->{$this->modelname}->primary_key;
        $forder = $this->input->get('forder', null, 'desc');
        $sfield = $this->input->get('sfield', null, '');
        $skeyword = $this->input->get('skeyword', null, '');


        $per_page = admin_listnum();
        $offset = ($page - 1) * $per_page;

        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */
        $this->{$this->modelname}->allow_search_field = array('like_id', 'target_user_id', 'portfolio_like.user_id', 'por_content', 'portfolio_like.por_id'); // 검색이 가능한 필드
        $this->{$this->modelname}->search_field_equal = array('like_id', 'portfolio_like.por_id'); // 검색중 like 가 아닌 = 검색을 하는 필드
        $this->{$this->modelname}->allow_order_field = array('like_id'); // 정렬이 가능한 필드

        $where = array();
        $result = $this->{$this->modelname}
            ->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
        $list_num = $result['total_rows'] - ($page - 1) * $per_page;
        if (element('list', $result)) {
            foreach (element('list', $result) as $key => $val) {
                $result['list'][$key]['display_name'] = display_username(
                    element('user_userid', $val),
                    element('user_username', $val),
                    element('user_photo', $val)
                );
                $select = 'user_id, user_userid, user_username, user_photo';
                $result['list'][$key]['target_user'] = $target_user = $this->User_model->get_by_id(element('target_user_id', $val), $select);
                $result['list'][$key]['target_display_name'] = display_username(
                    element('user_userid', $target_user),
                    element('user_username', $target_user),
                    element('user_photo', $target_user)
                );
                $result['list'][$key]['num'] = $list_num--;
            }
        }

        $view['view']['data'] = $result;

        /**
         * primary key 정보를 저장합니다
         */
        $view['view']['primary_key'] = $this->{$this->modelname}->primary_key;

          

        /**
         * 페이지네이션을 생성합니다
         */
        $config['base_url'] = admin_url($this->pagedir) . '?' . $param->replace('page');
        $config['total_rows'] = $result['total_rows'];
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $view['view']['paging'] = $this->pagination->create_links();
        $view['view']['page'] = $page;

        /**
         * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
         */
        $search_option = array('like_id' => 'Like ID');
        $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        $view['view']['search_option'] = search_option($search_option, $sfield);
        $view['view']['listall_url'] = admin_url($this->pagedir);
        $view['view']['list_delete_url'] = admin_url($this->pagedir . '/listdelete/?' . $param->output());


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
     * 라이크현황을 그래프 형식으로 보는 페이지입니다
     */
    public function graph($export = '')
    {

        $view = array();
        $view['view'] = array();


        $param =& $this->querystring;
        $datetype = $this->input->get('datetype', null, 'd');
        if ($datetype !== 'm' && $datetype !== 'y') {
            $datetype = 'd';
        }
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : cdate('Y-m-01');
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : cdate('Y-m-d');
        if ($datetype === 'y' OR $datetype === 'm') {
            $start_year = substr($start_date, 0, 4);
            $end_year = substr($end_date, 0, 4);
        }
        if ($datetype === 'm') {
            $start_month = substr($start_date, 5, 2);
            $end_month = substr($end_date, 5, 2);
            $start_year_month = $start_year * 12 + $start_month;
            $end_year_month = $end_year * 12 + $end_month;
        }
        $orderby = (strtolower($this->input->get('orderby')) === 'desc') ? 'desc' : 'asc';

        $result = $this->{$this->modelname}->get_like_count($datetype, $start_date, $end_date, $orderby);
        $sum_count = 0;
        $arr = array();
        $max = 0;

        if ($result && is_array($result)) {
            foreach ($result as $key => $value) {
                $s = element('day', $value);
                if ( ! isset($arr[$s])) {
                    $arr[$s] = 0;
                }
                $arr[$s] += element('cnt', $value);

                if ($arr[$s] > $max) {
                    $max = $arr[$s];
                }
                $sum_count += element('cnt', $value);
            }
        }

        $result = array();
        $i = 0;
        $save_count = -1;
        $tot_count = 0;

        if (count($arr)) {
            foreach ($arr as $key => $value) {
                $count = (int) $arr[$key];
                $result[$key]['count'] = $count;
                $i++;
                if ($save_count !== $count) {
                    $no = $i;
                    $save_count = $count;
                }
                $result[$key]['no'] = $no;

                $result[$key]['key'] = $key;
                $rate = ($count / $sum_count * 100);
                $result[$key]['rate'] = $rate;
                $s_rate = number_format($rate, 1);
                $result[$key]['s_rate'] = $s_rate;

                $bar = (int) ($count / $max * 100);
                $result[$key]['bar'] = $bar;
            }
            $view['view']['max_value'] = $max;
            $view['view']['sum_count'] = $sum_count;
        }

        if ($datetype === 'y') {
            for ($i = $start_year; $i <= $end_year; $i++) {
                if( ! isset($result[$i])) $result[$i] = '';
            }
        } elseif ($datetype === 'm') {
            for ($i = $start_year_month; $i <= $end_year_month; $i++) {
                $year = floor($i / 12);
                if ($year * 12 == $i) $year--;
                $month = sprintf("%02d", ($i - ($year * 12)));
                $date = $year . '-' . $month;
                if( ! isset($result[$date])) $result[$date] = '';
            }
        } elseif ($datetype === 'd') {
            $date = $start_date;
            while ($date <= $end_date) {
                if( ! isset($result[$date])) $result[$date] = '';
                $date = cdate('Y-m-d', strtotime($date) + 86400);
            }
        }

        if ($orderby === 'desc') {
            krsort($result);
        } else {
            ksort($result);
        }

        $view['view']['list'] = $result;

        $view['view']['start_date'] = $start_date;
        $view['view']['end_date'] = $end_date;
        $view['view']['datetype'] = $datetype;

        if ($export === 'excel') {
            
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=Like_' . cdate('Y_m_d') . '.xls');
            echo $this->load->view('admin/' . ADMIN_SKIN . '/' . $this->pagedir . '/graph_excel', $view, true);

        } else {
            /**
             * 어드민 레이아웃을 정의합니다
             */
            $layoutconfig = array('layout' => 'layout', 'skin' => 'graph');
            $view['layout'] = $this->layoutlib->admin($layoutconfig, $this->configlib->get_device_view_type());
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view));
            $this->view = element('view_skin_file', element('layout', $view));
        }
    }

    /**
     * 목록 페이지에서 선택삭제를 하는 경우 실행되는 메소드입니다
     */
    public function listdelete()
    {

        /**
         * 체크한 게시물의 삭제를 실행합니다
         */
        if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
            foreach ($this->input->post('chk') as $val) {
                if ($val) {
                    $getdata = $this->{$this->modelname}->get_one($val);

                    $this->{$this->modelname}->delete($val);

                    $where = array(
                        'por_id' => element('por_id', $getdata),
                    );
                    $like_cnt = $this->{$this->modelname}->count_by($where);

					$updatedata = array(
						'por_like' => $like_cnt,
					);
					$this->Portfolio_model->update(element('por_id', $getdata), $updatedata);
                }
            }
        }


        /**
         * 삭제가 끝난 후 목록페이지로 이동합니다
         */
        $this->session->set_flashdata(
            'message',
            '정상적으로 삭제되었습니다'
        );
        $param =& $this->querystring;
        $redirecturl = admin_url($this->pagedir . '?' . $param->output());

        redirect($redirecturl);
    }
}
