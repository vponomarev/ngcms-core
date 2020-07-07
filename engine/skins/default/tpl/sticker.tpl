<noscript>
    <div class="ngStickerClass {{ ('error' == type) ? 'ngStickerClassError' : 'ngStickerClassClassic' }}">
        {{ message }}
    </div>
</noscript>

<script>
	document.addEventListener('DOMContentLoaded', function(event) {
		ngNotifySticker('{{ message }}', {
			className: '{{ ('error' == type) ? 'ngStickerClassError' : 'ngStickerClassClassic' }}',
			sticked: {{ 'error' == type ? 'true' : 'false' }},
	        closeBTN: true,
		});
	});
</script>
