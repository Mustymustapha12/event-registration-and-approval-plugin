<?php

if (!defined('ABSPATH')) {
    exit;
}

class DISI_Activator {

    public static function activate() {

        if (class_exists('DISI_Database')) {

            DISI_Database::maybe_upgrade();
        }

        self::ensure_capabilities();

        flush_rewrite_rules();
    }

    public static function ensure_capabilities() {

        $role = get_role('administrator');

        if ($role && defined('DISI_MANAGE_CAPABILITY')) {
            $role->add_cap(DISI_MANAGE_CAPABILITY);
        }
    }
}
