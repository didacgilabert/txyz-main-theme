<?php
/**
 * txyz » Main Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since txyz 2025 » Main Theme 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'txyz_main_theme_post_format_setup' ) ) :
	function txyz_main_theme_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'txyz_main_theme_post_format_setup' );

// Registers custom block styles.
if ( ! function_exists( 'txyz_main_theme_block_styles' ) ) :
	function txyz_main_theme_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'txyz-main-theme' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}
				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'txyz_main_theme_block_styles' );

// Registers pattern categories.
if ( ! function_exists( 'txyz_main_theme_pattern_categories' ) ) :
	function txyz_main_theme_pattern_categories() {
		register_block_pattern_category(
			'txyz_main_theme_page',
			array(
				'label'       => __( 'Pages', 'txyz-main-theme' ),
				'description' => __( 'A collection of full page layouts.', 'txyz-main-theme' ),
			)
		);
		register_block_pattern_category(
			'txyz_main_theme_post-format',
			array(
				'label'       => __( 'Post formats', 'txyz-main-theme' ),
				'description' => __( 'A collection of post format patterns.', 'txyz-main-theme' ),
			)
		);
	}
endif;
add_action( 'init', 'txyz_main_theme_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'txyz_main_theme_register_block_bindings' ) ) :
	function txyz_main_theme_register_block_bindings() {
		register_block_bindings_source(
			'txyz-main-theme/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'txyz-main-theme' ),
				'get_value_callback' => 'txyz_main_theme_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'txyz_main_theme_register_block_bindings' );

if ( ! function_exists( 'txyz_main_theme_format_binding' ) ) :
	function txyz_main_theme_format_binding() {
		$post_format_slug = get_post_format();
		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;

// Excerpt on Pages
add_post_type_support( 'page', 'excerpt' );


/*	-----------------------------------------------------------------------------------------------
	STYLES — FRONTEND, EDITOR GUTENBERG I ADMIN

	FRONTEND:    Un sol enqueue explícit de style.css (consolidat).
	EDITOR:      add_editor_style() amb paths relatius — mai URLs absolutes
	             (URLs absolutes poden causar requests HTTP internes que saturen memòria).
	ADMIN:       Limitat a post.php i post-new.php. La resta de l'admin no en necessita.
--------------------------------------------------------------------------------------------------- */

// ── EDITOR GUTENBERG ──────────────────────────────────────────────────────────────────────────────
function txyz_editor_styles() {
	add_editor_style( [
		'style.css',
		'assets/css/editor-style.css',
	] );
}
add_action( 'after_setup_theme', 'txyz_editor_styles' );

// ── FRONTEND ──────────────────────────────────────────────────────────────────────────────────────
// Un sol fitxer CSS — dynamics, relateds i variations ja estan consolidats dins style.css.
function txyz_enqueue_frontend_styles() {
	wp_enqueue_style(
		'txyz-styles',
		get_theme_file_uri( '/style.css' ),
		[],
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'txyz_enqueue_frontend_styles' );

// ── ADMIN (només pantalles d'edició de posts) ─────────────────────────────────────────────────────
function txyz_enqueue_admin_styles( $hook ) {
	if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
		return;
	}
	// Gutenberg rep els estils via add_editor_style() — res addicional necessari aquí.
}
add_action( 'admin_enqueue_scripts', 'txyz_enqueue_admin_styles' );


/*	-----------------------------------------------------------------------------------------------
	CUSTOM FUNCTIONS FOR TXYZ 2025 MAIN THEME
--------------------------------------------------------------------------------------------------- */

// Desactiva l'accés a xmlrpc.php per evitar atacs automàtics i ús indegut
add_filter( 'xmlrpc_enabled', '__return_false' );


