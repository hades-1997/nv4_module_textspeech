<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DacLoi.,JSC <saka.dacloi@gmail.com>
 * @Copyright (C) 2022 DacLoi.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 09 May 2022 08:02:27 GMT
 */

if (!defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

$page_title = $lang_module['main'];
$stype = $nv_Request->get_string('stype', 'get', '-');
$sstatus = $nv_Request->get_int('sstatus', 'get', -1);
$catid = $nv_Request->get_int('catid', 'get', 0);
$per_page_old = $nv_Request->get_int('per_page', 'cookie', 50);
$per_page = $nv_Request->get_int('per_page', 'get', $per_page_old);
$num_items = $nv_Request->get_int('num_items', 'get', 0);
$order = $nv_Request->get_string('order', 'get') == 'asc' ? 'asc' : 'desc';


if ($per_page < 1 or $per_page > 500) {
    $per_page = 50;
}
if ($per_page_old != $per_page) {
    $nv_Request->set_Cookie('per_page', $per_page, NV_LIVE_COOKIE_TIME);
}

if ($catid == 0) {
    $from = NV_PREFIXLANG . '_news_rows r';
} else {
    $from = NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' r';
}
$where = '';
$page = $nv_Request->get_int('page', 'get', 1);
$checkss = $nv_Request->get_string('checkss', 'get', '');
$check_declined = false;
$ordername = 'id';

//------------------------------
// Viết code xử lý chung vào đây
//------------------------------
    $link_i = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=Other';
    $global_array_cat[0] = [
        'catid' => 0,
        'parentid' => 0,
        'title' => 'Other',
        'alias' => 'Other',
        'link' => $link_i,
        'viewcat' => 'viewcat_page_new',
        'subcatid' => 0,
        'numlinks' => 3,
        'description' => '',
        'keywords' => ''
    ];

    $db_slave->sqlreset()
        ->select('COUNT(*)')
        ->from($from)
        ->where($where);
    $_sql = $db_slave->sql();
	
    $num_checkss = md5($num_items . NV_CHECK_SESSION . $_sql);
	
    if ($num_checkss != $nv_Request->get_string('num_checkss', 'get', '')) {
        $num_items = $db_slave->query($_sql)->fetchColumn();
        $num_checkss = md5($num_items . NV_CHECK_SESSION . $_sql);
    }
	
	$base_url_mod = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;per_page=' . $per_page;

	if ($catid) {
        $base_url_mod .= '&amp;catid=' . $catid;
    }
    if (!empty($q)) {
        $base_url_mod .= '&amp;q=' . $q . '&amp;checkss=' . $checkss;
    }
    $base_url_mod .= '&amp;stype=' . $stype . '&amp;num_items=' . $num_items . '&amp;num_checkss=' . $num_checkss;
	

		
	$db_slave->select('r.id, r.catid, r.listcatid, r.admin_id, r.title, r.alias, r.status, r.weight, r.publtime, r.exptime, r.hitstotal, r.hitscm, r.admin_id, r.author')
        ->order('r.id ' . $order)
		->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $result = $db_slave->query($db_slave->sql());
	
    $data = $array_ids = $array_userid = [];
	//$data = array_merge($data, $body_contents);
//	var_dump($data);die;
	//unset($body_contents);
    while (list($id, $catid_i, $listcatid, $post_id, $title, $alias, $status, $weight, $publtime, $exptime, $hitstotal, $hitscm, $_userid, $author) = $result->fetch(3)) {
        $publtime = nv_date('H:i d/m/y', $publtime);

        if ($catid > 0) {
            $catid_i = $catid;
        }

        $check_permission_edit = $check_permission_delete = false;

        if (defined('NV_IS_ADMIN_MODULE')) {
            $check_permission_edit = $check_permission_delete = true;
        } else {
            $array_temp = explode(',', $listcatid);
            $check_edit = $check_del = 0;

            foreach ($array_temp as $catid_i) {
                if (isset($array_cat_admin[$admin_id][$catid_i])) {
                    if ($array_cat_admin[$admin_id][$catid_i]['admin'] == 1) {
                        ++$check_edit;
                        ++$check_del;
                        $_permission_action['publtime'] = true;
                        $_permission_action['re-published'] = true;
                        $_permission_action['exptime'] = true;
                        $_permission_action['declined'] = true;
                    } else {
                        if ($array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1) {
                            ++$check_edit;
                            if ($status) {
                                $_permission_action['exptime'] = true;
                            }
                        } elseif ($array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 and ($status == 0 or $status == 8 or $status == 2)) {
                            ++$check_edit;
                            $_permission_action['publtime'] = true;
                            $_permission_action['re-published'] = true;
                        } elseif ($array_cat_admin[$admin_id][$catid_i]['app_content'] == 1 and $status == 5) {
                            ++$check_edit;
                        } elseif (($status == 0 or $status == 4 or $status == 5) and $post_id == $admin_id) {
                            ++$check_edit;
                            $_permission_action['waiting'] = true;
                        }

                        if ($array_cat_admin[$admin_id][$catid_i]['del_content'] == 1) {
                            ++$check_del;
                        } elseif (($status == 0 or $status == 4 or $status == 5) and $post_id == $admin_id) {
                            ++$check_del;
                            $_permission_action['waiting'] = true;
                        }
                    }
                }
            }
                $check_permission_delete = true;
            
        }
		
        $admin_funcs = [];
        if ($check_permission_delete) {
			$detail = 0;
            $admin_funcs['delete'] = '<a class="btn btn-danger btn-xs" href="javascript:void(0);" onclick="nv_del_content(' . $id . ", '" . md5($id . NV_CHECK_SESSION) . "','" . NV_BASE_ADMINURL . "', " . $detail . ')"><em class="fa fa-trash-o margin-right"></em> ' . $lang_global['delete'] . '</a>';
            $_permission_action['delete'] = true;
        }
		$body_contents = $db_slave->query('SELECT titlesite, description, bodyhtml, keywords, sourcetext, files, audio, layout_func, imgposition, copyright, allowed_send, allowed_print, allowed_save FROM ' . NV_PREFIXLANG . '_news_detail where id=' . $id)->fetch();
        $data[$id] = [
            'id' => $id,
            'title' => $title,
            'title_clean' => nv_clean60($title),
            'publtime' => $publtime,
            'status_id' => $status,
			'audio'=>$body_contents["audio"],
            'weight' => $weight,
            'userid' => $_userid,
            'hitstotal' => number_format($hitstotal, 0, ',', '.'),
            'hitscm' => number_format($hitscm, 0, ',', '.'),
            'numtags' => 0,
            'feature' => $admin_funcs,
            'author' => $author
        ];

        $array_ids[$id] = $id;
        $array_userid[$_userid] = $_userid;
    }

$order2 = ($order == 'asc') ? 'desc' : 'asc';
$array_list_action = [
    'audio_export' => $lang_module['audio_export'],
    'delete' =>  $lang_global['delete'],
];


if (!empty($array_ids)) {

    // Xác định người sửa bài viết
    $db_slave->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_news_tmp')
        ->where('id IN( ' . implode(',', $array_ids) . ' )');
    $result = $db_slave->query($db_slave->sql());
    while ($_row = $result->fetch()) {
        $array_editdata[$_row['id']] = $_row;
        $array_userid[$_row['admin_id']] = $_row['admin_id'];
    }

    // Tim cac author noi bo
    $db_slave->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_news_authorlist')
        ->where('id IN (' . implode(',', $array_ids) . ')');
    $result = $db_slave->query($db_slave->sql());
    while ($_row = $result->fetch()) {
        !isset($internal_authors[$_row['id']]) && $internal_authors[$_row['id']] = [];
        $internal_authors[$_row['id']][] = [
            'href' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;q=' . urlencode($_row['alias']) . '&amp;stype=author&amp;checkss=' . NV_CHECK_SESSION,
            'pseudonym' => $_row['pseudonym']
        ];
    }
}
if (!empty($array_userid)) {
    $db_slave->sqlreset()
        ->select('tb1.userid, tb1.username, tb2.lev admin_lev')
        ->from(NV_USERS_GLOBALTABLE . ' tb1')
        ->join('LEFT JOIN ' . NV_AUTHORS_GLOBALTABLE . ' tb2 ON tb1.userid=tb2.admin_id')
        ->where('tb1.userid IN( ' . implode(',', $array_userid) . ' )');
    $array_userid = [];
    $result = $db_slave->query($db_slave->sql());
    while (list($_userid, $_username, $admin_lev) = $result->fetch(3)) {
        $array_userid[$_userid] = [
            'username' => $_username,
            'admin_lev' => $admin_lev
        ];
    }
}
$base_url_id = $base_url_mod . '&amp;ordername=id&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url_name = $base_url_mod . '&amp;ordername=title&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url_publtime = $base_url_mod . '&amp;ordername=publtime&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url_exptime = $base_url_mod . '&amp;ordername=exptime&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url_hitstotal = $base_url_mod . '&amp;ordername=hitstotal&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url_hitscm = $base_url_mod . '&amp;ordername=hitscm&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url = $base_url_mod . '&amp;sstatus=' . $sstatus . '&amp;ordername=' . $ordername . '&amp;order=' . $order;
$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

//-------------------------------
// Viết code xuất ra site vào đây
//-------------------------------

$url_copy = '';
foreach ($data as $row) {
    $is_excdata = 0;
    $is_editing_row = (isset($array_editdata[$row['id']]) and $array_editdata[$row['id']]['admin_id'] != $admin_info['userid']) ? true : false;
    $is_locked_row = (isset($array_editdata[$row['id']]) and !$array_editdata[$row['id']]['allowtakeover']) ? true : false;
    if ($is_locked_row) {
        unset($row['feature']['edit'], $row['feature']['delete']);
    }
	

    $row['feature'] = implode(' ', $row['feature']);
	
    if ($global_config['idsite'] > 0 and isset($site_mods['excdata']) and isset($push_content['module'][$module_name]) and $row['status_id'] == 1) {
        $count = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $site_mods['excdata']['module_data'] . '_sended WHERE id_content=' . $row['id'] . ' AND module=' . $db_slave->quote($module_name))
            ->fetchColumn();
        if ($count == 0) {
            $is_excdata = 1;
            $row['url_send'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=excdata&amp;' . NV_OP_VARIABLE . '=send&amp;module=' . $module_name . '&amp;id=' . $row['id'];
        }
    }

    if ($row['status_id'] == 4 and empty($row['title'])) {
        $row['title'] = $lang_module['no_name'];
    }
    $row['username'] = isset($array_userid[$row['userid']]) ? $array_userid[$row['userid']]['username'] : '';

    $authors = [];
    if (isset($internal_authors[$row['id']]) and !empty($internal_authors[$row['id']])) {
        foreach ($internal_authors[$row['id']] as $internal_author) {
            $authors[] = '<a href="' . $internal_author['href'] . '">' . $internal_author['pseudonym'] . '</a>';
        }
    }
    if (!empty($row['author'])) {
        $authors[] = $row['author'];
    }
	
	
	if ($row['audio']) {
		$row['audio'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['audio'];
		//var_dump( $row['audio']);die;
		$xtpl->assign('AUDIO', $row['audio']);
		$xtpl->parse('main.loop.audio');
    }
	
    $row['author'] = !empty($authors) ? implode(', ', $authors) : '';

    $xtpl->assign('ROW', $row);

    if ($is_excdata) {
        $xtpl->parse('main.loop.excdata');
    }

    if ($row['status_id'] == 4) {
        $xtpl->parse('main.loop.text');
    }

    if ($is_editing_row) {
        $xtpl->assign('USER_EDITING', $array_userid[$array_editdata[$row['id']]['admin_id']]['username']);
        $xtpl->assign('LEV_EDITING', $is_locked_row ? 'lock' : 'unlock-alt');
        $xtpl->parse('main.loop.is_editing');
    }
    if (!$is_locked_row) {
        $xtpl->parse('main.loop.checkrow');
    }

    $xtpl->parse('main.loop');
}

foreach ($array_list_action as $action_i => $title_i) {
	$action_assign = [
		'value' => $action_i,
		'title' => $title_i
	];
	$xtpl->assign('ACTION', $action_assign);
	$xtpl->parse('main.action');
}

if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}


$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
