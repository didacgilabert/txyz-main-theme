<?php
/**
 * Actualitzacions del tema des de GitHub.
 *
 * WordPress només mira a wordpress.org, i aquest tema no hi és. Aquesta classe
 * li diu on trobar-lo: llegeix el Version: del style.css que hi ha a GitHub,
 * el compara amb el que hi ha instal·lat, i si és més alt posa l'avís a
 * Escriptori › Actualitzacions.
 *
 * Per publicar un canvi: pujar el Version: del style.css, commit i push.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TXYZ_Theme_Updater {

    const GITHUB_USER = 'didacgilabert';
    const GITHUB_REPO = 'txyz-main-theme';
    const BRANCH      = 'main';
    const SLUG        = 'txyz-main-theme';

    /** Hores que es guarda la resposta de GitHub abans de tornar-hi. */
    const CACHE_HORES = 6;

    public static function init(): void {
        add_filter( 'pre_set_site_transient_update_themes', [ __CLASS__, 'check_update' ] );
        add_filter( 'upgrader_source_selection', [ __CLASS__, 'fix_source' ], 10, 4 );
    }

    /**
     * Omple la llista d'actualitzacions de temes si a GitHub hi ha una versió
     * més alta que la instal·lada.
     */
    public static function check_update( $transient ) {
        if ( empty( $transient->checked ) ) {
            return $transient;
        }

        $remota = self::get_remote_version();
        if ( ! $remota ) {
            return $transient;
        }

        $local = $transient->checked[ self::SLUG ] ?? wp_get_theme( self::SLUG )->get( 'Version' );

        if ( version_compare( $remota, $local, '>' ) ) {
            $transient->response[ self::SLUG ] = [
                'theme'       => self::SLUG,
                'new_version' => $remota,
                'url'         => 'https://github.com/' . self::GITHUB_USER . '/' . self::GITHUB_REPO,
                'package'     => self::get_package_url(),
            ];
        } else {
            unset( $transient->response[ self::SLUG ] );
        }

        return $transient;
    }

    /**
     * El zip que GitHub serveix d'una branca es descomprimeix amb el nom
     * "repositori-branca". Sense això WordPress instal·laria el tema en una
     * carpeta nova anomenada txyz-main-theme-main i deixaria l'antiga al costat.
     */
    public static function fix_source( $source, $remote_source, $upgrader, $hook_extra = null ) {
        global $wp_filesystem;

        if ( empty( $hook_extra['theme'] ) || self::SLUG !== $hook_extra['theme'] ) {
            return $source;
        }

        $desitjat = trailingslashit( $remote_source ) . self::SLUG;

        if ( untrailingslashit( $source ) === untrailingslashit( $desitjat ) ) {
            return $source;
        }

        if ( $wp_filesystem->move( $source, $desitjat, true ) ) {
            return trailingslashit( $desitjat );
        }

        return new WP_Error(
            'txyz_carpeta_no_reanomenada',
            'No s\'ha pogut reanomenar la carpeta descarregada del tema.'
        );
    }

    /**
     * Llegeix el Version: del style.css que hi ha a la branca de GitHub.
     * La resposta es guarda unes hores, perquè GitHub només deixa passar
     * seixanta peticions per hora a qui no s'identifica.
     */
    private static function get_remote_version() {
        $clau = 'txyz_updater_' . self::SLUG;

        if ( empty( $_GET['force-check'] ) ) {
            $desada = get_site_transient( $clau );
            if ( false !== $desada ) {
                return $desada;
            }
        }

        $url = sprintf(
            'https://raw.githubusercontent.com/%s/%s/%s/style.css',
            self::GITHUB_USER,
            self::GITHUB_REPO,
            self::BRANCH
        );

        $resposta = wp_remote_get( $url, [
            'headers' => [ 'User-Agent' => 'WordPress/' . get_bloginfo( 'version' ) ],
            'timeout' => 10,
        ] );

        if ( is_wp_error( $resposta ) || 200 !== wp_remote_retrieve_response_code( $resposta ) ) {
            // Es guarda el fracàs una estona curta, per no martellejar GitHub.
            set_site_transient( $clau, '', 15 * MINUTE_IN_SECONDS );
            return null;
        }

        $cos = wp_remote_retrieve_body( $resposta );

        if ( ! preg_match( '/^[ \t\/*#@]*Version:\s*(.+)$/mi', $cos, $coincidencia ) ) {
            set_site_transient( $clau, '', 15 * MINUTE_IN_SECONDS );
            return null;
        }

        $versio = trim( $coincidencia[1] );

        set_site_transient( $clau, $versio, self::CACHE_HORES * HOUR_IN_SECONDS );

        return $versio;
    }

    private static function get_package_url(): string {
        return sprintf(
            'https://github.com/%s/%s/archive/refs/heads/%s.zip',
            self::GITHUB_USER,
            self::GITHUB_REPO,
            self::BRANCH
        );
    }
}

TXYZ_Theme_Updater::init();
