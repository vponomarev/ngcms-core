
<div class="full">
  <h1>{l_pm:inbox}</h1>
  <div class="pad20_f">
    <div class="btn-group">
      <a href="/plugin/pm/" class="btn">{l_pm:inbox}</a>
      <a href="/plugin/pm/?action=outbox" class="btn">{l_pm:outbox}</a>
    </div>
    <div class="clear20"></div>
    <table class="table">
      <form name="form" method="POST" action="{php_self}?action=delete">
        <tr>
          <th>{l_pm:date}</th>
          <th>{l_pm:subject}</th>
          <th>{l_pm:from}</th>
          <th>{l_pm:state}</th>
          <th><input class="check" type="checkbox" name="master_box" title="{l_pm:checkall}" onclick="javascript:check_uncheck_all(form)"></th>
        </tr>
        <tr>
          <td colspan="5">{entries}</td>
        </tr>
        <tr align="center">
          <td colspan="5" >
          <div class="btn-group">
            <input class="btn" type="submit" value="{l_pm:delete}">
            <div class="clear20"></div>
          </div>
        </form>
<form name="pm" method="POST" action="{php_self}?action=write">        <input class="btn btn-large btn-primary" type="submit" value="{l_pm:write}">
</form>  
     
      </td>
      </tr>  
    </table>
  </div>
</div>

