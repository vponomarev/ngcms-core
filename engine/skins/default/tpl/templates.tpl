<link href="{{ skins_url }}/public/css/code-editor.css" rel="stylesheet">

<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6 d-none d-md-block ">
			<h1 class="m-0 text-dark">{{ lang.templates['title'] }}</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home"></i></a></li>
			<li class="breadcrumb-item active" aria-current="page">{{ lang.templates['title'] }}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<ul class="navbar-nav">
		<li class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown">
				{{ lang.templates['tplsite'] }}
			</a>
			<div class="dropdown-menu">
				{% for st in siteTemplates %}
				<a href="#" class="dropdown-item" data-teplate-mode="template" data-teplate-name="{{ st.name }}">{{ st.name }} ({{ st.title }})</a>
				{% endfor %}
			</div>
		</li>
		<li class="nav-item">
			<a href="#" class="nav-link" data-teplate-mode="plugin">{{ lang.templates['tplmodules'] }}</a>
		</li>
	</ul>
</nav>

<form id="templates" action="" method="post">
	<input type="hidden" name="token" value="{{ token }}" />

	<div class="templates-explorer">
		<div class="row mb-3">
			<div class="col-sm-5 col-md-3 pr-lg-0">
				<div class="p-2">
					{{ lang.templates['tpl.edit'] }}
					<br/>
					[<b id="templateNameArea">default</b>]
				</div>
			</div>
			<div class="col-sm-7 col-md-9 pl-lg-0">

				<div id="fileEditorInfo" class="p-2">
					&nbsp;
				</div>
			</div>
		</div>

		<div class="row mb-3">
			<div class="col-lg-3 pr-lg-0">
				<div id="fileTreeSelector" class="" style="background-color: #ABCDEF; height: 578px; overflow: auto;">
					TEST CONTENT
				</div>
				<!-- <div style="width: 100%; background-color: #E0E0E0; padding: 3px; ">
					<input style="width: 150px;" type="button" class="navbutton" value="Create template.."/>
				</div> -->
			</div>

			<div id="fileEditorContainer" class="col-lg-9 pl-lg-0">
				<textarea id="fileEditorSelector" wrap="off" style="width: 100%; height: 100%; float: left; background-color: #EEEEEE; white-space: nowrap; font-family: monospace; font-size: 10pt;">*** EDITOR ***</textarea>
				<div id="imageViewContainer" style="display: none; height: 100%; width: 100%; vertical-align: middle;"></div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-5 col-md-3 pr-lg-0">

			</div>
			<div id="fileEditorButtonLine" class="col-sm-7 col-md-9 pl-lg-0">
				<button id="submitTemplateEdit" type="button" class="btn btn-outline-success">Save file</button>
				<!-- <button type="button" class="btn btn-outline-danger">Delete file</button> -->
			</div>
		</div>
	</div>
</form>

<script src="{{ skins_url }}/public/js/code-editor.js"></script>
