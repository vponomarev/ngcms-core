
<div class="full">
  <h1>{l_pm:new}</h1>
  <div class="pad20_f">
    <div class="btn-group">
      <a href="/plugin/pm/" class="btn">{l_pm:inbox}</a>
      <a href="/plugin/pm/?action=outbox" class="btn">{l_pm:outbox}</a>
    </div>
    <div class="clear20"></div>
 <form method="POST" action="{php_self}?action=delete&pmid={pmid}&location={location}">
<input type="hidden" name="title" value="{subject}">
<input type="hidden" name="from" value="{from}">   
{content}
<div class="clear20"></div>
<div class="btn-group">
  <input class="btn btn-warning" type="submit" value="{l_pm:delete_one}">
</div>
 </form>   
  
  </div>
</div>


 [if-inbox]
<div class="full">
  <div class="pad20">
    <form name="pm" method="POST" action="{php_self}?action=reply&pmid={pmid}">
    <input class="btn btn-large btn-primary" type="submit" value="{l_pm:reply}"></form>
  </div>
</div>
[/if-inbox] 

