<?php
/**
 * Plugin Name:     Easy Digital Downloads - Hide Button Prices
 * Plugin URI:      http://wordpress.org/plugins/easy-digital-downloads-hide-button-prices/
 * Description:     Removes prices from purchase buttons on Easy Digital Downloads
 * Version:         1.0.2
 * Author:          Daniel J Griffiths
 * Author URI:      http://section214.com
 * Text Domain:     edd-hide-button-prices
 * 
 * @package         EDD\HideButtonPrices
 * @author          Daniel J Griffiths <dgriffiths2section214.com>
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'EDD_Hide_Button_Prices' ) ) {


    /**
     * Main EDD_Hide_Button_Prices class
     *
     * @since       1.0.1
     */
    class EDD_Hide_Button_Prices {


        /**
         * @var         EDD_Hide_Button_Prices $instance The one true EDD_Hide_Button_Prices
         * @since       1.0.1
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      object self::$instance The one true EDD_Hide_Button_Prices
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new EDD_Hide_Button_Prices();
                self::$instance->load_textdomain();
                self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function hooks() {
            // Edit plugin metalinks
            add_filter( 'plugin_row_meta', array( $this, 'plugin_metalinks' ), null, 2 );

            // Override prices
            add_filter( 'edd_purchase_link_defaults', array( $this, 'hide_button_prices' ) );
        }


        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
            $lang_dir = apply_filters( 'EDD_Hide_Button_Prices_lang_dir', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale     = apply_filters( 'plugin_locale', get_locale(), '' );
            $mofile     = sprintf( '%1$s-%2$s.mo', 'edd-hide-button-prices', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/edd-hide-button-prices/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/edd-hide-button-prices/ folder
                load_textdomain( 'edd-hide-button-prices', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/edd-hide-button-prices/languages/ folder
                load_textdomain( 'edd-hide-button-prices', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'edd-hide-button-prices', false, $lang_dir );
            }
        }


        /**
         * Modify plugin metalinks
         *
         * @access      public
         * @since       1.1.0
         * @param       array $links The current links array
         * @param       string $file A specific plugin table entry
         * @return      array $links The modified links array
         */
        public function plugin_metalinks( $links, $file ) {
            if( $file == plugin_basename( __FILE__ ) ) {
                $help_link = array(
                    '<a href="http://section214.com/support/forum/edd-hide-button-prices/" target="_blank">' . __( 'Support Forum', 'edd-hide-button-prices' ) . '</a>'
                );

                $docs_link = array(
                    '<a href="http://section214.com/docs/category/edd-hide-button-prices/" target="_blank">' . __( 'Docs', 'edd-hide-button-prices' ) . '</a>'
                );

                $links = array_merge( $links, $help_link, $docs_link );
            }

            return $links;
        }


        /**
         * Hide button prices
         *
         * @access          public
         * @since           1.0.1
         * @param           array $defaults
         * @return          array
         */
        public function hide_button_prices( $defaults ) {
            $defaults['price'] = (bool) false;

            return $defaults;
        }
    }
}


/**
 * The main function responsible for returning the one true EDD_Hide_Button_Prices
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      EDD_Hide_Button_Prices The one true EDD_Hide_Button_Prices
 */
function EDD_Hide_Button_Prices_load() {
    if( !class_exists( 'Easy_Digital_Downloads' ) ) {
        deactivate_plugins( __FILE__ );
        unset( $_GET['activate'] );

        // Display notice
        add_action( 'admin_notices', 'EDD_Hide_Button_Prices_missing_edd_notice' );
    } else {
        return EDD_Hide_Button_Prices::instance();
    }
}
add_action( 'plugins_loaded', 'EDD_Hide_Button_Prices_load' );


/**
 * We need Easy Digital Downloads... if it isn't present, notify the user!
 *
 * @since       1.1.0
 * @return      void
 */
function EDD_Hide_Button_Prices_missing_edd_notice() {
    echo '<div class="error"><p>' . __( 'Hide Button Prices requires Easy Digital Downloads! Please install it to continue!', 'edd-balanced-gateway' ) . '</p></div>';
}
