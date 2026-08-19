<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
  public function run(): void
  {
    $templates = [
      [
        'name' => 'Welcome User - BittGold',
        'key' => 'welcome-user',
        'subject' => 'Welcome to {{ site_name }}',
        'body' => <<<'HTML'
<div style="background:#0b1120;padding:20px 10px;font-family:Arial,Helvetica,sans-serif;">
    <div style="max-width:600px;margin:auto;background:#111827;border:1px solid #1f2937;border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.5);">

        <!-- Header -->
        <div style="background:linear-gradient(135deg,#ffb300,#ff8f00);padding:25px 20px;text-align:center;">
            <div style="margin-bottom:12px;">
                <table border="0" cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;">
                    <tr>
                        <td style="text-align:center;">
                            <img src="{{ logo }}" alt="{{ site_name }} Logo" width="140"
                                 style="display:block;height:auto;max-height:45px;object-fit:contain;border:0;">
                        </td>
                    </tr>
                </table>
            </div>
            <h1 style="margin:0;color:#000000;font-size:26px;line-height:1.2;">
                Welcome to {{ site_name }}
            </h1>
            <p style="margin:6px 0 0 0;color:#111827;font-size:14px;font-weight:bold;">
                Your account has been successfully created.
            </p>
        </div>

        <!-- Main Content -->
        <div style="padding:25px 20px;">

            <h2 style="color:#ffffff;margin-top:0;margin-bottom:15px;font-size:20px;">
                Hello {{ name }} 👋
            </h2>

            <p style="font-size:14px;color:#9ca3af;line-height:1.6;margin:0 0 12px 0;">
                Thank you for joining <strong style="color:#ffffff;">{{ site_name }}</strong>.
                We are excited to welcome you to our community.
                Your account has been successfully created and is ready to use.
            </p>

            <p style="font-size:14px;color:#9ca3af;line-height:1.6;margin:0 0 20px 0;">
                Login to your dashboard and start exploring earning opportunities,
                referral rewards, bonuses, and much more.
            </p>

            <!-- Account Details -->
            <div style="background:#1f2937;border:1px solid #374151;border-radius:12px;padding:18px;margin:0 0 20px 0;">
                <h3 style="margin-top:0;margin-bottom:12px;color:#ffffff;font-size:16px;">
                    🔐 Account Details
                </h3>

                <table width="100%" cellpadding="6" cellspacing="0" border="0">
                    <tr>
                        <td width="130" style="color:#9ca3af;font-size:14px;"><strong>Name:</strong></td>
                        <td style="color:#ffffff;font-size:14px;">{{ name }}</td>
                    </tr>
                    <tr>
                        <td style="color:#9ca3af;font-size:14px;"><strong>Email:</strong></td>
                        <td style="color:#ffffff;font-size:14px;">{{ email }}</td>
                    </tr>
                    <tr>
                        <td style="color:#9ca3af;font-size:14px;"><strong>User ID:</strong></td>
                        <td style="color:#ffffff;font-size:14px;">{{ userId }}</td>
                    </tr>
                    <tr>
                        <td style="color:#9ca3af;font-size:14px;"><strong>Password:</strong></td>
                        <td>
                            <span style="background:#ffb300;color:#000000;padding:4px 10px;border-radius:4px;font-weight:bold;font-size:14px;display:inline-block;">
                                For your security, your password is never sent by email.
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="color:#9ca3af;font-size:14px;"><strong>Referred By:</strong></td>
                        <td style="color:#ffffff;font-size:14px;">
                            {{ referrByName }} (ID: {{ referrById }}) <br>
                            <span style="font-size:12px;color:#9ca3af;">Email: {{ referrByEmail }}</span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Login Button -->
            <div style="text-align:center;margin:20px 0;">
                <a href="{{ activation_link }}"
                    style="background:#ffb300;color:#000000;text-decoration:none;padding:12px 30px;border-radius:30px;font-weight:bold;font-size:14px;display:inline-block;">
                    Login To Dashboard
                </a>
            </div>

            <!-- Referral Code Section -->
            <div style="background:#1f2937;border:1px solid #374151;border-radius:12px;padding:18px;margin-top:20px;">
                <h3 style="margin-top:0;margin-bottom:12px;color:#ffb300;font-size:16px;text-align:center;">
                    🎯 Your Referral Code
                </h3>

                <p style="color:#9ca3af;font-size:13px;margin:0 0 14px 0;text-align:center;line-height:1.5;">
                    Share your referral code with friends and grow your network.
                </p>

                <div style="background:#111827;border:2px solid #ffb300;border-radius:10px;padding:16px;text-align:center;">
                    <p style="margin:0 0 8px 0;color:#9ca3af;font-size:12px;font-weight:bold;">
                        YOUR REFERRAL CODE:
                    </p>
                    <h2 style="margin:0;color:#ffb300;font-size:24px;font-weight:bold;letter-spacing:2px;">
                        {{ userId }}
                    </h2>
                    <p style="margin:10px 0 0 0;color:#9ca3af;font-size:12px;">
                        Share this code with friends to earn rewards
                    </p>
                </div>

                <div style="background:#0f1419;border-left:3px solid #ffb300;padding:12px;border-radius:6px;margin-top:12px;">
                    <p style="margin:0;color:#9ca3af;font-size:12px;line-height:1.5;">
                        <strong style="color:#ffb300;">💡 Tip:</strong> Your referral code is unique to you. Share it to invite new members and earn commissions!
                    </p>
                </div>
            </div>

            <!-- Benefits -->
            <div style="margin-top:20px;padding:18px;background:#1f2937;border-radius:12px;border:1px solid #374151;">
                <h3 style="margin-top:0;margin-bottom:10px;color:#ffffff;font-size:16px;">
                    🎁 What You Can Do Next
                </h3>

                <ul style="padding-left:18px;margin:0;color:#9ca3af;line-height:1.6;font-size:13px;">
                    <li style="margin-bottom:4px;">Access your personal dashboard.</li>
                    <li style="margin-bottom:4px;">Invite friends using referral links.</li>
                    <li style="margin-bottom:4px;">Track earnings and rewards.</li>
                    <li style="margin-bottom:4px;">Participate in platform activities.</li>
                    <li>Build your network and increase income.</li>
                </ul>
            </div>

            <p style="margin:20px 0 12px 0;color:#9ca3af;font-size:13px;line-height:1.6;">
                If you have any questions or need assistance, feel free to contact our support team at 
                <a href="mailto:{{ support_email }}" style="color:#ffb300;text-decoration:none;">{{ support_email }}</a>.
                We're always here to help.
            </p>

            <p style="color:#9ca3af;font-size:14px;margin:0;">
                Best Regards,<br>
                <strong style="color:#ffffff;">{{ site_name }} Team</strong>
            </p>

        </div>

        <!-- Footer -->
        <div style="background:#090d16;padding:18px;text-align:center;border-top:1px solid #1f2937;">
            <p style="margin:0;color:#9ca3af;font-size:13px;">
                Thank you for choosing {{ site_name }}
            </p>

            <p style="margin:5px 0 0 0;color:#6b7280;font-size:11px;">
                © {{ site_name }}. All Rights Reserved.
            </p>
        </div>

    </div>
</div>
HTML,
        'variables' => [
          'name',
          'email',
          'mobile',
          'userId',
          'plain_password',
          'activation_link',
          'referrByName',
          'referrById',
          'referrByEmail',
          'logo',
          'site_name',
          'support_email',
        ],
      ],
      [
        'name' => 'User Password Reset - BittGold',
        'key' => 'user-password-reset',
        'subject' => 'Reset your {{ site_name }} password',
        'body' => <<<'HTML'
<div style="background:#0b1120;padding:24px 10px;font-family:Arial,Helvetica,sans-serif;">
    <div style="max-width:600px;margin:auto;background:#111827;border:1px solid #374151;border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,.5);">
        <div style="background:linear-gradient(135deg,#ffb300,#ff8f00);padding:26px 20px;text-align:center;">
            <div style="margin-bottom:12px;">
                <table border="0" cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;">
                    <tr>
                        <td style="text-align:center;">
                            <img src="{{ logo }}" alt="BittGold Logo" width="140"
                                 style="display:block;height:auto;max-height:45px;object-fit:contain;border:0;">
                        </td>
                    </tr>
                </table>
            </div>
            <h1 style="margin:0;color:#111827;font-size:25px;line-height:1.2;">Password Reset Request</h1>
            <p style="margin:7px 0 0;color:#111827;font-size:14px;font-weight:bold;">Secure access for your player account</p>
        </div>

        <div style="padding:28px 24px;">
            <h2 style="margin:0 0 14px;color:#ffffff;font-size:20px;">Hello {{ name }},</h2>
            <p style="margin:0 0 14px;color:#b6bec9;font-size:14px;line-height:1.7;">We received a request to reset the password for your {{ site_name }} account.</p>
            <p style="margin:0 0 20px;color:#b6bec9;font-size:14px;line-height:1.7;">Click the button below to choose a new password. This secure link expires in <strong style="color:#ffffff;">{{ expiry_minutes }} minutes</strong>.</p>

            <div style="text-align:center;margin:25px 0;">
                <a href="{{ reset_link }}" style="display:inline-block;background:#ffb300;color:#111827;text-decoration:none;padding:13px 30px;border-radius:28px;font-size:14px;font-weight:bold;">Reset My Password</a>
            </div>

            <p style="margin:20px 0 0;color:#b6bec9;font-size:13px;line-height:1.6;">If you did not request a password reset, you can safely ignore this email. Your current password will remain unchanged.</p>
            <p style="margin:16px 0 0;color:#b6bec9;font-size:13px;">Need help? Contact us at <a href="mailto:{{ support_email }}" style="color:#ffbf2e;text-decoration:none;">{{ support_email }}</a>.</p>
        </div>

        <div style="padding:17px;background:#090d16;border-top:1px solid #1f2937;text-align:center;color:#6b7280;font-size:11px;">© {{ site_name }}. All rights reserved.</div>
    </div>
</div>
HTML,
        'variables' => [
          'name',
          'email',
          'reset_link',
          'expiry_minutes',
          'support_email',
          'site_name',
          'site_url',
          'logo',
        ],
      ],
    ];

    /*
     * Email clients do not reliably render the dashboard background image and
     * external image URLs can be blocked. These two templates intentionally use
     * the same dark navy + gold BittGold UI through email-safe inline styles.
     */
    $templates[0]['body'] = <<<'HTML'
<div style="margin:0;padding:32px 12px;background:#030609;font-family:Arial,Helvetica,sans-serif;color:#eaf0f7;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center">
    <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background:#09131f;border:1px solid #765b05;border-radius:18px;overflow:hidden;">
      <tr><td style="padding:26px 30px;background:#07101a;border-bottom:1px solid #765b05;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
          <td><img src="{{ logo }}" alt="BittGold" width="132" style="display:block;width:132px;height:auto;border:0;outline:none;text-decoration:none;"></td>
          <td align="right" style="color:#9eb0c3;font-size:11px;font-weight:bold;letter-spacing:1px;">MEMBER PORTAL</td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:34px 30px 26px;background:linear-gradient(135deg,#0b1725,#07101a);">
        <p style="margin:0 0 9px;color:#f5bd25;font-size:12px;font-weight:bold;letter-spacing:1.2px;">ACCOUNT CREATED</p>
        <h1 style="margin:0;color:#ffffff;font-size:28px;line-height:1.25;">Welcome to BittGold, {{ name }}</h1>
        <p style="margin:14px 0 0;color:#b4c1cf;font-size:14px;line-height:1.7;">Your member account is ready. Log in to manage your wallet, investments, rewards and growing network.</p>
      </td></tr>
      <tr><td style="padding:0 30px 28px;background:#09131f;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #5f4a08;border-radius:12px;background:#0b1623;"><tr><td style="padding:18px 20px;">
          <p style="margin:0 0 12px;color:#f5bd25;font-size:11px;font-weight:bold;letter-spacing:1px;">YOUR MEMBER DETAILS</p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Name <span style="color:#ffffff;font-weight:bold;float:right;">{{ name }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Email <span style="color:#ffffff;font-weight:bold;float:right;">{{ email }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Mobile <span style="color:#ffffff;font-weight:bold;float:right;">{{ mobile }}</span></p>
          <p style="margin:0;color:#9eb0c3;font-size:13px;">Member ID <span style="color:#f5bd25;font-weight:bold;float:right;">{{ userId }}</span></p>
        </td></tr></table>
        <div style="text-align:center;padding:28px 0 12px;"><a href="{{ activation_link }}" style="display:inline-block;padding:14px 30px;border-radius:8px;background:#f5bd25;color:#07101a;text-decoration:none;font-weight:800;font-size:14px;">Login to Dashboard &nbsp;→</a></div>
        <p style="margin:18px 0 0;padding:16px;border-left:3px solid #f5bd25;background:#0b1623;color:#b4c1cf;font-size:13px;line-height:1.7;"><strong style="color:#f5bd25;">Login Password:</strong> {{ plain_password }}<br><strong style="color:#f5bd25;">Sponsor:</strong> {{ referrByName }}<br><strong style="color:#f5bd25;">Sponsor Email:</strong> {{ referrByEmail }}<br><strong style="color:#f5bd25;">Sponsor Referral Code:</strong> {{ referrById }}</p>
      </td></tr>
      <tr><td align="center" style="padding:18px 20px;background:#050a10;border-top:1px solid #263443;color:#718195;font-size:11px;">Need help? <a href="mailto:{{ support_email }}" style="color:#f5bd25;text-decoration:none;">{{ support_email }}</a><br><span style="display:block;margin-top:8px;">© {{ site_name }}. All rights reserved.</span></td></tr>
    </table>
  </td></tr></table>
</div>
HTML;

    $templates[1]['body'] = <<<'HTML'
<div style="margin:0;padding:32px 12px;background:#030609;font-family:Arial,Helvetica,sans-serif;color:#eaf0f7;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center">
    <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background:#09131f;border:1px solid #765b05;border-radius:18px;overflow:hidden;">
      <tr><td style="padding:26px 30px;background:#07101a;border-bottom:1px solid #765b05;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
          <td><img src="{{ logo }}" alt="BittGold" width="132" style="display:block;width:132px;height:auto;border:0;outline:none;text-decoration:none;"></td>
          <td align="right" style="color:#9eb0c3;font-size:11px;font-weight:bold;letter-spacing:1px;">SECURE ACCOUNT</td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:34px 30px 18px;background:linear-gradient(135deg,#0b1725,#07101a);">
        <p style="margin:0 0 9px;color:#f5bd25;font-size:12px;font-weight:bold;letter-spacing:1.2px;">PASSWORD RECOVERY</p>
        <h1 style="margin:0;color:#ffffff;font-size:28px;line-height:1.25;">Reset your password</h1>
        <p style="margin:14px 0 0;color:#b4c1cf;font-size:14px;line-height:1.7;">Hello {{ name }}, we received a request to reset the password for your BittGold member account.</p>
      </td></tr>
      <tr><td style="padding:0 30px 30px;background:#09131f;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:8px 0 24px;border:1px solid #5f4a08;border-radius:12px;background:#0b1623;"><tr><td style="padding:17px 20px;color:#b4c1cf;font-size:13px;line-height:1.6;"><strong style="color:#f5bd25;">Secure reset link</strong><br>This link will expire in <strong style="color:#ffffff;">{{ expiry_minutes }} minutes</strong>.</td></tr></table>
        <div style="text-align:center;padding:4px 0 22px;"><a href="{{ reset_link }}" style="display:inline-block;padding:14px 30px;border-radius:8px;background:#f5bd25;color:#07101a;text-decoration:none;font-weight:800;font-size:14px;">Reset My Password &nbsp;→</a></div>
        <p style="margin:0;padding:16px;border-left:3px solid #f5bd25;background:#0b1623;color:#b4c1cf;font-size:13px;line-height:1.6;">If you did not request this change, you can safely ignore this email. Your current password will remain unchanged.</p>
      </td></tr>
      <tr><td align="center" style="padding:18px 20px;background:#050a10;border-top:1px solid #263443;color:#718195;font-size:11px;">Need help? <a href="mailto:{{ support_email }}" style="color:#f5bd25;text-decoration:none;">{{ support_email }}</a><br><span style="display:block;margin-top:8px;">© {{ site_name }}. All rights reserved.</span></td></tr>
    </table>
  </td></tr></table>
</div>
HTML;

    /*
    |--------------------------------------------------------------------------
    | database/seeders/EmailTemplateSeeder.php mein badlaav
    |--------------------------------------------------------------------------
    | $templates array (jisme 'welcome-user' aur 'user-password-reset' already
    | hain) ke turant baad, ye poora block copy-paste kar do — ye code
    | $templates[] = [...]; se naya (teesra) template array mein push karta hai.
    |
    | Ye us jagah jaana chahiye jahan tumhare current do templates already
    | define ho chuke hain, lekin foreach($templates as $template) loop se
    | PEHLE (jo neeche seeder ke end mein hai).
    */

    $templates[] = [
      'name' => 'Contact Us Notification - BittGold',
      'key' => 'contact-us',
      'subject' => 'New Contact Message from {{ name }} - {{ site_name }}',
      'body' => <<<'HTML'
<div style="margin:0;padding:32px 12px;background:#030609;font-family:Arial,Helvetica,sans-serif;color:#eaf0f7;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center">
    <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background:#09131f;border:1px solid #765b05;border-radius:18px;overflow:hidden;">
      <tr><td style="padding:26px 30px;background:#07101a;border-bottom:1px solid #765b05;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
          <td><img src="{{ logo }}" alt="BittGold" width="132" style="display:block;width:132px;height:auto;border:0;outline:none;text-decoration:none;"></td>
          <td align="right" style="color:#9eb0c3;font-size:11px;font-weight:bold;letter-spacing:1px;">CONTACT FORM</td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:34px 30px 18px;background:linear-gradient(135deg,#0b1725,#07101a);">
        <p style="margin:0 0 9px;color:#f5bd25;font-size:12px;font-weight:bold;letter-spacing:1.2px;">NEW ENQUIRY RECEIVED</p>
        <h1 style="margin:0;color:#ffffff;font-size:26px;line-height:1.25;">New message from {{ name }}</h1>
        <p style="margin:14px 0 0;color:#b4c1cf;font-size:14px;line-height:1.7;">Someone has submitted the Contact Us form on {{ site_name }}. Details are below.</p>
      </td></tr>
      <tr><td style="padding:0 30px 30px;background:#09131f;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:8px 0 20px;border:1px solid #5f4a08;border-radius:12px;background:#0b1623;"><tr><td style="padding:18px 20px;">
          <p style="margin:0 0 12px;color:#f5bd25;font-size:11px;font-weight:bold;letter-spacing:1px;">SENDER DETAILS</p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Name <span style="color:#ffffff;font-weight:bold;float:right;">{{ name }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Email <span style="color:#ffffff;font-weight:bold;float:right;">{{ email }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Phone <span style="color:#ffffff;font-weight:bold;float:right;">{{ phone }}</span></p>
          <p style="margin:0;color:#9eb0c3;font-size:13px;">Subject <span style="color:#f5bd25;font-weight:bold;float:right;">{{ subject }}</span></p>
        </td></tr></table>

        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #263443;border-radius:12px;background:#0b1623;"><tr><td style="padding:18px 20px;">
          <p style="margin:0 0 10px;color:#f5bd25;font-size:11px;font-weight:bold;letter-spacing:1px;">MESSAGE</p>
          <p style="margin:0;color:#e5ecf3;font-size:14px;line-height:1.7;white-space:pre-line;">{{ message }}</p>
        </td></tr></table>

        <div style="text-align:center;padding:26px 0 4px;">
          <a href="mailto:{{ email }}" style="display:inline-block;padding:14px 30px;border-radius:8px;background:#f5bd25;color:#07101a;text-decoration:none;font-weight:800;font-size:14px;">Reply to {{ name }} &nbsp;→</a>
        </div>
      </td></tr>
      <tr><td align="center" style="padding:18px 20px;background:#050a10;border-top:1px solid #263443;color:#718195;font-size:11px;">This message was submitted via the {{ site_name }} Contact Us page.<br><span style="display:block;margin-top:8px;">© {{ site_name }}. All rights reserved.</span></td></tr>
    </table>
  </td></tr></table>
</div>
HTML,
      'variables' => [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'logo',
        'site_name',
      ],
    ];

    $templates[] = [
      'name' => 'Contact Reply - BittGold',
      'key' => 'contact-reply',
      'subject' => 'Re: {{ subject }} - {{ site_name }}',
      'body' => <<<'HTML'
<div style="margin:0;padding:32px 12px;background:#030609;font-family:Arial,Helvetica,sans-serif;color:#eaf0f7;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center">
    <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background:#09131f;border:1px solid #765b05;border-radius:18px;overflow:hidden;">
      <tr><td style="padding:26px 30px;background:#07101a;border-bottom:1px solid #765b05;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
          <td><img src="{{ logo }}" alt="BittGold" width="132" style="display:block;width:132px;height:auto;border:0;outline:none;text-decoration:none;"></td>
          <td align="right" style="color:#9eb0c3;font-size:11px;font-weight:bold;letter-spacing:1px;">SUPPORT REPLY</td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:34px 30px 18px;background:linear-gradient(135deg,#0b1725,#07101a);">
        <p style="margin:0 0 9px;color:#f5bd25;font-size:12px;font-weight:bold;letter-spacing:1.2px;">RESPONSE TO YOUR INQUIRY</p>
        <h1 style="margin:0;color:#ffffff;font-size:26px;line-height:1.25;">Hello {{ name }}</h1>
        <p style="margin:14px 0 0;color:#b4c1cf;font-size:14px;line-height:1.7;">Thank you for reaching out to {{ site_name }}. We have reviewed your message and here is our response.</p>
      </td></tr>
      <tr><td style="padding:0 30px 30px;background:#09131f;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:8px 0 20px;border:1px solid #5f4a08;border-radius:12px;background:#0b1623;"><tr><td style="padding:18px 20px;">
          <p style="margin:0 0 10px;color:#f5bd25;font-size:11px;font-weight:bold;letter-spacing:1px;">REGARDING: {{ subject }}</p>
          <p style="margin:0;color:#e5ecf3;font-size:14px;line-height:1.7;white-space:pre-line;">{{ message }}</p>
        </td></tr></table>

        <div style="text-align:center;padding:26px 0 4px;">
          <p style="margin:0;color:#b4c1cf;font-size:13px;">Have more questions? Feel free to reply to this email or visit our website.</p>
        </div>
      </td></tr>
      <tr><td align="center" style="padding:18px 20px;background:#050a10;border-top:1px solid #263443;color:#718195;font-size:11px;">Need immediate assistance? Contact us at <a href="mailto:{{ support_email }}" style="color:#f5bd25;text-decoration:none;">{{ support_email }}</a><br><span style="display:block;margin-top:8px;">© {{ site_name }}. All rights reserved.</span></td></tr>
    </table>
  </td></tr></table>
</div>
HTML,
      'variables' => [
        'name',
        'subject',
        'message',
        'logo',
        'site_name',
        'support_email',
      ],
    ];

    $templates[] = [
      'name' => 'User Login OTP - BittGold',
      'key' => 'user-login-otp',
      'subject' => 'Your {{ site_name }} Login OTP Code',
      'body' => <<<'HTML'
<div style="margin:0;padding:32px 12px;background:#030609;font-family:Arial,Helvetica,sans-serif;color:#eaf0f7;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center">
    <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background:#09131f;border:1px solid #765b05;border-radius:18px;overflow:hidden;">
      <tr><td style="padding:26px 30px;background:#07101a;border-bottom:1px solid #765b05;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
          <td><img src="{{ logo }}" alt="BittGold" width="132" style="display:block;width:132px;height:auto;border:0;outline:none;text-decoration:none;"></td>
          <td align="right" style="color:#9eb0c3;font-size:11px;font-weight:bold;letter-spacing:1px;">SECURE LOGIN</td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:34px 30px 18px;background:linear-gradient(135deg,#0b1725,#07101a);">
        <p style="margin:0 0 9px;color:#f5bd25;font-size:12px;font-weight:bold;letter-spacing:1.2px;">TWO-FACTOR VERIFICATION</p>
        <h1 style="margin:0;color:#ffffff;font-size:28px;line-height:1.25;">Your OTP Code</h1>
        <p style="margin:14px 0 0;color:#b4c1cf;font-size:14px;line-height:1.7;">Hello {{ name }}, your one-time password (OTP) for login verification is below. This code will expire in {{ expiry_minutes }} minutes.</p>
      </td></tr>
      <tr><td style="padding:0 30px 30px;background:#09131f;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:8px 0 24px;border:1px solid #5f4a08;border-radius:12px;background:#0b1623;"><tr><td style="padding:28px 20px;text-align:center;">
          <p style="margin:0 0 14px;color:#9eb0c3;font-size:13px;font-weight:bold;letter-spacing:1px;">YOUR OTP CODE</p>
          <div style="background:#07101a;border:2px solid #f5bd25;border-radius:10px;padding:20px;display:inline-block;">
            <p style="margin:0;color:#f5bd25;font-size:32px;font-weight:bold;letter-spacing:4px;">{{ otp_code }}</p>
          </div>
          <p style="margin:14px 0 0;color:#9eb0c3;font-size:12px;font-weight:bold;">Never share this code with anyone</p>
        </td></tr></table>

        <div style="padding:16px;border-left:3px solid #f5bd25;background:#0b1623;border-radius:6px;">
          <p style="margin:0;color:#b4c1cf;font-size:13px;line-height:1.6;"><strong style="color:#f5bd25;">⏱️ Expires in:</strong> {{ expiry_minutes }} minutes<br><strong style="color:#f5bd25;">🔒 Security Tip:</strong> Never share your OTP with anyone, including {{ site_name }} staff.</p>
        </div>

        <div style="text-align:center;padding:24px 0 6px;">
          <p style="margin:0;color:#b4c1cf;font-size:13px;">Enter this code on the login verification screen to complete your login.</p>
        </div>
      </td></tr>
      <tr><td align="center" style="padding:18px 20px;background:#050a10;border-top:1px solid #263443;color:#718195;font-size:11px;">If you did not attempt to log in, please secure your account immediately.<br>Contact us at <a href="mailto:{{ support_email }}" style="color:#f5bd25;text-decoration:none;">{{ support_email }}</a><br><span style="display:block;margin-top:8px;">© {{ site_name }}. All rights reserved.</span></td></tr>
    </table>
  </td></tr></table>
</div>
HTML,
      'variables' => [
        'name',
        'otp_code',
        'expiry_minutes',
        'logo',
        'site_name',
        'support_email',
      ],
    ];

    $templates[] = [
      'name' => 'KYC Submitted Admin Notification - BittGold',
      'key' => 'kyc-submitted-admin',
      'subject' => 'New KYC Request Submitted by {{ user_name }} - {{ site_name }}',
      'body' => <<<'HTML'
<div style="margin:0;padding:32px 12px;background:#030609;font-family:Arial,Helvetica,sans-serif;color:#eaf0f7;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center">
    <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background:#09131f;border:1px solid #765b05;border-radius:18px;overflow:hidden;">
      <tr><td style="padding:26px 30px;background:#07101a;border-bottom:1px solid #765b05;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
          <td><img src="{{ logo }}" alt="BittGold" width="132" style="display:block;width:132px;height:auto;border:0;outline:none;text-decoration:none;"></td>
          <td align="right" style="color:#9eb0c3;font-size:11px;font-weight:bold;letter-spacing:1px;">KYC REVIEW</td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:34px 30px 18px;background:linear-gradient(135deg,#0b1725,#07101a);">
        <p style="margin:0 0 9px;color:#f5bd25;font-size:12px;font-weight:bold;letter-spacing:1.2px;">NEW KYC REQUEST</p>
        <h1 style="margin:0;color:#ffffff;font-size:28px;line-height:1.25;">KYC submitted by {{ user_name }}</h1>
        <p style="margin:14px 0 0;color:#b4c1cf;font-size:14px;line-height:1.7;">A new member has submitted KYC documents for review. Please review and approve or reject the request.</p>
      </td></tr>
      <tr><td style="padding:0 30px 30px;background:#09131f;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #5f4a08;border-radius:12px;background:#0b1623;"><tr><td style="padding:18px 20px;">
          <p style="margin:0 0 12px;color:#f5bd25;font-size:11px;font-weight:bold;letter-spacing:1px;">MEMBER DETAILS</p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Name <span style="color:#ffffff;font-weight:bold;float:right;">{{ user_name }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Email <span style="color:#ffffff;font-weight:bold;float:right;">{{ user_email }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Member ID <span style="color:#ffffff;font-weight:bold;float:right;">{{ member_id }}</span></p>
          <p style="margin:0;color:#9eb0c3;font-size:13px;">Referral ID <span style="color:#f5bd25;font-weight:bold;float:right;">{{ referral_code }}</span></p>
        </td></tr></table>
      </td></tr>
      <tr><td align="center" style="padding:18px 20px;background:#050a10;border-top:1px solid #263443;color:#718195;font-size:11px;">Please review the submission in the admin panel.<br><span style="display:block;margin-top:8px;">© {{ site_name }}. All rights reserved.</span></td></tr>
    </table>
  </td></tr></table>
</div>
HTML,
      'variables' => [
        'user_name',
        'user_email',
        'member_id',
        'referral_code',
        'site_name',
        'logo',
      ],
    ];

    $templates[] = [
      'name' => 'KYC Status User Notification - BittGold',
      'key' => 'kyc-status-user',
      'subject' => 'Your KYC {{ status }} - {{ site_name }}',
      'body' => <<<'HTML'
<div style="margin:0;padding:32px 12px;background:#030609;font-family:Arial,Helvetica,sans-serif;color:#eaf0f7;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center">
    <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background:#09131f;border:1px solid #765b05;border-radius:18px;overflow:hidden;">
      <tr><td style="padding:26px 30px;background:#07101a;border-bottom:1px solid #765b05;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
          <td><img src="{{ logo }}" alt="BittGold" width="132" style="display:block;width:132px;height:auto;border:0;outline:none;text-decoration:none;"></td>
          <td align="right" style="color:#9eb0c3;font-size:11px;font-weight:bold;letter-spacing:1px;">KYC STATUS</td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:34px 30px 18px;background:linear-gradient(135deg,#0b1725,#07101a);">
        <p style="margin:0 0 9px;color:#f5bd25;font-size:12px;font-weight:bold;letter-spacing:1.2px;">UPDATE</p>
        <h1 style="margin:0;color:#ffffff;font-size:28px;line-height:1.25;">Hello {{ user_name }}</h1>
        <p style="margin:14px 0 0;color:#b4c1cf;font-size:14px;line-height:1.7;">Your KYC request has been <strong>{{ status }}</strong>.</p>
      </td></tr>
      <tr><td style="padding:0 30px 30px;background:#09131f;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #5f4a08;border-radius:12px;background:#0b1623;"><tr><td style="padding:18px 20px;">
          <p style="margin:0;color:#e5ecf3;font-size:14px;line-height:1.7;white-space:pre-line;"><strong style="color:#f5bd25;">Status:</strong> {{ status }}<br><strong style="color:#f5bd25;">Message:</strong> {{ reason }}</p>
        </td></tr></table>
      </td></tr>
      <tr><td align="center" style="padding:18px 20px;background:#050a10;border-top:1px solid #263443;color:#718195;font-size:11px;">For support, contact <a href="mailto:{{ support_email }}" style="color:#f5bd25;text-decoration:none;">{{ support_email }}</a><br><span style="display:block;margin-top:8px;">© {{ site_name }}. All rights reserved.</span></td></tr>
    </table>
  </td></tr></table>
</div>
HTML,
      'variables' => [
        'user_name',
        'status',
        'reason',
        'site_name',
        'support_email',
        'logo',
      ],
    ];

    $templates[] = [
      'name' => 'Withdrawal Submitted Admin Notification - BittGold',
      'key' => 'withdrawal-submitted-admin',
      'subject' => 'New Withdrawal Request from {{ user_name }} - {{ site_name }}',
      'body' => <<<'HTML'
<div style="margin:0;padding:32px 12px;background:#030609;font-family:Arial,Helvetica,sans-serif;color:#eaf0f7;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center">
    <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background:#09131f;border:1px solid #765b05;border-radius:18px;overflow:hidden;">
      <tr><td style="padding:26px 30px;background:#07101a;border-bottom:1px solid #765b05;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
          <td><img src="{{ logo }}" alt="BittGold" width="132" style="display:block;width:132px;height:auto;border:0;outline:none;text-decoration:none;"></td>
          <td align="right" style="color:#9eb0c3;font-size:11px;font-weight:bold;letter-spacing:1px;">WITHDRAWAL REVIEW</td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:34px 30px 18px;background:linear-gradient(135deg,#0b1725,#07101a);">
        <p style="margin:0 0 9px;color:#f5bd25;font-size:12px;font-weight:bold;letter-spacing:1.2px;">NEW WITHDRAWAL REQUEST</p>
        <h1 style="margin:0;color:#ffffff;font-size:28px;line-height:1.25;">Withdrawal requested by {{ user_name }}</h1>
        <p style="margin:14px 0 0;color:#b4c1cf;font-size:14px;line-height:1.7;">A member has submitted a withdrawal request and needs admin review.</p>
      </td></tr>
      <tr><td style="padding:0 30px 30px;background:#09131f;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #5f4a08;border-radius:12px;background:#0b1623;"><tr><td style="padding:18px 20px;">
          <p style="margin:0 0 12px;color:#f5bd25;font-size:11px;font-weight:bold;letter-spacing:1px;">REQUEST DETAILS</p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Name <span style="color:#ffffff;font-weight:bold;float:right;">{{ user_name }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Email <span style="color:#ffffff;font-weight:bold;float:right;">{{ user_email }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Member ID <span style="color:#ffffff;font-weight:bold;float:right;">{{ member_id }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Referral ID <span style="color:#f5bd25;font-weight:bold;float:right;">{{ referral_code }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Amount <span style="color:#ffffff;font-weight:bold;float:right;">₹{{ amount }}</span></p>
          <p style="margin:0;color:#9eb0c3;font-size:13px;">Bank Details <span style="color:#ffffff;font-weight:bold;float:right;">{{ bank_details }}</span></p>
        </td></tr></table>
      </td></tr>
      <tr><td align="center" style="padding:18px 20px;background:#050a10;border-top:1px solid #263443;color:#718195;font-size:11px;">Please review the withdrawal request in the admin panel.<br><span style="display:block;margin-top:8px;">© {{ site_name }}. All rights reserved.</span></td></tr>
    </table>
  </td></tr></table>
</div>
HTML,
      'variables' => [
        'user_name',
        'user_email',
        'member_id',
        'referral_code',
        'amount',
        'bank_details',
        'site_name',
        'logo',
      ],
    ];

    $templates[] = [
      'name' => 'Deposit Submitted Admin Notification - BittGold',
      'key' => 'deposit-submitted-admin',
      'subject' => 'New Deposit Request from {{ user_name }} - {{ site_name }}',
      'body' => <<<'HTML'
<div style="margin:0;padding:32px 12px;background:#030609;font-family:Arial,Helvetica,sans-serif;color:#eaf0f7;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center">
    <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background:#09131f;border:1px solid #765b05;border-radius:18px;overflow:hidden;">
      <tr><td style="padding:26px 30px;background:#07101a;border-bottom:1px solid #765b05;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
          <td><img src="{{ logo }}" alt="BittGold" width="132" style="display:block;width:132px;height:auto;border:0;outline:none;text-decoration:none;"></td>
          <td align="right" style="color:#9eb0c3;font-size:11px;font-weight:bold;letter-spacing:1px;">DEPOSIT REVIEW</td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:34px 30px 18px;background:linear-gradient(135deg,#0b1725,#07101a);">
        <p style="margin:0 0 9px;color:#f5bd25;font-size:12px;font-weight:bold;letter-spacing:1.2px;">NEW DEPOSIT REQUEST</p>
        <h1 style="margin:0;color:#ffffff;font-size:28px;line-height:1.25;">Deposit requested by {{ user_name }}</h1>
        <p style="margin:14px 0 0;color:#b4c1cf;font-size:14px;line-height:1.7;">A member has submitted a deposit request and needs admin review.</p>
      </td></tr>
      <tr><td style="padding:0 30px 30px;background:#09131f;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #5f4a08;border-radius:12px;background:#0b1623;"><tr><td style="padding:18px 20px;">
          <p style="margin:0 0 12px;color:#f5bd25;font-size:11px;font-weight:bold;letter-spacing:1px;">REQUEST DETAILS</p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Name <span style="color:#ffffff;font-weight:bold;float:right;">{{ user_name }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Email <span style="color:#ffffff;font-weight:bold;float:right;">{{ user_email }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Member ID <span style="color:#ffffff;font-weight:bold;float:right;">{{ member_id }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Referral ID <span style="color:#f5bd25;font-weight:bold;float:right;">{{ referral_code }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Amount <span style="color:#ffffff;font-weight:bold;float:right;">₹{{ amount }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Payment Method <span style="color:#ffffff;font-weight:bold;float:right;">{{ payment_method }}</span></p>
          <p style="margin:0;color:#9eb0c3;font-size:13px;">Reference No <span style="color:#ffffff;font-weight:bold;float:right;">{{ reference_no }}</span></p>
        </td></tr></table>
      </td></tr>
      <tr><td align="center" style="padding:18px 20px;background:#050a10;border-top:1px solid #263443;color:#718195;font-size:11px;">Please review the deposit request in the admin panel.<br><span style="display:block;margin-top:8px;">© {{ site_name }}. All rights reserved.</span></td></tr>
    </table>
  </td></tr></table>
</div>
HTML,
      'variables' => [
        'user_name',
        'user_email',
        'member_id',
        'referral_code',
        'amount',
        'payment_method',
        'reference_no',
        'site_name',
        'logo',
      ],
    ];

    $templates[] = [
      'name' => 'Investment Confirmation - BittGold',
      'key' => 'investment-confirmation',
      'subject' => 'Investment Successful - ₹{{ amount }} - {{ site_name }}',
      'body' => <<<'HTML'
<div style="margin:0;padding:32px 12px;background:#030609;font-family:Arial,Helvetica,sans-serif;color:#eaf0f7;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center">
    <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background:#09131f;border:1px solid #765b05;border-radius:18px;overflow:hidden;">
      <tr><td style="padding:26px 30px;background:#07101a;border-bottom:1px solid #765b05;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
          <td><img src="{{ logo }}" alt="BittGold" width="132" style="display:block;width:132px;height:auto;border:0;outline:none;text-decoration:none;"></td>
          <td align="right" style="color:#9eb0c3;font-size:11px;font-weight:bold;letter-spacing:1px;">INVESTMENT CONFIRMED</td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:34px 30px 18px;background:linear-gradient(135deg,#0b1725,#07101a);">
        <p style="margin:0 0 9px;color:#f5bd25;font-size:12px;font-weight:bold;letter-spacing:1.2px;">INVESTMENT SUCCESSFUL</p>
        <h1 style="margin:0;color:#ffffff;font-size:28px;line-height:1.25;">Hello {{ user_name }}</h1>
        <p style="margin:14px 0 0;color:#b4c1cf;font-size:14px;line-height:1.7;">Your investment has been successfully activated. Thank you for investing with {{ site_name }}.</p>
      </td></tr>
      <tr><td style="padding:0 30px 30px;background:#09131f;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #5f4a08;border-radius:12px;background:#0b1623;"><tr><td style="padding:18px 20px;">
          <p style="margin:0 0 12px;color:#f5bd25;font-size:11px;font-weight:bold;letter-spacing:1px;">INVESTMENT DETAILS</p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Amount Invested <span style="color:#ffffff;font-weight:bold;float:right;">₹{{ amount }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Investment ID <span style="color:#ffffff;font-weight:bold;float:right;">#{{ investment_id }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Activated On <span style="color:#ffffff;font-weight:bold;float:right;">{{ activated_at }}</span></p>
          <p style="margin:0;color:#9eb0c3;font-size:13px;">Status <span style="color:#4ade80;font-weight:bold;float:right;">{{ status }}</span></p>
        </td></tr></table>

        <div style="background:#0b1623;border-left:3px solid #f5bd25;padding:16px;border-radius:6px;margin-top:16px;">
          <p style="margin:0;color:#b4c1cf;font-size:13px;line-height:1.6;"><strong style="color:#f5bd25;">💡 What's Next?</strong><br>Your investment is now active and will start generating returns according to the plan. You can track your earnings in your dashboard.</p>
        </div>
      </td></tr>
      <tr><td align="center" style="padding:18px 20px;background:#050a10;border-top:1px solid #263443;color:#718195;font-size:11px;">For support, contact <a href="mailto:{{ support_email }}" style="color:#f5bd25;text-decoration:none;">{{ support_email }}</a><br><span style="display:block;margin-top:8px;">© {{ site_name }}. All rights reserved.</span></td></tr>
    </table>
  </td></tr></table>
</div>
HTML,
      'variables' => [
        'user_name',
        'user_email',
        'amount',
        'investment_id',
        'activated_at',
        'status',
        'site_name',
        'support_email',
        'logo',
      ],
    ];

    $templates[] = [
      'name' => 'Deposit Status User Notification - BittGold',
      'key' => 'deposit-status-user',
      'subject' => 'Your Deposit Request {{ status }} - {{ site_name }}',
      'body' => <<<'HTML'
<div style="margin:0;padding:32px 12px;background:#030609;font-family:Arial,Helvetica,sans-serif;color:#eaf0f7;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center">
    <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background:#09131f;border:1px solid #765b05;border-radius:18px;overflow:hidden;">
      <tr><td style="padding:26px 30px;background:#07101a;border-bottom:1px solid #765b05;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
          <td><img src="{{ logo }}" alt="BittGold" width="132" style="display:block;width:132px;height:auto;border:0;outline:none;text-decoration:none;"></td>
          <td align="right" style="color:#9eb0c3;font-size:11px;font-weight:bold;letter-spacing:1px;">DEPOSIT STATUS</td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:34px 30px 18px;background:linear-gradient(135deg,#0b1725,#07101a);">
        <p style="margin:0 0 9px;color:#f5bd25;font-size:12px;font-weight:bold;letter-spacing:1.2px;">UPDATE</p>
        <h1 style="margin:0;color:#ffffff;font-size:28px;line-height:1.25;">Hello {{ user_name }}</h1>
        <p style="margin:14px 0 0;color:#b4c1cf;font-size:14px;line-height:1.7;">Your deposit request has been <strong>{{ status }}</strong>.</p>
      </td></tr>
      <tr><td style="padding:0 30px 30px;background:#09131f;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #5f4a08;border-radius:12px;background:#0b1623;"><tr><td style="padding:18px 20px;">
          <p style="margin:0 0 10px;color:#f5bd25;font-size:11px;font-weight:bold;letter-spacing:1px;">REQUEST DETAILS</p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Amount <span style="color:#ffffff;font-weight:bold;float:right;">₹{{ amount }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Payment Method <span style="color:#ffffff;font-weight:bold;float:right;">{{ payment_method }}</span></p>
          <p style="margin:0 0 7px;color:#9eb0c3;font-size:13px;">Reference No <span style="color:#ffffff;font-weight:bold;float:right;">{{ reference_no }}</span></p>
          <p style="margin:0;color:#9eb0c3;font-size:13px;">Status <span style="color:#f5bd25;font-weight:bold;float:right;">{{ status }}</span></p>
        </td></tr></table>
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top:12px;border:1px solid #5f4a08;border-radius:12px;background:#0b1623;"><tr><td style="padding:18px 20px;">
          <p style="margin:0;color:#e5ecf3;font-size:14px;line-height:1.7;white-space:pre-line;"><strong style="color:#f5bd25;">Admin Remark:</strong> {{ admin_remark }}</p>
        </td></tr></table>
      </td></tr>
      <tr><td align="center" style="padding:18px 20px;background:#050a10;border-top:1px solid #263443;color:#718195;font-size:11px;">For support, contact <a href="mailto:{{ support_email }}" style="color:#f5bd25;text-decoration:none;">{{ support_email }}</a><br><span style="display:block;margin-top:8px;">© {{ site_name }}. All rights reserved.</span></td></tr>
    </table>
  </td></tr></table>
</div>
HTML,
      'variables' => [
        'user_name',
        'user_email',
        'amount',
        'payment_method',
        'reference_no',
        'status',
        'admin_remark',
        'site_name',
        'support_email',
        'logo',
      ],
    ];

    $templates[] = [
      'name' => 'Withdrawal Status User Notification - BittGold',
      'key' => 'withdrawal-status-user',
      'subject' => 'Your Withdrawal Request {{ status }} - {{ site_name }}',
      'body' => <<<'HTML'
<div style="margin:0;padding:32px 12px;background:#030609;font-family:Arial,Helvetica,sans-serif;color:#eaf0f7;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center">
    <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background:#09131f;border:1px solid #765b05;border-radius:18px;overflow:hidden;">
      <tr><td style="padding:26px 30px;background:#07101a;border-bottom:1px solid #765b05;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
          <td><img src="{{ logo }}" alt="BittGold" width="132" style="display:block;width:132px;height:auto;border:0;outline:none;text-decoration:none;"></td>
          <td align="right" style="color:#9eb0c3;font-size:11px;font-weight:bold;letter-spacing:1px;">WITHDRAWAL STATUS</td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:34px 30px 18px;background:linear-gradient(135deg,#0b1725,#07101a);">
        <p style="margin:0 0 9px;color:#f5bd25;font-size:12px;font-weight:bold;letter-spacing:1.2px;">UPDATE</p>
        <h1 style="margin:0;color:#ffffff;font-size:28px;line-height:1.25;">Hello {{ user_name }}</h1>
        <p style="margin:14px 0 0;color:#b4c1cf;font-size:14px;line-height:1.7;">Your withdrawal request has been <strong>{{ status }}</strong>.</p>
      </td></tr>
      <tr><td style="padding:0 30px 30px;background:#09131f;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #5f4a08;border-radius:12px;background:#0b1623;"><tr><td style="padding:18px 20px;">
          <p style="margin:0;color:#e5ecf3;font-size:14px;line-height:1.7;white-space:pre-line;"><strong style="color:#f5bd25;">Status:</strong> {{ status }}<br><strong style="color:#f5bd25;">Amount:</strong> ₹{{ amount }}</p>
        </td></tr></table>
      </td></tr>
      <tr><td align="center" style="padding:18px 20px;background:#050a10;border-top:1px solid #263443;color:#718195;font-size:11px;">For support, contact <a href="mailto:{{ support_email }}" style="color:#f5bd25;text-decoration:none;">{{ support_email }}</a><br><span style="display:block;margin-top:8px;">© {{ site_name }}. All rights reserved.</span></td></tr>
    </table>
  </td></tr></table>
</div>
HTML,
      'variables' => [
        'user_name',
        'status',
        'amount',
        'site_name',
        'support_email',
        'logo',
      ],
    ];

    foreach ($templates as $template) {
      EmailTemplate::updateOrCreate(
        ['key' => $template['key']],
        array_merge($template, ['is_active' => true]),
      );
    }
  }
}