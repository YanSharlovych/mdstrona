<?php
/**
 * Secure contact form rendering and processing.
 *
 * @package Aluteco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the ALUTECO contact form.
 *
 * @return string
 */
function aluteco_render_contact_form() {
	$status = isset( $_GET['contact_status'] ) ? sanitize_key( wp_unslash( $_GET['contact_status'] ) ) : '';

	ob_start();
	?>
	<form class="message-form reveal" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-contact-form>
		<input type="hidden" name="action" value="aluteco_contact">
		<input type="hidden" name="aluteco_started" value="<?php echo esc_attr( time() ); ?>">
		<?php wp_nonce_field( 'aluteco_contact_submit', 'aluteco_contact_nonce' ); ?>

		<label for="aluteco-name"><?php esc_html_e( 'Full name', 'aluteco' ); ?></label>
		<input id="aluteco-name" name="name" type="text" autocomplete="name" placeholder="<?php esc_attr_e( 'Full Name', 'aluteco' ); ?>" required>

		<label for="aluteco-email"><?php esc_html_e( 'Email address', 'aluteco' ); ?></label>
		<input id="aluteco-email" name="email" type="email" autocomplete="email" placeholder="<?php esc_attr_e( 'Email Address', 'aluteco' ); ?>" required>

		<label for="aluteco-phone"><?php esc_html_e( 'Phone number', 'aluteco' ); ?></label>
		<input id="aluteco-phone" name="phone" type="tel" autocomplete="tel" placeholder="<?php esc_attr_e( 'Phone Number', 'aluteco' ); ?>">

		<label for="aluteco-message"><?php esc_html_e( 'Message', 'aluteco' ); ?></label>
		<textarea id="aluteco-message" name="message" rows="6" placeholder="<?php esc_attr_e( 'Message', 'aluteco' ); ?>" required></textarea>

		<div class="aluteco-honeypot" aria-hidden="true">
			<label for="aluteco-company"><?php esc_html_e( 'Company website', 'aluteco' ); ?></label>
			<input id="aluteco-company" name="company" type="text" tabindex="-1" autocomplete="off">
		</div>

		<button class="button" type="submit"><?php esc_html_e( 'Send message', 'aluteco' ); ?> <span>&rarr;</span></button>

		<?php if ( 'success' === $status ) : ?>
			<p class="form-status is-success" role="status"><?php esc_html_e( 'Thank you. Your message has been sent.', 'aluteco' ); ?></p>
		<?php elseif ( 'invalid' === $status ) : ?>
			<p class="form-status is-error" role="alert"><?php esc_html_e( 'Please check the form fields and try again.', 'aluteco' ); ?></p>
		<?php elseif ( 'error' === $status ) : ?>
			<p class="form-status is-error" role="alert"><?php esc_html_e( 'The message could not be sent. Please try again later.', 'aluteco' ); ?></p>
		<?php endif; ?>
	</form>
	<?php

	return (string) ob_get_clean();
}
add_shortcode( 'aluteco_contact_form', 'aluteco_render_contact_form' );

/**
 * Redirect back to the form with a status code.
 *
 * @param string $status Public status code.
 */
function aluteco_contact_redirect( $status ) {
	$referer = wp_get_referer();
	$target  = $referer ? $referer : home_url( '/contact/' );
	$target  = remove_query_arg( 'contact_status', $target );

	wp_safe_redirect( add_query_arg( 'contact_status', sanitize_key( $status ), $target ) );
	exit;
}

/**
 * Process a submitted contact form.
 */
function aluteco_handle_contact_form() {
	if (
		! isset( $_POST['aluteco_contact_nonce'] ) ||
		! wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['aluteco_contact_nonce'] ) ),
			'aluteco_contact_submit'
		)
	) {
		aluteco_contact_redirect( 'invalid' );
	}

	$honeypot = isset( $_POST['company'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['company'] ) ) ) : '';
	$started  = isset( $_POST['aluteco_started'] ) ? absint( $_POST['aluteco_started'] ) : 0;
	$elapsed  = time() - $started;

	if ( '' !== $honeypot || $elapsed < 2 || $elapsed > 2 * DAY_IN_SECONDS ) {
		aluteco_contact_redirect( 'invalid' );
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if (
		strlen( $name ) < 2 ||
		! is_email( $email ) ||
		strlen( $message ) < 10 ||
		strlen( $message ) > 5000
	) {
		aluteco_contact_redirect( 'invalid' );
	}

	$subject = sprintf(
		/* translators: %s: contact name. */
		__( 'ALUTECO website enquiry from %s', 'aluteco' ),
		$name
	);

	$body = implode(
		"\n\n",
		array(
			sprintf( '%s: %s', __( 'Name', 'aluteco' ), $name ),
			sprintf( '%s: %s', __( 'Email', 'aluteco' ), $email ),
			sprintf( '%s: %s', __( 'Phone', 'aluteco' ), $phone ? $phone : __( 'Not provided', 'aluteco' ) ),
			sprintf( "%s:\n%s", __( 'Message', 'aluteco' ), $message ),
		)
	);

	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );
	$sent    = wp_mail( get_option( 'admin_email' ), $subject, $body, $headers );

	aluteco_contact_redirect( $sent ? 'success' : 'error' );
}
add_action( 'admin_post_aluteco_contact', 'aluteco_handle_contact_form' );
add_action( 'admin_post_nopriv_aluteco_contact', 'aluteco_handle_contact_form' );

/**
 * Use a valid sender before PHPMailer validates the message in local Docker.
 *
 * @param string $from Default sender address.
 * @return string
 */
function aluteco_local_mail_from( $from ) {
	if ( ! getenv( 'ALUTECO_SMTP_HOST' ) ) {
		return $from;
	}

	$admin_email = sanitize_email( get_option( 'admin_email' ) );

	return is_email( $admin_email ) ? $admin_email : $from;
}
add_filter( 'wp_mail_from', 'aluteco_local_mail_from' );

/**
 * Use the site name as the local sender name.
 *
 * @param string $name Default sender name.
 * @return string
 */
function aluteco_local_mail_from_name( $name ) {
	if ( ! getenv( 'ALUTECO_SMTP_HOST' ) ) {
		return $name;
	}

	return wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
}
add_filter( 'wp_mail_from_name', 'aluteco_local_mail_from_name' );

/**
 * Route local mail through Mailpit when Docker provides SMTP settings.
 *
 * @param PHPMailer\PHPMailer\PHPMailer $mailer WordPress mailer.
 */
function aluteco_configure_local_mailer( $mailer ) {
	$host = getenv( 'ALUTECO_SMTP_HOST' );
	$port = absint( getenv( 'ALUTECO_SMTP_PORT' ) );

	if ( ! $host ) {
		return;
	}

	$mailer->isSMTP();
	$mailer->Host       = sanitize_text_field( $host );
	$mailer->Port       = $port ? $port : 1025;
	$mailer->SMTPAuth   = false;
	$mailer->SMTPAutoTLS = false;

	$from_email = sanitize_email( get_option( 'admin_email' ) );

	if ( is_email( $from_email ) ) {
		$mailer->setFrom(
			$from_email,
			wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
			false
		);
	}
}
add_action( 'phpmailer_init', 'aluteco_configure_local_mailer' );

/**
 * Record transport failures in the WordPress debug log for local diagnosis.
 *
 * @param WP_Error $error Mail transport error.
 */
function aluteco_log_mail_failure( $error ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( 'ALUTECO wp_mail failure: ' . $error->get_error_message() );
	}
}
add_action( 'wp_mail_failed', 'aluteco_log_mail_failure' );