// ADDING SECONDARY TITLE TO RANK MATH
add_filter( 'rank_math/frontend/title', 'custom_archive_title', 10, 1 );
function custom_archive_title( $title ) {
    global $post;

    // Static cache — evita múltiples get_the_title() per IDs fixes en la mateixa request
    static $titles = array();
    static $secondary = array();

    $cached_ids = array( 27, 458, 20, 10481, 4359 );
    foreach ( $cached_ids as $id ) {
        if ( ! isset( $titles[ $id ] ) ) {
            $titles[ $id ]    = get_the_title( $id );
            $secondary[ $id ] = get_secondary_title( $id );
        }
    }

    if ( is_home() ) {
        return $titles[27] . ', ' . $secondary[27] . ' » troposfera.xyz';
    } elseif ( is_front_page() ) {
        return $titles[4359] . ', ' . $secondary[4359] . ' » troposfera.xyz';
    } elseif ( is_post_type_archive('labs') ) {
        return $titles[458] . ', ' . $secondary[458] . ' » troposfera.xyz';
    } elseif ( is_post_type_archive('workshops') ) {
        return $titles[10481] . ', ' . $secondary[10481] . ' » troposfera.xyz';
    } elseif ( is_post_type_archive('work') ) {
        return $titles[20] . ', ' . $secondary[20] . ' » troposfera.xyz';
    } elseif ( is_singular('work') ) {
        return get_the_title() . ', ' . get_secondary_title( $post->ID ) . ' » ' . $titles[20];
    } elseif ( is_singular('labs') ) {
        return get_the_title() . ', ' . get_secondary_title( $post->ID ) . ' » ' . $titles[458];
    } elseif ( is_singular('workshops') ) {
        return get_the_title() . ', ' . get_secondary_title( $post->ID ) . ' » ' . $titles[10481];
    } elseif ( is_singular() ) {
        return get_the_title() . ', ' . get_secondary_title( $post->ID );
    } elseif ( is_page() ) {
        return get_the_title() . ', ' . get_secondary_title( $post->ID ) . ' » troposfera.xyz';
    }

    return $title;
}


// ADDING CUSTOM DESCRIPTIONS ON COMPLEX ARCHIVES
add_filter( 'rank_math/frontend/description', 'custom_archive_description', 10, 1 );
function custom_archive_description( $description ) {
    if ( is_post_type_archive('work') ) {
        return get_the_excerpt(20);
    } elseif ( is_post_type_archive('labs') ) {
        return get_the_excerpt(458);
    } elseif ( is_post_type_archive('workshops') ) {
        return get_the_excerpt(10481);
    } elseif ( is_category() || is_tag() || is_tax() ) {
        return mb_substr( term_description(), 0, 300 ) . '...';
    }
    return $description;
}


// REMOVE CATEGORY: AND TAG:
add_filter('get_the_archive_title', function ($title) {
	if (is_category()) {
		$title = single_cat_title('', false) . ' <span class="secondary-title">» Blog</span>';
	} elseif (is_tag()) {
		$title = single_tag_title('', false) . ' <span class="secondary-title">» Blog</span>';
	} elseif (is_tax('project')) {
		$title = single_tag_title('', false) . ' <span class="secondary-title">» Blog</span>';
	} elseif (is_tax('location')) {
		$title = single_tag_title('', false) . ' <span class="secondary-title">» Blog</span>';
	} elseif (is_tax('state')) {
		$title = ' <span class="secondary-title">Work » </span>' . single_tag_title('', false);
	} elseif (is_tax('workshop-focus')) {
		$title = single_tag_title('', false) . ' <span class="secondary-title">» Workshops</span>';
	} elseif (is_tax('workshop-level')) {
		$title = single_tag_title('', false) . ' <span class="secondary-title">» Workshops</span>';
	} elseif (is_tax()) {
		$title = single_tag_title('', false);
	} elseif (is_author()) {
		$title = '<span class="vcard">' . get_the_author() . '</span>' . ' <span class="secondary-title">» Blog</span>';
	} elseif (is_post_type_archive()) {
		$title = post_type_archive_title('', false);
		$prefix = _x('Archives:', 'post type archive title prefix');
	}
	return $title;
});


require_once get_template_directory() . '/inc/firts-page-blog-content.php';
require_once get_template_directory() . '/inc/archive-dynamic-cta.php';
// require_once get_template_directory() . '/inc/related-filters.php';
require_once get_template_directory() . '/inc/related-to-article.php';
require_once get_template_directory() . '/inc/avoid-mail-spam.php';
require_once get_template_directory() . '/inc/updater.php';


/*-----------------------------------------------------------------------------------------------*
  SECONDARY TITLE ON QUICK EDITOR
*-----------------------------------------------------------------------------------------------*/

function txyz_quickedit_secondary_title_field( $column_name, $post_type ) {
	if ( $column_name !== 'secondary_title' ) {
		return;
	}
	?>
	<fieldset class="inline-edit-col-right">
		<div class="inline-edit-col">
			<label>
				<span class="title"><?php esc_html_e( 'Secondary Title', 'your-textdomain' ); ?></span>
				<span class="input-text-wrap">
					<input type="text" name="secondary_title" value="">
				</span>
			</label>
		</div>
	</fieldset>
	<?php
}
add_action( 'quick_edit_custom_box', 'txyz_quickedit_secondary_title_field', 10, 2 );

