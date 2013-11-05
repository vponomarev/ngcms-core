<script type="text/javascript">
var ajax = new sack();
function rating(rating, post_id){
	ajax.onShow("");
	ajax.setVar("rating", rating);
	ajax.setVar("post_id", post_id);
	ajax.requestFile = "{home}/plugin/rating/?rating="+rating+"&post_id="+post_id;
	ajax.method = 'POST';
	ajax.element = 'ratingdiv_'+post_id;
	ajax.runAJAX();
}
</script>
<div id="ratingdiv_{post_id}">
	<div class="rating" style="float:left;">
		<ul class="uRating">
			<li class="r{rating}">{rating}</li>
			<li><a href="#" title="{l_rating_1}" class="r1u" onclick="rating('1', '{post_id}'); return false;"></a></li>
			<li><a href="#" title="{l_rating_2}" class="r2u" onclick="rating('2', '{post_id}'); return false;"></a></li>
			<li><a href="#" title="{l_rating_3}" class="r3u" onclick="rating('3', '{post_id}'); return false;"></a></li>
			<li><a href="#" title="{l_rating_4}" class="r4u" onclick="rating('4', '{post_id}'); return false;"></a></li>
			<li><a href="#" title="{l_rating_5}" class="r5u" onclick="rating('5', '{post_id}'); return false;"></a></li>
		</ul>
	</div>
</div>