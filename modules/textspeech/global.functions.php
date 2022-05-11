<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DacLoi.,JSC <saka.dacloi@gmail.com>
 * @Copyright (C) 2022 DacLoi.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 09 May 2022 08:02:27 GMT
 */



/**
 * nv_link_delete_news()
 *
 * @param int $id
 * @param int $detail
 * @return string
 */
function nv_link_delete_news($id, $detail = 0)
{
	var_dump('done');die;
    global $lang_global;
    $link = '<a class="btn btn-danger btn-xs" href="javascript:void(0);" onclick="nv_del_content(' . $id . ", '" . md5($id . NV_CHECK_SESSION) . "','" . NV_BASE_ADMINURL . "', " . $detail . ')"><em class="fa fa-trash-o margin-right"></em> ' . $lang_global['delete'] . '</a>';

    return $link;
}

/**
 * update audio detail()
 *
 * @param int $id
 * @param int $detail
 * @return string
 */
 
 function audio_detail($postid, $name_audio){
	 var_dump('done');die;
	$ct_query = [];
	$sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_detail SET
		titlesite=:titlesite,
		description=:description,
		bodyhtml=:bodyhtml,
		keywords=:keywords,
		sourcetext=:sourcetext,
		files=:files,
		imgposition=' . (int) ($rowcontent['imgposition']) . ',
		layout_func=:layout_func,
		copyright=' . (int) ($rowcontent['copyright']) . ',
		allowed_send=' . (int) ($rowcontent['allowed_send']) . ',
		allowed_print=' . (int) ($rowcontent['allowed_print']) . ',
		allowed_save=' . (int) ($rowcontent['allowed_save']) . '
	WHERE id =' . $rowcontent['id']);

	$sth->bindParam(':files', $rowcontent['files'], PDO::PARAM_STR);
	$sth->bindParam(':titlesite', $rowcontent['titlesite'], PDO::PARAM_STR);
	$sth->bindParam(':layout_func', $rowcontent['layout_func'], PDO::PARAM_STR, strlen($rowcontent['layout_func']));
	$sth->bindParam(':description', $rowcontent['description'], PDO::PARAM_STR, strlen($rowcontent['description']));
	$sth->bindParam(':bodyhtml', $rowcontent['bodyhtml'], PDO::PARAM_STR, strlen($rowcontent['bodyhtml']));
	$sth->bindParam(':keywords', $rowcontent['keywords'], PDO::PARAM_STR, strlen($rowcontent['keywords']));
	$sth->bindParam(':sourcetext', $rowcontent['sourcetext'], PDO::PARAM_STR, strlen($rowcontent['sourcetext']));

	$ct_query[] = (int) $sth->execute();

	if ($rowcontent_old['listcatid'] != $rowcontent['listcatid']) {
		$array_cat_old = explode(',', $rowcontent_old['listcatid']);
		$array_cat_new = explode(',', $rowcontent['listcatid']);
		$array_cat_diff = array_diff($array_cat_old, $array_cat_new);
		foreach ($array_cat_diff as $catid) {
			if (!empty($catid)) {
				$ct_query[] = $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id = ' . (int) ($rowcontent['id']));
			}
		}
	}

	foreach ($catids as $catid) {
		if (!empty($catid)) {
			$db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id = ' . $rowcontent['id']);
			$ct_query[] = $db->exec('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $rowcontent['id']);
		}
	}

	if (array_sum($ct_query) != sizeof($ct_query)) {
		$error[] = $lang_module['errorsave'];
	}
	if ($module_config[$module_name]['elas_use'] == 1) {
		$body_contents = $db_slave->query('SELECT bodyhtml, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $rowcontent['id'])->fetch();
		$rowcontent = array_merge($rowcontent, $body_contents);

		$rowcontent['unsigned_title'] = nv_EncString($rowcontent['title']);
		$rowcontent['unsigned_bodyhtml'] = nv_EncString($rowcontent['bodyhtml']);
		$rowcontent['unsigned_author'] = nv_EncString($rowcontent['author']);
		$rowcontent['unsigned_hometext'] = nv_EncString($rowcontent['hometext']);

		$nukeVietElasticSearh = new NukeViet\ElasticSearch\Functions($module_config[$module_name]['elas_host'], $module_config[$module_name]['elas_port'], $module_config[$module_name]['elas_index']);
		$result_search = $nukeVietElasticSearh->update_data(NV_PREFIXLANG . '_' . $module_data . '_rows', $rowcontent['id'], $rowcontent);
	}

	// Sau khi sửa, tiến hành xóa bản ghi lưu trạng thái sửa trong csdl
	$db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE id = ' . $rowcontent['id']);
 }