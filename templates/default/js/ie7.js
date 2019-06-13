window.onload = function () {
	$('#profile .profile-top-bg').remove();
	$('#profile').appendTo('body');
	var style = {
		right: '40%',
		left: '40%',
		top: '10%'
	};
	$('#profile').css(style);
};