function txyz_quickedit_save_secondary_title( $post_id ) {
	if ( isset( $_POST['secondary_title'] ) ) {
		update_post_meta( $post_id, '_secondary_title', sanitize_text_field( $_POST['secondary_title'] ) );
	}
}
add_action( 'save_post', 'txyz_quickedit_save_secondary_title' );

function txyz_quickedit_secondary_title_js() {
	?>
	<script>
	jQuery(function($){
		$('body').on('click', '.editinline', function(){
			let postId = $(this).closest('tr').attr('id').replace('post-', '');
			let secondaryTitle = $('#post-' + postId + ' .column-secondary_title').text().trim();
			$('input[name="secondary_title"]').val(secondaryTitle);
		});
	});
	</script>
	<?php
}
add_action( 'admin_footer-edit.php', 'txyz_quickedit_secondary_title_js' );


// Fes editable (i afegible) la imatge destacada a la columna d'Admin Columns
add_action( 'admin_enqueue_scripts', function( $hook ) {
	if ( $hook !== 'edit.php' ) return;

	wp_enqueue_media();

	wp_add_inline_style( 'wp-admin', '
		.column-featured_image,
		.column-thumbnail {
			cursor: pointer !important;
		}
		.column-featured_image img,
		.column-thumbnail img {
			cursor: pointer !important;
			border-radius: 4px;
		}
	' );

	wp_add_inline_script( 'jquery', '
		jQuery(function($){
			let frame;

			function openMediaUploader(postId, $target){
				if (!frame) {
					frame = wp.media({
						title: "Selecciona la imatge destacada",
						button: { text: "Usar aquesta imatge" },
						multiple: false
					});
				}

				frame.off("select").on("select", function(){
					const attachment = frame.state().get("selection").first().toJSON();
					$.post(ajaxurl, {
						action: "txyz_set_featured_image",
						post_id: postId,
						attachment_id: attachment.id,
						_ajax_nonce: txyz_featured_image.nonce
					}, function(resp){
						if (resp.success) {
							const thumbUrl = attachment.sizes?.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
							if ($target.find("img").length) {
								$target.find("img").attr("src", thumbUrl);
							} else {
								$target.html(`<img src="${thumbUrl}" style="width:60px;height:auto;border-radius:4px;">`);
							}
						} else {
							alert("Error en desar la imatge");
						}
					});
				});

				$("body").on("click", ".column-featured_image img, .column-thumbnail img", function(){
					const $cell = $(this).closest("td");
					const postId = $(this).closest("tr").attr("id").replace("post-", "");
					openMediaUploader(postId, $cell);
				});

				$("body").on("click", ".column-featured_image, .column-thumbnail", function(e){
					if ($(e.target).is("img")) return;
					const $cell = $(this);
					const postId = $cell.closest("tr").attr("id").replace("post-", "");
					openMediaUploader(postId, $cell);
				});
		});
	');

	wp_localize_script( 'jquery', 'txyz_featured_image', [
		'nonce' => wp_create_nonce( 'txyz_featured_image_nonce' ),
	]);
});

// AJAX: Desa la imatge destacada
add_action( 'wp_ajax_txyz_set_featured_image', function() {
	check_ajax_referer( 'txyz_featured_image_nonce', '_ajax_nonce' );
	$post_id       = intval( $_POST['post_id'] ?? 0 );
	$attachment_id = intval( $_POST['attachment_id'] ?? 0 );

	if ( current_user_can( 'edit_post', $post_id ) ) {
		set_post_thumbnail( $post_id, $attachment_id );
		wp_send_json_success();
	}
	wp_send_json_error();
});



add_filter( 'render_block_core/comments', function( $content ) {
    $content = preg_replace( '/<h2([^>]*)class="wp-block-comments-title"([^>]*)>/', '<p$1class="wp-block-comments-title"$2>', $content );
    $content = str_replace( '</h2>', '</p>', $content );
    $content = preg_replace( '/<h3([^>]*)class="comment-reply-title"([^>]*)>/', '<p$1class="comment-reply-title"$2>', $content );
    $content = str_replace( '</h3>', '</p>', $content );
    return $content;
} );



add_action('admin_head', function() {
    ?>
    <style>
    .edit-post-header-toolbar .wpm-language-switcher,
    .edit-widgets-header__navigable-toolbar-wrapper .wpm-language-switcher {
        position: absolute;
        left: 0px;
        z-index: 999;
    }
    </style>
    <?php
});