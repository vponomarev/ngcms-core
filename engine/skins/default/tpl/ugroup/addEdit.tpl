<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{ php_self }}"><i class="fa fa-home"></i></a></li>
		<li class="breadcrumb-item"><a href="{{ php_self }}?mod=ugroup">{{ lang['user_groups'] }}</a></li>
		<li class="breadcrumb-item active" aria-current="page">
			{% if (flags.editMode) %}
			{{ entry.identity }}
			{% else %}
			{{ lang['add_group'] }}
			{% endif %}
		</li>
	</ol>
</nav>

<form action="{{ php_self }}?mod=ugroup" method="post">
	<input type="hidden" name="token" value="{{ token }}" />
	{% if (flags.editMode) %}
	<input type="hidden" name="action" value="edit" />
	<input type="hidden" name="id" value="{{ entry.id }}" />
	{% else %}
	<input type="hidden" name="action" value="add" />
	{% endif %}

	<div class="row">
		<!-- Left edit column -->
		<div class="col-lg-8">

			<!-- MAIN CONTENT -->
			<div id="maincontent" class="card mb-4">
				<div class="card-header">{{ lang['edit_group'] }}</div>
				<div class="card-body">
					<div class="form-row mb-3">
						<label class="col-lg-6 col-form-label">ID</label>
						<div class="col-lg-6">
							<input type="text" readonly class="form-control-plaintext" value="{{ entry.id }}" />
						</div>
					</div>

					<div class="form-row mb-3">
						<label class="col-lg-6 col-form-label">{{ lang['identifier'] }}</label>
						<div class="col-lg-6">
							<input type="text" name="identity" value="{{ entry.identity }}" class="form-control" />
						</div>
					</div>

					{% for eLang,eLValue in entry.langName %}
					<div class="form-row mb-3">
						<label class="col-lg-6 col-form-label">{{ lang['name_group_lang'] }} [{{ eLang }}]</label>
						<div class="col-lg-6">
							<input type="text" name="langname[{{ eLang }}]" value="{{ eLValue }}" class="form-control" />
						</div>
					</div>
					{% endfor %}
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col col-lg-8">
			<div class="row">
				{% if (flags.canModify) %}
					<div class="col-md-6 mb-4">
						<button type="button" class="btn btn-outline-dark" onclick="history.back();">
							{{ lang['cancel'] }}
						</button>
					</div>

					<div class="col-md-6 mb-4 text-right">
						<button type="submit" class="btn btn-outline-success">
							<span class="d-xl-none"><i class="fa fa-floppy-o"></i></span>
							<span class="d-none d-xl-block">{{ lang['save'] }}</span>
						</button>
					</div>
				{% endif %}
			</div>
		</div>
	</div>
</form>
