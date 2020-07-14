<noscript>
    <div class="alert alert-{{ ('error' == type) ? 'danger' : type }}">
        {{ message }}
    </div>
</noscript>

<script>
	document.addEventListener('DOMContentLoaded', function(event) {
		ngNotifySticker('{{ message }}', {
			className: 'alert-{{ ('error' == type) ? 'danger' : type }}',
			sticked: {{ 'error' == type ? 'true' : 'false' }},
	        closeBTN: true,
		});
	});
</script>
