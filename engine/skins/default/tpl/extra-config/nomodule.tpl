<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{ php_self }}"><i class="fa fa-home"></i></a></li>
		<li class="breadcrumb-item"><a href="{{ php_self }}?mod=extras">{{ lang['extras'] }}</a></li>
		<li class="breadcrumb-item active" aria-current="page">{{action}}: {{ plugin }}</li>
	</ol>
</nav>

<div class="alert alert-danger">
	{{ action_text }}
</div>
