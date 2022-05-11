<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DacLoi.,JSC <saka.dacloi@gmail.com>
 * @Copyright (C) 2022 DacLoi.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 09 May 2022 08:02:27 GMT
 */

if (!defined('NV_ADMIN'))
    die('Stop!!!');

$submenu['config'] = $lang_module['config'];
$table_module = 'news';

if (!function_exists('nv_speed_array_cat_admin')) {
    /**
     * nv_speed_array_cat_admin()
     *
     * @param string $table_module
     * @return array
     */
	 
    function nv_speed_array_cat_admin($table_module)
    {
        global $db_slave;

        $array_cat_admin = [];
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_news_admins ORDER BY userid ASC';
        $result = $db_slave->query($sql);

        while ($row = $result->fetch()) {
            $array_cat_admin[$row['userid']][$row['catid']] = $row;
        }

        return $array_cat_admin;
    }
}

$is_refresh = false;
$array_cat_admin = nv_speed_array_cat_admin($table_module);

if (!empty($module_info['admins'])) {
    $module_admin = explode(',', $module_info['admins']);
    foreach ($module_admin as $userid_i) {
        if (!isset($array_cat_admin[$userid_i])) {
            $db->query('INSERT INTO ' . NV_PREFIXLANG . '_news_admins (userid, catid, admin, add_content, pub_content, edit_content, del_content) VALUES (' . $userid_i . ', 0, 1, 1, 1, 1, 1)');
            $is_refresh = true;
        }
    }
}
if ($is_refresh) {
    $array_cat_admin = nv_news_array_cat_admin($table_module);
}

$admin_id = $admin_info['admin_id'];
$NV_IS_ADMIN_MODULE = false;
$NV_IS_ADMIN_FULL_MODULE = false;
if (defined('NV_IS_SPADMIN')) {
    $NV_IS_ADMIN_MODULE = true;
    $NV_IS_ADMIN_FULL_MODULE = true;
} else {
    if (isset($array_cat_admin[$admin_id][0])) {
        $NV_IS_ADMIN_MODULE = true;
        if ((int) ($array_cat_admin[$admin_id][0]['admin']) == 2) {
            $NV_IS_ADMIN_FULL_MODULE = true;
        }
    }
}