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
$num_items = $nv_Request->get_int('num_items', 'get', 0);
$array_config = [];
$array_config['voice'] = '';
$array_config['speed'] = 1;

$sql = 'SELECT config_name, config_value FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config';
$result = $db->query($sql);
while (list($c_config_name, $c_config_value) = $result->fetch(3)) {
    $array_config[$c_config_name] = $c_config_value;
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

//------------------------------
// Viết code xử lý chung vào đây
//------------------------------
if($nv_Request->isset_request('checkss', 'get') and $nv_Request->get_string('checkss', 'get') == NV_CHECK_SESSION){
	$listid = $nv_Request->get_string('listid', 'get');
	$id_array = array_map('intval', explode(',', $listid));
	
	$sql = 'SELECT id,title,hometext, listcatid, status, publtime, exptime FROM ' . NV_PREFIXLANG . '_news_rows WHERE id in (' . implode(',', $id_array) . ')';
    $result = $db->query($sql);
	
	//$array_id = explode(',', $listid);
	foreach($result as $row){
		
		$body_contents = $db_slave->query('SELECT titlesite, description, bodyhtml, keywords, sourcetext, files, audio, layout_func, imgposition, copyright, allowed_send, allowed_print, allowed_save FROM ' . NV_PREFIXLANG . '_news_detail where id=' . $row['id'])->fetch();
		
		$string ='"'. $row['title'] ." ".strip_tags($row['hometext']). " ".strip_tags($body_contents['bodyhtml']). '"';	 
		 $params = [
			'text' => $string,
			'voice' => $array_config['voice'],
			'id' => '2',
			'without_filter' => false,
			'speed' => $array_config['speed'],
			'tts_return_option' => '2',
			'timeout' => 60000
		 ];
		 $test = json_encode($params);
		 
		 $curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://viettelgroup.ai/voice/api/tts/v1/rest/syn',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => json_encode($params),
			CURLOPT_HTTPHEADER => array(
				'Connection: keep-alive',
				'sec-ch-ua: " Not;A Brand";v="99", "Google Chrome";v="91", "Chromium";v="91"',
				'sec-ch-ua-mobile: ?0',
				'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36',
				'token: ',
				'Content-Type: application/json',
				'Accept: */*',
				'Origin: https://viettelgroup.ai',
				'Sec-Fetch-Site: same-origin',
				'Sec-Fetch-Mode: cors',
				'Sec-Fetch-Dest: empty',
				'Referer: https://viettelgroup.ai/service/tts',
				'Accept-Language: vi-VN,vi;q=0.9,en-US;q=0.8,en;q=0.7,fr-FR;q=0.6,fr;q=0.5'
			),
		));
		$response = curl_exec($curl);
		// Xác định và tạo các thư mục upload
		if ($response) {
			
			$name_audio = 'audio_'.$row['id'].'.mp3';
			$file_dir = NV_UPLOADS_REAL_DIR .'/' . $module_upload.'/audio_'.$row['id'].'.mp3';
			
			file_put_contents($file_dir, $response);
			$meta_key = 'tts_audio_';
			$ct_query = [];
			
			$sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_news_detail SET
				audio=:audio
			WHERE id =' .$row['id']);
			 $sth->bindParam(':audio', $name_audio , PDO::PARAM_STR);
			 
			$ct_query[] = (int) $sth->execute();
		}
	}
}
nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
