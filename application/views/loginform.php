<div class='content'>
    <div class='connect-box content-box'>
            <h1>Connexion</h1><br>
            <?php
                echo form_open('admin/login', "class='login-form'");
                
                $attributes2 = array(
                    'for' => 'password',
                    'class' => 'icon-user login-field-icon',
                );
                
                $attributes4 = array(
                    'for' => 'login',
                    'class' => 'icon-lock login-field-icon',
                );
                $attributes1 = array(
                    'name'        => 'username',
                    'class'       => 'login-field',
                    'id'          => 'username',
                    'placeholder' => 'Identifiant',
                    'autofocus'   => 'username',
		    'required'    => 'username'
                );
                $attributes3 = array(
                    'name'        => 'password',
                    'class'       => 'login-field',
                    'id'          => 'password',
                    'placeholder' => 'Mot de passe',
                    'required'    => 'password'
                );
                
                
                echo form_input($attributes1);
                echo form_label('', 'username', $attributes2);
                
                echo form_password($attributes3);
                echo form_label('', 'password', $attributes4);
                
                echo form_submit('submit','Connexion', "class='button'");
                
                echo "<a href='". base_url() ."pw_forgotten'>Mot de passe oublié ?</a>";
                
                echo form_close();
                
                ?>
                <div class="errors">
                    <p><?php echo validation_errors();?></p>
                    <p><?php echo @$error_credentials;?></p>
                </div>
    </div>
</div>
