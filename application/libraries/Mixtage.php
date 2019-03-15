<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mixtage
 */


class Mixtage extends CI_Controller
{

    private $CI;

    function __construct()
    {
        $this->CI = & get_instance();
    }


    /**
     * delete_portfolio 의 정보를 얻습니다
     */
    public function delete_portfolio($por_id = 0)
    {
        if (empty($por_id)) {
            return false;
        }

		$this->CI->load->model(array('Portfolio_model', 'Portfolio_blame_model', 'Portfolio_clip_model', 'Portfolio_comment_model', 'Portfolio_comment_blame_model', 'Portfolio_file_model', 'Portfolio_like_model', 'Portfolio_tag_model'));
		$portfolio = $this->CI->Portfolio_model->get_one($por_id);
		if ( ! element('por_id', $portfolio)) return;


		$this->CI->Portfolio_model->delete($por_id);
		$this->CI->Portfolio_blame_model->delete('', array('por_id' =>$por_id));
		$this->CI->Portfolio_clip_model->delete('', array('por_id' =>$por_id));
		$this->CI->Portfolio_comment_model->delete('', array('por_id' =>$por_id));
		$this->CI->Portfolio_comment_blame_model->delete('', array('por_id' =>$por_id));
		$this->CI->Portfolio_file_model->delete('', array('por_id' =>$por_id));
		$this->CI->Portfolio_like_model->delete('', array('por_id' =>$por_id));
		$this->CI->Portfolio_tag_model->delete('', array('por_id' =>$por_id));

		return true;

	}



    /**
     * delete_portfolio_comment 의 정보를 얻습니다
     */
    public function delete_portfolio_comment($pco_id = 0)
    {
        if (empty($pco_id)) {
            return false;
        }

		$this->CI->load->model(array('Portfolio_model', 'Portfolio_comment_model', 'Portfolio_comment_blame_model'));
		$comment = $this->CI->Portfolio_comment_model->get_one($pco_id);
		if ( ! element('pco_id', $comment)) return;

		$this->CI->Portfolio_comment_model->delete($pco_id);
		$this->CI->Portfolio_comment_blame_model->delete('', array('pco_id' =>$pco_id));

		$where = array(
			'por_id' => element('por_id', $comment),
		);
		$comment_cnt = $this->CI->Portfolio_model->count_by($where);

		$mentor_comment_count = $this->CI->Portfolio_comment_model->count_mentor_comment(element('por_id', $comment));


		$updatedata = array(
			'por_comment' => $comment_cnt,
			'por_mentor_comment' => $mentor_comment_count,
		);
		$this->CI->Portfolio_model->update(element('por_id', $comment), $updatedata);

		return true;

	}

	public function delete_collaboration($col_id = 0)
	{
        if (empty($col_id)) {
            return false;
        }

		$this->CI->load->model(array('Portfolio_model', 'Collaboration_model', 'Collaboration_user_model'));
		$collaboration = $this->CI->Collaboration_model->get_one($pco_id);
		if ( ! element('col_id', $collaboration)) return;

		$this->CI->Collaboration_model->delete($col_id);
		$this->CI->Collaboration_user_model->delete('', array('col_id' =>$col_id));

		$updatedata = array(
			'col_id' => '0'	
		);
		$where = array(
			'col_id' => $col_id,	
		);
		$this->CI->Portfolio_model->update('', $updatedata, $where);

		return true;
	
	}

	public function delete_user($user_id = 0)
	{
        if (empty($user_id)) {
            return false;
        }

		$updatedata = array(
			'user_level' => '1',
			'user_denied' => '1',
			'user_is_admin' => '0',
			'user_token' => '',
		);
		$this->CI->User_model->update($user_id, $updatedata);

		return true;
	
	}

	public function noti($user_id = 0, $target_user_id = 0, $not_type = '', $not_content_id = '', $por_id = '0')
	{
        $this->CI->load->model( array('Noti_model'));

        $user_id = (int) $user_id;
        $target_user_id = (int) $target_user_id;

        if (empty($user_id) OR $user_id < 1) {
            $result = json_encode( array('error' => 'user_id 가 존재하지 않습니다'));
            return $result;
        }
        if (empty($not_type)) {
            $result = json_encode( array('error' => 'not_type 가 존재하지 않습니다'));
            return $result;
        }
        if (empty($not_content_id)) {
            $result = json_encode( array('error' => 'not_content_id 가 존재하지 않습니다'));
            return $result;
        }
        if ($user_id === $target_user_id) {
            $result = json_encode( array('error' => 'user_id 와 target_user_id 이 같으므로 알림을 저장하지 않습니다'));
            return $result;
        }

        $insertdata = array(
            'user_id' => $user_id,
            'target_user_id' => $target_user_id,
            'not_type' => $not_type,
            'not_content_id' => $not_content_id,
            'por_id' => $por_id,
            'not_datetime' => cdate('Y-m-d H:i:s'),
        );
        $not_id = $this->CI->Noti_model->insert($insertdata);

        $result = json_encode( array('success' => '알림이 저장되었습니다'));

        return $result;
	}

	public function deletenoti($user_id = 0, $target_user_id = 0, $not_type = '', $not_content_id = '')
	{
        $this->CI->load->model( array('Noti_model'));

        $user_id = (int) $user_id;
        $target_user_id = (int) $target_user_id;

        if (empty($user_id) OR $user_id < 1) {
            $result = json_encode( array('error' => 'user_id 가 존재하지 않습니다'));
            return $result;
        }
        if (empty($not_type)) {
            $result = json_encode( array('error' => 'not_type 가 존재하지 않습니다'));
            return $result;
        }
        if (empty($not_content_id)) {
            $result = json_encode( array('error' => 'not_content_id 가 존재하지 않습니다'));
            return $result;
        }
        if ($user_id === $target_user_id) {
            $result = json_encode( array('error' => 'user_id 와 target_user_id 이 같으므로 알림을 저장하지 않습니다'));
            return $result;
        }

        $where = array(
            'user_id' => $user_id,
            'target_user_id' => $target_user_id,
            'not_type' => $not_type,
            'not_content_id' => $not_content_id,
        );
        $this->CI->Noti_model->delete('', $where);

        $result = json_encode( array('success' => '알림이 삭제되었습니다.'));

        return $result;
	}
}
