<?php

use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleCommentController;
use App\Http\Controllers\ArticleReplyController;
use App\Http\Controllers\Contactform\ContactController;
use App\Http\Controllers\CategorypageController;
use App\Http\Controllers\AssistantController;

use App\Http\Controllers\GuestticketController;
use App\Http\Controllers\User\Ticket\CommentsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\User\DashboardController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

include('installer.php');


Route::middleware(ProtectAgainstSpam::class)->group(function() {

	Route::middleware(['checkinstallation'])->group(function () {


		Route::middleware(['admincountryblock','datarecovery','throttle:refresh', 'ipblockunblock'])->group(function () {


			Route::group(['namespace' => 'Admin', 'prefix'	 => 'administration'], function () {

				// Équivalent d'Auth::routes(['register' => false]) avec des URL françaises (mêmes noms de routes)
				Route::get('connexion', 'Auth\LoginController@showLoginForm')->name('login');
				Route::post('connexion', 'Auth\LoginController@login');
				Route::post('deconnexion', 'Auth\LoginController@logout')->name('logout');
				Route::get('mot-de-passe/oublie', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
				Route::post('mot-de-passe/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
				Route::get('mot-de-passe/reinitialiser/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
				Route::post('mot-de-passe/reinitialiser', 'Auth\ResetPasswordController@reset')->name('password.update');

				Route::get('/lien-reinitialisation/{token}', 'Auth\ResetPasswordController@showResetForm')->name('reset.passwords');
				Route::get('/changer-mot-de-passe', 'ChangepasswordController@index');
                Route::get('/validation-connexion', 'Auth\LoginController@validatelog');
				Route::post('/changer-mot-de-passe', 'ChangepasswordController@changePassword');

				Route::middleware('auth','admin.auth')->group( function () {

					Route::get('/mark-as-read', 'AdminDashboardController@markNotification')->name('admin.markNotification');
					Route::get('/recherche', 'AdminSearchController@search')->middleware('throttle:60,1')->name('admin.search');

					Route::get('/', 'AdminDashboardController@index')->name('admin.dashboard');
					Route::get('/activeticket', 'AdminDashboardController@activeticket')->name('admin.activeticket');
					Route::get('/closedticket', 'AdminDashboardController@closedticket')->name('admin.closedticket');
					Route::group(['prefix' => 'activeticket', 'as' => 'admin.activeticket.'], function(){
						Route::get('/inprogress', 'AdminTicketViewController@allactiveinprogresstickets')->name('allactiveinprogresstickets');
						Route::get('/reopen', 'AdminTicketViewController@allactivereopentickets')->name('allactivereopentickets');
						Route::get('/onhold', 'AdminTicketViewController@allactiveonholdtickets')->name('allactiveonholdtickets');
						Route::get('/assigned', 'AdminTicketViewController@allactiveassignedtickets')->name('allactiveassignedtickets');
					});
					Route::get('/onholdtickets', 'AdminDashboardController@onholdticket')->name('admin.onholdticket');
					Route::get('/overduetickets', 'AdminDashboardController@overdueticket')->name('admin.overdueticket');
					Route::get('/allassignedtickets', 'AdminDashboardController@adminallassignedtickets')->name('admin.allassignedtickets');
					Route::get('/recenttickets', 'AdminDashboardController@recenttickets')->name('admin.recenttickets');
					Route::get('/suspendedtickets', 'AdminDashboardController@suspendedtickets')->name('admin.suspendedtickets');
					Route::get('notification/{id}', 'AdminDashboardController@Notificationview')->name('admin.notiication.view');
					Route::get('/categories', 'CategoriesController@index')->name('categorys.index');
					Route::post('/categories/create', 'CategoriesController@store');
					Route::get('/categories/{id}/edit', 'CategoriesController@show');
					Route::get('/categories/status{id}', 'CategoriesController@status');
					Route::get('/categoryassigned/{id}', 'CategoriesController@agentshow');
					Route::get('/category/list/{ticket_id}', 'CategoriesController@categorylist');
					Route::get('/categorylist', 'CategoriesController@categorylistshow');
					Route::post('/categoryenvatoassign', 'CategoriesController@categoryenvatoassign');
					Route::post('/category/change/', 'CategoriesController@categorychange');
					Route::post('/category/all/', 'CategoriesController@categorygetall')->name('category.admin.all');
					Route::get('/subcategories', 'CategoriesController@subcategoryindex')->name('subcategorys.index');
					Route::post('/subcategories', 'CategoriesController@subcategorystore')->name('subcategorys.store');
					Route::get('/subcategories/{id}/edit', 'CategoriesController@subcategoryshow')->name('subcategorys.show');
					Route::post('/subcategory/status/', 'CategoriesController@subcategorystatusupdate')->name('category.admin.subcategorystatusupdate');
					Route::post('/subcategory/delete/', 'CategoriesController@subcategorydelete')->name('category.admin.subcategorydelete');
					Route::get('/subcategory/deleteall', 'CategoriesController@subcategorydeleteall')->name('category.admin.subcategorydeleteall');
					Route::get('/groupassigned/{id}', 'CategoriesController@groupshow');
					Route::post('/groupcategory/group', 'CategoriesController@categorygroupassign');
					Route::post('/selfassign', 'CategoriesController@selfassign')->name('admin.selfassign');
					Route::post('/assignedcategory/createagent', 'CategoriesController@agentshowcreate');
					Route::get('/categories/{id}', 'CategoriesController@destroy')->name('delete');
					Route::get('/category/deleteall', 'CategoriesController@categorymassdestroy')->name('category.deleteall');
					Route::get('/profile', 'AdminprofileController@index');
					Route::get('/profile/edit', 'AdminprofileController@profileedit');
					Route::post('/profile', 'AdminprofileController@profilesetup');
					Route::post('/image/remove/{id}', 'AdminprofileController@imageremove');
					Route::get('/article', 'ArticlesController@index');
					Route::post('/article', 'ArticlesController@article')->name('admin.article.index');
					Route::get('/article/create', 'ArticlesController@create');
					Route::post('/article/create', 'ArticlesController@store');
					Route::post('/article/imageupload', 'ArticlesController@storeMedia')->name('admin.imageupload');
					Route::post('/article/featureimageupload', 'ArticlesController@featureimagestoreMedia')->name('admin.featureimageupload');
					Route::get('/article/{id}/edit', 'ArticlesController@show')->name('admin.article');
					Route::post('/article/{id}/edit', 'ArticlesController@update');
					Route::get('/article/{id}', 'ArticlesController@destroy');
					Route::get('/massarticle/delete', 'ArticlesController@articlemassdestroy');
					Route::post('/article/status{id}', 'ArticlesController@status');
					Route::post('/article/privatestatus/{id}', 'ArticlesController@privatestatus');
					Route::post('/article/featureimage/{id}', 'ArticlesController@featureimage');
					Route::get('/employee', 'AgentCreateController@index')->name('employee');
					Route::get('/employee/create', 'AgentCreateController@create');
					Route::post('/agent', 'AgentCreateController@store');
					Route::get('/agentprofile/{id}', 'AgentCreateController@show');
					Route::post('/agentprofile/{id}', 'AgentCreateController@update');
					Route::get('/userimport', 'AgentCreateController@userimportindex')->name('user.userimport');
					Route::post('/agent/{id}', 'AgentCreateController@destroy');
					Route::post('/massuser/deleteall', 'AgentCreateController@usermassdestroy');
					Route::post('/agent/status/{id}', 'AgentCreateController@status');
                    Route::post('/employeesreplyingremove', 'AdminTicketController@employeesreplyingremove')->name('employeesreplyingremove');
                    Route::post('/employeesreplyingstore', 'AdminTicketController@employeesreplyingstore')->name('employeesreplyingstore');
                    Route::get('/getemployeesreplying/{id}', 'AdminTicketController@getemployeesreplying')->name('getemployeesreplying');
					Route::get('/ticket/{ticket_id}', 'AdminTicketController@destroy');
					Route::post('/priority/change/', 'AdminTicketController@changepriority');
					Route::get('/ticket/delete/tickets', 'AdminTicketController@ticketmassdestroy')->name('admin.ticket.sremove');
					Route::get('/ticket-view/{ticket_id}', 'AdminTicketController@show')->name('admin.ticketshow');
					Route::post('/ticket-comment/{ticket_id}', 'AdminTicketController@commentshow')->name('admin.ticketcomment');
					Route::post('/ticket/{ticket_id}', 'CommentsController@postComment');
					Route::post('/ticket/imageupload/{ticket_id}', 'CommentsController@storeMedia');
					Route::post('/closed/{ticket_id}', 'AdminTicketController@close');
					Route::get('/delete-ticket/{id}', 'AdminTicketController@destroy');
					Route::get('/purchasedetailsverify', 'AdminTicketController@purchasedetailsverify')->name('purchasedetailsverify');
					Route::get('/wrongcustomer', 'AdminTicketController@wrongcustomer')->name('wrongcustomer');
					Route::post('/ticket/editcomment/{id}', 'CommentsController@updateedit');
                    Route::get('/ticket/deletecomment/{id}', 'CommentsController@deletecomment');
					Route::delete('/image/delete/{id}', 'CommentsController@imagedestroy');
					Route::post('/ticket/reopen/{id}', 'CommentsController@reopenticket');
					Route::get('/roleaccess', 'PermissionstatusController@index')->name('ajaxproducts.index');
					Route::get('/roleaccess/{id}/edit', 'PermissionstatusController@edit');
					Route::post('/roleaccess/{id}/edit', 'PermissionstatusController@update');
					Route::get('/faq', 'FAQController@index')->name('faq.index');
					Route::post('/faq', 'FAQController@faq');
					Route::get('/faq/create','FAQController@faqcreate')->name('faq.create');
                    Route::post('/faq/create', 'FAQController@store')->name('faq.store');
                    Route::get('/faq/{id}', 'FAQController@show')->name('faq.edit');
					Route::delete('/faq/delete/{id}', 'FAQController@destroy');
					Route::post('/faq/deleteall', 'FAQController@allfaqdelete')->name('faq.deleteall');
					Route::post('/faq/status{id}', 'FAQController@status');
					Route::post('/faq/privatestatus/{id}', 'FAQController@privatestatus');
					Route::get('/testimonial', 'TestimonialController@index');
					Route::post('/testimonial/create', 'TestimonialController@store');
					Route::post('/testimonial', 'TestimonialController@testi');
					Route::get('/testimonial/{id}', 'TestimonialController@show');
					Route::get('/testimonial/delete/{id}', 'TestimonialController@destroy');
					Route::post('/testimonial/deleteall', 'TestimonialController@alltestimonialdelete')->name('testimonial.deleteall');
					Route::get('/call-to-action', 'CalltoactionController@index');
					Route::post('/call-to-action', 'CalltoactionController@store');
					Route::get('/feature-box', 'FeatureBoxController@index');
					Route::post('/feature-box/feature', 'FeatureBoxController@feature');
					Route::post('/feature-box/create', 'FeatureBoxController@store');
					Route::get('/feature-box/{id}', 'FeatureBoxController@show');
					Route::get('/feature-box/delete/{id}', 'FeatureBoxController@destroy');
					Route::post('/featurebox/deleteall', 'FeatureBoxController@allfeaturedelete')->name('featurebox.deleteall');
					Route::get('/general', 'ApptitleController@index');
					Route::get('/bannersetting', 'ApptitleController@bannerpage');
					Route::post('/general', 'ApptitleController@store');
					Route::post('/bannerstore', 'ApptitleController@bannerstore');
					Route::post('/footer', 'ApptitleController@footerstore');
					Route::post('/logodelete', 'ApptitleController@logodelete')->name('admin.logodelete');
					Route::post('/bussinesslogodelete', 'AdminSettingController@bussinesslogodelete')->name('admin.bussinesslogodelete');
					Route::get('/seo', 'SeopageController@index');
					Route::post('/seo/create', 'SeopageController@store');
					Route::post('/assigned/create', 'AdminAssignedticketsController@create');
					Route::get('/assigned/{id}', 'AdminAssignedticketsController@show');
					Route::get('/assigned/update/{id}', 'AdminAssignedticketsController@update');
					Route::get('/announcement', 'AdminAnnouncementController@index');
                    Route::get('/announcement/create', 'AdminAnnouncementController@create')->name('announcement.create');
					Route::post('/announcement/create', 'AdminAnnouncementController@store');
					Route::get('/announcement/{id}', 'AdminAnnouncementController@show');
					Route::get('/announcement/delete/{id}', 'AdminAnnouncementController@destroy');
					Route::post('/massannouncedelete', 'AdminAnnouncementController@allannouncementdelete')->name('announcementall.delete');
					Route::post('/announcement/status{id}', 'AdminAnnouncementController@status');
                    Route::post('/announcementsetting', 'AdminSettingController@announcementsetting')->name('settings.announcement');
                    Route::get('/announcement/edit/{id}', 'AdminAnnouncementController@edit')->name('announcement.edit');
                    Route::post('/announcement/update', 'AdminAnnouncementController@update')->name('announcement.update');
					Route::get('/emailsetting', 'AdminSettingController@email')->name('email.setting.alert');
					Route::post('/emailsetting', 'AdminSettingController@emailstore')->name('settings.email.store');
					Route::get('/emailtemplates', 'AdminSettingController@emailtemplates');
					Route::get('/emailtemplates/{id}', 'AdminSettingController@emailtemplatesEdit')->name('settings.email.edit');
					Route::post('/emailtemplates/{id}', 'AdminSettingController@emailtemplatesUpdate')->name('settings.email.update');
					Route::get('/captcha', 'AdminSettingController@captcha')->name('settings.captcha');
					Route::post('/captcha', 'AdminSettingController@captchastore')->name('settings.captcha.store');
					Route::post('/captchatype', 'AdminSettingController@captchatypestore');
					Route::post('/captchacontact', 'AdminSettingController@captchacontact')->name('settings.captchacontact.store');
					Route::post('/captcharegister', 'AdminSettingController@captcharegister')->name('settings.captcharegister.store');
					Route::post('/captchalogin', 'AdminSettingController@captchalogin')->name('settings.captchalogin.store');
					Route::post('/captchaadminlogin', 'AdminSettingController@captchaadminlogin')->name('settings.captchaadminlogin.store');
					Route::post('/captchaguest', 'AdminSettingController@captchaguest')->name('settings.captchaguest.store');
					Route::get('/sociallogin', 'AdminSettingController@sociallogin')->name('settings.sociallogin');
					Route::post('/sociallogin', 'AdminSettingController@socialloginupdate')->name('settings.sociallogin.update');
					Route::get('/ticketsetting', 'AdminSettingController@ticketsetting')->name('settings.ticket');
					Route::post('/ticketsetting', 'AdminSettingController@ticketsettingstore')->name('settings.ticket.store');
					Route::get('/languagesetting', function () { return redirect('/administration/general'); });
					Route::post('/languagesetting', 'AdminSettingController@languagesettingstore')->name('settings.lang.store');
					Route::post('/datetimeformat', 'AdminSettingController@datetimeformatstore')->name('settings.timedateformat.store');
					Route::post('/startweek', 'AdminSettingController@startweekstore')->name('settings.startweek.store');
					Route::post('/timezoneupdate', 'AdminSettingController@timezoneupdate')->name('settings.timezone.store');
                    Route::post('/contactemail', 'AdminSettingController@contactemail')->name('settings.contactemail.store');
                    Route::post('/chatgptenable', 'AdminSettingController@enablechatgpt')->name('settings.chatgpt.store');
					Route::get('/general/dark', 'ApptitleController@check');
					Route::get('/customer', 'AdminprofileController@customers')->name('admin.customer');
                    Route::get('/customer/resendverification/{email}', 'AdminprofileController@resendverification');
					Route::post('/knowledge', 'AdminSettingController@knowledge')->name('settings.knowledge.store');
					Route::post('/profileuser', 'AdminSettingController@profileuser')->name('settings.profileuser.store');
					Route::post('/profileagent', 'AdminSettingController@profileagent')->name('settings.profileagent.store');
					Route::get('/customer/create', 'AdminprofileController@customerscreate');
					Route::post('/customer/create', 'AdminprofileController@customersstore');
					Route::get('/customer/{id}', 'AdminprofileController@customersshow');
					Route::get('/usersettings', 'AdminprofileController@usersetting');
					Route::post('/customer/{id}', 'AdminprofileController@customersupdate');
					Route::get('/voilating/{id}', 'AdminprofileController@voilating')->name('voilating.customer');
					Route::get('/unvoilating/{id}', 'AdminprofileController@unvoilating')->name('unvoilating.customer');
					Route::get('/customer/delete/{id}', 'AdminprofileController@customersdelete');
					Route::get('/masscustomer/delete', 'AdminprofileController@customermassdestroy');
					Route::get('/general/register', 'AdminSettingController@registerpopup');
					Route::get('/googleanalytics', 'AdminSettingController@googleanalytics');
					Route::post('/googleanalytics', 'AdminSettingController@googleanalyticsStore')->name('settings.googleanalytics');
					Route::post('/filesetting', 'AdminSettingController@filesettingstore')->name('settings.file.store');
					Route::post('/sendtestmail', 'AdminSettingController@sendTestMail')->name('settings.email.sendtestmail');
					Route::post('/colorsetting', 'AdminSettingController@frontendStore')->name('settings.color.colorsetting');
					Route::post('/urlset', 'AdminSettingController@seturl')->name('settings.url.urlset');
					Route::get('/envatosetting', 'AdminSettingController@envatosetting')->name('settings.envatosetting');
                    Route::post('/expiredsupport', 'AdminSettingController@expiredsupport')->name('settings.expiredsupport');
					Route::get('/customcssjssetting', 'CustomcssjsController@index');
					Route::post('/customcssjssetting', 'CustomcssjsController@customcssjs')->name('settings.custom.cssjs');
					Route::get('/customchatsetting', 'CustomcssjsController@customchat');
					Route::post('/customchatsetting', 'CustomcssjsController@customchats')->name('settings.custom.chat');
					Route::get('/error404', 'CustomerrorpagesController@index');
					Route::post('/error404', 'CustomerrorpagesController@store');
					Route::get('/maintenancepage', 'CustomerrorpagesController@maintenancepage');
					Route::post('/maintenancepage', 'CustomerrorpagesController@maintenancepagestore');
					Route::get('/createticket', 'AdminTicketController@createticket');
					Route::post('/createticket', 'AdminTicketController@gueststore');
					Route::post('/imageupload', 'AdminTicketController@guestmedia')->name('imageuploadadmin');
					Route::get('/alltickets', 'AdminTicketController@alltickets')->name('admin.alltickets');
					Route::get('/alertes/export-xlsx', 'AlertExportController@xlsx')->middleware('throttle:6,1')->name('admin.alerts.export.xlsx');
					Route::get('/alertes/rapport-pdf', 'AlertExportController@pdf')->middleware('throttle:6,1')->name('admin.alerts.report.pdf');
					Route::get('role','RoleCreateController@index');
					Route::get('role/create','RoleCreateController@create');
					Route::post('role/create','RoleCreateController@store');
					Route::get('role/edit/{id}','RoleCreateController@edit');
					Route::post('role/edit/{id}','RoleCreateController@update');
					Route::get('/pages', 'GeneralPageController@index')->name('pages.index');
                    Route::get('/createpage', 'GeneralPageController@createpage')->name('pages.createpage');
                    Route::post('/pages/create', 'GeneralPageController@store')->name('pages.storepage');
                    Route::get('/pages/{id}', 'GeneralPageController@show')->name('pages.show');
                    Route::post('/pagesdelete/{id}', 'GeneralPageController@destroy');
                    Route::post('/pagesdeleteall', 'GeneralPageController@destroyall')->name('deleteall');

					Route::group(['prefix' => 'groups'], function () {

						Route::get('/','GroupCreateController@index');
						Route::get('create','GroupCreateController@create')->name('groups.create');
						Route::post('store','GroupCreateController@store');
						Route::get('view/{id}','GroupCreateController@show');
						Route::post('update/{id}','GroupCreateController@update');
						Route::post('delete/{id}','GroupCreateController@destroy');
						Route::post('deleteall','GroupCreateController@destroyall')->name('groups.deleteall');
						Route::post('statuschange/{id}','GroupCreateController@statuschange')->name('groups.statuschange');

					});
					Route::post('/note/create', 'AdminTicketController@note');
					Route::get('/products/{ticket_id}', 'AdminTicketController@noteshow');
					Route::delete('/ticketnote/delete/{id}', 'AdminTicketController@notedestroy');
					Route::post('userimport', 'AgentCreateController@usercsv')->name('customer.ucsvimport');
					Route::post('projectimport', 'ProjectsController@projetcsv')->name('project.pcsvimport');
					Route::get('projectimport', 'ProjectsController@projetimport')->name('projects.pcsvimports');
					Route::get('maintenancemode', 'MaintanancemodeController@index');
					Route::post('maintenancemode', 'MaintanancemodeController@store')->name('maintanance');
					Route::get('/projects', 'ProjectsController@index')->name('projects');
					Route::get('/notifications', 'ProjectsController@notificationpage')->name('notificationpage');
					Route::post('/projects/create', 'ProjectsController@store')->name('projects.create');
					Route::get('/projects/{id}', 'ProjectsController@show')->name('projects.view');
					Route::get('/projects/delete/{id}', 'ProjectsController@destroy');
					Route::get('massproject/delete', 'ProjectsController@projectmassdestroy');
					Route::get('/projectsassigned', 'ProjectsController@projectlist');
					Route::post('/projectsassigned', 'ProjectsController@projectassignee');
					Route::post('subcat','AdminTicketController@sublist')->name('admin.subcat');
					Route::post('refresh/{id}', 'AdminDashboardController@autorefresh');
					Route::get('reports', 'AdminReportController@index');
					Route::get('customer/adminlogin/{id}', 'AdminprofileController@adminLogin');
					Route::get('ticketreports', 'AdminReportController@ticketreports')->name('admin.ticketreports');

					Route::group(['prefix' => 'customnotification'], function(){

						Route::get('/', 'MailboxController@index')->name('mail.index');
						Route::get('/customercompose', 'MailboxController@customercompose')->name('mail.customer');
						Route::post('/customercompose', 'MailboxController@customercomposesend')->name('mail.customersend');
						Route::get('/employeecompose', 'MailboxController@employeecompose')->name('mail.employee');
						Route::post('/employeecompose', 'MailboxController@employeecomposesend')->name('mail.employeesend');
						Route::get('/sentmail', 'MailboxController@mailsent')->name('mail.sendmail');
						Route::get('/{id}', 'MailboxController@show')->name('mail.show');
						Route::delete('delete/{id}', 'MailboxController@destroy')->name('mail.delete');
						Route::post('/massdelete', 'MailboxController@allnotifydelete')->name('notifyall.delete');

					});

					Route::get('securitysetting', 'SecuritySettingController@index');
					Route::post('securitysetting', 'SecuritySettingController@store')->name('settings.security.country');
					Route::post('adminsecuritysetting/', 'SecuritySettingController@adminstore')->name('settings.security.admin.country');
					Route::post('securitysetting/ip', 'SecuritySettingController@dosstore')->name('settings.security.ip');
					Route::get('ipblocklist', 'IpblockController@index')->name('ipblocklist');
					Route::get('ipblocklist/{id}', 'IpblockController@show')->name('ipblocklist.id');
					Route::post('ipblocklist/create', 'IpblockController@store')->name('ipblocklist.store');
					Route::delete('ipblocklist/delete/{id}', 'IpblockController@destroy')->name('ipblocklist.destroy');
					Route::post('ipblocklist/reset/{id}', 'IpblockController@resetipblock')->name('ipblocklist.reset');
					Route::post('/ipblocklist/deleteall', 'IpblockController@allipblocklistdelete')->name('ipblocklist.deleteall');
					Route::get('emailtotickets', 'SecuritySettingController@emailtoticket')->name('admin.emailtoticket');
					Route::post('emailticket', 'SecuritySettingController@emailticketstore')->name('admin.emaitickets');
					Route::get('language/{locale}', 'SecuritySettingController@setLanguage')->name('admin.front.set_language');

					Route::get('bussinesshour', 'BussinesshourController@index')->name('admin.bussinesshour.index');
					Route::post('bussinesshour', 'BussinesshourController@store')->name('admin.bussinesshour.store');
					Route::post('/bussinesshour/store', 'AdminSettingController@bussinesshourtitle')->name('admin.bussinesshour.bussinesshourtitle');

					Route::post('notify/delete','AdminDashboardController@notifydelete')->name('admin.notifydelete');
					Route::post('/general/logindisable', 'AdminSettingController@logindisable');
					Route::post('employeepasswordreset', 'AgentCreateController@employeepasswordreset')->name('admin.employeepasswordreset');

					Route::get('/faq-category', 'FaqCategoryController@index')->name('faqsub.index');
					Route::post('/faqcategory/store', 'FaqCategoryController@storeupdate')->name('faqsub.storeupdate');
					Route::post('/faqcategory/delete/{id}', 'FaqCategoryController@destroy')->name('faqsub.delete');
					Route::post('/faqcategory/deleteall', 'FaqCategoryController@allfaqcategorydelete')->name('faqcategory.deleteall');
					Route::post('/faqcategory/status/{id}', 'FaqCategoryController@status')->name('faqcategory.status');

					// Assistant GANXÔ (réglages, base de connaissances, statistiques anonymes)
					Route::get('/assistant', 'AssistantAdminController@index')->name('admin.assistant');
					Route::post('/assistant/settings', 'AssistantAdminController@saveSettings')->name('admin.assistant.settings');
					Route::post('/assistant/kb', 'AssistantAdminController@kbSave')->name('admin.assistant.kb.save');
					Route::post('/assistant/kb/{id}/toggle', 'AssistantAdminController@kbToggle')->name('admin.assistant.kb.toggle');
					Route::post('/assistant/kb/{id}/delete', 'AssistantAdminController@kbDelete')->name('admin.assistant.kb.delete');

					// Newsletter (abonné·es du pied de page)
					Route::get('/newsletter', 'NewsletterAdminController@index')->name('admin.newsletter');
					Route::get('/newsletter/export', 'NewsletterAdminController@export')->name('admin.newsletter.export');
					Route::post('/newsletter/{id}/basculer', 'NewsletterAdminController@toggle')->name('admin.newsletter.toggle');
					Route::post('/newsletter/{id}/supprimer', 'NewsletterAdminController@destroy')->name('admin.newsletter.delete');
					Route::post('/newsletter/apercu', 'NewsletterAdminController@preview')->middleware('throttle:30,1')->name('admin.newsletter.preview');
					Route::post('/newsletter/test', 'NewsletterAdminController@sendTest')->middleware('throttle:6,1')->name('admin.newsletter.test');
					Route::post('/newsletter/campagnes', 'NewsletterAdminController@startCampaign')->middleware('throttle:6,1')->name('admin.newsletter.campaign');
					Route::post('/newsletter/campagnes/{id}/lot', 'NewsletterAdminController@sendBatch')->middleware('throttle:120,1')->name('admin.newsletter.batch');
					Route::post('/newsletter/campagnes/{id}/abandonner', 'NewsletterAdminController@abortCampaign')->name('admin.newsletter.abort');

					Route::get('reports/employeedetails/{id}', 'AdminReportController@employeedetails')->name('admin.reports.employeedetails');
					Route::post('reports/ratingticket/delete/{id}', 'AdminReportController@ratingticketdelete')->name('admin.reports.ratingticketdelete');

					Route::group(['prefix' => 'selfassigned'], function(){

						Route::get('/', 'AdminTicketViewController@selfassignticketview')->name('admin.selfassignticketview');
					});

					Route::group(['prefix' => 'myassignedtickets'], function()
					{
						Route::get('/', 'AdminDashboardController@myassignedTickets')->name('admin.myassignedticket');

					});


					Route::get('/myclosedtickets', 'AdminTicketViewController@myclosedtickets')->name('admin.myclosedtickets');
					Route::get('/mysuspendtickets', 'AdminTicketViewController@mysuspendtickets')->name('admin.mysuspendtickets');
					Route::get('/tickettrashed', 'AdminTicketViewController@tickettrashed')->name('admin.tickettrashed');
					Route::post('/tickettrashedrestore/{id}', 'AdminTicketViewController@tickettrashedrestore')->name('admin.tickettrashedrestore');
					Route::post('/tickettrasheddestroy/{id}', 'AdminTicketViewController@tickettrasheddestroy')->name('admin.tickettrasheddestroy');
					Route::get('/tickettrashedview/{id}', 'AdminTicketViewController@tickettrashedview')->name('admin.tickettrashedview');
					Route::post('/trashedticket/restore', 'AdminTicketViewController@alltrashedticketrestore')->name('admin.alltrashedticketrestore');
					Route::post('/trashedticket/delete', 'AdminTicketViewController@alltrashedticketdelete')->name('admin.alltrashedticketdelete');

					Route::post('/emaildomain', 'SecuritySettingController@emaildomainlist')->name('admin.emaildomainlist');


					Route::group(['prefix' => 'customfield', 'as'=> 'admin.customfield.'], function () {
						Route::get('/', 'CustomfieldController@index')->name('index');
						Route::post('/', 'CustomfieldController@storeupdate')->name('storeupdate');
						Route::get('/edit/{id}', 'CustomfieldController@edit')->name('edit');
						Route::post('/delete/{id}', 'CustomfieldController@destroy')->name('delete');
						Route::post('/deleteall', 'CustomfieldController@destroyall')->name('deleteall');
                        Route::post('/status/{id}', 'CustomfieldController@status')->name('status');

					});

					Route::get('/ticketarticle/{ticket}/{comment?}', 'ArticlesController@ticketarticle')->name('admin.article.ticket');


					Route::group(['prefix' => 'languages', 'as'=> 'admin.languages.'], function () {
						Route::get('/', 'LanguagesController@index')->name('index');
						Route::get('/create', 'LanguagesController@create')->name('create');
						Route::post('/store', 'LanguagesController@store')->name('store');
						Route::get('/translates/{code}', 'LanguagesController@translate')->name('translate');
						Route::post('{id}/default', 'LanguagesController@setDefault')->name('default');
						// not used
						Route::get('newkeyadding/{id}', 'LanguagesController@newkeyadding')->name('newkeyadding');
						Route::post('newkeyaddingstore/{id}', 'LanguagesController@newkeyaddingstore')->name('newkeyaddingstore');
						// not used
						Route::get('/edit/{id}', 'LanguagesController@edit')->name('edit');
						Route::post('/edit/{id}', 'LanguagesController@update')->name('edit.update');
						Route::post('/destroy/{id}', 'LanguagesController@destroy')->name('destroy');

						Route::post('{id}/update', 'LanguagesController@translateUpdate')->name('translates.update');
						Route::get('translate/{code}/{group}', 'LanguagesController@translate')->name('translate.group');
					});
					Route::post('clearcache','AdminDashboardController@clearcache')->name('admin.clearcache');

					Route::group(['prefix' => 'department', 'as' => 'department.'], function(){

						Route::get('/', 'DepartmentController@index')->name('index');
						Route::post('create', 'DepartmentController@create')->name('create');
						Route::get('edit/{id}', 'DepartmentController@edit')->name('edit');
						Route::delete('delete/{id}', 'DepartmentController@delete')->name('delete');
						Route::post('deleteall', 'DepartmentController@deleteall')->name('deleteall');
						Route::post('status/{id}', 'DepartmentController@status')->name('status');
					});
					Route::get('tickethistory/{id}', 'AdminTicketViewController@tickethistory')->name('admin.tickethistory');
					Route::get('customerprevioustickets/{cust_id}', 'AdminTicketViewController@customerprevioustickets')->name('admin.customer.tickethistory');

					Route::get('/customerimport', 'AdminprofileController@customerimportindex')->name('admin.customer.import');
					Route::post('/customerimport', 'AdminprofileController@customercsv')->name('admin.customer.csv');

				});

			});

		});
		Route::group(['namespace' => 'Admin', 'prefix'	 => 'administration'], function () {

			Route::get('/assigned/{id}', 'AdminAssignedticketsController@show')->name('assigning.ticket');
			Route::get('/ticket-view/ticketassigneds/{id}', 'AdminAssignedticketsController@show');
			Route::get('dashboardtabledata','AdminDashboardController@dashboardtabledata')->name('admin.dashboardtabledata');
			Route::post('notifystatus','AdminDashboardController@notifystatus')->name('admin.notifystatus');
			Route::post('notifysearch','AdminDashboardController@notifysearch')->name('admin.notifysearch');

			Route::get('faqcategory/list','FaqCategoryController@faqcategorylist')->name('faqsub.faqcategorylist');
			Route::get('faqcategory/edit/{id}','FaqCategoryController@edit')->name('faqsub.edit');
			Route::post('markallnotify','AdminDashboardController@markallnotify')->name('admin.notify.markallread');
			Route::post('emailonoff','AdminprofileController@emailonoff')->name('admin.emailonoff');

			Route::post('suspend', 'AdminTicketViewController@suspend')->name('admin.suspend');
			Route::post('customerprofiledelete', 'AdminSettingController@customerprofiledelete')->name('admin.customerprofiledelete');
		});


		Route::middleware(['countrylistbub', 'datarecovery', 'throttle:refresh', 'ipblockunblock'])->group(function () {


			Route::group(['namespace' => 'User', 'prefix' => 'espace-client'], function(){



				Route::group(['namespace' => 'Auth'], function(){

					Route::get('/connexion', 'LoginController@showLoginForm')->middleware('guest:customer')->name('auth.login');
					Route::post('/connexion', 'LoginController@login')->middleware('guest:customer')->name('client.do_login');
					Route::post('/connexion-ajax', 'LoginController@ajaxlogin')->middleware('guest:customer')->name('client.do_ajaxlogin');

					Route::post('/deconnexion', 'LoginController@logout')->middleware('auth:customer')->name('client.logout');

                    Route::get('/verification-email', 'LoginController@emailverification')->middleware('guest:customer')->name('user.emailverification');
                    Route::get('/verification-email/{email}', 'LoginController@emailverificationstore')->middleware('guest:customer')->name('user.emailverificationstore');

					// Social Auth
					Route::get('/connexion/{social}', 'LoginController@socialLogin')->name('social.login');
					Route::get('/connexion/{social}/retour','LoginController@handleProviderCallback')->name('social.login-callback');

					Route::get('/inscription', 'RegisterController@showRegistrationForm')->middleware('guest:customer')->name('register');
					Route::post('/inscription', 'RegisterController@register')->name('auth.register')->middleware('guest:customer');
					Route::post('/inscription-ajax', 'RegisterController@registers')->name('register1')->middleware('guest:customer');
					Route::get('/mot-de-passe-oublie', 'Passwords\ForgotpasswordController@forgot')->middleware('guest:customer');
					Route::post('/mot-de-passe-oublie', 'Passwords\ForgotpasswordController@Email')->middleware('guest:customer');
					Route::post('/mot-de-passe-oublie-ajax', 'Passwords\ForgotpasswordController@Emailajax')->name('ajax.forgot')->middleware('guest:customer');
					Route::post('/profil/mot-de-passe', 'ChangepasswordController@changepassword')->name('change.password');
					Route::get('/mot-de-passe/reinitialiser/{token}', 'Passwords\ResetpasswordController@resetpassword')->middleware('guest:customer')->name('reset.password');
					Route::post('/mot-de-passe/reinitialiser',  'Passwords\ResetpasswordController@updatePassword')->middleware('guest:customer');
					Route::get('/verification/{token}','RegisterController@verifyUser')->middleware('guest:customer')->name('verify.email');
				});

				Route::middleware('auth:customer','customer.auth')->group(function () {

					Route::get('notifications/{id}', 'DashboardController@Notificationview')->name('customer.notiication.view');
					Route::get('/notifications/marquer-lu', 'DashboardController@markNotification')->name('customer.markNotification');
					Route::get('/', 'DashboardController@userTickets')->name('client.dashboard');
					Route::get('/profil','Profile\UserprofileController@profile')->name('client.profile');
					Route::post('/profil','Profile\UserprofileController@profilesetup')->name('client.profilesetup');
					Route::post('/profil/supprimer/{id}','Profile\UserprofileController@profiledelete')->name('client.profiledelete');
					Route::delete('/profil/photo/{id}', 'Profile\UserprofileController@imageremove');
					Route::post('/preferences/reglages', 'Profile\UserprofileController@custsetting');
					Route::get('/alertes/nouvelle','Ticket\TicketController@create')->name('client.ticket');
					Route::get('/alertes/{etat}', 'GnClientController@alertes')->where('etat', 'en-attente|suivies|resolues')->name('gn.alertes');
					Route::post('/alertes/nouvelle','Ticket\TicketController@store')->name('client.ticketcreate');
					Route::post('/pieces-jointes','Ticket\TicketController@storeMedia')->name('imageupload');
					Route::get('/alertes/{ticket_id}','Ticket\TicketController@show')->name('loadmore.load_data');
					Route::post('/alertes/{ticket_id}/repondre','Ticket\CommentsController@postComment')->name('client.comment');
					Route::post('/alertes/{ticket_id}/pieces-jointes','Ticket\CommentsController@storeMedia')->name('client.ticket.image');
					Route::get('/alertes/supprimer/{id}','Ticket\TicketController@destroy')->name('client.ticket.delete');
					Route::post('/alertes/supprimer-plusieurs', 'Ticket\TicketController@ticketmassdestroy')->name('ticket.massremove');
					Route::post('/alertes/commentaire/{id}','Ticket\CommentsController@updateedit')->name('client.comment.edit');
					Route::redirect('/alertes-actives', '/espace-client/alertes/suivies')->name('activeticket');
										Route::redirect('/alertes-cloturees', '/espace-client/alertes/resolues')->name('closedticket');
					Route::redirect('/alertes-en-pause', '/espace-client/alertes/en-attente')->name('onholdticket');
					Route::post('/alertes/{ticket_id}/cloturer','Ticket\TicketController@close')->name('client.ticketclose');
					Route::delete('/alertes/piece-jointe/{id}','Ticket\CommentsController@imagedestroy')->name('client.imagedestroy');
					Route::post('categories/sous-categories','Ticket\TicketController@sublist')->name('subcat');
					Route::get('/alertes/{ticket_id}/evaluation', 'Ticket\TicketController@rating')->name('rating')->middleware('disablepreventback');
					Route::get('/alertes/{id}/evaluation/etoile-5', 'Ticket\TicketController@star5');
					Route::get('/alertes/{id}/evaluation/etoile-4', 'Ticket\TicketController@star4');
					Route::get('/alertes/{id}/evaluation/etoile-3', 'Ticket\TicketController@star3');
					Route::get('/alertes/{id}/evaluation/etoile-2', 'Ticket\TicketController@star2');
					Route::get('/alertes/{id}/evaluation/etoile-1', 'Ticket\TicketController@star1');
					Route::get('/preferences', 'GeneralSettingController@index')->name('client.general');
					Route::post('/preferences/notifications', 'GeneralSettingController@NotifyOn')->name('client.generalsetting');
					Route::get('/notifications', 'DashboardController@notify')->name('client.notification');

					Route::get('/notifications/tout-lire', function(){

						$notify = Auth::guard('customer')->user();
						$notify->unreadNotifications->markAsRead();

					})->name('cust.mark');

					Route::post('notifications/supprimer','DashboardController@notifydelete')->name('customer.notifydelete');
				});

				Route::get('alertes/{id}/pdf','Ticket\TicketController@pdfmake')->name('user.pdfmake');

			});

			Route::get('/', [HomeController::class, 'index'])->name('home');
			Route::get('language/{locale}', [HomeController::class, 'setLanguage'])->name('front.set_language');
			Route::get('/captchareload', [HomeController::class, 'captchareload'])->name('captcha.reload');
			Route::get('/article/{id}', [HomeController::class, 'knowledge'])->name('article');
			Route::get('/likes/{id}',[HomeController::class, 'like']);
			Route::get('/dislikes/{id}',[HomeController::class, 'dislike']);
			Route::post('likedislike',[HomeController::class, 'likedislike'])->name('likedislike');
			Route::get('/assistant', [AssistantController::class, 'page'])->name('assistant');
			Route::post('/newsletter', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->middleware('throttle:6,1')->name('newsletter.subscribe');
			Route::get('/newsletter/desinscription/{token}', [\App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->middleware('throttle:20,1')->name('newsletter.unsubscribe');
			Route::post('/assistant/ask', [AssistantController::class, 'ask'])->middleware('throttle:30,1')->name('assistant.ask');
			Route::get('/assistant/alert-config', [AssistantController::class, 'alertConfig'])->middleware('throttle:30,1')->name('assistant.alertconfig');
			Route::get('/faq', [HomeController::class, 'faqpage']);
			Route::get('/faq/{id}', [HomeController::class, 'faqcategorypage'])->name('faq.faqcategory');
			Route::get('/page/{pageslug}', [HomeController::class, 'frontshow']);

			Route::get('/knowledge', [ArticleCommentController::class, 'index'])->name('knowledge');
			Route::post('/comment{id}', [ArticleCommentController::class, 'store']);
			Route::post('/replies{id}', [ArticleReplyController::class, 'store']);

			Route::get('/contact-us', [ContactController::class, 'contact']);
			Route::post('/contact-us', [ContactController::class, 'saveContact']);

			Route::get('/category/{id}', [CategorypageController::class, 'index']);


			Route::prefix('guest')->group(function () {

				Route::get('openticket', [GuestticketController::class, 'index'])->name('guest.ticket');
				Route::post('openticket', [GuestticketController::class, 'gueststore']);
				Route::post('openticketnootp', [GuestticketController::class, 'gueststore1']);
				Route::post('storemedia', [GuestticketController::class, 'guestmedia'])->name('guest.imageupload');
				Route::get('ticketdetails/{id}', [GuestticketController::class, 'guestdetails'])->name('guest.gusetticket');
				Route::get('ticket/{ticket_id}', [GuestticketController::class, 'guestview'])->name('gusetticket');
				Route::post('closed/{ticket_id}',[GuestticketController::class, 'close'])->name('guesttickets.ticketclose');
				Route::post('ticket/{ticket_id}',[GuestticketController::class, 'postComment'])->name('guest.comment');
				Route::post('emailsvalidate', [GuestticketController::class, 'emailsvalidateguest'])->name('guest.emailsvalidate');
				Route::post('verifyotp', [GuestticketController::class, 'verifyotp'])->name('guest.verifyotp');
				Route::get('ticket-view/{id}', [GuestticketController::class, 'ticketview'])->name('guest.ticketview');
				Route::post('ticket-view', [GuestticketController::class, 'senddataverify'])->name('guest.senddataverify');
				Route::post('verifyguestotp', [GuestticketController::class, 'verifyguestotp'])->name('guest.verifyguestotp');
				Route::get('ticket-view/details/{ticket_id}', [GuestticketController::class, 'ticketdetailshow'])->name('guest.ticketdetailshow');

			});

			Route::post('envatoverify',[GuestticketController::class, 'envatoverify'])->name('guest.envatoverify');
			Route::delete('/image/delete/{id}', [GuestticketController::class, 'imagedestroy']);
			Route::get('/rating/{ticket_id}', [GuestticketController::class, 'rating'])->name('guest.rating')->middleware('disablepreventback');
			Route::get('/rating/star5/{id}', [GuestticketController::class, 'star5']);
			Route::get('/rating/star4/{id}', [GuestticketController::class, 'star4']);
			Route::get('/rating/star3/{id}', [GuestticketController::class, 'star3']);
			Route::get('/rating/star2/{id}', [GuestticketController::class, 'star2']);
			Route::get('/rating/star1/{id}', [GuestticketController::class, 'star1']);

			Route::post('/ticket/rating', [GuestticketController::class, 'ticketrating']);
			Route::post('/guestticket/editcomment/{id}', [CommentsController::class, 'updateedit']);
		});

		Route::post('espace-client/notifications/statut',[DashboardController::class, 'notifystatus'])->name('customer.notifystatus');
		Route::post('espace-client/notifications/recherche',[DashboardController::class, 'notifysearch'])->name('customer.notifysearch');

		Route::post('/guest/emailsvalidate', [GuestticketController::class, 'emailsvalidateguest'])->name('guest.emailsvalidate');
		Route::post('/guest/verifyotp', [GuestticketController::class, 'verifyotp'])->name('guest.verifyotp');
		Route::post('subcategorylist',[GuestticketController::class, 'subcategorylist'])->name('guest.subcategorylist');
		Route::post('/search',[HomeController::class, 'searchlist']);
		Route::post('/suggestarticle',[HomeController::class, 'suggestarticle']);
		Route::get('ipblock', [App\Http\Controllers\CaptchaipblockController::class, 'index'])->name('ipblock');
		Route::post('ipblock/update', [App\Http\Controllers\CaptchaipblockController::class, 'update'])->name('ipblock.update');
		Route::get('/captchasreload', [App\Http\Controllers\CaptchaipblockController::class, 'captchasreload'])->name('captchas.reload');
		Route::get('/apifailed', [App\Http\Controllers\ApiController::class, 'index'])->name('apifail.index');

		Route::get('notificationsreading', [GuestticketController::class, 'notificationsreading'])->name('notificationsreading');
		Route::get('badgecount', [GuestticketController::class, 'badgecount'])->name('badgecount');
		Route::get('markasreadcount', [GuestticketController::class, 'markasreadcount'])->name('markasreadcount');
		Route::get('notificationalerts', [GuestticketController::class, 'notificationalerts'])->name('update.notificationalerts');
		Route::post('notificationalerts', [GuestticketController::class, 'notificationalertsread'])->name('update.notificationalertsread');
		Route::get('espace-client/notifications/alertes', [GuestticketController::class, 'cnotificationalerts'])->name('customer.update.notificationalerts');
		Route::post('espace-client/notifications/alertes', [GuestticketController::class, 'cnotificationalertsread'])->name('customer.update.notificationalertsread');
		Route::get('espace-client/notifications/lecture', [GuestticketController::class, 'cnotificationsreading'])->name('customer.notificationsreading');
		Route::get('espace-client/notifications/compteur', [GuestticketController::class, 'cbadgecount'])->name('customer.badgecount');
		Route::get('espace-client/notifications/compteur-lu', [GuestticketController::class, 'cmarkasreadcount'])->name('customer.markasreadcount');
		Route::post('espace-client/notifications/tout-marquer-lu', [GuestticketController::class, 'markallnotify'])->name('customer.notify.markallread');



		Route::get('image/{id}/{imageurl}', 'ImageController@index')->name('imageurl');
		Route::get('imagedownload/{id}/{imageurl}', 'ImageController@imagedownload')->name('imagedownload');
		Route::get('emailtoticket/{id}/{imageurl}', 'ImageController@emailtoticketshow')->name('emailtoticketimageurl');
		Route::get('emailtoticketdownload/{id}/{imageurl}', 'ImageController@emailtoticketdownload')->name('emailtoticketdownload');
		Route::get('guestimage/{id}/{imageurl}', 'ImageController@guestimage')->name('guest.imageurl');
	});

	Route::view('timeupdate', 'admin.superadmindashboard.timeupdate')->name('timeupdate');

});

// SEO : robots.txt et sitemap.xml dynamiques (URLs derivees de config('app.url'))
Route::get('/offline', fn () => response()->view('offline'))->name('pwa.offline');
Route::get('/robots.txt', [\App\Http\Controllers\SeoController::class, 'robots'])->name('seo.robots');
Route::get('/sitemap.xml', [\App\Http\Controllers\SeoController::class, 'sitemap'])->name('seo.sitemap');
