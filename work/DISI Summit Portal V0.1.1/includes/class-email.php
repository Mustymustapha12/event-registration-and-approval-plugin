<?php

if (!defined('ABSPATH')) {
    exit;
}

class DISI_Email {

    public static function send_approval_email(
        $registration
    ) {

        if (empty($registration->email)) {
            return false;
        }

        $payment_link =
        esc_url(
            $registration->paystack_authorization_url ?? ''
        );

        $registration_number =
        DISI_Registration_Manager::get_registration_number(
            $registration
        );

        $name =
        self::recipient_name($registration);

        $total_amount =
        self::format_amount(
            $registration->total_amount ?? 0
        );

        $type =
        DISI_Registration_Manager::label_registration_type(
            $registration->registration_type
        );

        $brand = DISI_Settings::brand();
        $event_name = $brand['event_name'];

        $message =
        self::email_shell(
            $event_name . ' Registration Approved',
            '
            <p>Dear ' . esc_html($name) . ',</p>

            <p>
                Congratulations. Your ' . esc_html($event_name) . ' registration
                has been approved. Please proceed with payment to
                complete your registration.
            </p>

            <table cellpadding="8" style="width:100%;margin:20px 0;border-collapse:collapse;">
                <tr>
                    <td>Registration Number</td>
                    <td><strong>' . esc_html($registration_number) . '</strong></td>
                </tr>
                <tr>
                    <td>Registration Type</td>
                    <td><strong>' . esc_html($type) . '</strong></td>
                </tr>
                <tr>
                    <td>Registration Amount</td>
                    <td><strong>' . esc_html(self::format_amount($registration->registration_amount ?? 0)) . '</strong></td>
                </tr>
                <tr>
                    <td>Workshop Add-on</td>
                    <td><strong>' . esc_html(self::format_amount($registration->workshop_amount ?? 0)) . '</strong></td>
                </tr>
                <tr>
                    <td>Exhibition Add-on</td>
                    <td><strong>' . esc_html(self::format_amount($registration->exhibition_amount ?? 0)) . '</strong></td>
                </tr>
                <tr>
                    <td>Total Amount to Pay</td>
                    <td><strong>' . esc_html($total_amount) . '</strong></td>
                </tr>
                <tr>
                    <td>Payment Reference</td>
                    <td><strong>' . esc_html($registration->paystack_reference ?? '') . '</strong></td>
                </tr>
            </table>

            <p>Click the button below to make payment.</p>

            ' . self::payment_button($payment_link) . '
            '
        );

        return wp_mail(
            $registration->email,
            $event_name . ' Registration Approved - Proceed With Payment',
            $message,
            self::headers()
        );
    }

    public static function send_rejection_email(
        $registration
    ) {

        if (empty($registration->email)) {
            return false;
        }

        $registration_number =
        DISI_Registration_Manager::get_registration_number(
            $registration
        );

        $reason =
        trim(
            $registration->rejection_reason ?? ''
        );

        $brand = DISI_Settings::brand();
        $event_name = $brand['event_name'];
        $organization_name = $brand['organization_name'];

        $message =
        self::email_shell(
            $event_name . ' Registration Update',
            '
            <p>Dear ' . esc_html(self::recipient_name($registration)) . ',</p>

            <p>
                Thank you for your interest in ' . esc_html($event_name) . '.
                After reviewing your registration, we are unable to
                approve it at this time.
            </p>

            <p>
                Registration Number:
                <strong>' . esc_html($registration_number) . '</strong>
            </p>

            ' . (
                !empty($reason)
                ? '<p><strong>Reason:</strong> ' . esc_html($reason) . '</p>'
                : ''
            ) . '

            <p>
                If you believe this was a mistake, please contact the
                ' . esc_html($organization_name) . ' team for assistance.
            </p>
            '
        );

        return wp_mail(
            $registration->email,
            $event_name . ' Registration Update',
            $message,
            self::headers()
        );
    }

    private static function email_shell(
        $title,
        $body
    ) {

        $brand = DISI_Settings::brand();
        $logo = !empty($brand['logo_url'])
            ? $brand['logo_url']
            : DISI_PLUGIN_URL . 'assets/images/disi-logo.png';
        $primary = $brand['primary_color'];
        $secondary = $brand['secondary_color'];
        $accent = $brand['accent_color'];
        $organization_name = $brand['organization_name'];

        return '
        <html>
        <body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,sans-serif;">
            <div style="max-width:720px;margin:30px auto;background:#ffffff;border-radius:12px;overflow:hidden;border-top:8px solid ' . esc_attr($accent) . ';">
                <div style="background:' . esc_attr($secondary) . ';padding:30px;text-align:center;">
                    <img src="' . esc_url($logo) . '" alt="' . esc_attr($organization_name) . '" style="max-width:220px;height:auto;">
                </div>

                <div style="height:8px;background:linear-gradient(90deg,' . esc_attr($primary) . ' 0%,' . esc_attr($accent) . ' 100%);"></div>

                <div style="padding:40px;color:' . esc_attr($secondary) . ';font-size:15px;line-height:1.6;">
                    <h2 style="margin-top:0;color:' . esc_attr($primary) . ';">' . esc_html($title) . '</h2>
                    ' . $body . '
                </div>
            </div>
        </body>
        </html>
        ';
    }

    private static function payment_button($payment_link) {

        if (empty($payment_link)) {
            return '
            <p>
                Payment details will be shared with you shortly.
            </p>
            ';
        }

        return '
        <p style="text-align:center;margin:40px 0;">
            <a href="' . esc_url($payment_link) . '"
            style="background:' . esc_attr(DISI_Settings::brand()['accent_color']) . ';color:' . esc_attr(DISI_Settings::brand()['secondary_color']) . ';text-decoration:none;padding:14px 28px;border-radius:8px;display:inline-block;font-weight:bold;">
                Pay Now
            </a>
        </p>

        <p>
            If the button does not work, copy and paste this link into
            your browser:
            <a href="' . esc_url($payment_link) . '">' .
                esc_html($payment_link) .
            '</a>
        </p>
        ';
    }

    private static function recipient_name($registration) {

        $name =
        trim(
            ($registration->first_name ?? '') .
            ' ' .
            ($registration->last_name ?? '')
        );

        return !empty($name)
        ? $name
        : 'Participant';
    }

    private static function format_amount($amount) {

        $amount =
        floatval($amount);

        return '₦' .
        number_format(
            $amount,
            2
        );
    }

    private static function headers() {

        $brand = DISI_Settings::brand();
        $from_name = $brand['event_name'];
        $from_email = !empty($brand['email'])
            ? $brand['email']
            : get_option('admin_email');

        return [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $from_name . ' <' . $from_email . '>'
        ];
    }
}
