<?php
$user = sb_get_current_user();
?>
<div id="sidebar" class="col-md-2">
	<div id="user-info">
		<div class="row">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="image">
					<img src="<?php print $user->GetAvatar(); ?>" alt="" />
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div style="word-wrap:break-word;">
					Bienvenid@<br/>
					<?php print $user->first_name; ?>
				</div>
			</div>
		</div>
	</div><!-- end id="user-info" -->
	<div id="user-menu">
		<ul>
			<li>
				<a href="<?php print SB_Route::_('index.php?tpl_file=new'); ?>">
					<span class="glyphicon glyphicon-pencil"></span> Nuevo Guion
				</a>
			</li>
			<li>
				<a href="<?php print SB_Route::_('index.php?mod=scripts'); ?>">
					<span class="glyphicon glyphicon-book"></span> Abrir Guion
				</a>
			</li>
			<li>
				<a href="http://500sitios.com/expertuso" target="_blank">
					<span class="glyphicon glyphicon-book"></span> Formacion
				</a>
			</li>
			<li>
				<a href="http://500sitios.com/expertbonos" target="_blank">
					<span class="glyphicon glyphicon-book"></span> Bonos
				</a>
			</li>
		</ul>
	</div>
</div><!-- end id="sidebar" -->