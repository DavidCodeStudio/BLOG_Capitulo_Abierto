<?php
/** Funciones del tema Capítulo Abierto. */
if (!defined('ABSPATH')) { exit; }
add_action('after_setup_theme', function () {
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_editor_style('style.css');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
});
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('capitulo-abierto-blog', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));
});
add_action('init', function () {
    register_block_pattern_category('capitulo', array('label' => __('Capítulo Abierto', 'capitulo-abierto-blog')));
});
/** El formulario se puede mover en el Editor usando el bloque Shortcode: [capitulo_newsletter]. */
add_shortcode('capitulo_newsletter', function () {
    $state = isset($_GET['ca_newsletter']) ? sanitize_key(wp_unslash($_GET['ca_newsletter'])) : '';
    ob_start(); ?>
    <div class="ca-newsletter-form-wrap">
      <p class="ca-newsletter-heading">✉ Newsletter</p>
      <?php if ($state === 'ok'): ?><p class="ca-newsletter-message" role="status">Solicitud enviada. Revisa tu correo para futuras comunicaciones.</p><?php endif; ?>
      <?php if ($state === 'error'): ?><p class="ca-newsletter-message" role="alert">No se ha podido enviar. Inténtalo más tarde.</p><?php endif; ?>
      <?php if ($state === 'invalid'): ?><p class="ca-newsletter-message" role="alert">Comprueba el correo y acepta la política de privacidad.</p><?php endif; ?>
      <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="ca-newsletter-form">
        <input type="hidden" name="action" value="capitulo_newsletter_signup">
        <?php wp_nonce_field('capitulo_newsletter_signup', 'ca_newsletter_nonce'); ?>
        <label class="screen-reader-text" for="ca-newsletter-email">Tu correo electrónico</label>
        <input id="ca-newsletter-email" type="email" name="ca_email" autocomplete="email" placeholder="Tu correo electrónico" required maxlength="254">
        <div class="ca-newsletter-hp" aria-hidden="true"><label for="ca-website">Deja este campo vacío</label><input type="text" id="ca-website" name="ca_website" tabindex="-1" autocomplete="off"></div>
        <button type="submit">Suscribirme</button>
        <label class="ca-newsletter-consent"><input type="checkbox" name="ca_consent" value="1" required> He leído y acepto la <a href="<?php echo esc_url(home_url('/privacidad/')); ?>">política de privacidad</a>.</label>
      </form>
    </div><?php
    return ob_get_clean();
});
/** Notificación a la bandeja elegida; NO equivale a una lista de distribución. */
function capitulo_newsletter_handle() {
    $nonce = isset($_POST['ca_newsletter_nonce']) ? sanitize_text_field(wp_unslash($_POST['ca_newsletter_nonce'])) : '';
    if (!wp_verify_nonce($nonce, 'capitulo_newsletter_signup')) {
        wp_die('Formulario caducado. Vuelve atrás e inténtalo de nuevo.', 'Error de validación', array('response' => 403));
    }
    $return = wp_get_referer();
    $return = $return ? wp_validate_redirect($return, home_url('/')) : home_url('/');
    $return = remove_query_arg('ca_newsletter', $return);
    if (!empty($_POST['ca_website'])) {
        wp_safe_redirect(add_query_arg('ca_newsletter', 'ok', $return) . '#newsletter'); exit;
    }
    $email = isset($_POST['ca_email']) ? sanitize_email(wp_unslash($_POST['ca_email'])) : '';
    $consent = isset($_POST['ca_consent']) && wp_unslash($_POST['ca_consent']) === '1';
    if (!is_email($email) || !$consent) {
        wp_safe_redirect(add_query_arg('ca_newsletter', 'invalid', $return) . '#newsletter'); exit;
    }
    $key = 'ca_subscribe_' . md5(strtolower($email));
    if (get_transient($key)) {
        wp_safe_redirect(add_query_arg('ca_newsletter', 'ok', $return) . '#newsletter'); exit;
    }
    $subject = 'Solicitud de newsletter — Capítulo Abierto';
    $body = "Nueva solicitud de información de la newsletter de Capítulo Abierto.\n\nCorreo: " . $email . "\nConsentimiento del formulario: sí\nFecha: " . wp_date('d/m/Y H:i') . "\n\nEste formulario no da de alta automáticamente al usuario en un servicio de newsletter. Verifica el consentimiento y utiliza un proveedor con doble opt-in para envíos masivos.";
    $sent = wp_mail('infods.365@gmail.com', $subject, $body, array('Content-Type: text/plain; charset=UTF-8'));
    if ($sent) { set_transient($key, 1, HOUR_IN_SECONDS); }
    wp_safe_redirect(add_query_arg('ca_newsletter', $sent ? 'ok' : 'error', $return) . '#newsletter'); exit;
}
add_action('admin_post_nopriv_capitulo_newsletter_signup', 'capitulo_newsletter_handle');
add_action('admin_post_capitulo_newsletter_signup', 'capitulo_newsletter_handle');
