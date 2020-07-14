<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="{php_self}"><i class="fa fa-home"></i></a></li>
		<li class="breadcrumb-item"><a href="{php_self}?mod=extras">{l_extras}</a></li>
		<li class="breadcrumb-item active" aria-current="page">{mode_text}: {plugin}</li>
	</ol>
</nav>

<form action="{php_self}?mod=extras" method="get">
	<input type=hidden name="mod" value="extras" />

	<div class="card mb-5">
		<h5 class="card-header">{plugin}</h5>

		<table class="table table-sm">
			<tbody>
				{entries}
			</tbody>
		</table>

		<div class="card-footer text-center">
			<button type="submit" class="btn btn-outline-success">{msg}</button>
		</div>
	</div>
</form>
