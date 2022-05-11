<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DacLoi.,JSC <saka.dacloi@gmail.com>
 * @Copyright (C) 2022 DacLoi.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 09 May 2022 08:02:27 GMT
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN'))
    die('Stop!!!');

define('NV_IS_FILE_ADMIN', true);

$allow_func = array('main','audio_export', 'config');
