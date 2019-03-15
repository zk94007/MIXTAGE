<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *--------------------------------------------------------------------------
 *Admin Page 에 보일 메뉴를 정의합니다.
 *--------------------------------------------------------------------------
 *
 * Admin Page 에 새로운 메뉴 추가시 이곳에서 수정해주시면 됩니다.
 *
 */


$config['admin_page_menu'] = array(
	'portfolio'                                     => array(
		'__config'                         => array('portfolio', 'fa-book'),
		'menu'                             => array(
			'portfolio'                    => array('portfolio', ''),
			'like'                         => array('Like', ''),
			'clip'                         => array('Clip', ''),
			'comment'                      => array('Comment', ''),
		),
	),
	'collaboration'                                     => array(
		'__config'                           => array('collaboration', 'fa-pie-chart'),
		'menu'                                => array(
			'collaboration'                      => array('collaboration', ''),
			'request'                                => array('Requested content', ''),
		),
	),
	'user'                               => array(
		'__config'                           => array('User setting', 'fa-users'),
		'menu'                                => array(
			'users'							=> array('User management', ''),
			'userfollow'					=> array('Follow', ''),
			'recommend'                      => array('Recommended artist in this month', ''),
			'wantartist'					=> array('Mixtage artist support', ''),
		),
	),
	'page'                                     => array(
		'__config'                           => array('General page', 'fa-laptop'),
		'menu'                                => array(
			'faqgroup'                      => array('FAQ management', ''),
			'faq'                                => array('FAQ content', '', 'hide'),
			'generalrequest'                  => array('Problem blame', ''),
			'seminar'                  => array('Seminar management', ''),
		),
	),
	'blame'                                     => array(
		'__config'                           => array('blame', 'fa-exclamation-triangle'),
		'menu'                                => array(
			'users'							=> array('User blame', ''),
			'collaboration'                    => array('Collaboration blame', ''),
			'portfolio'                        => array('Portfolio blame', ''),
			'comment'                          => array('Comment blame', ''),
		),
	),
	'config'                                   => array(
		'__config'                           => array('setting', 'fa-gears'), // 1차 메뉴, 순서대로 (메뉴명, 아이콘클래스(font-awesome))
		'menu'                                => array(
			'configlibs'                     => array('Main setting', ''), // 2차 메뉴, 순서대로 (메뉴명, a태그에 속성 부여)
			//'layoutskin'                    => array('레이아웃/메타태그', ''),
			'userconfig'					=> array('User log in setting', ''),
			//'dbupgrade'                   => array('DB 업그레이드', ''),
		),
	),
	'apis'                                 => array(
		'__config'                           => array('API management', 'fa-cloud'),
		'menu'                                => array(
			'apimanage'					=> array('API management', ''),
			'apidocument'               => array('API documentation', 'target="_blank"'),
		),
	),
);
