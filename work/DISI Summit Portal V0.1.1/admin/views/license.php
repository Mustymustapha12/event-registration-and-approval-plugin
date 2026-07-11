<?php

if (!defined('ABSPATH')) {
    exit;
}

$notice = '';
$notice_class = '';

if (
    isset($_POST['disi_activate_license']) &&
    check_admin_referer('disi_activate_license_action')
) {
    $result = DISI_License::activate(
        wp_unslash($_POST['disi_license_key'] ?? '')
    );

    if (is_wp_error($result)) {
        $notice = $result->get_error_message();
        $notice_class = 'notice-error';
    } else {
        $notice = 'This WordPress installation has been approved successfully.';
        $notice_class = 'notice-success';
    }
}

$is_active = DISI_License::is_active();
$purchase_url = class_exists('DISI_Settings')
    ? DISI_Settings::purchase_url()
    : '';
$product_name = class_exists('DISI_Settings')
    ? DISI_Settings::product_name()
    : 'Event Registration and Approval Plugin';
?>

<div class="wrap disi-license-page">
    <h1><?php echo esc_html($product_name); ?> License</h1>

    <?php if ($notice) : ?>
        <div class="notice <?php echo esc_attr($notice_class); ?>">
            <p><?php echo esc_html($notice); ?></p>
        </div>
    <?php endif; ?>

    <div class="disi-license-status <?php
    echo $is_active ? 'is-active' : 'is-inactive';
    ?>">
        <strong><?php
        echo $is_active ? 'Approved' : 'Approval Required';
        ?></strong>
        <span><?php
        echo esc_html(DISI_License::status_message());
        ?></span>
    </div>

    <?php if (!$is_active) : ?>
        <p>
            Buy access from the official checkout page, then activate this
            WordPress installation with the key issued after payment.
        </p>

        <?php if (!empty($purchase_url)) : ?>
            <p>
                <a
                    class="button button-primary"
                    href="<?php echo esc_url($purchase_url); ?>"
                    target="_blank"
                    rel="noopener"
                >
                    Buy Access
                </a>
            </p>
        <?php endif; ?>

        <p>
            If your checkout system requests a site code, use the request
            code below.
        </p>

        <table class="form-table">
            <tr>
                <th>Approved Site URL</th>
                <td><code><?php
                echo esc_html(DISI_License::site_identity());
                ?></code></td>
            </tr>
            <tr>
                <th>Request Code</th>
                <td>
                    <textarea
                        class="large-text code"
                        rows="4"
                        readonly
                        onclick="this.select();"
                    ><?php
                    echo esc_textarea(DISI_License::request_code());
                    ?></textarea>
                </td>
            </tr>
        </table>

        <form method="post">
            <?php wp_nonce_field('disi_activate_license_action'); ?>

            <table class="form-table">
                <tr>
                    <th>Activation Key</th>
                    <td>
                        <textarea
                            name="disi_license_key"
                            class="large-text code"
                            rows="6"
                            required
                            placeholder="DISI-LIC-..."
                        ></textarea>
                    </td>
                </tr>
            </table>

            <p>
                <button
                    type="submit"
                    name="disi_activate_license"
                    class="button button-primary"
                >
                    Approve This Installation
                </button>
            </p>
        </form>
    <?php else : ?>
        <p>
            The portal is fully enabled for
            <code><?php echo esc_html(DISI_License::site_identity()); ?></code>.
        </p>
    <?php endif; ?>
</div>
