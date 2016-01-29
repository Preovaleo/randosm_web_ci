<div class='content'>
	<div class='content-box'>
		<?php if($success == 1):?>
			<h1>Compte validé!</h1>
			<br/>Votre compte a bien été validé. Vous pouvez maintenant vous connecter.
		
		 <?php elseif($success == 2):?>
			<br/>Votre compte a déjà été validé.
		 
		<?php elseif($success == 3):?>
			<br/>La clé d'activation envoyé dans cet e-mail ne correspond pas à la clé que nous vous avons envoyé pour ce compte.

		<?php endif; ?>
	</div>
</div>
