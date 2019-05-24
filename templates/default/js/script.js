$(document).ready(function () {
	$('#menu ul li ul').parent().addClass('parent');
	$('#popular-news').tabs();
	$('.popular-block article:last-child').addLastClass();
	$('#profile .profile-block ul li').addLastClass();
	$('#table2').width(function () {
		return parseInt($(this).width()) - 1;
	});

	$('.articles-switch a').click(function () {
		if (!$(this).hasClass('active')) {
			var articlesSwitch = $(this).parent();
			var articles = articlesSwitch.parent();

			articlesSwitch.find('.active').removeClass('active')
			$(this).addClass('active');
			if (articles.hasClass('full')) {
				articles.removeClass('full');
			} else {
				articles.addClass('full');
			}
		}
		return false;
	});

	$("pre").each(function () {
		var h = parseInt($(this).height());
		var mh = parseInt($(this).css('max-height'));

		if (h >= mh) {
			$(this).addClass('blue-skin').customScrollbar();
		}
	});

	$('.table-striped tr:odd td').addClass('odd');
	$('.table-striped tr:even td').addClass('even');

	$('.comments ul li:odd').addClass('odd');
	$('.comments ul li:even').addClass('even');

	$('.answer').each(function () {
		var el = $(this);
		var prsEl = el.find('.answer-progress');
		var width = prsEl.data('width');
		prsEl.animate({width: width}, 1500);

	});

	if ($('.article-slider').exists()) {
		$(".article-slider").jCarouselLite({
			btnNext: ".articles-slider .next-slide",
			btnPrev: ".articles-slider .prev-slide",
			visible: 2
		});
	}

	$('.popular-authors-block li:eq(0)').addClass('first');
	$('.popular-authors-block li:eq(1)').addClass('second');
	$('.popular-authors-block li:eq(2)').addClass('third');

	$('.auth-profile').click(function () {
		$('#profile').slideDown();
		$('body').append('<div class="shadow-bg"></div>');
		$('.shadow-bg').fadeIn();
		$('.shadow-bg').click(function () {
			$('#profile').slideUp();
			$(this).fadeOut(function () {
				$(this).remove();
			});
		});
		return false;
	});


	/* MODALS */
	$('a[rel="modal"]').click(function () {
		var modalID = $(this).attr('href');
		var modBox = $(modalID).find('.modal-box');
		modBox.css('margin-left', modBox.width() / -2);

		$('body').append('<div class="shadow-bg"></div>');
		$('.shadow-bg').fadeIn();

		var modBoxClick = true;
		$(modalID).fadeIn();
		$(modalID).find('.modal-box').click(function () {
			modBoxClick = false
		});
		$(modalID).click(function () {
			if (modBoxClick) {
				$(modalID).fadeOut();
				$('.shadow-bg').fadeOut(function () {
					$(this).remove();
				});
			}
			modBoxClick = true;
		});
		$(modalID).find('.modal-clouse').click(function () {
			$(modalID).fadeOut();
			$('.shadow-bg').fadeOut(function () {
				$(this).remove();
			});
		});
		$(modalID).find('.loginza').click(function () {
			$(modalID).fadeOut();
			$('.shadow-bg').fadeOut(function () {
				$(this).remove();
			});
		});
		return false;
	});

});


jQuery.fn.exists = function () {
	return $(this).length;
};
jQuery.fn.addLastClass = function () {
	$(this).filter(':last-child').addClass('last');
	return this;
};

