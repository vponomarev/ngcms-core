<tr align="left" class="contHead">
<td class="contentEntry1" width="50%">{dirname}</td>
<td class="contentEntry1" width="50%">
<a href="{php_self}?mod=templates&action=themedelete&theme_name={dirname}&where={where}">{l_delete}</a>
 | <a href="{php_self}?mod=templates&action=newtheme&theme_name={dirname}&where={where}" onclick="if(ren=window.prompt('{l_name}:','{dirname}')){ window.location.href=this.href+'&new_theme_name='+ren; } return false;">{l_themenew}</a>
 | <a href="{php_self}?mod=templates&action=themerename&theme_name={dirname}&where={where}" onclick="if(ren=window.prompt('{l_name}:','{dirname}')){ window.location.href=this.href+'&new_theme_name='+ren; } return false;">{l_rename}</a>
</td>
</tr>