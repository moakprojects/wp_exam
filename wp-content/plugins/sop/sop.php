<?php

/*
Plugin Name: SOP
Plugin URI: 
Description: This is a Søgemaskine Optimering Plugin.
Version: 1.0
Author: Erika Grebur, Akos Molnar
*/

if(!function_exists('add_action')) {
    echo "You cannot access directly to the plugin";
    exit;
}

class Sop {
    function add_admin_menu() {
        add_menu_page(
            'Søgemaskine Optimering Plugin',
            'SOP',
            'manage_options',
            'sop',
            array($this,'sop'),
            'dashicons-dashboard',
            35 );
    }

    function sop() {
        echo "<h3>echo szát in náááninn bla bla</h3>";
    }
}

if (class_exists('Sop')) {
    $sopObj = new Sop();
}

add_action('admin_menu', array($sopObj,'add_admin_menu'));

?>