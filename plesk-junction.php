<?php
/*
Plugin Name: Plesk-Junction Plugin
Plugin URI: http://example.com/
Description: Plugin WooCommerce pour la gestion des services d'hébergement avec Plesk.
Version: 1.0
Author: Votre Nom
Author URI: http://example.com/
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Inclure les fichiers nécessaires
require_once plugin_dir_path( __FILE__ ) . 'includes/class-plesk-junction-admin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/plesk-junction-functions.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/plesk-junction-settings.php';

// Initialiser le plugin
function plesk_junction_init() {
    // Enregistrer les styles et scripts d'administration
    add_action( 'admin_enqueue_scripts', 'plesk_junction_enqueue_admin_scripts' );
    
    // Ajouter les menus au back-office
    add_action( 'admin_menu', 'plesk_junction_add_admin_menu' );
}
add_action( 'plugins_loaded', 'plesk_junction_init' );

// Enregistrer les styles et scripts d'administration
function plesk_junction_enqueue_admin_scripts() {
    wp_enqueue_style( 'plesk-junction-admin', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css' );
    wp_enqueue_script( 'plesk-junction-admin', plugin_dir_url( __FILE__ ) . 'assets/js/admin.js', array('jquery'), null, true );
}

// Ajouter les menus au back-office
function plesk_junction_add_admin_menu() {
    add_menu_page(
        'Plesk-Junction', 
        'Plesk-Junction', 
        'manage_options', 
        'plesk-junction', 
        'plesk_junction_dashboard_page', 
        'dashicons-admin-generic', 
        2
    );

    add_submenu_page(
        'plesk-junction', 
        'Clients', 
        'Clients', 
        'manage_options', 
        'plesk-junction-clients', 
        'plesk_junction_clients_page'
    );

    // Ajouter les autres sous-menus de la même manière...
}

// Fonction de callback pour afficher le tableau de bord
function plesk_junction_dashboard_page() {
    include plugin_dir_path( __FILE__ ) . 'admin/partials/plesk-junction-admin-dashboard.php';
}

// Fonction de callback pour afficher la page Clients
function plesk_junction_clients_page() {
    include plugin_dir_path( __FILE__ ) . 'admin/partials/plesk-junction-admin-clients.php';
}

// Ajouter des fonctions similaires pour les autres sous-menus...
// Ajouter un lien de réinitialisation sur la page des plugins
function plesk_junction_add_reset_link( $links, $file ) {
    if ( $file == plugin_basename( __FILE__ ) ) {
        $reset_link = '<a href="' . admin_url( 'plugins.php?reset_plesk_junction=1' ) . '">Réinitialiser</a>';
        array_unshift( $links, $reset_link );
    }
    return $links;
}
add_filter( 'plugin_action_links', 'plesk_junction_add_reset_link', 10, 2 );

// Vérifier si le lien de réinitialisation a été cliqué
function plesk_junction_handle_reset() {
    if ( isset( $_GET['reset_plesk_junction'] ) && $_GET['reset_plesk_junction'] == '1' ) {
        plesk_junction_reset_settings();
        wp_redirect( admin_url( 'plugins.php?reset_plesk_junction_success=1' ) );
        exit;
    }
}
add_action( 'admin_init', 'plesk_junction_handle_reset' );

// Afficher un message de succès après réinitialisation
function plesk_junction_reset_success_notice() {
    if ( isset( $_GET['reset_plesk_junction_success'] ) && $_GET['reset_plesk_junction_success'] == '1' ) {
        echo '<div class="updated notice is-dismissible"><p>Les paramètres du plugin ont été réinitialisés avec succès.</p></div>';
    }
}
add_action( 'admin_notices', 'plesk_junction_reset_success_notice' );

// Fonction pour réinitialiser les paramètres du plugin
function plesk_junction_reset_settings() {
    delete_option('plesk_junction_setting_example');
    // Ajouter d'autres options à réinitialiser ici
}
?>
