<div class="login-form">
<div class="row">
	<div class="tab-wrap">
	<div class="pane login-box active">
		<h3><?php _e('Login', 'theme_front'); ?></h3>
		<form name="loginform" id="loginform" class="validate-form" action="<?php echo wp_login_url(get_permalink()); ?>" method="post">
			<input type="hidden" name="rememberme" value="forever" />
			<p class="login-username">
				<label for="user_login"><?php _e('Username', 'theme_front'); ?> <span>*</span></label>
				<input type="text" name="log" id="user_login" class="input" value="" size="20" data-rule-required="true" data-msg-required="<?php _e('Username is required.', 'theme_front'); ?>">
			</p>
			<p class="login-password">
				<label for="user_pass"><?php _e('Password', 'theme_front'); ?> <span>*</span></label>
				<input type="password" name="pwd" id="user_pass" class="input" value="" size="20" data-rule-required="true" data-msg-required="<?php _e('Password is required.', 'theme_front'); ?>">
			</p>
			<div class="form-response"></div>
			<p class="login-submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="primary" value="<?php _e('Log In', 'theme_front'); ?>">
				<input type="hidden" name="redirect_to" value="<?php echo esc_attr(home_url()); ?>">
			</p>
		</form>
		<div class="vspace"></div>
	</div>
	<div class="pane register-box">
		<h3><?php _e('Register', 'theme_front'); ?></h3>
		<form action="<?php echo wp_login_url(); ?>?action=register" id="register-form" class="validate-form" method="post">
             <input type="hidden" name="user-cookie" value="1">
             <input type="hidden" name="redirect_to" value="<?php echo home_url(); ?>" />
            <p>
                <label for="register-username"><?php _e('Username', 'theme_front'); ?> <span>*</span></label>
                <input id="register-username" name="user_login" type="text" data-rule-required="true" data-msg-required="<?php _e('Username is required.', 'theme_front'); ?>">
            </p>

            <p>
                <label for="register-email"><?php _e('E-mail', 'theme_front'); ?> <span>*</span></label>
                <input id="register-email" name="user_email" type="text" data-rule-required="true" data-msg-required="<?php _e('E-mail is required.', 'theme_front'); ?>" data-rule-email="true" data-msg-email="<?php _e('Invalid E-mail address.', 'theme_front'); ?>">
            </p>
            <div class="form-response"></div>
           	<p class="login-submit">
            	<input type="submit" name="user-submit" class="primary" value="<?php _e('Register', 'theme_front'); ?>">
            </p>
        </form>
		<div class="vspace"></div>
	</div>
	<div class="pane forgot-box">
		<h3><?php _e('Forgot Password', 'theme_front'); ?></h3>
		<form action="<?php echo wp_login_url(); ?>?action=lostpassword" id="forgot-form" method="post" class="validate-form">
			<input type="hidden" name="user-cookie" value="1">
            <p>
                <label for="user-forgot"><?php _e('Username or E-mail', 'theme_front'); ?> <span>*</span></label>
                <input id="user-forgot" name="user_login" type="text" data-rule-required="true" data-msg-required="<?php _e('Username or E-mail is required.', 'theme_front'); ?>">
            </p>
            <div class="form-response"></div>
            <p class="login-submit">
            	<input type="submit" name="user-submit" class="primary" value="<?php _e('Reset Password', 'theme_front'); ?>">
            </p>
        </form>
		<div class="vspace"></div>
	</div>
	<ul class="tab-list">
		<li class="active"><a href="#" data-pane="login-box"><?php _e('Login', 'theme_front'); ?></a></li>
		<li><a href="#" data-pane="register-box"><?php _e('Register', 'theme_front'); ?></a></li>
		<li><a href="#" data-pane="forgot-box"><?php _e('Forgot Password', 'theme_front'); ?></a></li>
	</ul>
	</div>
</div>
</div>
