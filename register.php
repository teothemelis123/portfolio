<?php
/**
 * Regiter template
 *
 * @category Wodrpress-Plugins
 * @package  WP-FoodTec
 * @author   FoodTec Solutions <info@foodtecsolutions.com>
 * @license  GPLv2 or later
 * @link     https://gitlab.foodtecsolutions.com/fts/wordpress/plugins/wp-foodtec
 * @since    1.0.0
 *
 * @phan-file-suppress PhanUndeclaredGlobalVariable $args
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<form method="post" class="form-horizontal foodtec-register register-form" data-nonce="<?php echo $args['nonce']; ?>" >
	

	<div class="form-group form-group-lg required ">
		<label for="registration-firstname" class="col-sm-3 control-label">
			<?php _e( 'First Name:', 'wp-foodtec' ); ?>
			<span class="required-asterisk" aria-hidden="true">*</span>
			<span class="sr-only"><?php _e( 'Required', 'wp-foodtec' ); ?></span>
		</label>
		<div class="col-sm-9">
			<input
				class="form-control"
				placeholder="<?php _e( 'enter your first name', 'wp-foodtec' ); ?>"
				type="text" name="firstName"
				id="registration-firstname"
				autocomplete="firstName"
				required>
		</div>
	</div>

	<div class="form-group form-group-lg required ">
		<label for="registration-lastname" class="col-sm-3 control-label">
			<?php _e( 'Last Name:', 'wp-foodtec' ); ?>
			<span class="required-asterisk" aria-hidden="true">*</span>
			<span class="sr-only"><?php _e( 'Required', 'wp-foodtec' ); ?></span>
		</label>
		<div class="col-sm-9">
			<input
				class="form-control"
				placeholder="<?php _e( 'enter your last name', 'wp-foodtec' ); ?>"
				type="text" name="lastName"
				id="registration-lastname"
				autocomplete="lastName"
				required>
		</div>
	</div>

	<div class="form-group form-group-lg">
		<label for="registration-phone" class="col-sm-3 control-label">
			<?php _e( 'Mobile Phone:', 'wp-foodtec' ); ?>
			<span class="required-asterisk" aria-hidden="true">*</span>
			<span class="sr-only"><?php _e( 'Required', 'wp-foodtec' ); ?></span>
		</label>
		<div class="col-sm-9">
			<input
				class="form-control"
				placeholder="We may use this number to call you."
				type="tel"
				id="registration-phone"
				name="phone"
				autocomplete="tel"
				maxlength="14"
				pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
				required>
		</div>
	</div>
	<div class="form-group form-group-lg required ">
		<label for="registration-email" class="col-sm-3 control-label">
			<?php _e( 'Email:', 'wp-foodtec' ); ?>
			<span class="required-asterisk" aria-hidden="true">*</span>
			<span class="sr-only"><?php _e( 'Required', 'wp-foodtec' ); ?></span>
		</label>
		<div class="col-sm-9">
			<input
				class="form-control"
				placeholder="<?php _e( "We'll never share your email.", 'wp-foodtec' ); ?>"
				type="email"
				name="email"
				autocomplete="email"
				id="registration-email" required>
		</div>
	</div>

	<div class="form-group form-group-lg required ">
		<label for="registration-password" class="col-sm-3 control-label">
			<?php _e( 'Password:', 'wp-foodtec' ); ?>
			<span class="required-asterisk" aria-hidden="true">*</span>
			<span class="sr-only"><?php _e( 'Required', 'wp-foodtec' ); ?></span>
		</label>
		<div class="col-sm-9">
			<input
				class="form-control"
				placeholder="<?php _e( 'enter password', 'wp-foodtec' ); ?>"
				type="password"
				name="password"
				autocomplete="new-password"
				id="registration-password"
				required>
		</div>
	</div>
	<?php echo $args['store_select']; ?>
	<?php
	if ($args['birthday']) :
		?>
		<div class="form-group form-group-lg required ">
			<label for="birthMonth" class="col-sm-3 control-label">
				<span class="sr-only"><?php _e( 'Required', 'wp-foodtec' ); ?></span>
				<?php _e( 'Date Of Birth:', 'wp-foodtec' ); ?>
			</label>
			<div class="col-sm-9">
				<label class="sr-only" for="registration-month-of-birth"><?php _e( 'Month of birth:', 'wp-foodtec' ); ?></label>
				<select class="form-control form-select" id="registration-month-of-birth" name="birthMonth">
					<option value="" selected="selected">Month</option>
					<?php for ($wp_foodtec_i = 1; $wp_foodtec_i <= 12; $wp_foodtec_i++) { ?>
						<option value="<?php echo strval( $wp_foodtec_i ); ?>"><?php echo str_pad( strval( $wp_foodtec_i ), 2, '0', STR_PAD_LEFT ); ?></option>
					<?php } ?>
				</select>
				<label class="sr-only" for="registration-day-of-birth"><?php _e( 'Day of birth:', 'wp-foodtec' ); ?></label>
				<select class="form-control form-select" id="registration-day-of-birth" name="birthDay">
					<option value="" selected="selected">Day</option>
					<?php for ($wp_foodtec_i = 1; $wp_foodtec_i <= 31; $wp_foodtec_i++) { ?>
						<option value="<?php echo strval( $wp_foodtec_i ); ?>"><?php echo str_pad( strval( $wp_foodtec_i ), 2, '0', STR_PAD_LEFT ); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	<?php
	endif;

	if (sizeof( $args['loyalty_plans'] )) :
		$wp_foodtec_plan_options = $args['loyalty_plans'][0];
		?>

		<div class="loyalty-identifiers hidden">

			<?php
			// phpcs:disable
			if ($wp_foodtec_plan_options->hasLoyaltyCards) :
				?>
				<div class="form-group form-group-lg <?= $wp_foodtec_plan_options->needsLoyaltyCards ? 'required' : '' ?>">
					<label for="registration-loyalty-card" class="col-sm-3 control-label">
						<span class="required-asterisk" aria-hidden="true">*</span>
						<span class="sr-only"><?php _e( 'Required', 'wp-foodtec' ); ?></span>
						<?php _e( 'Loyalty Card:', 'wp-foodtec' ); ?>
					</label>
					<div class="col-sm-9">
						<input
							class="form-control"
							placeholder="<?php _e( 'enter your loyalty card', 'wp-foodtec' ); ?>"
							type="text"
							id="registration-loyalty-card"
							name="loyaltyCard"
							<?= $wp_foodtec_plan_options->needsLoyaltyCards ? 'required' : '' ?>>
					</div>
				</div>
			<?php
			endif;

			if ($wp_foodtec_plan_options->hasGiftCards) :
				?>
				<div class="form-group form-group-lg <?= $wp_foodtec_plan_options->needsLoyaltyCards ? 'required' : '' ?>">
					<label for="registration-gift-card" class="col-sm-3 control-label"><?php _e('Gift Card:', 'wp-foodtec'); ?></label>
					<div class="col-sm-9">
						<input
							class="form-control"
							placeholder="<?php _e('enter your gift card', 'wp-foodtec'); ?>"
							type="text"
							id="registration-gift-card"
							name="giftCard"
							<?= $wp_foodtec_plan_options->needsGiftCards ? 'required' : '' ?>>
					</div>
				</div>
			<?php
			endif;
			// phpcs:enable
			?>

		</div>

		<div class="form-group form-group-lg">
			<div class="col-sm-offset-3 col-sm-9">
				<div class="checkbox ">
					<label class="control-label text-left">
						<input type="checkbox" name="signupForLoyalty" checked>
						<?php
						_e( ' Sign up for ', 'wp-foodtec' );
						echo $wp_foodtec_plan_options->name;
						?>
					</label>
				</div>
			</div>
		</div>

	<?php
	endif;
	?>

	<div class="form-group form-group-lg channels">
		<div class="col-sm-offset-3 col-sm-9">
			<h4 style="margin-bottom: 0;">Receive exclusive offers and promotions via</h4>
			<div class="checkbox ">
				<label class="control-label text-left">
					
					<input type="checkbox" name="emailOffers" checked >
					<?php _e( 'E-mail', 'wp-foodtec' ); ?>
				</label>
				<?php if ($args['has_sms']): ?>
					<br>
					<label class="control-label text-left">
						<input type="checkbox" name="smsOffers">
						<?php _e( 'SMS', 'wp-foodtec' ); ?>
					</label>
					<input type="hidden" value="" name="smsDisclaimer">
					<script>
						document.addEventListener("DOMContentLoaded", function() {
							var smsDisclaimerText = document.querySelector("#sms-disclaimer small").textContent;
							var hiddenInput = document.querySelector("input[name='smsDisclaimer']");
							hiddenInput.value = smsDisclaimerText;
						});
					</script>
					<p id="sms-disclaimer"><small>By checking this box, you are agreeing to our Terms and Conditions and <a href="/privacy-policy">Privacy Policy</a>. Msg&Data rates may apply. Message frequency may vary. STOP to opt out.</small></p>
				<?php endif;?>




			</div>
		</div>
	</div>

	<div class="form-group form-group-lg">
		<div class="col-sm-offset-3 col-sm-9">
			<div class="checkbox ">
				<label class="control-label text-left">
					<input type="checkbox" name="acceptPolicy" required>
					<?php
					_e( ' I am 13 years of age or older', 'wp-foodtec' );
					if (get_privacy_policy_url()) :
						_e( ' and I accept the ', 'wp-foodtec' );
						?>
						<a style="display: flex;" href="<?php echo get_privacy_policy_url(); ?>" target="_blank">privacy policy</a>
					<?php
					endif;
					?>
				</label>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php echo $args['recaptcha']; ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<p class="alert hidden" tabindex="-1"></p>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button type="submit" class="btn btn-primary btn-lg"><?php _e( 'Sign Up', 'wp-foodtec' ); ?></button>
		</div>
	</div>

</form>
