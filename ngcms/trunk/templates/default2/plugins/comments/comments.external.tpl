<div class="post">
	<div class="post-header">
		<div class="post-title"><a href="{link}">{title}</a></div>
	</div>
	<div style="height: 10px;"></div>
	<div class="post-text">
		<p>[comheader]��� ����������� ����������� � ������ �������[/comheader]</p>
	</div>
	<div class="post-footer">
		<a class="btn" href="{link}">�����</a>
	</div>
</div>
<div id="comments">
	[comheader]<div class="title">����������:</div>[/comheader]
	<!-- Here is user's comments -->
	<div id="new_comments_rev"></div>
	{entries}
	<!-- Here is `add comments` form -->
	[regonly]<div class="ng-message">������ ������������������ ������������ ����� ��������� � ������ ������� ���� �����������.</div>[/regonly]
	[commforbidden]<div class="ng-message">��������������� ������ ������� ���������.</div>[/commforbidden]
	[more_comments]
	<div class="paginator nonebr" style="margin-top: 25px; margin-bottom: 25px;">
		<ul>
			<li>{more_comments}</li>
		</ul>
	</div>
	[/more_comments]
	{form}
</div>