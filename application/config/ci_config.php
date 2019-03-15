<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * true 일 경우 관리자페이지의 기본환경설정 > 접근기능 기능을 사용합니다.
 * 만약 사용을 원치 않는 경우는 false 로 변경해주시면 됩니다.
 */
$config['use_lock_ip'] = true;



/**
 * 게시판에서 첨부파일 등을 통해 올리는 이미지의 경로를 결정합니다.
 * 기본적으로 uploads 로 되어있고, 만약 이 값을 변경하기 원하시면 배포된 파일의 uploads 디렉토리도 똑같은 이름으로 변경해주셔야 합니다.
 * 또한 assets/js/captcha.js 에서 uploads 로 된 값을 원하시는 값으로 변경해주시면 됩니다.
 * 사이트를 운영 중에 이 값을 변경하시면 사이트에 이미 올라간 이미지의 경로가 깨집니다. 디비에 저장된 경로를 모두 변경해주셔야 합니다.
 * 따라서 사이트를 운영 중에는 이 값을 변경하지 않으시길 권장드립니다.
 */
$config['uploads_dir'] = 'uploads';


/**
 * install 되었는지를 매번 체크하고 install 되어있지 않으면 install page 로 이동합니다.
 * 사이트 install 한 후에는 값을 false 로 변경해주세요.
 * 설치를 진행하기 위해서는 현재 접속하고 계신 remote_addr 를 입력하셔야 합니다.
 * 설치가 끝난 후에는 다시 빈 값으로 변경해주시고, 바로 위에 chk_installed 의 값을 false 로 변경해주시면 매번 install 되었는지를 체크하지 않으므로 속도 향상에 도움이 됩니다.
 */
$config['chk_installed'] = true;
$config['install_ip'] = '121.135.144.25'; // 여기에 all 이라고 적으면, 모든 IP 에서 접근 가능합니다, 간혹 localhost 에 설치할 때에 ip 체크를 제대로 하지 못한 경우를 대비합니다.


/**
 * profiler 를 활성화할지 결정합니다.
 * 사이트를 개발시에는 profiler 를 활성화하여놓고 개발하시면 각페이지에서 profiler 를 확인할 수 있습니다.
 * profiler 활성화 선언은 application/core/MY_Controller.php 의 __construct() 함수에 있습니다,
 */
$config['enable_profiler'] = false;


/**
 * user agent parser 를 선택합니다
 */
$config['user_agent_parser'] = 'phpuseragent';  // phpuseragent 중에 선택


/**
 * smpt email 을 사용하시는 경우 세팅해주세요
 */
$config['email_protocal'] = 'smtp'; // mail/sendmail/smtp
$config['email_smtp_host'] = 'smtp.naver.com';
$config['email_smtp_user'] = 'developpark@naver.com';
$config['email_smtp_pass'] = 'Tjsdud994*()';
$config['email_smtp_port'] = '465';
$config['email_smtp_crypto'] = 'ssl'; // SMTP Encryption. Can be null, tls or ssl.


/**
 * 캐시 기능 사용시, 우선순위를 결정합니다
 */
$config['cache_method'] = array('adapter' => 'file', 'backup' => 'file');
