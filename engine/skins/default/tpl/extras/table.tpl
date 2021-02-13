<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6 d-none d-md-block ">
			<h1 class="m-0 text-dark">{{ lang['extras'] }}</h1>
	  </div><!-- /.col -->
	  <div class="col-12 col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home"></i></a></li>
			<li class="breadcrumb-item active" aria-current="page">{{ lang['extras'] }}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

<div class="input-group mb-3">
	<input type="text" id="searchInput" class="form-control" placeholder="{{ lang['extras.search'] }}">
	<div class="input-group-append">
		<span class="input-group-text"><i class="fa fa-search"></i></span>
	</div>
</div>

<ul class="nav nav-tabs nav-fill mb-3 d-md-flex d-block">
	<li class="nav-item"><a href="#" class="nav-link active" data-filter="pluginEntryActive">{{ lang['list.active'] }} <span class="badge badge-light">{{ cntActive }}</span></a></li>
	<li class="nav-item"><a href="#" class="nav-link" data-filter="pluginEntryInactive">{{ lang['list.inactive'] }} <span class="badge badge-light">{{ cntInactive }}</span></a></li>
	<li class="nav-item"><a href="#" class="nav-link" data-filter="pluginEntryUninstalled">{{ lang['list.needinstall'] }} <span class="badge badge-light">{{ cntUninstalled }}</span></a></li>
	<li class="nav-item"><a href="#" class="nav-link" data-filter="all">{{ lang['list.all'] }} <span class="badge badge-light">{{ cntAll }}</span></a></li>
</ul>

<div class="table-responsive">
	<table class="table table-sm">
		<thead>
			<tr>
				<th> </th>
				<th>{{ lang['id'] }}</th>
				<th>{{ lang['title'] }}</th>
				<th>{{ lang['type'] }}</th>
				<th>{{ lang['version'] }}</th>
				<th>&nbsp;</th>
				<th>{{ lang['description'] }}</th>
				<th>{{ lang['author'] }}</th>
				<th>{{ lang['action'] }}</th>
			</tr>
		</thead>
		<tbody id="entryList">
			{% for entry in entries %}
			<tr class="{{ entry.style }} all" id="plugin_{{ entry.id }}">
				<td>{% if entry.flags.isCompatible %}<img src="{{ skins_url }}/images/msg.png">{% else %} {% endif %}</td>
				<td nowrap>{{ entry.id }} {{ entry.new }}</td>
				<td>{{ entry.url }}</td>
				<td>{{ entry.type }}</td>
				<td>{{ entry.version }}</td>
				<td nowrap>{{ entry.readme }} {{ entry.history }}</td>
				<td>{{ entry.description }}</td>
				<td>{{ entry.author_url }}</td>
				<td nowrap>{{ entry.link }} {{ entry.install }}</td>
			</tr>
			{% endfor %}
		</tbody>
	</table>
</div>

<script>
	function tabsSwitch(pill) {
		$(".nav-tabs li>a").removeClass('active');

		const newSelection = $(pill).addClass('active')
			.attr('data-filter');

		$('tr.all').show().not('.'+newSelection).hide();
	}

	$(document).ready(function() {
		$(".nav-tabs").on('click', 'li>a:not(.active)', function() {
			$("#searchInput").val('');
			tabsSwitch($(this));
		});

		// Default plugin list display: active plugins
		tabsSwitch($(".nav-tabs li>a[data-filter=pluginEntryActive]"));

		$("#searchInput").on('keyup', function(event) {
			tabsSwitch($('.nav-tabs li>a').eq(0));

			const filter = $('#searchInput').val().toUpperCase();

			$('#entryList').find('tr').each(function(index, element) {
				const plugin = $(element).find('td').first().text();

				if (plugin && plugin.toUpperCase().includes(filter)) {
					$(element).show();
				} else {
					$(element).hide();
				}
			});
		});
	});

	function ngPluginSwitch(plugin, state) {
		ngShowLoading();
		$.post('/engine/rpc.php', {
			json: 1,
			methodName: 'admin.extras.switch',
			rndval: new Date().getTime(),
			params: json_encode({
				'token': '{{ token }}',
				'plugin': plugin,
				'state': state,
			})
		}, function(data) {
			ngHideLoading();
			// Try to decode incoming data
			try {
				resTX = eval('(' + data + ')');
			} catch (err) {
				ngNotifyWindow('{{ lang['rpc_jsonError '] }} ' + data, '{{ lang['notifyWindowError '] }}');
			}
			if (!resTX['status']) {
				ngNotifyWindow('Error [' + resTX['errorCode'] + ']: ' + resTX['errorText'], '{{ lang['notifyWindowInfo '] }}');
			} else {
				ngNotifyWindow(resTX['errorText'], '{{ lang['notifyWindowInfo '] }}');
			}
		}, "text").error(function() {
			ngHideLoading();
			ngNotifyWindow('{{ lang['rpc_httpError '] }}', '{{ lang['notifyWindowError '] }}');
		});
	}
</script>
