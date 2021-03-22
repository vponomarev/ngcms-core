<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6 d-none d-md-block ">
			<h1 class="m-0 text-dark">{l_page-title}</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="{php_self}"><i class="fa fa-home"></i></a></li>

			<li class="breadcrumb-item active" aria-current="page">{l_page-title}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

<form name="form" method="post" action="{php_self}?mod=editcomments">
	<input type="hidden" name="mod" value="editcomments" />
	<input type="hidden" name="newsid" value="{newsid}" />
	<input type="hidden" name="comid" value="{comid}" />
	<input type="hidden" name="poster" value="{author}" />
	<input type="hidden" name="subaction" value="doeditcomment" />

	<div class="row">
		<!-- Left edit column -->
		<div class="col-lg-8">

			<!-- MAIN CONTENT -->
			<div id="maincontent" class="card mb-4">
				<div class="card-header">{l_maincontent}</div>
				<div class="card-body">
					<div class="form-group">
						<label class="">{l_comment}</label>
						<textarea name="comment" class="form-control" rows="10" cols="70">{text}</textarea>
					</div>

					{quicktags}
					<!-- SMILES -->
					<div id="modal-smiles" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="smiles-modal-label" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 id="smiles-modal-label" class="modal-title">Вставить смайл</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body">
									{smilies}
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="">{l_answer}</label>
						<textarea id="content" name="content" class="form-control" rows="10" cols="70">{answer}</textarea>
					</div>
				</div>
			</div>
		</div>

		<!-- Additional edit column -->
		<div id="additional" class="col col-lg-4">
			<div class="card mb-4">
				<div class="card-header">{l_additional}</div>
				<div class="card-body">
					<ul class="list-unstyled">
						<li>{l_date}: <b>{comdate}</b></li>
						<li>{l_ip}: <b><a href="http://www.nic.ru/whois/?ip={ip}" target="_blank">{ip}</a></b></li>
						<li>{l_name}: <b>{author}</b></li>
					</ul>

					<div class="form-group">
						<label>{l_email}:</label>
						<input type="text" name="mail" value="{mail}" class="form-control" />
					</div>

					<div class="form-group mb-0">
						<button type="button" onclick="document.location='{php_self}?mod=ipban&iplock={ip}'" class="btn btn-outline-danger">{l_block_ip}</>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col col-lg-8">
			<div class="row">
				<div class="col-6 mb-4">
					<button type="button" class="btn btn-outline-danger" onclick="confirmit('{php_self}?mod=editcomments&subaction=deletecomment&newsid={newsid}&comid={comid}&poster={author}', '{l_sure_del}')">
						<span class="d-xl-none"><i class="fa fa-trash"></i></span>
						<span class="d-none d-xl-block">{l_delete}</span>
					</button>
				</div>

				<div class="col-6 mb-4 text-right">
					<div class="form-group">
						<button type="submit" class="btn btn-outline-success" accesskey="s">
							<span class="d-xl-none"><i class="fa fa-floppy-o"></i></span>
							<span class="d-none d-xl-block">{l_save}</span>
						</button>
					</div>

					<div class="form-group">
						<label class="d-block">
							<input type="checkbox" name="send_notice" value="send_notice" />
							{l_send_notice}
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
