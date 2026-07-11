<?php

if (!defined('ABSPATH')) {
    exit;
}

$rows = DISI_Registration_Manager::get_sponsorship_enquiries();

?>

<div class="wrap">

<h1>Sponsorship Enquiries</h1>

<p class="description">
Entries submitted through the configured Sponsorship Enquiry Form appear here.
</p>

<table class="widefat striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Company</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Submitted</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($rows)) : ?>
            <?php foreach ($rows as $row) : ?>
                <tr>
                    <td><?php echo esc_html($row->name ?: '-'); ?></td>
                    <td><?php echo esc_html($row->company ?: '-'); ?></td>
                    <td><?php echo esc_html($row->email ?: '-'); ?></td>
                    <td><?php echo esc_html($row->phone ?: '-'); ?></td>
                    <td><?php echo esc_html($row->created_at); ?></td>
                    <td>
                        <?php if (!empty($row->email)) : ?>
                            <a
                                class="button"
                                href="mailto:<?php echo esc_attr($row->email); ?>"
                            >Email</a>
                        <?php endif; ?>

                        <?php if (!empty($row->phone)) : ?>
                            <a
                                class="button"
                                href="tel:<?php echo esc_attr($row->phone); ?>"
                            >Call</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
                $data = json_decode($row->submitted_data ?? '', true);
                ?>
                <?php if (is_array($data) && !empty($data)) : ?>
                    <tr>
                        <td colspan="6">
                            <strong>Submitted fields:</strong>
                            <?php foreach ($data as $key => $value) : ?>
                                <?php
                                if (DISI_Registration_Manager::is_hidden_submission_field($key)) {
                                    continue;
                                }
                                if (is_array($value)) {
                                    $value = implode(', ', $value);
                                }
                                ?>
                                <span style="display:inline-block;margin:4px 12px 4px 0;">
                                    <?php echo esc_html(DISI_Registration_Manager::label_submission_field($key)); ?>:
                                    <strong><?php echo esc_html($value); ?></strong>
                                </span>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6">No sponsorship enquiries have been captured yet.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</div>
