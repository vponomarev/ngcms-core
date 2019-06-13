window.onload = function () {
	$('#menu ul li:first-child, #right-control #user-panel > a:first').addClass('first');
	$('#menu ul li:last-child, #menu ul li ul li:last, #right-control #user-panel > a:last').addClass('last');
	$('#menu ul li ul').parent().addClass('parent');
	$('#menu ul li').hover(function () {
		$(this).children('ul').show();
	}, function () {
		$(this).children('ul').hide();
	});
	$('#login').click(function () {
		$('#dialog').fadeIn();
	});
	$('#dialog .bg, #dialog .dialog-clouse').click(function () {
		$('#dialog').fadeOut();
	});
}
$(document).ready(function () {
	$(".showhide").hide();
});

$("#show_all_archive").click(function () {
	$(".showhide").toggle("fast");
});