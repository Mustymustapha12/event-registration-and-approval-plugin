<?php

if (!defined('ABSPATH')) {
    exit;
}

class DISI_Login_Branding {

    public function __construct() {

        add_action(
            'login_enqueue_scripts',
            [$this, 'styles']
        );

        add_filter(
            'login_headerurl',
            [$this, 'url']
        );

        add_filter(
            'login_headertext',
            [$this, 'title']
        );
    }

    public function styles() {

        wp_enqueue_style(

            'disi-login',

            DISI_PLUGIN_URL .
            'assets/css/login.css',

            [],

            time()

        );
    }

    public function url() {

        $brand = DISI_Settings::brand();
        return !empty($brand['website'])
            ? $brand['website']
            : home_url('/');
    }

    public function title() {

        return DISI_Settings::brand()['organization_name'];
    }
}
