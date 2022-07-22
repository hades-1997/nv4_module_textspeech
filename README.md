# nv4_module_textspeech
module chuyển từ dạng text sang dạng audio
Giọng đọc nhân tạo Viettel AI là tính năng được các đội ngũ viettel phát triển nhằm giúp những người khiếm thị có thể tiếp cận báo chí dễ dàng. Chức năng đọc audio đa dạng vùng miền dễ dàng tích hợp với mọi hệ thống

Lưu ý: module hiện tại chỉ hỗ trợ phiên bản nukeviet 4.5 trở đi, nếu dùng ở bản 4.4 vui lòng dùng file export_audio.php để phát triển lại

# Thư viện aplay.js
	<link href="/{NV_ASSETS_DIR}/css/aplayer/APlayer.min.css" rel="stylesheet">
	<script src="/{NV_ASSETS_DIR}/js/aplayer/APlayer.min.js" type="text/javascript"></script>
# Thêm dòng code hiển thị dưới title hoặc ở đâu bạn muốn
	<div id="myplayer" class="aplayer"></div>

# Thêm đoạn hiển thị script chân trang
	<!-- BEGIN: audio -->
	<script>
		const ap = new APlayer({
			element: document.getElementById('myplayer'),
			music: {
				name: '{DETAIL.title}',
				artist: '{DETAIL.author}',
				url: '{DETAIL.audio}',
				cover: ' {DETAIL.image.src}',
			}
		});
	</script>
	<!-- END: audio -->