jQuery.fn.tabs = function () {
	var tabs = $(this);
	var hash = window.location.hash;


	(function (hash) {
		if (hash != undefined && hash != "#" && $(hash + '.tab-pane').exists()) {
			$(hash).parent().find('.active').removeClass('active');
			$(hash).addClass('active');

			$('a[href="' + hash + '"]').parent().parent().find('.active').removeClass('active');
			$('a[href="' + hash + '"]').parent().addClass('active');
		}
	})(hash);

	tabs.find('a').on('click', function () {
		if (!$(this).parent().hasClass('active')) {
			var id = $(this).attr('href');
			var tabsUL = $(this).parent().parent();
			var tabCont = tabsUL.next();

			if (!tabsUL.hasClass('clicked')) {
				tabsUL.find('.active').removeClass('active');
				$(this).parent().addClass('active');
			}

			if ($(this).data('transitional')) {
				switch ($(this).data('transitional')) {
					case 'fade':
						if (!tabsUL.hasClass('clicked')) {
							tabsUL.addClass('clicked');
							tabCont.find('.tab-pane.active').fadeOut("fast", function () {
								$(this).removeAttr('style').removeClass('active');

								tabCont.find('.tab-pane' + id).fadeIn("fast", function () {
									$(this).removeAttr('style').addClass('active');
									tabsUL.removeClass('clicked');
								});
							});
						}
						break;
					case 'slide':
						if (!tabsUL.hasClass('clicked')) {
							tabsUL.addClass('clicked');
							tabCont.find('.tab-pane.active').slideUp("fast", function () {
								$(this).removeAttr('style').removeClass('active');

								tabCont.find('.tab-pane' + id).slideDown("fast", function () {
									$(this).removeAttr('style').addClass('active');
									tabsUL.removeClass('clicked');
								});
							});
						}
						break;
					default:
						if (!tabsUL.hasClass('clicked')) {
							tabsUL.addClass('clicked');
							tabCont.find('.tab-pane.active').removeClass('active');
							tabCont.find('.tab-pane' + id).addClass('active');
							tabsUL.removeClass('clicked');
						}
						break;
				}
			} else {
				if (!tabsUL.hasClass('clicked')) {
					tabsUL.addClass('clicked');
					tabCont.find('.tab-pane.active').removeClass('active');
					tabCont.find('.tab-pane' + id).addClass('active');
					tabsUL.removeClass('clicked');
				}
			}
		}
		return false;
	});
};

function set_cookie(name, value, expires) {

	if (!expires) {

		expires = new Date();

	}

	document.cookie = name + "=" + escape(value) + "; expires=" + expires.toGMTString() + "; path=/";

}


function get_cookie(name) {

	cookie_name = name + "=";

	cookie_length = document.cookie.length;

	cookie_begin = 0;

	while (cookie_begin < cookie_length) {

		value_begin = cookie_begin + cookie_name.length;

		if (document.cookie.substring(cookie_begin, value_begin) == cookie_name) {

			var value_end = document.cookie.indexOf(";", value_begin);

			if (value_end == -1) {

				value_end = cookie_length;

			}

			return unescape(document.cookie.substring(value_begin, value_end));

		}

		cookie_begin = document.cookie.indexOf(" ", cookie_begin) + 1;

		if (cookie_begin == 0) {

			break;

		}

	}

	return null;

}


function save_articles_switch_one() {

	var name = "ng_articles_switch";

	var tmp = "1";

	expires = new Date();

	expires.setTime(expires.getTime() + (1000 * 86400 * 365));

	set_cookie(name, tmp, expires);

}


function save_articles_switch_two() {

	var name = "ng_articles_switch";

	var tmp = "2";

	expires = new Date();

	expires.setTime(expires.getTime() + (1000 * 86400 * 365));

	set_cookie(name, tmp, expires);

}


window.onload = function () {

	if (document.getElementById('articles-switch-1') == undefined) {
		return false
	}


	var tmp = get_cookie('ng_articles_switch');

	if (tmp == undefined) {

		save_articles_switch_one();

		document.getElementById('articles-switch-1').setAttribute('class', 'articles-switch-1 active');


		$('.articles-switch a').each(function () {

			var articlesSwitch = $(this).parent();

			var articles = articlesSwitch.parent();


			if (articles.hasClass('full')) {

				articles.removeClass('full');

			} else {

				articles.addClass('full');

			}


			return false;

		});


	}

	else if (tmp == '1') {

		document.getElementById('articles-switch-1').setAttribute('class', 'articles-switch-1 active');


		$('.articles-switch a').each(function () {

			var articlesSwitch = $(this).parent();

			var articles = articlesSwitch.parent();


			if (articles.hasClass('full')) {

				articles.removeClass('full');

			} else {

				articles.addClass('full');

			}


			return false;

		});


	}

	else {

		document.getElementById('articles-switch-2').setAttribute('class', 'articles-switch-2 active');

	}


}


$(document).ready(function () {
	$(".showhide").hide();
});

$("#show_all_archive").click(function () {
	$(".showhide").toggle("fast");
});