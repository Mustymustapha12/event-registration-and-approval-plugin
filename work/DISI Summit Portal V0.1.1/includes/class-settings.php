<?php

if (!defined('ABSPATH')) {
    exit;
}

class DISI_Settings {

    public static function get_configuration() {

        $defaults = [
            'provider' => '',
            'participant_form' => '',
            'group_booking_form' => '',
            'sponsorship_form' => '',
            'organization_name' => 'Event Registration',
            'event_name' => 'Event Registration',
            'organization_email' => '',
            'organization_phone' => '',
            'organization_website' => '',
            'organization_address' => '',
            'organization_logo_url' => '',
            'primary_color' => '#157664',
            'secondary_color' => '#172b3b',
            'accent_color' => '#ffc801',
            'paystack_secret_key' => '',
            'paystack_public_key' => '',
            'paystack_callback_url' => '',
            'paystack_mode' => 'test',
            'professional_amount' => '',
            'vip_amount' => '',
            'academic_amount' => '',
            'student_amount' => '',
            'group_booking_amount' => '',
            'workshop_amount' => '',
            'exhibition_amount' => ''
        ];

        return wp_parse_args(
            get_option(
            'disi_form_configuration',
                []
            ),
            $defaults
        );
    }

    public static function save_configuration($data) {

        update_option(
            'disi_form_configuration',
            $data
        );
    }

    public static function brand() {

        $config = self::get_configuration();

        return [
            'organization_name' => self::clean_brand_value(
                $config['organization_name'] ?? '',
                'Event Registration'
            ),
            'event_name' => self::clean_brand_value(
                $config['event_name'] ?? '',
                $config['organization_name'] ?? 'Event Registration'
            ),
            'email' => sanitize_email($config['organization_email'] ?? ''),
            'phone' => sanitize_text_field($config['organization_phone'] ?? ''),
            'website' => esc_url_raw($config['organization_website'] ?? ''),
            'address' => sanitize_textarea_field($config['organization_address'] ?? ''),
            'logo_url' => esc_url_raw($config['organization_logo_url'] ?? ''),
            'primary_color' => self::color(
                $config['primary_color'] ?? '',
                '#157664'
            ),
            'secondary_color' => self::color(
                $config['secondary_color'] ?? '',
                '#172b3b'
            ),
            'accent_color' => self::color(
                $config['accent_color'] ?? '',
                '#ffc801'
            )
        ];
    }

    public static function product_name() {

        return 'Event Registration and Approval Plugin';
    }

    private static function clean_brand_value($value, $fallback) {

        $value = trim(sanitize_text_field((string) $value));
        return $value !== '' ? $value : $fallback;
    }

    private static function color($value, $fallback) {

        $value = trim((string) $value);
        return preg_match('/^#[0-9a-fA-F]{6}$/', $value)
            ? strtolower($value)
            : $fallback;
    }
}
