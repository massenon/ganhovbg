<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class SettingUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'key' => 'GUEST_TICKET_OTP',
                'value' => 'no',
                'created_at' => now(),
                'updated_at' => now()
            ], [
                'key' => 'CUSTOMER_TICKET',
                'value' => 'no',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'start_week',
                'value' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'TICKET_CHARACTER',
                'value' => '10',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'default_timezone',
                'value' => 'UTC',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'businesshourstitle',
                'value' => 'Support',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'businesshourssubtitle',
                'value' => 'Our technical team is available in the IST timezone.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'businesshoursswitch',
                'value' => 'on',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'supporticonimage',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'admin_reply_mail',
                'value' => 'yes',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'customer_panel_employee_protect',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'employeeprotectname',
                'value' => 'Support',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'ticketrating',
                'value' => 'on',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'sidemenu_icon_style',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'login_disable',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'login_disable_statement',
                'value' => 'Due technical issue',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'cust_profile_delete_enable',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'key' => 'EMAILDOMAIN_BLOCKTYPE',
                'value' => 'blockemail',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'EMAILDOMAIN_LIST',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'key' => 'customer_inactive_notify',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'customer_inactive_notify_date',
                'value' => '1',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'key' => 'customer_inactive_week_date',
                'value' => '1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'guest_inactive_notify',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'guest_inactive_notify_date',
                'value' => '1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'guest_inactive_week_date',
                'value' => '1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'RECAPTCH_ENABLE_ADMIN_LOGIN',
                'value' => 'NO',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'cc_email',
                'value' => 'h:i A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'contact_form_mail',
                'value' => env('contact_form_mail'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'only_social_logins',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'article_count',
                'value' => 'on',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'key' => 'trashed_ticket_autodelete',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'trashed_ticket_delete_time',
                'value' => '7',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'RESTRICT_TO_CREATE_TICKET',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'MAXIMUM_ALLOW_TICKETS',
                'value' => '1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'MAXIMUM_ALLOW_HOURS',
                'value' => '1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'SUPPORT_POLICY_URL',
                'value' => '#',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'RESTRICT_TO_REPLY_TICKET',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'MAXIMUM_ALLOW_REPLIES',
                'value' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'REPLY_ALLOW_IN_HOURS',
                'value' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'NOTE_CREATE_MAILS',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'ANNOUNCEMENT_USER',
                'value' => 'all_users',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'CUSTOMER_RESTICT_TO_DELETE_TICKET',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],


        ]);

        DB::table('email_templates')->insert([

            [
                'code' => 'admin_send_email_ticket_reply',
                'title' => 'Send email to admin, when customer replies to ticket',
                'subject' => 'You got reply from the customer',
                'body' => '<p>Hey Admin, </p><p>You have got a reply from customer. Please respond to the ticket.</p><p>Please visit the application to&nbsp;<a href="{{ticket_admin_url}}" style="">ViewTicket</a></p><p>Ticket Title: {{ticket_title}}<br></p><p>Ticket ID: {{ticket_id}}</p><p>Client Last Reply: {{comment}}</p><p><br></p><p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Sincerely,</p><p class="root-block-node" data-paragraphid="20" data-from-init="true" data-changed="false">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'when_ticket_assign_to_other_employee',
                'title' => 'Send email to employee, when a ticket is assigned.',
                'subject' => 'You have been assigned a ticket.',
                'body' => '<p>Dear User,<br></p>
                    <p>You have been assigned a ticket.</p>
                    <p>Please visit the application and respond accordingly. <br></p>
                    <p><a href="{{ticket_admin_url}}" style="">Click-here </a>to view ticket.<br></p>
                    <p>User Name: {{ticket_username}}<br></p>
                    <p>Ticket Title: {{ticket_title}}<br></p>
                    <p>Ticket ID: {{ticket_id}}</p>
                    <p><br></p>
                    <p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Sincerely,</p><p class="root-block-node" data-paragraphid="20" data-from-init="true" data-changed="false">Support Team</p>',
                    'created_at' => now(),
                    'updated_at' => now()
            ],
            [
                'code' => 'when_send_customnotify_email_to_selected_member',
                'title' => 'Send email to employee/customer, when a custom notification is sent to them.',
                'subject' => 'You got a new notificatin.',
                'body' => '<p>Hey user, </p><p>You got a new notification. Please login to your account to see the notification in detail.</p><p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Sincerely,</p><p class="root-block-node" data-paragraphid="20" data-from-init="true" data-changed="false">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'code' => 'when_send_customnotify_email_todelete_member',
                'title' => 'Send an email alert to the customers when they are not using the application.',
                'subject' => 'Your account is unused and will soon be deleted.',
                'body' => '<p>Attention {{customer_username}}</p><p>Your {{customer_email}} personal account has been unused for {{customer_months}}.</p><p>It would be helpful if you could confirm that your account is still active by verifying it now.</p><p>Click here to <a href="{{ticket_customer_url}}" target="_blank">login</a></p><p>Note:  If you fail to login, your unused account with the associated data will be deleted in {{customer_time}}.</p><p><br></p><p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Sincerely,</p><p class="root-block-node" data-paragraphid="20" data-from-init="true" data-changed="false">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // new
            [
                'code' => 'admin_sendemail_whenticketclosed',
                'title' => 'Send an email to admin panel users when ticket is closed.',
                'subject' => 'The ticket {{ticket_id}} has been closed.',
                'body' => '<p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">Dear User,</p> <p class="root-block-node" data-paragraphid="17" data-from-init="true" data-changed="false">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; This email is to inform you that the ticket {{ticket_id}} has been closed.</p> <p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Sincerely,</p> <p class="root-block-node" data-paragraphid="20" data-from-init="true" data-changed="false">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'customer_sendemail_whenticketclosed',
                'title' => 'Send an email to customer, when a ticket is closed.',
                'subject' => 'The ticket {{ticket_id}} has been closed',
                'body' => '<p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">Dear {{ticket_username}},</p> <p class="root-block-node" data-paragraphid="17" data-from-init="true" data-changed="false">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;We hope that the ticket {{ticket_id}} was resolved to your satisfaction. If you feel that the ticket should not be closed or if the ticket has not been resolved, please go ahead and reopen it.</p> <p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Sincerely,</p> <p class="root-block-node" data-paragraphid="20" data-from-init="true" data-changed="false">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'CCmail_sendemail_whenticketclosed',
                'title' => 'Send an email to CC when the ticket is closed.',
                'subject' => 'Your ticket {{ticket_id}} has been closed.',
                'body' => '<p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">Dear {{ticket_username}},</p> <p class="root-block-node" data-paragraphid="17" data-from-init="true" data-changed="false">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;We hope that the ticket {{ticket_id}} was resolved to your satisfaction. If you feel that the ticket should not be closed or if the ticket has not been resolved, please go ahead and reopen it.</p> <p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Sincerely,</p> <p class="root-block-node" data-paragraphid="20" data-from-init="true" data-changed="false">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'admin_sendemail_whenticketreopen',
                'title' => 'Send an email to admin panel users when ticket is reopened.',
                'subject' => 'The ticket has been reopened.',
                'body' => '<p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">Dear User,</p> <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;The ticket {{ticket_id}}&nbsp;has been reopened by the {{ticket_username}}. Please review the ticket again.</p> <p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Sincerely,</p> <p class="root-block-node" data-paragraphid="20" data-from-init="true" data-changed="false">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'guestticket_email_verification_view',
                'title' => 'Guest email verification to view ticket.',
                'subject' => 'Guest-Ticket Email Verification',
                'body' => '<p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">Dear {{guestname}},</p><p class="root-block-node" data-paragraphid="17" data-from-init="true" data-changed="false">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; {{guestotp}} is your one time password (otp) to view your ticket.</p><p class="root-block-node" data-paragraphid="17" data-from-init="true" data-changed="false">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; {{guestemail}}</p><p class="root-block-node" data-paragraphid="17" data-from-init="true" data-changed="false">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Please do not share your otp’s with anyone.</p>
                <p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Sincerely,</p>
                <p class="root-block-node" data-paragraphid="20" data-from-init="true" data-changed="false">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // When ticket closed  by Admin no rating
            [
                'code' => 'send_mail_to_customer_when_ticket_closed_by_admin',
                'title' => 'Send mail to customer when ticket closed by admin',
                'subject' => 'Your ticket {{ticket_id}} has been closed succesfully.',
                'body' => '<p class="root-block-node" data-paragraphid="33" data-from-init="true" data-changed="false">Dear {{ticket_username}},</p><p class="root-block-node" data-paragraphid="34" data-from-init="true" data-changed="false">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Your ticket {{ticket_id}} has been closed by our team support.</p>
                <p class="root-block-node" data-changed="false" data-paragraphid="45">Sincerely,<br></p><p class="root-block-node" data-changed="false" data-paragraphid="45">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // When ticket closed  by Admin mail to agent or admin
            [
                'code' => 'send_mail_to_agent_when_ticket_closed_by_admin_or_agent',
                'title' => 'Send mail to agent and admin when ticket closed by admin or agent',
                'subject' => 'The ticket has been closed.',
                'body' => '<p class="root-block-node" data-paragraphid="33" data-from-init="true" data-changed="false">Dear Admin panel user,</p><p class="root-block-node" data-paragraphid="34" data-from-init="true" data-changed="false">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; The ticket {{ticket_id}} has been closed by our support team {{closed_agent_role}} {{closed_agent_name}}.</p>
                <p class="root-block-node" data-changed="false" data-paragraphid="45">Sincerely,<br></p><p class="root-block-node" data-changed="false" data-paragraphid="45">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'send_mail_admin_panel_users_when_category_changed',
                'title' => 'Send mail to admin panel users when ticket category changed',
                'subject' => 'The ticket category has been changed.',
                'body' => '<p class="root-block-node" data-paragraphid="33" data-from-init="true" data-changed="false">Dear Admin panel user,</p>
                        <p class="root-block-node" data-paragraphid="34" data-from-init="true" data-changed="false">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; The ticket {{ticket_id}}  category has been changed.</p>
                        <p class="root-block-node" data-changed="false" data-paragraphid="45">Sincerely,<br></p><p class="root-block-node" data-changed="false" data-paragraphid="45">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'send_mail_customer_when_category_changed',
                'title' => 'Send mail to customers when ticket category is changed',
                'subject' => 'The category has been changed.',
                'body' => '<p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">Dear {{ticket_username}},</p><p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">This email is to let you know that you have selected the wrong category {{ticket_oldcategory}} for this project. Our support team has changed it to the right category, {{ticket_changedcategory}}.</p>
                <p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Sincerely,</p>
                <p class="root-block-node" data-paragraphid="20" data-from-init="true" data-changed="false">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'send_mail_to_admin_when_ticket_note_created',
                'title' => 'Send mail to admin when ticket note is created.',
                'subject' => 'A ticket note for {{ticket_id}} is created.',
                'body' => '<p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">Dear Admin,</p><p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">This email is to let you know that {{note_username}} has added a new note for a ticket {{ticket_id}}.</p>
                <p></p><p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false"><b>Note by employee&nbsp;</b>: </p><p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">{{ticket_note}}</p><p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false"><b>Ticket URL</b> :</p><p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">{{ticket_admin_url}}&nbsp;</p>
                <p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Sincerely,</p>
                <p class="root-block-node" data-paragraphid="20" data-from-init="true" data-changed="false">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

    }
}

