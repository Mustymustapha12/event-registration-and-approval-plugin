# Event Registration and Approval Plugin

Commercial WordPress plugin for event registration capture, review, pricing,
payment notifications, e-ticketing, sponsorship enquiries, duplicate handling,
and form-provider integration.

## Latest Release

Version `1.0.1`

Installable package:
`outputs/event-registration-approval-plugin-v1.0.1.zip`

## Source

The editable plugin source is in:
`work/DISI Summit Portal V0.1.1`

The workspace source path is kept for continuity, while the v1.0.0 installable
ZIP uses the commercial plugin folder `event-registration-and-approval-plugin`.

## Site Approval

Version `1.0.1` keeps signed site approval and adds a purchase/checkout URL
field so a hosted checkout or future license server can issue access
automatically after payment. The owner-only generator is documented in
`owner-tools/README.md`. Its private signing key is excluded from Git and from
the installable plugin package.

## Version 1.0.1

- Fixes repeated WordPress upgrade warnings from `wp-admin/includes/upgrade.php`
  by avoiding `dbDelta()` during normal plugin upgrade checks.
- Keeps table creation and in-place schema upgrades working through direct
  `CREATE TABLE IF NOT EXISTS` plus manual column/index upgrades.

## Version 1.0.0

- Rebrands the plugin to Event Registration and Approval Plugin for commercial use.
- Adds organization branding settings for organization name, event name, email,
  phone, website, address, logo URL, primary color, secondary color, and accent color.
- Applies configured branding to the dashboard, integration screen, emails,
  public e-ticket page, ticket emails, and PDF report titles.
- Adds commercial checkout settings for purchase URL, future license API endpoint,
  suggested product price, and currency.
- Keeps the proven approval, payment, export, e-ticketing, sponsorship enquiry,
  duplicate entry, group booking, VIP, workshop, and exhibition features.

## Version 0.5.4

- Adds a VIP Amount field on the Integration page.
- Detects VIP registrations from supported form submissions.
- Includes VIP in registration and E-ticketing type filters.
- Adds Exhibition Payment Amount as an add-on like Workshop.
- Adds the `manage_disi_portal` capability for role editor plugins.
- Adds a separate Group Booking Form selector on the Integration page.
- Group Booking tickets now show the admitted number of people and key group booking fields, with attendee names title-cased and wrapped onto separate lines.
- Adds memorable backup license keys requested by the plugin owner.
- Adds Sponsorship Enquiries form assignment, capture, listing, email, and call actions.
- Adds Duplicate Entries queue for blocked duplicate email/phone submissions, with guarded approve/reject actions.
