


<div class="full">
  <h1>Ответить</h1>
  <div class="pad20_f">
    <div class="btn-group">
      <a href="/plugin/pm/" class="btn">{l_pm:inbox}</a>
      <a href="/plugin/pm/?action=outbox" class="btn">{l_pm:outbox}</a>
    </div>
<div class="clear20"></div>
  <form method=post name=form action="{php_self}?action=send">
<input type="hidden" name="title" value="{title}">
<input type="hidden" name="sendto" value="{sendto}">  
{quicktags}<br />{smilies}

<div>
  <textarea name="content" id="content" class="textarea" tabindex="1" maxlength="3000" /></textarea>
</div>

<div>
  <label>{l_pm:saveoutbox}&nbsp;&nbsp;<input name="saveoutbox" class="check" type="checkbox"/></label>
</div>  

 <input class="btn btn-large btn-primary" type="submit" value="{l_pm:send}" accesskey="s" /> 
 </form> 
 <div class="clear20"></div> 
    </div>
    </div>
 
