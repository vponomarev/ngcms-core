<div class="page-title">
	<h2>{{ lang.pm }}</h2>
</div>
{{ lang.from }}: {{ fromID }} ({{ fromName }})<br/>
{{ lang.receiver }}: {{ toID }} ({{ toName }})<br/>
<form method="post" action="?mod=pm&action=reply&pmid={{ id }}">
	<input type="hidden" name="title" value="{{ title }}" />
	<input type="hidden" name="from" value="{{ from }}" />

	<div class="card">
		<h5 class="card-header">{{ lang.title }}: {{ title }}</h5>
		<div class="card-body">{{ content }}</div>
		<div class="card-footer text-center">
			<button type="submit" class="btn btn-outline-success">{{ lang.reply }}</button>
		</div>
	</div>
</form>
