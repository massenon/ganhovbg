<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Translate;
use App\Models\User;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->english();
        $this->superadmin();
    }


    public function english()
    {

        // Menu section

        Translate::create([
            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Dashboard',
            'value' => 'Dashboard',

        ]);
        Translate::create([
            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Profile',
            'value' => 'Profile',

        ]);
        Translate::create([
            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'All Tickets',
            'value' => 'All Tickets',

        ]);
        Translate::create([
            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Recent Tickets',
            'value' => 'Recent Tickets',

        ]);
        Translate::create([
            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Total Tickets',
            'value' => 'Total Tickets',

        ]);
        Translate::create([
            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Total Active Tickets',
            'value' => 'Total Active Tickets',

        ]);
        Translate::create([
            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Total Closed Tickets',
            'value' => 'Total Closed Tickets',

        ]);
        Translate::create([
            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Total On-Hold Tickets',
            'value' => 'Total On-Hold Tickets',

        ]);
        Translate::create([
            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Total Overdue Tickets',
            'value' => 'Total Overdue Tickets',

        ]);
        Translate::create([
            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Total Assigned Tickets',
            'value' => 'Total Assigned Tickets',

        ]);
        Translate::create([
            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'My Tickets',
            'value' => 'My Tickets',

        ]);
        Translate::create([
            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Self Assigned Tickets',
            'value' => 'Self Assigned Tickets',

        ]);
        Translate::create([
            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Assigned Tickets',
            'value' => 'Assigned Tickets',

        ]);
        Translate::create([
            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'My Assigned Tickets',
            'value' => 'My Assigned Tickets',

        ]);
        Translate::create([
            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'My Closed Tickets',
            'value' => 'My Closed Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Categories',
            'value' => 'Categories',

        ]);
        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Main Categories',
            'value' => 'Main Categories',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'SubCategory',
            'value' => 'SubCategory',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Knowledge',
            'value' => 'Knowledge',

        ]);
        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Articles',
            'value' => 'Articles',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Projects',
            'value' => 'Projects',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Trashed Tickets',
            'value' => 'Trashed Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Faq Category',
            'value' => 'Faq Category',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Manage Roles',
            'value' => 'Manage Roles',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Roles & Permissions',
            'value' => 'Roles & Permissions',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Create Employee',
            'value' => 'Create Employee',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Employees List',
            'value' => 'Employees List',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Landing Page Settings',
            'value' => 'Landing Page Settings',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Banner',
            'value' => 'Banner',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Feature Box',
            'value' => 'Feature Box',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Call To Action',
            'value' => 'Call To Action',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Testmonial',
            'value' => 'Testmonial',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'FAQ’s',
            'value' => 'FAQ’s',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Customers',
            'value' => 'Customers',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Canned Response',
            'value' => 'Canned Response',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Envato',
            'value' => 'Envato',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Envato API Token',
            'value' => 'Envato API Token',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Envato License Verification',
            'value' => 'Envato License Verification',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'App Info',
            'value' => 'App Info',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'App Purchase Code',
            'value' => 'App Purchase Code',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Groups',
            'value' => 'Groups',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Create Group',
            'value' => 'Create Group',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Groups List',
            'value' => 'Groups List',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Notifications',
            'value' => 'Notifications',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'All Notifications',
            'value' => 'All Notifications',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Custom Notifications',
            'value' => 'Custom Notifications',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Custom Pages',
            'value' => 'Custom Pages',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Pages',
            'value' => 'Pages',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => '404 Error Page',
            'value' => '404 Error Page',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Under Maintenance Page',
            'value' => 'Under Maintenance Page',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Bussiness Hours',
            'value' => 'Bussiness Hours',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'App Setting',
            'value' => 'App Setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'General Setting',
            'value' => 'General Setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Ticket Setting',
            'value' => 'Ticket Setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'SEO',
            'value' => 'SEO',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Google Analytics',
            'value' => 'Google Analytics',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Custom CSS & JS',
            'value' => 'Custom CSS & JS',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Captcha Setting',
            'value' => 'Captcha Setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Social Logins',
            'value' => 'Social Logins',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Email Setting',
            'value' => 'Email Setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'External Chat',
            'value' => 'External Chat',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Maintenance Mode',
            'value' => 'Maintenance Mode',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Security Setting',
            'value' => 'Security Setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'IP List',
            'value' => 'IP List',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Email to Tickets',
            'value' => 'Email to Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Announcements',
            'value' => 'Announcements',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Email Templates',
            'value' => 'Email Templates',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Clear Cache',
            'value' => 'Clear Cache',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Custom Field',
            'value' => 'Custom Field',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Languages',
            'value' => 'Languages',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Create Ticket',
            'value' => 'Create Ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Home Page',
            'value' => 'Home Page',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Profile',
            'value' => 'Profile',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Logout',
            'value' => 'Logout',

        ]);

        Translate::create([
            'lang_code' => 'en',
              'group_langname' => 'menu',
              'key' => 'Home',
              'value' => 'Home',

          ]);

          Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Contact Us',
            'value' => 'Contact Us',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Tickets',
            'value' => 'Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Login',
            'value' => 'Login',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Register',
            'value' => 'Register',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Submit Ticket',
            'value' => 'Submit Ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Edit Profile',
            'value' => 'Edit Profile',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Active Tickets',
            'value' => 'Active Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'Closed Tickets',
            'value' => 'Closed Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'menu',
            'key' => 'On-Hold Tickets',
            'value' => 'On-Hold Tickets',

        ]);


        // Notifications
        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'New Notifications',
            'value' => 'New Notifications',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Mark all as read',
            'value' => 'Mark all as read',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'A new ticket has been created',
            'value' => 'A new ticket has been created',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Ticket is assigned',
            'value' => 'Ticket is assigned',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'This ticket has been closed',
            'value' => 'This ticket has been closed',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'This ticket status is On-Hold',
            'value' => 'This ticket status is On-Hold',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'This ticket has been reopened',
            'value' => 'This ticket has been reopened',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'You got a new reply on this ticket',
            'value' => 'You got a new reply on this ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'This ticket status is overdue',
            'value' => 'This ticket status is overdue',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'There are no new notifications to display',
            'value' => 'There are no new notifications to display',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'See All Notifications',
            'value' => 'See All Notifications',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Your new ticket has been created',
            'value' => 'Your new ticket has been created',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Your ticket has been closed',
            'value' => 'Your ticket has been closed',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Your ticket status is On-Hold',
            'value' => 'Your ticket status is On-Hold',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Your ticket has been Reopened',
            'value' => 'Your ticket has been Reopened',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'You got a new reply on this ticket',
            'value' => 'You got a new reply on this ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Your ticket status is overdue',
            'value' => 'Your ticket status is overdue',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Filter Notifications',
            'value' => 'Filter Notifications',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Sort by Status',
            'value' => 'Sort by Status',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'New Tickets',
            'value' => 'New Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Closed Tickets',
            'value' => 'Closed Ticketss',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'On-Hold Tickets',
            'value' => 'On-Hold Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Overdue Tickets',
            'value' => 'Overdue Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Re-Open Tickets',
            'value' => 'Re-Open Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Inprogress Tickets',
            'value' => 'Inprogress Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Custom Notifications',
            'value' => 'Custom Notifications',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'New Ticket',
            'value' => 'New Ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Closed Ticket',
            'value' => 'Closed Ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'On-Hold Ticket',
            'value' => 'On-Hold Ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Overdue Ticket',
            'value' => 'Overdue Ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Re-Open Ticket',
            'value' => 'Re-Open Ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'notification',
            'key' => 'Inprogress Ticket',
            'value' => 'Inprogress Ticket',

        ]);



        // ALERTS
        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'We have e-mailed your password reset link!',
            'value' => 'We have e-mailed your password reset link!',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Your Account is Inactive. Please Contact to Admin.',
            'value' => 'Your Account is Inactive. Please Contact to Admin.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Your Account is Not Verified.',
            'value' => 'Your Account is Not Verified.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Your password has been changed!',
            'value' => 'Your password has been changed!',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'An announcement has been successfully updated.',
            'value' => 'An announcement has been successfully updated.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The announcement was successfully deleted.',
            'value' => 'The announcement was successfully deleted.',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Updated successfully',
            'value' => 'Updated successfully',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The ticket was successfully assigned.',
            'value' => 'The ticket was successfully assigned.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Deleted successfully',
            'value' => 'Deleted successfully',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Please check the format and size of the file.',
            'value' => 'Please check the format and size of the file.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Your profile has been successfully updated.',
            'value' => 'Your profile has been successfully updated.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The profile image was successfully removed.',
            'value' => 'The profile image was successfully removed.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'A new customer was successfully added.',
            'value' => 'A new customer was successfully added.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The customer profile was successfully updated.',
            'value' => 'The customer profile was successfully updated.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The account has been deactivated.',
            'value' => 'The account has been deactivated.',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The customer was deleted successfully.',
            'value' => 'The customer was deleted successfully.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'A test email was sent successfully.',
            'value' => 'A test email was sent successfully.',

        ]);
        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The test email couldn’t be sent.',
            'value' => 'The test email couldn’t be sent.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The ticket has been closed.',
            'value' => 'The ticket has been closed.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The ticket was successfully deleted.',
            'value' => 'The ticket was successfully deleted.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'A ticket has been opened with the ticket ID.',
            'value' => 'A ticket has been opened with the ticket ID.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Domain is Blocked List',
            'value' => 'Domain is Blocked List',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The note was successfully submitted.',
            'value' => 'The note was successfully submitted.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The note was successfully deleted.',
            'value' => 'The note was successfully deleted.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The ticket was successfully restore.',
            'value' => 'The ticket was successfully restore.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'A new employee was successfully added.',
            'value' => 'A new employee was successfully added.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The employee’s profile was successfully updated.',
            'value' => 'The employee’s profile was successfully updated.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The employee was successfully deleted.',
            'value' => 'The employee was successfully deleted.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The employee list was imported successfully.',
            'value' => 'The employee list was imported successfully.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The password has been successfully changed!',
            'value' => 'The password has been successfully changed!',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'A new article was successfully created.',
            'value' => 'A new article was successfully created.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'This article has been successfully updated.',
            'value' => 'This article has been successfully updated.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The article was successfully deleted.',
            'value' => 'The article was successfully deleted.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Cannot Update the data',
            'value' => 'Cannot Update the data',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The category was successfully updated.',
            'value' => 'The category was successfully updated.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The category was successfully deleted.',
            'value' => 'The category was successfully deleted.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The subcategory was successfully updated.',
            'value' => 'The subcategory was successfully updated.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The subcategory was successfully deleted.',
            'value' => 'The subcategory was successfully deleted.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Your new password can not be the same as your old password. Please choose a new password.',
            'value' => 'Your new password can not be the same as your old password. Please choose a new password.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The current password does not match!',
            'value' => 'The current password does not match!',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The response to the ticket was successful.',
            'value' => 'The response to the ticket was successful.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The ticket has been successfully reopened.',
            'value' => 'The ticket has been successfully reopened.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The FAQ has been successfully updated.',
            'value' => 'The FAQ has been successfully updated.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The FAQ has been successfully deleted.',
            'value' => 'The FAQ has been successfully deleted.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Featurebox Updated successfully',
            'value' => 'Featurebox Updated successfully',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Featurebox Updated successfully',
            'value' => 'Featurebox Updated successfully',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'A group was successfully created.',
            'value' => 'A group was successfully created.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The group updated successfully.',
            'value' => 'The group updated successfully.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The group deleted successfully.',
            'value' => 'The group deleted successfully.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The IP address was successfully created and updated.',
            'value' => 'The IP address was successfully created and updated.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The IP address has been successfully removed.',
            'value' => 'The IP address has been successfully removed.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'A custom notification was successfully sent to the customer.',
            'value' => 'A custom notification was successfully sent to the customer.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'A custom notification was successfully sent to the employee.',
            'value' => 'A custom notification was successfully sent to the employee.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => '"Custom notification" was successfully deleted.',
            'value' => '"Custom notification" was successfully deleted.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Role updated successfully',
            'value' => 'Role updated successfully',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The project has been updated successfully.',
            'value' => 'The project has been updated successfully.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The project was successfully deleted.',
            'value' => 'The project was successfully deleted.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The projects were successfully assigned.',
            'value' => 'The projects were successfully assigned.',

        ]);
        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Projects have not been assigned.',
            'value' => 'Projects have not been assigned.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The project list was imported successfully.',
            'value' => 'The project list was imported successfully.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The role was successfully created.',
            'value' => 'The role was successfully created.',

        ]);
        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The role has been successfully updated.',
            'value' => 'The role has been successfully updated.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'You are not supposed to block your own country.',
            'value' => 'You are not supposed to block your own country.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The language has been successfully updated',
            'value' => 'The language has been successfully updated',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The testimonial has been successfully updated',
            'value' => 'The testimonial has been successfully updated',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The testimonial to was successfully deleted.',
            'value' => 'The testimonial to was successfully deleted.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Thank you for contacting us!',
            'value' => 'Thank you for contacting us!',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Password successfully changed!',
            'value' => 'Password successfully changed!',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Current password does not match!',
            'value' => 'Current password does not match!',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Your email has not been verified. Please verify your email.',
            'value' => 'Your email has not been verified. Please verify your email.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'This email is not registered.',
            'value' => 'This email is not registered.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Invalid email or password',
            'value' => 'Invalid email or password',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Techincal Issue',
            'value' => 'Techincal Issue',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The email verification link was successfully sent. Please check and verify your email.',
            'value' => 'The email verification link was successfully sent. Please check and verify your email.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Your e-mail has been verified. You can now login.',
            'value' => 'Your e-mail has been verified. You can now login.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Your e-mail has already been verified. You can now login.',
            'value' => 'Your e-mail has already been verified. You can now login.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Sorry, your email could not be determined.',
            'value' => 'Sorry, your email could not be determined.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Your account has been deleted!',
            'value' => 'Your account has been deleted!',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The ticket has been already closed.',
            'value' => 'The ticket has been already closed.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The image has been deleted successfully!',
            'value' => 'The image has been deleted successfully!',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'You already rating has submitted.',
            'value' => 'You already rating has submitted.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Thank you for rating us.',
            'value' => 'Thank you for rating us.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The purchase code has been verified, but your product support has expired.',
            'value' => 'The purchase code has been verified, but your product support has expired.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The purchase code has been validated and is supported.',
            'value' => 'The purchase code has been validated and is supported.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'The Purchase Code is invalid.',
            'value' => 'The Purchase Code is invalid.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Your comment has be submitted.',
            'value' => 'Your comment has be submitted.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Please check your Email',
            'value' => 'Please check your Email',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Email is already registered, Please login to Create a Ticket',
            'value' => 'Email is already registered, Please login to Create a Ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Please select at least one check box.',
            'value' => 'Please select at least one check box.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Category name already exists',
            'value' => 'Category name already exists',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'SubCategory name already exists',
            'value' => 'SubCategory name already exists',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Setting Not Updated',
            'value' => 'Setting Not Updated',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Project Name is Already Exists',
            'value' => 'Project Name is Already Exists',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Are you sure you want to continue?',
            'value' => 'Are you sure you want to continue?',

        ]);
        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'This might erase your records permanently',
            'value' => 'This might erase your records permanently',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Are you sure you want to unassign this agent?',
            'value' => 'Are you sure you want to unassign this agent?',

        ]);
        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'This agent may no longer exist for this ticket.',
            'value' => 'This agent may no longer exist for this ticket.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Are you sure you want to reset this record?',
            'value' => 'Are you sure you want to reset this record?',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'alerts',
            'key' => 'Are you sure you want to remove the profile image?',
            'value' => 'Are you sure you want to remove the profile image?',

        ]);


        //Setting

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'App Title & Logos',
            'value' => 'App Title & Logos',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Upload Light-Logo',
            'value' => 'Upload Light-Logo',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Upload Dark-Logo',
            'value' => 'Upload Dark-Logo',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Upload Dark-Icon',
            'value' => 'Upload Dark-Icon',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Upload Light-Icon',
            'value' => 'Upload Light-Icon',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Upload Favicon',
            'value' => 'Upload Favicon',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Set URL',
            'value' => 'Set URL',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Terms of service URL',
            'value' => 'Terms of service URL',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Color Setting',
            'value' => 'Color Setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Primary Color',
            'value' => 'Primary Color',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Secondary Color',
            'value' => 'Secondary Color',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Global Language Setting',
            'value' => 'Global Language Setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Select Language',
            'value' => 'Select Language',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Global Date & Time Format',
            'value' => 'Global Date & Time Format',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Select Date Format',
            'value' => 'Select Date Format',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Select Time Format',
            'value' => 'Select Time Format',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'First Day of the Week',
            'value' => 'First Day of the Week',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Select Day',
            'value' => 'Select Day',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Global Timezones',
            'value' => 'Global Timezones',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Select Global Timezones',
            'value' => 'Select Global Timezones',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'App Global Settings',
            'value' => 'App Global Settings',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Enable Personal Setting For Admin Panel',
            'value' => 'Enable Personal Setting For Admin Panel',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you disable this setting, the "Personal Setting" with the "Switch to Dark-Mode" option will disappear from the Admin panel on the profile page.',
            'value' => 'If you disable this setting, the "Personal Setting" with the "Switch to Dark-Mode" option will disappear from the Admin panel on the profile page.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Enable Personal Setting For Customer Panel',
            'value' => 'Enable Personal Setting For Customer Panel',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this setting, it will appear on all customer panels on the profile page with the "Switch to Dark-Mode" option. And global "Dark-Mode',
            'value' => 'If you enable this setting, it will appear on all customer panels on the profile page with the "Switch to Dark-Mode" option. And global "Dark-Mode',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Enable Dark-Mode',
            'value' => 'Enable Dark-Mode',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this setting, the whole application will convert into "Dark-Mode" except for the users that are permitted with "Personal Settings."',
            'value' => 'If you enable this setting, the whole application will convert into "Dark-Mode" except for the users that are permitted with "Personal Settings."',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Enable Popup Register/Login',
            'value' => 'Enable Popup Register/Login',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this setting, only customers can access the registration and login forms with the “popup modal”.',
            'value' => 'If you enable this setting, only customers can access the registration and login forms with the “popup modal”.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Disable Register',
            'value' => 'Disable Register',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this setting, "Register" options will disappear from the application in the header section, and new visitors wont be able to register',
            'value' => 'If you enable this setting, "Register" options will disappear from the application in the header section, and new visitors wont be able to register',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Disable Google Font',
            'value' => 'Disable Google Font',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this setting, "Google Font" will not apply to the whole application or site.',
            'value' => 'If you enable this setting, "Google Font" will not apply to the whole application or site.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Enable Force SSL (https)',
            'value' => 'Enable Force SSL (https)',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this setting, it will make your web application secure using "force SSL" when it is not secure, even if your domain is certified with an SSL certificate.',
            'value' => 'If you enable this setting, it will make your web application secure using "force SSL" when it is not secure, even if your domain is certified with an SSL certificate.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Enable Knowledge',
            'value' => 'Enable Knowledge',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you disable this setting, the "Knowledge" option will disappear from the application in the header section.',
            'value' => 'If you disable this setting, the "Knowledge" option will disappear from the application in the header section.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Enable Faq',
            'value' => 'Enable Faq',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you disable this setting, the "faq" option will disappear from the application in the header section.',
            'value' => 'If you disable this setting, the "faq" option will disappear from the application in the header section.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Enable Contact Us',
            'value' => 'Enable Contact Us',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you disable this setting, the "Contact" option will disappear from the application in the header section.',
            'value' => 'If you disable this setting, the "Contact" option will disappear from the application in the header section.',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Enable Customer Profile Image Upload',
            'value' => 'Enable Customer Profile Image Upload',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you disable this setting, the "Upload file" option will disappear from the customers profile page, and they wont be able to upload their profile picture.',
            'value' => 'If you disable this setting, the "Upload file" option will disappear from the customers profile page, and they wont be able to upload their profile picture.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Envato On',
            'value' => 'Envato On',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you disable this Envato switch, the "Envato" option will disappear from the application’s side menu.',
            'value' => 'If you disable this Envato switch, the "Envato" option will disappear from the application’s side menu.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Envato Expired On',
            'value' => 'Envato Expired On',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'An expired license cannot be used by "customers" or "guests" if you enable this Envato Expired switch.',
            'value' => 'An expired license cannot be used by "customers" or "guests" if you enable this Envato Expired switch.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Purchase code enable to agent',
            'value' => 'Purchase code enable to agent',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this, employees can see the purchase code.',
            'value' => 'If you enable this, employees can see the purchase code.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Default Login',
            'value' => 'Default Login',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable it will be a login page is default',
            'value' => 'If you enable it will be a login page is default',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Sidemenu Icon Style',
            'value' => 'Sidemenu Icon Style',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you "Enable" this setting, the whole application sidemenu will be converted into "Sidemenu Icon Style".',
            'value' => 'If you "Enable" this setting, the whole application sidemenu will be converted into "Sidemenu Icon Style".',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Login Disable',
            'value' => 'Login Disable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you "Enable" this setting, customers cannot login to their dashboard.',
            'value' => 'If you "Enable" this setting, customers cannot login to their dashboard.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Customer Account Delete On/off',
            'value' => 'Customer Account Delete On/off',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you "Enable" this setting, customers can "Delete" their account permenently.',
            'value' => 'If you "Enable" this setting, customers can "Delete" their account permenently.',

        ]);


        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Footer Copyright Text',
            'value' => 'Footer Copyright Text',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Customer Can Re-Open Ticket Enable/Disable',
            'value' => 'Customer Can Re-Open Ticket Enable/Disable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you disable this ticket setting, customers can not "Re-Open" their tickets. If it is enabled, then the customers can "Re-Open" their tickets within the mentioned days in the input field below. And if it is set to 0 (zero), then the customers can reopen their tickets at any time.',
            'value' => 'If you disable this ticket setting, customers can not "Re-Open" their tickets. If it is enabled, then the customers can "Re-Open" their tickets within the mentioned days in the input field below. And if it is set to 0 (zero), then the customers can reopen their tickets at any time.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Re-Open Days',
            'value' => 'Re-Open Days',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Auto Close Ticket Enable/Disable',
            'value' => 'Auto Close Ticket Enable/Disable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you disable this ticket setting, the inactive ticket won’t be closed automatically. Users will need to close the ticket manually. If it is enabled, the inactive ticket will close automatically after the completion of the days that are mentioned in the input field below.',
            'value' => 'If you disable this ticket setting, the inactive ticket won’t be closed automatically. Users will need to close the ticket manually. If it is enabled, the inactive ticket will close automatically after the completion of the days that are mentioned in the input field below.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Auto Close Days',
            'value' => 'Auto Close Days',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Auto Ticket Overdue Enable/Disable',
            'value' => 'Auto Ticket Overdue Enable/Disable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you disable this ticket setting, the "overdue" status won’t be reflected on the tickets table in the admin panel. If it is enabled and the users of an admin panel don’t give a reply to the customer within the mentioned days, then the ticket status changes to "Overdue."',
            'value' => 'If you disable this ticket setting, the "overdue" status won’t be reflected on the tickets table in the admin panel. If it is enabled and the users of an admin panel don’t give a reply to the customer within the mentioned days, then the ticket status changes to "Overdue."',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Auto Overdue Days',
            'value' => 'Auto Overdue Days',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Ticket Reply Status Time Enable/Disable',
            'value' => 'Ticket Reply Status Time Enable/Disable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Basically, this setting is used to change the ticket status to "Waiting for response" when a customer doesn’t give a reply to the ticket within the mentioned hours in the input field. If you disable this ticket setting, then it won’t change the ticket status.',
            'value' => 'Basically, this setting is used to change the ticket status to "Waiting for response" when a customer doesn’t give a reply to the ticket within the mentioned hours in the input field. If you disable this ticket setting, then it won’t change the ticket status.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Auto Reply Status in Hours',
            'value' => 'Auto Reply Status in Hours',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Auto Delete Notifications Enable/disable',
            'value' => 'Auto Delete Notifications Enable/disable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you disable this notification setting, the red notification won’t be deleted from both panels, i.e., the customer and admin panel. If it is enabled, then the red notifications will be deleted after the completion of the mentioned days in the input field.',
            'value' => 'If you disable this notification setting, the red notification won’t be deleted from both panels, i.e., the customer and admin panel. If it is enabled, then the red notifications will be deleted after the completion of the mentioned days in the input field.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Auto Notification Days',
            'value' => 'Auto Notification Days',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Custom Ticket Id',
            'value' => 'Custom Ticket Id',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Simply customise your ticket ID. For example, SPT-1 is the ticket id. You can only customise the first letters of the ticket ID of your choice. It displays SPT-1 for the registered user and SPTG-1 for the guest user. By default, the letter "G" represents the guest user.',
            'value' => 'Simply customise your ticket ID. For example, SPT-1 is the ticket id. You can only customise the first letters of the ticket ID of your choice. It displays SPT-1 for the registered user and SPTG-1 for the guest user. By default, the letter "G" represents the guest user.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Ticket Title Character Limit',
            'value' => 'Ticket Title Character Limit',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Basically, this setting is used for the limit of ticket titles.Which means the ticket`s title allows the maximum character entered value.',
            'value' => 'Basically, this setting is used for the limit of ticket titles.Which means the ticket`s title allows the maximum character entered value.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Employee Name Protection',
            'value' => 'Employee Name Protection',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you "Enable" this setting, customers will only be able to see the name that you give in the below input field. They will not be able to see the employees name and role.',
            'value' => 'If you "Enable" this setting, customers will only be able to see the name that you give in the below input field. They will not be able to see the employees name and role.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Guest Ticket Enable/Disable',
            'value' => 'Guest Ticket Enable/Disable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you disable this ticket setting, the "Guest Ticket" option will disappear from the application in the header section.',
            'value' => 'If you disable this ticket setting, the "Guest Ticket" option will disappear from the application in the header section.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Customer File Upload in Ticket',
            'value' => 'Customer File Upload in Ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you disable this ticket setting, the "File Upload" option will disappear from the "Tickets" page, while creating or replying to the ticket to the registered customers.',
            'value' => 'If you disable this ticket setting, the "File Upload" option will disappear from the "Tickets" page, while creating or replying to the ticket to the registered customers.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Guest User File Upload in Ticket',
            'value' => 'Guest User File Upload in Ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you disable this ticket setting, the "File Upload" option will disappear from the "Guest Ticket" page while creating or replying to the ticket to the guest users.',
            'value' => 'If you disable this ticket setting, the "File Upload" option will disappear from the "Guest Ticket" page while creating or replying to the ticket to the guest users.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Guest Ticket OTP Enable/Disable',
            'value' => 'Guest Ticket OTP Enable/Disable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this ticket setting, the "Guest Ticket OTP" option will be disabled.',
            'value' => 'If you enable this ticket setting, the "Guest Ticket OTP" option will be disabled.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Customer Ticket Enable/Disable',
            'value' => 'Customer Ticket Enable/Disable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this ticket setting, the "Customer Ticket" option will disappear from the application in the user dashboard.',
            'value' => 'If you enable this ticket setting, the "Customer Ticket" option will disappear from the application in the user dashboard.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Rating Page Enable/Disable',
            'value' => 'Rating Page Enable/Disable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you "Enable" this setting, the "Rating Page" will not appear to the customers after closing the ticket.',
            'value' => 'If you "Enable" this setting, the "Rating Page" will not appear to the customers after closing the ticket.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'File Setting',
            'value' => 'File Setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Captcha Disable',
            'value' => 'Captcha Disable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'By default, both manual and Google Captcha will be disabled.',
            'value' => 'By default, both manual and Google Captcha will be disabled.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Manual Captcha Enable',
            'value' => 'Manual Captcha Enable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'This setting will enable the "Manual" captcha.',
            'value' => 'This setting will enable the "Manual" captcha.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Google Captcha Enable',
            'value' => 'Google Captcha Enable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'This setting will enable the "Google" captcha.',
            'value' => 'This setting will enable the "Google" captcha.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Enable On Contact Form',
            'value' => 'Enable On Contact Form',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this captcha setting feature, it will appear on the "Contact Form".',
            'value' => 'If you enable this captcha setting feature, it will appear on the "Contact Form".',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Enable On Register Form',
            'value' => 'Enable On Register Form',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this captcha setting feature, it will appear on the "Register Form".',
            'value' => 'If you enable this captcha setting feature, it will appear on the "Register Form".',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Enable On Login Form',
            'value' => 'Enable On Login Form',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this captcha setting feature, it will appear on the "Login Form".',
            'value' => 'If you enable this captcha setting feature, it will appear on the "Login Form".',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Enable On Guest Ticket',
            'value' => 'Enable On Guest Ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this captcha setting feature, it will appear on the "Guest Ticket".',
            'value' => 'If you enable this captcha setting feature, it will appear on the "Guest Ticket".',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'External Chat Enable/Disable',
            'value' => 'External Chat Enable/Disable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this “External Chat” setting feature, it will appear on the "Application".',
            'value' => 'If you enable this “External Chat” setting feature, it will appear on the "Application".',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'All Users',
            'value' => 'All Users',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this "All Users" setting feature, the "External Chat" will appear to both the users, i.e., for registered users as well as for guest users on the "Application."',
            'value' => 'If you enable this "All Users" setting feature, the "External Chat" will appear to both the users, i.e., for registered users as well as for guest users on the "Application."',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Only  Registered Users',
            'value' => 'Only  Registered Users',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'If you enable this "Only Registered Users" setting feature, the "External Chat" will appear only for the registered users on the "Application."',
            'value' => 'If you enable this "Only Registered Users" setting feature, the "External Chat" will appear only for the registered users on the "Application."',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Callback/Redirect URL',
            'value' => 'Callback/Redirect URL',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Add this callback URL to your "Envato" App settings.',
            'value' => 'Add this callback URL to your "Envato" App settings.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Add this callback URL to your "Google" App settings.',
            'value' => 'Add this callback URL to your "Google" App settings.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Add this callback URL to your "Facebook" App settings.',
            'value' => 'Add this callback URL to your "Facebook" App settings.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'setting',
            'key' => 'Add this callback URL to your "Twitter" App settings.',
            'value' => 'Add this callback URL to your "Twitter" App settings.',

        ]);

        //Filesetting

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'filesetting',
            'key' => 'The file size should not be more than 5MB',
            'value' => 'The file size should not be more than 5MB',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'filesetting',
            'key' => 'Max File Upload',
            'value' => 'Max File Upload',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'filesetting',
            'key' => 'Max File Size Upload',
            'value' => 'Max File Size Upload',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'filesetting',
            'key' => 'MB',
            'value' => 'MB',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'filesetting',
            'key' => 'Allow File Types',
            'value' => 'Allow File Types',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'filesetting',
            'key' => 'Upload File',
            'value' => 'Upload File',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'filesetting',
            'key' => 'Download',
            'value' => 'Download',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'filesetting',
            'key' => 'File Format: .xlsx & .csv',
            'value' => 'File Format: .xlsx & .csv',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'filesetting',
            'key' => 'Import Data',
            'value' => 'Import Data',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'filesetting',
            'key' => 'Sample File',
            'value' => 'Sample File',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'filesetting',
            'key' => 'The file size should not be more than',
            'value' => 'The file size should not be more than',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'filesetting',
            'key' => 'MB',
            'value' => 'MB',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'filesetting',
            'key' => 'The file size should not be more than 500kb',
            'value' => 'The file size should not be more than 500kb',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'filesetting',
            'key' => 'Filesize should not be morethan 10MB',
            'value' => 'Filesize should not be morethan 10MB',

        ]);

        /// General
        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Employees',
            'value' => 'Employees',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Edit Employee',
            'value' => 'Edit Employee',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'First Name',
            'value' => 'First Name',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Last Name',
            'value' => 'Last Name',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Username',
            'value' => 'Username',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Email',
            'value' => 'Email',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Employee ID',
            'value' => 'Employee ID',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Select Role',
            'value' => 'Select Role',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Mobile Number',
            'value' => 'Mobile Number',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Languages',
            'value' => 'Languages',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Skills',
            'value' => 'Skills',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Country',
            'value' => 'Country',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Timezone',
            'value' => 'Timezone',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Status',
            'value' => 'Status',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Update Profile',
            'value' => 'Update Profile',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Password',
            'value' => 'Password',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add Employee',
            'value' => 'Add Employee',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Import Employees List',
            'value' => 'Import Employees List',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Delete',
            'value' => 'Delete',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Sl.No',
            'value' => 'Sl.No',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Employee Name',
            'value' => 'Employee Name',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Roles',
            'value' => 'Roles',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Register Date',
            'value' => 'Register Date',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Actions',
            'value' => 'Actions',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Generate Password',
            'value' => 'Generate Password',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Save',
            'value' => 'Save',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'User Import',
            'value' => 'User Import',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Import file',
            'value' => 'Import file',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Upload file',
            'value' => 'Upload file',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add New Announcement',
            'value' => 'Add New Announcement',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Title',
            'value' => 'Title',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Start Date',
            'value' => 'Start Date',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'End Date',
            'value' => 'End Date',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add Announcement',
            'value' => 'Add Announcement',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Edit Announcement',
            'value' => 'Edit Announcement',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Notice Text',
            'value' => 'Notice Text',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Close',
            'value' => 'Close',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'New Article',
            'value' => 'New Article',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Category',
            'value' => 'Category',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'SubCategory',
            'value' => 'SubCategory',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Description',
            'value' => 'Description',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Tags',
            'value' => 'Tags',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Publish',
            'value' => 'Publish',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'UnPublish',
            'value' => 'UnPublish',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Privacy Mode',
            'value' => 'Privacy Mode',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Update',
            'value' => 'Update',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Article Title',
            'value' => 'Article Title',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Edit Article',
            'value' => 'Edit Article',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add Article',
            'value' => 'Add Article',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Article List',
            'value' => 'Article List',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Hide Section',
            'value' => 'Hide Section',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Article Section',
            'value' => 'Article Section',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Subtitle',
            'value' => 'Subtitle',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Save Changes',
            'value' => 'Save Changes',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Ticket To Article',
            'value' => 'Ticket To Article',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'New',
            'value' => 'New',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'In-Progress',
            'value' => 'In-Progress',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'On-Hold',
            'value' => 'On-Hold',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Re-Open',
            'value' => 'Re-Open',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'OverDue',
            'value' => 'OverDue',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Closed',
            'value' => 'Closed',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Ticket Details',
            'value' => 'Ticket Details',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'User',
            'value' => 'User',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Assign To',
            'value' => 'Assign To',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Assign To Agent',
            'value' => 'Assign To Agent',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Restore',
            'value' => 'Restore',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Ticket Information',
            'value' => 'Ticket Information',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Created Date',
            'value' => 'Created Date',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Conversations',
            'value' => 'Conversations',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Priority',
            'value' => 'Priority',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Project',
            'value' => 'Project',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Opened Date',
            'value' => 'Opened Date',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Reply Status',
            'value' => 'Reply Status',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Customer Details',
            'value' => 'Customer Details',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'IP',
            'value' => 'IP',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Don’t have any notes yet',
            'value' => 'Don’t have any notes yet',

        ]);
        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add your notes here',
            'value' => 'Add your notes here',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Change Password',
            'value' => 'Change Password',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Old Password',
            'value' => 'Old Password',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'New Password',
            'value' => 'New Password',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Confirm Password',
            'value' => 'Confirm Password',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Forgot Password?',
            'value' => 'Forgot Password?',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Enter the email address that is linked to your account.',
            'value' => 'Enter the email address that is linked to your account.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Submit',
            'value' => 'Submit',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Send me Back',
            'value' => 'Send me Back',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Reset Password',
            'value' => 'Reset Password',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Enter the email address registered on your account',
            'value' => 'Enter the email address registered on your account',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Remembered your password?',
            'value' => 'Remembered your password?',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Remember me',
            'value' => 'Remember me',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Bussiness Hours Title',
            'value' => 'Bussiness Hours Title',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Days',
            'value' => 'Days',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Closed/Open',
            'value' => 'Closed/Open',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Start-time',
            'value' => 'Start-time',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'End-time',
            'value' => 'End-time',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Call Action',
            'value' => 'Call Action',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Call Action Section',
            'value' => 'Call Action Section',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Button-Text',
            'value' => 'Button-Text',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Select Category',
            'value' => 'Select Category',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Category List',
            'value' => 'Category List',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add Category',
            'value' => 'Add Category',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Envato Api Assign',
            'value' => 'Envato Api Assign',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Assign Envato Api',
            'value' => 'Assign Envato Api',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Category Name',
            'value' => 'Category Name',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Ticket/Knowledge',
            'value' => 'Ticket/Knowledge',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Assign To Groups',
            'value' => 'Assign To Groups',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Assigned Priority',
            'value' => 'Assigned Priority',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add New Category',
            'value' => 'Add New Category',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Edit Category',
            'value' => 'Edit Category',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'View On :',
            'value' => 'View On :',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'View On Both',
            'value' => 'View On Both',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'View On Tickets',
            'value' => 'View On Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'View On Knowledge',
            'value' => 'View On Knowledge',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Choose Priority',
            'value' => 'Choose Priority',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Choose Priority :',
            'value' => 'Choose Priority :',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Low',
            'value' => 'Low',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Medium',
            'value' => 'Medium',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'High',
            'value' => 'High',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Status :',
            'value' => 'Status :',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Sub-Category',
            'value' => 'Sub-Category',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Subcategory List',
            'value' => 'Subcategory List',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add SubCategory',
            'value' => 'Add SubCategory',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Subcategory Name',
            'value' => 'Subcategory Name',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Parent Category list',
            'value' => 'Parent Category list',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add New SubCategory',
            'value' => 'Add New SubCategory',

        ]);

          Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Edit Subcategory',
            'value' => 'Edit Subcategory',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Parent Category',
            'value' => 'Parent Category',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Compose for Customers',
            'value' => 'Compose for Customers',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Compose for Employees',
            'value' => 'Compose for Employees',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Compose Notification For Customers',
            'value' => 'Compose Notification For Customers',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Compose Notification For Employees',
            'value' => 'Compose Notification For Employees',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'To',
            'value' => 'To',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Tag',
            'value' => 'Tag',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Select Tag Color',
            'value' => 'Select Tag Color',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Send Message',
            'value' => 'Send Message',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Message',
            'value' => 'Message',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Employees',
            'value' => 'Employees',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Custom Notifications List',
            'value' => 'Custom Notifications List',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'User Type',
            'value' => 'User Type',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Customer',
            'value' => 'Customer',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Create Customer',
            'value' => 'Create Customer',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Please copy the Password',
            'value' => 'Please copy the Password',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Customers List',
            'value' => 'Customers List',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add Customer',
            'value' => 'Add Customer',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Verification',
            'value' => 'Verification',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Edit Customer',
            'value' => 'Edit Customer',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Timezone',
            'value' => 'Timezone',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Timezones',
            'value' => 'Timezones',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Email Template',
            'value' => 'Email Template',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Email Subject',
            'value' => 'Email Subject',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Email Body',
            'value' => 'Email Body',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Send Test Mail',
            'value' => 'Send Test Mail',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Enter Mail',
            'value' => 'Enter Mail',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Send',
            'value' => 'Send',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Mail Driver',
            'value' => 'Mail Driver',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Mail Host',
            'value' => 'Mail Host',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Mail Port',
            'value' => 'Mail Port',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Mail Username',
            'value' => 'Mail Username',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Mail Password',
            'value' => 'Mail Password',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'From Name',
            'value' => 'From Name',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'From Email',
            'value' => 'From Email',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Last Updated on',
            'value' => 'Last Updated on',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Action',
            'value' => 'Action',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Main Title',
            'value' => 'Main Title',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add Faq Category',
            'value' => 'Add Faq Category',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Faq Category Name',
            'value' => 'Faq Category Name',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'FAQ’s Section',
            'value' => 'FAQ’s Section',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add FAQ',
            'value' => 'Add FAQ',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Question',
            'value' => 'Question',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Answer',
            'value' => 'Answer',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add New FAQ',
            'value' => 'Add New FAQ',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Edit FAQ',
            'value' => 'Edit FAQ',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Select Faq Category',
            'value' => 'Select Faq Category',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Feature Box Section',
            'value' => 'Feature Box Section',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Feature Box List',
            'value' => 'Feature Box List',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add Feature',
            'value' => 'Add Feature',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add New Feature Box',
            'value' => 'Add New Feature Box',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Edit Feature Box',
            'value' => 'Edit Feature Box',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'URL',
            'value' => 'URL',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Open in a new tab',
            'value' => 'Open in a new tab',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Create Page',
            'value' => 'Create Page',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add New Page',
            'value' => 'Add New Page',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Edit page',
            'value' => 'Edit page',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'View on header',
            'value' => 'View on header',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'View on footer',
            'value' => 'View on footer',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Are you sure want to remove this logo?',
            'value' => 'Are you sure want to remove this logo?',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Login Disable Statement',
            'value' => 'Login Disable Statement',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Banner Section',
            'value' => 'Banner Section',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Captcha Enable/Disable',
            'value' => 'Captcha Enable/Disable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Google Re-Captcha Setting',
            'value' => 'Google Re-Captcha Setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Site Key',
            'value' => 'Site Key',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Secret Key',
            'value' => 'Secret Key',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Captcha Setting in Forms',
            'value' => 'Captcha Setting in Forms',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'External Chat Setting',
            'value' => 'External Chat Setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Custom CSS',
            'value' => 'Custom CSS',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Custom JS',
            'value' => 'Custom JS',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Enable Google Analytics',
            'value' => 'Enable Google Analytics',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Tracking ID',
            'value' => 'Tracking ID',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Social Login',
            'value' => 'Social Login',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Envato Settings',
            'value' => 'Envato Settings',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Envato App ID',
            'value' => 'Envato App ID',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Envato Secret',
            'value' => 'Envato Secret',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Google App ID',
            'value' => 'Google App ID',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Google Secret',
            'value' => 'Google Secret',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Facebook App ID',
            'value' => 'Facebook App ID',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Facebook Secret',
            'value' => 'Facebook Secret',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Twitter App ID',
            'value' => 'Twitter App ID',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Twitter Secret',
            'value' => 'Twitter Secret',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Google',
            'value' => 'Google',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Facebook',
            'value' => 'Facebook',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Twitter',
            'value' => 'Twitter',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Create Group',
            'value' => 'Create Group',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Select Employees',
            'value' => 'Select Employees',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Edit Group',
            'value' => 'Edit Group',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Groups List',
            'value' => 'Groups List',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add Group',
            'value' => 'Add Group',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Category Assign',
            'value' => 'Category Assign',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Group Name',
            'value' => 'Group Name',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Count',
            'value' => 'Count',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Go Live',
            'value' => 'Go Live',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Under Maintenance',
            'value' => 'Under Maintenance',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Notification View',
            'value' => 'Notification View',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Delete Photo',
            'value' => 'Delete Photo',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Personal Details',
            'value' => 'Personal Details',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Phone',
            'value' => 'Phone',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Role',
            'value' => 'Role',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Role Name',
            'value' => 'Role Name',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Location',
            'value' => 'Location',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Personal setting',
            'value' => 'Personal setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Switch to Dark-Mode',
            'value' => 'Switch to Dark-Mode',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Setting',
            'value' => 'Setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Email Notification On/Off',
            'value' => 'Email Notification On/Off',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Profile Details',
            'value' => 'Profile Details',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add Project',
            'value' => 'Add Project',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Assign Projects',
            'value' => 'Assign Projects',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add New Project',
            'value' => 'Add New Project',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Edit Project',
            'value' => 'Edit Project',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Create Role & Permissions',
            'value' => 'Create Role & Permissions',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Select All',
            'value' => 'Select All',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Permissions',
            'value' => 'Permissions',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Edit Role & Permissions',
            'value' => 'Edit Role & Permissions',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Roles List',
            'value' => 'Roles List',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add Role & Permissions',
            'value' => 'Add Role & Permissions',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Employees Count',
            'value' => 'Employees Count',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Permissions Count',
            'value' => 'Permissions Count',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Email to tickets Enable/Disable',
            'value' => 'Email to tickets Enable/Disable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'IMAP Host',
            'value' => 'IMAP Host',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'IMAP Port',
            'value' => 'IMAP Port',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'IMAP Encryption',
            'value' => 'IMAP Encryption',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'IMAP Protocol',
            'value' => 'IMAP Protocol',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'IMAP Username',
            'value' => 'IMAP Username',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'IMAP Password',
            'value' => 'IMAP Password',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add IP Address',
            'value' => 'Add IP Address',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Entry ',
            'value' => 'Entry  ',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Types',
            'value' => 'Types',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Edit IP Address',
            'value' => 'Edit IP Address',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add New IP Address',
            'value' => 'Add New IP Address',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Unlock',
            'value' => 'Unlock',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Lock',
            'value' => 'Lock',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Block',
            'value' => 'Block',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Front End',
            'value' => 'Front End',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Admin',
            'value' => 'Admin',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'DOS',
            'value' => 'DOS',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Domain Lists',
            'value' => 'Domain Lists',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Country Block/Unblock Setting',
            'value' => 'Country Block/Unblock Setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Countries List',
            'value' => 'Countries List',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Blocked Countries',
            'value' => 'Blocked Countries',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Allowed Countries',
            'value' => 'Allowed Countries',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Admin Country Block/Unblock Setting',
            'value' => 'Admin Country Block/Unblock Setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'DOS Attack Setting',
            'value' => 'DOS Attack Setting',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Enable/Disable',
            'value' => 'Enable/Disable',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'If you enable this setting, it prevents denial-of-service (DoS) attacks on the application.',
            'value' => 'If you enable this setting, it prevents denial-of-service (DoS) attacks on the application.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'If there are more than',
            'value' => 'If there are more than',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'attempts in',
            'value' => 'attempts in',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'seconds',
            'value' => 'seconds',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'View Captcha',
            'value' => 'View Captcha',

        ]);
        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Block IP Address',
            'value' => 'Block IP Address',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Author',
            'value' => 'Author',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Keywords',
            'value' => 'Keywords',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Testmonial Section',
            'value' => 'Testmonial Section',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Testimonial List',
            'value' => 'Testimonial List',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add Testmonial',
            'value' => 'Add Testmonial',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Add New Testimonial',
            'value' => 'Add New Testimonial',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Edit Testimonial',
            'value' => 'Edit Testimonial',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Designation',
            'value' => 'Designation',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Assigned Closed Tickets',
            'value' => 'Assigned Closed Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Assigned In-Progress Tickets',
            'value' => 'Assigned In-Progress Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Assigned New Tickets',
            'value' => 'Assigned New Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Assigned On-Hold Tickets',
            'value' => 'Assigned On-Hold Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Assigned Overdue Tickets',
            'value' => 'Assigned Overdue Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Assigned Re-Open Tickets',
            'value' => 'Assigned Re-Open Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'My Assigned Closed Tickets',
            'value' => 'My Assigned Closed Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'My Assigned In-Progress Tickets',
            'value' => 'My Assigned In-Progress Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'My Assigned New Tickets',
            'value' => 'My Assigned New Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'My Assigned On-Hold Tickets',
            'value' => 'My Assigned On-Hold Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'My Assigned Overdue Tickets',
            'value' => 'My Assigned Overdue Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'My Assigned Re-Open Tickets',
            'value' => 'My Assigned Re-Open Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Self Assigned Closed Tickets',
            'value' => 'Self Assigned Closed Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Self Assigned In-Progress Tickets',
            'value' => 'Self Assigned In-Progress Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Self Assigned New Tickets',
            'value' => 'Self Assigned New Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Self Assigned On-Hold Tickets',
            'value' => 'Self Assigned On-Hold Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Self Assigned Overdue Tickets',
            'value' => 'Self Assigned Overdue Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Self Assigned Re-Open Tickets',
            'value' => 'Self Assigned Re-Open Tickets',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Delete Ticket',
            'value' => 'Delete Ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Ticket Activity',
            'value' => 'Ticket Activity',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Today',
            'value' => 'Today',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Yesterday',
            'value' => 'Yesterday',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Previous',
            'value' => 'Previous',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Next',
            'value' => 'Next',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Guest Ticket',
            'value' => 'Guest Ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Thank you for submitting your ticket to us. Your tickets are always our top priority. You are submitting a guest ticket.',
            'value' => 'Thank you for submitting your ticket to us. Your tickets are always our top priority. You are submitting a guest ticket.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Please register your account with us to access more features.',
            'value' => 'Please register your account with us to access more features.',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'View Ticket',
            'value' => 'View Ticket',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'View',
            'value' => 'View',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Active',
            'value' => 'Active',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Inactive',
            'value' => 'Inactive',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Support Active',
            'value' => 'Support Active',

        ]);

        Translate::create([

            'lang_code' => 'en',
            'group_langname' => 'general',
            'key' => 'Support Expired',
            'value' => 'Support Expired',

        ]);

    }

    public function superadmin()
    {
        $user = User::find(1);
        if($user)
        {
            $user->dashboard = 'Admin';
            $user->update();
        }

    }
}
