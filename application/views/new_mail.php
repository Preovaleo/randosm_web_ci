<!DOCTYPE html>

<div class="content">
   	<div class="content-box">
		<?php if($success == 1):?>
		<h1>Inscription</h1>
		<br/>Un nouveau mail a bien été envoyé.

		<?php elseif($success == 2):?>
		<h1>Nouveau mot de passe</h1>
		<br/>Un mail permettant la modification de votre mot de passe a été envoyé.

		<?php endif; ?>
	</div>
</div>
