/**
 * ��������js
 */
var s = $('.messages');
msgshow(s);

// ��Ϣ
$('.messages .close').click(function() {
	var s = $(this).parent('.messages');
	msghide(s);
});

// ��ʾ��Ϣ
function msgshow(ele) {
	var t = setTimeout(function() {
		ele.slideDown(200);
		clearTimeout(t);
	}, 400);
};
// �ر���Ϣ
function msghide(ele) {
	ele.animate({
		opacity : .01
	}, 200, function() {
		ele.slideUp(200, function() {
			ele.remove();
		});
	});
};