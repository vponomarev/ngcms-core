<div id="tags" class="btn-toolbar mb-3" role="toolbar">
	<div class="btn-group btn-group-sm mr-2">
		<button type="submit" class="btn btn-outline-dark"><i class="fa fa-floppy-o"></i></button>
	</div>

	<div class="btn-group btn-group-sm mr-2">
		<button type="button" class="btn btn-outline-dark" onclick="insertext('[p]','[/p]', {{ area }})"><i class="fa fa-paragraph"></i></button>
	</div>

	<div class="btn-group btn-group-sm mr-2">
		<button id="tags-font" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="fa fa-font"></i>
		</button>
		<div class="dropdown-menu" aria-labelledby="tags-font">
			<a href="#" class="dropdown-item" onclick="insertext('[b]','[/b]', {{ area }})"><i class="fa fa-bold"></i> {{ lang['tags.bold'] }}</a>
			<a href="#" class="dropdown-item" onclick="insertext('[i]','[/i]', {{ area }})"><i class="fa fa-italic"></i> {{ lang['tags.italic'] }}</a>
			<a href="#" class="dropdown-item" onclick="insertext('[u]','[/u]', {{ area }})"><i class="fa fa-underline"></i> {{ lang['tags.underline'] }}</a>
			<a href="#" class="dropdown-item" onclick="insertext('[s]','[/s]', {{ area }})"><i class="fa fa-strikethrough"></i> {{ lang['tags.crossline'] }}</a>
		</div>
	</div>

	<div class="btn-group btn-group-sm mr-2">
		<button id="tags-align" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="fa fa-align-left"></i>
		</button>
		<div class="dropdown-menu" aria-labelledby="tags-align">
			<a href="#" class="dropdown-item" onclick="insertext('[left]','[/left]', {{ area }})"><i class="fa fa-align-left"></i> {{ lang['tags.left'] }}</a>
			<a href="#" class="dropdown-item" onclick="insertext('[center]','[/center]', {{ area }})"><i class="fa fa-align-center"></i> {{ lang['tags.center'] }}</a>
			<a href="#" class="dropdown-item" onclick="insertext('[right]','[/right]', {{ area }})"><i class="fa fa-align-right"></i> {{ lang['tags.right'] }}</a>
			<a href="#" class="dropdown-item" onclick="insertext('[justify]','[/justify]', {{ area }})"><i class="fa fa-align-justify"></i> {{ lang['tags.justify'] }}</a>
		</div>
	</div>

	<div class="btn-group btn-group-sm mr-2">
		<button id="tags-block" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="fa fa-quote-left"></i>
		</button>
		<div class="dropdown-menu" aria-labelledby="tags-block">
			<a href="#" class="dropdown-item" onclick="insertext('[ul]\n[li][/li]\n[li][/li]\n[li][/li]\n[/ul]','', {{ area }})"><i class="fa fa-list-ul"></i> {{ lang['tags.bulllist'] }}</a>
			<a href="#" class="dropdown-item" onclick="insertext('[ol]\n[li][/li]\n[li][/li]\n[li][/li]\n[/ol]','', {{ area }})"><i class="fa fa-list-ol"></i> {{ lang['tags.numlist'] }}</a>
			<div class="dropdown-divider"></div>
			<a href="#" class="dropdown-item" onclick="insertext('[code]','[/code]', {{ area }})"><i class="fa fa-code"></i> {{ lang['tags.code'] }}</a>
			<a href="#" class="dropdown-item" onclick="insertext('[quote]','[/quote]', {{ area }})"><i class="fa fa-quote-left"></i> {{ lang['tags.comment'] }}</a>
			<a href="#" class="dropdown-item" onclick="insertext('[spoiler]','[/spoiler]', {{ area }})"><i class="fa fa-list-alt"></i> {{ lang['tags.spoiler'] }}</a>
			<a href="#" class="dropdown-item" onclick="insertext('[acronym=]','[/acronym]', {{ area }})"><i class="fa fa-tags"></i> {{ lang['tags.acronym'] }}</a>
		</div>
	</div>

	<div class="btn-group btn-group-sm mr-2">
		<button id="tags-link" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="fa fa-link"></i>
		</button>
		<div class="dropdown-menu" aria-labelledby="tags-link">
			<a href="#" class="dropdown-item" onclick="insertext('[url]','[/url]', {{ area }})"><i class="fa fa-link"></i> {{ lang['tags.link'] }}</a>
			<a href="#" class="dropdown-item" onclick="insertext('[email]','[/email]', {{ area }})"><i class="fa fa-envelope-o"></i> {{ lang['tags.email'] }}</a>
			<a href="#" class="dropdown-item" onclick="insertext('[img]','[/img]', {{ area }})"><i class="fa fa-file-image-o"></i> {{ lang['tags.image'] }}</a>
		</div>
	</div>

	<div class="btn-group btn-group-sm mr-2">
		<button type="button" data-toggle="modal" data-target="#modal-smiles" class="btn btn-outline-dark">
			<i class="fa fa-smile-o"></i>
		</button>
	</div>
</div>
