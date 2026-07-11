<?php

if (!defined('ABSPATH')) {
    exit;
}

if (
    isset($_POST['disi_duplicate_action']) &&
    check_admin_referer('disi_duplicate_action')
) {
    $duplicate_id = intval($_POST['duplicate_id'] ?? 0);
    $action = sanitize_text_field($_POST['disi_duplicate_action']);

    if ($action === 'approve') {
        $result = DISI_Registration_Manager::approve_duplicate($duplicate_id);
        $message = is_wp_error($result)
            ? $result->get_error_message()
            : 'Duplicate entry approved and added to registrations.';
    } elseif ($action === 'reject') {
        $result = DISI_Registration_Manager::reject_duplicate($duplicate_id);
        $message = $result === false
            ? 'Duplicate entry could not be rejected.'
            : 'Duplicate entry rejected.';
    }

    if (!empty($message)) {
        echo '<div class="notice notice-info"><p>' .
            esc_html($message) .
            '</p></div>';
    }
}

$rows = DISI_Registration_Manager::get_duplicates();

?>

<div class="wrap">

<h1>Duplicate Entries</h1>

<p class="description">
Entries blocked because their email or phone number already exists appear here.
</p>

<table class="widefat striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Type</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Submitted</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($rows)) : ?>
            <?php foreach ($rows as $row) : ?>
                <?php
                $name = trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? ''));
                ?>
                <tr>
                    <td><?php echo esc_html($name ?: '-'); ?></td>
                    <td><?php echo esc_html($row->email ?: '-'); ?></td>
                    <td><?php echo esc_html($row->phone ?: '-'); ?></td>
                    <td>
                        <?php echo esc_html(
                            DISI_Registration_Manager::label_registration_type(
                                $row->registration_type
                            )
                        ); ?>
                    </td>
                    <td><?php echo esc_html($row->duplicate_reason ?: '-'); ?></td>
                    <td><?php echo esc_html(ucfirst($row->status)); ?></td>
                    <td><?php echo esc_html($row->created_at); ?></td>
                    <td>
                        <?php if ($row->status === 'pending') : ?>
                            <form method="post" style="display:inline-block;">
                                <?php wp_nonce_field('disi_duplicate_action'); ?>
                                <input type="hidden" name="duplicate_id" value="<?php echo esc_attr($row->id); ?>">
                                <button
                                    class="button button-primary"
                                    name="disi_duplicate_action"
                                    value="approve"
                                    onclick="return confirm('Are you sure? An entry with the above email or phone number has already been entered. Do you still wish to approve and add this registration?');"
                                >Approve</button>
                            </form>
                            <form method="post" style="display:inline-block;">
                                <?php wp_nonce_field('disi_duplicate_action'); ?>
                                <input type="hidden" name="duplicate_id" value="<?php echo esc_attr($row->id); ?>">
                                <button
                                    class="button"
                                    name="disi_duplicate_action"
                                    value="reject"
                                    onclick="return confirm('Are you sure? An entry with the above email or phone number has already been entered. Do you still wish to reject this duplicate entry?');"
                                >Reject</button>
                            </form>
                        <?php elseif (!empty($row->created_registration_id)) : ?>
                            <a
                                class="button"
                                href="<?php echo esc_url(
                                    admin_url(
                                        'admin.php?page=disi-registration-view&id=' .
                                        intval($row->created_registration_id)
                                    )
                                ); ?>"
                            >View Registration</a>
                        <?php else : ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="8">No duplicate entries have been captured yet.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</div>
