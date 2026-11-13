<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;


class NewUpdateSeederV3_1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            //Cronjob alert in all pages for superadmin only
            [
                'key' => 'cronjob_set',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            //Open AI Settings
            [
                'key' => 'enable_gpt',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'openai_api',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'openai_api',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Email to ticket file attachment failed
        DB::table('email_templates')->insert([

            [
                'code' => 'customer_send_guestticket_created_with_attachment_failed',
                'title' => 'Send email to guest, when file attachment is failed. Guest-ticket is created.',
                'subject' => 'We received your guest ticket successfully. But upload attachment was failed.',
                'body' => '<p>Dear {{ticket_username}},</p><p><br></p><p>We would like to acknowledge that we have received your request and a gust ticket has been created.</p><p>A support representative will be reviewing your request and will send you a personal response 1-2 bussiness days.</p><p><br></p><p>To view the status of the ticket or add comments, please visit</p><p><a href="{{ticket_customer_url}}" target="_blank">{{ticket_customer_url}}</a></p><p>Note:- Without logging into the above link, you cannot access your ticket.</p><p>File upload failed, Please make sure that the file size is within the allowed limits and that the file format is supported.</p><p>Allowed Limits of Files&nbsp; :&nbsp;</p><p>File Formats : {{ticket_file_format}}</p><p>File Size : {{ticket_file_size}} MB</p><p>Max Files that can be uploaded : {{ticket_file_count}}</p><p>We appreciate your patience.</p><p><br></p><p>Sincerely,</p><p>Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
