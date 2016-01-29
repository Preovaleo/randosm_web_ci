<div class='content'>
	<div class='content-box'>
		<h1>Mot de passe oublié ?</h1>
		
		
		Rentrez votre pseudo :
		<br/>

		<?=form_open('pw_forgotten/pw_change_mail'); ?>

		    	<label for="pseudo">Pseudo :</label>
		   	<input type="text" name="pseudo" id="pseudo" />
		    	<br />
		    
		    	<button class="button2 icon-valid">
		        <span>Valider</span>
		    	</button>

            	<?=form_close();?>

		<br/>

		<?php if(isset($success)):?>
			<div class="errors">
				Pseudo inexistant.
			</div>
		<?php endif; ?>
		
	</div>
</div>
