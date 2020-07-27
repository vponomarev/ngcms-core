<div class="page-title">
	<h2>{{ lang['extras'] }}</h2>
</div>

<div class="input-group mb-3">
	<input type="text" id="searchInput" class="form-control" placeholder="{{ lang['extras.search'] }}">
	<div class="input-group-append">
		<span class="input-group-text"><i class="fa fa-search"></i></span>
	</div>
</div>

<ul class="nav nav-pills mb-3 d-md-flex d-block">
	<li class="nav-item"><a href="#" class="nav-link" data-filter="pluginEntryActive">{{ lang['list.active'] }} <span class="badge badge-light">{{ cntActive }}</span></a></li>
	<li class="nav-item"><a href="#" class="nav-link" data-filter="pluginEntryInactive">{{ lang['list.inactive'] }} <span class="badge badge-light">{{ cntInactive }}</span></a></li>
	<li class="nav-item"><a href="#" class="nav-link" data-filter="pluginEntryUninstalled">{{ lang['list.needinstall'] }} <span class="badge badge-light">{{ cntUninstalled }}</span></a></li>
	<li class="nav-item"><a href="#" class="nav-link active" data-filter="all">{{ lang['list.all'] }} <span class="badge badge-light">{{ cntAll }}</span></a></li>
</ul>

<div class="table-responsive">
	<table class="table table-sm">
		<thead>
			<tr>
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

<script language="javascript" type="text/javascript">
	function tabsSwitch(pill) {
		$(".nav-pills li>a").removeClass('active');

		const newSelection = $(pill).addClass('active')
			.attr('data-filter');

		$('tr.all').show().not('.'+newSelection).hide();
	}

	$(document).ready(function() {
		$(".nav-pills").on('click', 'li>a:not(.active)', function() {
			$("#searchInput").val('');
			tabsSwitch($(this));
		});

		$("#searchInput").on('keyup', function(event) {
			tabsSwitch($('.nav-pills li>a').eq(0));

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
				ngNotifyWindow('Error [' + resTX['errorCode'] + ']: ' + resTX['errorText'], '{{ lang['
					notifyWindowInfo '] }}');
			} else {
				ngNotifyWindow(resTX['errorText'], '{{ lang['
					notifyWindowInfo '] }}');
			}
		}, "text").error(function() {
			ngHideLoading();
			ngNotifyWindow('{{ lang['
				rpc_httpError '] }}', '{{ lang['
				notifyWindowError '] }}');
		});
	}
</script>
