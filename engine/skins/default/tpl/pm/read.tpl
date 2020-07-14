<div class="page-title">
	<h2>{l_pm}</h2>
</div>

<form method="post" action="{php_self}?mod=pm&action=reply&pmid={pmid}">
	<input type="hidden" name="title" value="{title}" />
	<input type="hidden" name="from" value="{from}" />

	<div class="card">
		<h5 class="card-header">{title}</h5>
		<div class="card-body">{content}</div>
		<div class="card-footer text-center">
			<button type="submit" class="btn btn-outline-success">{l_reply}</button>
		</div>
	</div>
</form>
