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

    function __construct() {

    }

    function add_admin_menu() {
        add_menu_page(
            'Søgemaskine Optimering Plugin',
            'SOP',
            'manage_options',
            'sop',
            array($this,'sop'),
            'dashicons-feedback',
            35 );
    }

    function sop() {
        echo "<h3>SOP</h3>";

        echo "<table>
                <tr>
                    <td id='f0'></td>
                    <td id='f1'></td>
                </tr>
                <tr>
                    <td id='s0'></td>
                    <td id='s1'></td>
                </tr>
                <tr>
                    <td id='t0'></td>
                    <td id='t1'></td>
                </tr>
                <tr>
                    <td id='fo0'></td>
                    <td id='fo1'></td>
                </tr>
                <tr>
                    <td id='fi0'></td>
                    <td id='fi1'></td>
                </tr>
                <tr>
                    <td id='si0'></td>
                    <td id='si1'></td>
                </tr>
                <tr>
                    <td id='se0'></td>
                    <td id='se1'></td>
                </tr>
                <tr>
                    <td id='e0'></td>
                    <td id='e1'></td>
                </tr>
                <tr>
                    <td id='n0'></td>
                    <td id='n1'></td>
                </tr>
                <tr>
                    <td id='te0'></td>
                    <td id='te1'></td>
                </tr>
            </table>";
    }

    function enqueue() {
        wp_enqueue_script('AjaxJquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js');
        wp_enqueue_script('mypluginscript', plugins_url('/admin/js/main.js', __FILE__));
    }

    function register() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
    }
}

if (class_exists('Sop')) {
    $sopObj = new Sop();
    $sopObj->register();
}

add_action('admin_menu', array($sopObj,'add_admin_menu'));

?>