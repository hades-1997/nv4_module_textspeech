# nv4_module_textspeech
module chuyển từ dạng text sang dạng audio
Giọng đọc nhân tạo Viettel AI là tính năng được các đội ngũ viettel phát triển nhằm giúp những người khiếm thị có thể tiếp cận báo chí dễ dàng. Chức năng đọc audio đa dạng vùng miền dễ dàng tích hợp với mọi hệ thống
Hướng dẫn cài đặt module speed text audio 
Lưu ý: module hiện tại chỉ hỗ trợ phiên bản nukeviet 4.5 trở đi, nếu dùng ở bản 4.4 vui lòng dùng file export_audio.php để phát triển lại

+ Thư viện aplay.js
<link href="/{NV_ASSETS_DIR}/css/aplayer/APlayer.min.css" rel="stylesheet">
<script src="/{NV_ASSETS_DIR}/js/aplayer/APlayer.min.js" type="text/javascript"></script>
Tại file detail.tpl thêm dòng code hiển thị dưới title hoặc ở đâu bạn muốn
	 <div id="myplayer" class="aplayer"></div>
Thêm đoạn hiển thị script chân trang
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
Tiến hành vào giao diện admin để sử dụng 
Chọn bài viết cần xuất audio -> nhấn thực hiện để chạy xuất audio 
Sau khi xuất audio sẽ xuất hiện icon tai nghe. bạn có thể nhấp vào để nghe thử.  Mong bài viết giúp ích được các bạn phần nào trong thiết kế Website. Hãy nhấn nút like và share  để mọi người cùng học hỏi kiến thức mới nhé. Cảm ơn các bạn đã quan tâm VNCODE. Chúc mọi người thành công, vì đây là bản share miễn phí nên mình sẽ không hỗ trợ trực tiếp mà hỗ trợ qua issue github ạ.
