<?php
/**
 * avoid-mail-spam.php
 * Shortcode: [contact_email to="info" label="Mostra el correu" class="btn secondary"]
 */

defined('ABSPATH') || exit;

/**
 * Config de correus (3 opcions).
 * Canvia aquí els valors.
 */
function ams_email_map(): array {
  return [
    'info'      => ['user' => 'info',      'domain' => 'troposfera.xyz'],
    'community' => ['user' => 'community', 'domain' => 'troposfera.xyz'],
    'help'      => ['user' => 'help',      'domain' => 'troposfera.xyz'],
  ];
}

/**
 * Converteix una string a codis ASCII separats per punt.
 * Això evita que hi hagi @ o mailto al HTML.
 */
function ams_pack_ascii(string $s): string {
  $out = [];
  $len = strlen($s);
  for ($i = 0; $i < $len; $i++) {
    $out[] = (string) ord($s[$i]);
  }
  return implode('.', $out);
}

add_action('init', function () {

  add_shortcode('contact_email', function ($atts) {

    $atts = shortcode_atts([
      'to'    => 'info',
      'label' => 'Mostra el correu',
      'class' => '',
    ], $atts);

    $key = sanitize_key($atts['to']);
    $map = ams_email_map();

    if (!isset($map[$key])) {
      $key = 'info';
    }

    $user   = $map[$key]['user'];
    $domain = $map[$key]['domain'];

    $email  = $user . '@' . $domain;
    $packed = ams_pack_ascii($email);

    // Classes extra (opcionals), netes i segures
    $extra = trim((string) $atts['class']);
    $extra_classes = [];

    if ($extra !== '') {
      foreach (preg_split('/\s+/', $extra) as $c) {
        $c = sanitize_html_class($c);
        if ($c !== '') $extra_classes[] = $c;
      }
    }

    // Mantén sempre la classe interna necessària pel JS
    $class_attr = 'js-contact' . ($extra_classes ? ' ' . implode(' ', $extra_classes) : '');

    return sprintf(
      '<a class="%s" href="#" rel="nofollow" data-x="%s">%s</a>',
      esc_attr($class_attr),
      esc_attr($packed),
      esc_html($atts['label'])
    );
  });
});

/**
 * JS inline al footer. S'executa un cop.
 * Primer clic: revela i crea mailto.
 * Segon clic: obre mailto (comportament normal).
 */
add_action('wp_footer', function () {
  static $printed = false;
  if ($printed) return;
  $printed = true;
  ?>
  <script>
    (function(){
      document.addEventListener("click", function(e){
        var a = e.target.closest(".js-contact");
        if (!a) return;

        e.preventDefault();

        var packed = a.getAttribute("data-x") || "";
        if (!packed) return;

        var parts = packed.split(".");
        var out = "";
        for (var i = 0; i < parts.length; i++){
          var n = parseInt(parts[i], 10);
          if (!isNaN(n)) out += String.fromCharCode(n);
        }

        a.textContent = out;
        a.setAttribute("href", "mailto:" + out);

        // A partir d'aquí, el següent clic ja és un mailto normal
        a.classList.remove("js-contact");
        a.removeAttribute("rel");
      });
    })();
  </script>
  <?php
}, 99);
