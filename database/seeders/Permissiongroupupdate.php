<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use DB;

class Permissiongroupupdate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Roles & Permissions
        $this->updatePermissions();
    }




    public function updatePermissions()
    {
        $Cannedresponses = Permission::find(['100', '101', '102', '103']);

        foreach($Cannedresponses as $Cannedresponse)
        {
            $Cannedresponse->permissionsgroupname = 'Canned Response';
            $Cannedresponse->update();
        }

        //Faq
        $faqs = Permission::find(['129', '130', '131', '132', '133', '51', '52', '53', '54']);

        foreach($faqs as $faq)
        {
            $faq->permissionsgroupname = 'FAQ`s';
            $faq->update();
        }

        //Category
        $categorys = Permission::find(['27', '28', '29','30', '135', '111', '112', '113', '114', '115']);

        foreach($categorys as $category)
        {
            $category->permissionsgroupname = 'Category';
            $category->update();
        }
        //Roles & Permission
        $rolespermissioms = Permission::find(['31','32', '33', '34']);

        foreach($rolespermissioms as $rolespermissiom)
        {
            $rolespermissiom->permissionsgroupname = 'Roles & Permission';
            $rolespermissiom->update();
        }

        //Employee
        $employees = Permission::find(['35','36','37','38','39']);

        foreach($employees as $employee)
        {
            $employee->permissionsgroupname = 'Employee';
            $employee->update();
        }
        //Project
        $projects = Permission::find(['15','16','17','18','19','20']);

        foreach($projects as $project)
        {
            $project->permissionsgroupname = 'Project';
            $project->update();
        }
        //Article
        $articles = Permission::find(['21','22','23','24','25','26']);

        foreach($articles as $article)
        {
            $article->permissionsgroupname = 'Knowledge';
            $article->update();
        }
        //Feature Box
        $featureboxs = Permission::find(['42','43','44','45']);

        foreach($featureboxs as $featurebox)
        {
            $featurebox->permissionsgroupname = 'Feature Box';
            $featurebox->update();
        }
        //Testimonial
        $testimonials = Permission::find(['47','48','49','50']);

        foreach($testimonials as $testimonial)
        {
            $testimonial->permissionsgroupname = 'Testimonial';
            $testimonial->update();
        }
        //Customer
        $customers = Permission::find(['55','56','57','58','59','60']);

        foreach($customers as $customer)
        {
            $customer->permissionsgroupname = 'Customer';
            $customer->update();
        }
        //Groups
        $groups = Permission::find(['61','62','63','64', '136']);

        foreach($groups as $group)
        {
            $group->permissionsgroupname = 'Groups';
            $group->update();
        }
        //Custom Notifications
        $customnotifications = Permission::find(['65','66','67','68','69']);

        foreach($customnotifications as $customnotification)
        {
            $customnotification->permissionsgroupname = 'Custom Notifications';
            $customnotification->update();
        }
        //Custom pages
        $custompages = Permission::find(['70','71','72','73','74','75','109','110']);

        foreach($custompages as $custompage)
        {
            $custompage->permissionsgroupname = 'Custom pages';
            $custompage->update();
        }
        //Ip Block
        $ipblocks = Permission::find(['89','90','91','92']);

        foreach($ipblocks as $ipblock)
        {
            $ipblock->permissionsgroupname = 'IP Block';
            $ipblock->update();
        }
        //Annoucements
        $announcements = Permission::find(['93','94','95','96']);

        foreach($announcements as $announcement)
        {
            $announcement->permissionsgroupname = 'Annoucements';
            $announcement->update();
        }
        //Email Template
        $emailtemplates = Permission::find(['97','98']);

        foreach($emailtemplates as $emailtemplate)
        {
            $emailtemplate->permissionsgroupname = 'Email Template';
            $emailtemplate->update();
        }
        //Envato
        $envatos = Permission::find(['104','105','106']);

        foreach($envatos as $envato)
        {
            $envato->permissionsgroupname = 'Envato';
            $envato->update();
        }
        //App Info
        $appinfos = Permission::find(['107','108']);

        foreach($appinfos as $appinfo)
        {
            $appinfo->permissionsgroupname = 'App Info';
            $appinfo->update();
        }

        //App Setting
        $appsettings = Permission::find(['76','77','78','79','80','81','82','83','84','85','86','87','88']);

        foreach($appsettings as $appsetting)
        {
            $appsetting->permissionsgroupname = 'App Setting';
            $appsetting->update();
        }

        //Landing Page Setting
        $landingpagesettings = Permission::find(['40','41','46']);

        foreach($landingpagesettings as $landingpagesetting)
        {
            $landingpagesetting->permissionsgroupname = 'Landing Page Setting';
            $landingpagesetting->update();
        }

        //Reports
        $reportss = Permission::find(['99']);

        foreach($reportss as $reports)
        {
            $reports->permissionsgroupname = 'Reports';
            $reports->update();
        }

        //Profile Edit
        $profiles = Permission::find(['1']);

        foreach($profiles as $profile)
        {
            $profile->permissionsgroupname = 'Profile Edit';
            $profile->update();
        }

        //Tickets Group
        $tickets = Permission::find(['3','4','5','6']);

        foreach($tickets as $ticket)
        {
            $ticket->permissionsgroupname = 'Tickets';
            $ticket->update();
        }


        //Tickets Group
        $ticketsdelete = Permission::find(['2','7','8','9','10','11','12','13','14']);

        foreach($ticketsdelete as $ticketdelete)
        {
            $ticketdelete->delete();
        }


        $per = [
            [
                'id' => 116,
                'name' => 'Department Access',
                'guard_name' => 'web',
                'permissionsgroupname' => 'Department'
            ],
            [
                'id' => 117,
                'name' => 'Department Create',
                'guard_name' => 'web',
                'permissionsgroupname' => 'Department'
            ],
            [
                'id' => 118,
                'name' => 'Department Edit',
                'guard_name' => 'web',
                'permissionsgroupname' => 'Department'
            ],
            [
                'id' => 119,
                'name' => 'Department Delete',
                'guard_name' => 'web',
                'permissionsgroupname' => 'Department'
            ],

            [
                'id' => 120,
                'name' => 'CustomField Access',
                'guard_name' => 'web',
                'permissionsgroupname' => 'CustomField'
            ],
            [
                'id' => 121,
                'name' => 'CustomField Create',
                'guard_name' => 'web',
                'permissionsgroupname' => 'CustomField'
            ],
            [
                'id' => 122,
                'name' => 'CustomField Edit',
                'guard_name' => 'web',
                'permissionsgroupname' => 'CustomField'
            ],
            [
                'id' => 123,
                'name' => 'CustomField Delete',
                'guard_name' => 'web',
                'permissionsgroupname' => 'CustomField'
            ],

            [
                'id' => 124,
                'name' => 'Languages Access',
                'guard_name' => 'web',
                'permissionsgroupname' => 'Languages'
            ],
            [
                'id' => 125,
                'name' => 'Languages Create',
                'guard_name' => 'web',
                'permissionsgroupname' => 'Languages'
            ],
            [
                'id' => 126,
                'name' => 'Languages Edit',
                'guard_name' => 'web',
                'permissionsgroupname' => 'Languages'
            ],
            [
                'id' => 127,
                'name' => 'Languages Delete',
                'guard_name' => 'web',
                'permissionsgroupname' => 'Languages'
            ],
            [
                'id' => 128,
                'name' => 'Languages Translate',
                'guard_name' => 'web',
                'permissionsgroupname' => 'Languages'
            ],
            ['id' => 129, 'name' => 'Main FAQ Access'  , 'guard_name' => 'web', 'permissionsgroupname' => 'FAQ`s'],
            ['id' => 130, 'name' => 'FAQ Category Access'  , 'guard_name' => 'web', 'permissionsgroupname' => 'FAQ`s'],
            ['id' => 131, 'name' => 'FAQ Category Create'  , 'guard_name' => 'web', 'permissionsgroupname' => 'FAQ`s'],
            ['id' => 132, 'name' => 'FAQ Category Edit'  , 'guard_name' => 'web', 'permissionsgroupname' => 'FAQ`s'],
            ['id' => 133, 'name' => 'FAQ Category Delete'  , 'guard_name' => 'web', 'permissionsgroupname' => 'FAQ`s'],
            ['id' => 134, 'name' => 'Bussiness Hours Access'  , 'guard_name' => 'web', 'permissionsgroupname' => 'Bussiness Hours'],

            ['id' => 135, 'name' => 'Category Delete'  , 'guard_name' => 'web', 'permissionsgroupname' => 'Category'],

            ['id' => 136, 'name' => 'Groups Delete'  , 'guard_name' => 'web', 'permissionsgroupname' => 'Groups'],

            ['id' => 137, 'name' => 'Reset Password'  , 'guard_name' => 'web', 'permissionsgroupname' => 'Employee'],

            ['id' => 138, 'name' => 'Employee Status'  , 'guard_name' => 'web', 'permissionsgroupname' => 'Employee'],



        ];

        foreach($per as $pers){
            Permission::updateOrCreate(['id' => $pers['id']], $pers);
        }

        $role = Role::where('name', 'Superadmin')->first();
        $permissions = Permission::get();
        foreach ( $permissions as $code ) {
			$role->givePermissionTo($code);
		};


    }
}
