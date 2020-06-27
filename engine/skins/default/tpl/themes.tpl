<!-- Navigation bar -->
<ul class="breadcrumb">
    <li><a href="admin.php">{{ lang['home'] }}</a></li>
    <li class="active">{{ lang.themes['title'] }}</li>
</ul>

<!-- Info content -->
<div class="page-main">
    <div class="row">
        
    </div>
</div>

<script>
if (typeof(localStorage) == "undefined") {
 $.notify({message: 'Sorry! No Web Storage support . . .'},{type: 'danger'});
}
</script>