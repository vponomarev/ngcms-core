
<script type="text/javascript">
 var vajax = new sack();
 function make_voteL(mode, vid){
  var form = document.getElementById('voteForm_'+vid);
  var choice = -1;
  for (i=0;i<form.elements.length;i++) {
  	var elem = form.elements[i];
  	if (elem.type == 'radio') {
  		if (elem.checked == true) {
  			choice = elem.value;
  		}
  	}
  }	

  if (choice < 0) {
  	alert('Сначала необходимо выбрать вариант!');
  	return false;
  }	

  vajax.setVar("mode", "vote");
  vajax.setVar("style","ajax");
  vajax.setVar("list","1");
  vajax.setVar("choice", choice);
  vajax.requestFile = "{post_url}";
  vajax.method = 'POST';
  vajax.element = 'zz_voting_'+vid;
  vajax.runAJAX();
  return false;
 }
</script>
