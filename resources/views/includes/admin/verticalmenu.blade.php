<!--aside open-->
<aside class="app-sidebar">
    <div class="app-sidebar__logo">
        <a class="header-brand" href="{{url('admin')}}">
            {{--Logo--}}
            @if ($title->image == null)

            <img src="{{asset('uploads/logo/logo/logo-white.png')}}" class="header-brand-img dark-logo" alt="logo">
            @else

            <img src="{{asset('uploads/logo/logo/'.$title->image)}}" class="header-brand-img dark-logo" alt="logo">
            @endif

            {{--Dark-Logo--}}
            @if ($title->image1 == null)

            <img src="{{asset('uploads/logo/darklogo/logo.png')}}" class="header-brand-img desktop-lgo" alt="dark-logo">
            @else

            <img src="{{asset('uploads/logo/darklogo/'.$title->image1)}}" class="header-brand-img desktop-lgo"
                alt="dark-logo">
            @endif

            {{--Mobile-Logo--}}
            @if ($title->image2 == null)

            <img src="{{asset('uploads/logo/icon/icon.png')}}" class="header-brand-img mobile-logo" alt="mobile-logo">
            @else

            <img src="{{asset('uploads/logo/icon/'.$title->image2)}}" class="header-brand-img mobile-logo"
                alt="mobile-logo">
            @endif

            {{--Mobile-Dark-Logo--}}
            @if ($title->image3 == null)

            <img src="{{asset('uploads/logo/darkicon/icon-white.png')}}" class="header-brand-img darkmobile-logo"
                alt="mobile-dark-logo">
            @else

            <img src="{{asset('uploads/logo/darkicon/'.$title->image3)}}" class="header-brand-img darkmobile-logo"
                alt="mobile-dark-logo">
            @endif

        </a>
    </div>
    <div class="app-sidebar3">

        <ul class="side-menu custom-ul">

            <li class="slide">
                <a class="side-menu__item" href="{{url('admin/')}}">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#000000">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M19 5v2h-4V5h4M9 5v6H5V5h4m10 8v6h-4v-6h4M9 17v2H5v-2h4M21 3h-8v6h8V3zM11 3H3v10h8V3zm10 8h-8v10h8V11zm-10 4H3v6h8v-6z" />
                    </svg>
                    <span class="side-menu__label">{{lang('Tableau de Bord', 'Menu')}}</span>
                </a>
            </li>

           <!--- Employee menu tickets --->

           <li class="side-item side-item-category gn-menu-group">Alertes et cas VBG</li>
           @if(Auth::user()->dashboard == 'Admin')

           <li class="slide">
               <a class="side-menu__item" data-bs-toggle="slide" href="#">
                   <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M22 10V6c0-1.11-.9-2-2-2H4c-1.1 0-1.99.89-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-2-1.46c-1.19.69-2 1.99-2 3.46s.81 2.77 2 3.46V18H4v-2.54c1.19-.69 2-1.99 2-3.46 0-1.48-.8-2.77-1.99-3.46L4 6h16v2.54zM11 15h2v2h-2zm0-4h2v2h-2zm0-4h2v2h-2z"/></svg>
                   <span class="side-menu__label">{{lang('Alertes VBG', 'Menu')}}</span><i class="angle fa fa-angle-right"></i>
               </a>
               <ul class="slide-menu custom-ul">
                   <li><a href="{{route('admin.recenttickets')}}" class="slide-item">{{lang('Alertes VBG récentes', 'Menu')}}</a></li>
                   <li><a href="{{url('/admin/alltickets')}}" class="slide-item">{{lang('Total Alertes VBG', 'Menu')}}</a></li>
                   <li><a href="{{url('/admin/activeticket')}}" class="slide-item">{{lang('Alertes VBG suivies', 'Menu')}}</a></li>
                   <li><a href="{{url('/admin/closedticket')}}" class="slide-item">{{lang('Alertes VBG résolues', 'Menu')}}</a></li>
                   <li><a href="{{route('admin.onholdticket')}}" class="slide-item">{{lang('Alertes VBG en attente', 'Menu')}}</a></li>
                   <li><a href="{{route('admin.overdueticket')}}" class="slide-item">{{lang('Alerte VBG non résolues', 'Menu')}}</a></li>
                   <li><a href="{{route('admin.allassignedtickets')}}" class="slide-item">{{lang('Alertes VBG assignées', 'Menu')}}</a></li>
                   <li><a href="{{route('admin.suspendedtickets')}}" class="slide-item">{{lang('Alertes VBG suspendues', 'Menu')}}</a></li>


               </ul>
           </li>

           <li class="slide">
               <a class="side-menu__item" data-bs-toggle="slide" href="#">
                   <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M22 10V6c0-1.11-.9-2-2-2H4c-1.1 0-1.99.89-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-2-1.46c-1.19.69-2 1.99-2 3.46s.81 2.77 2 3.46V18H4v-2.54c1.19-.69 2-1.99 2-3.46 0-1.48-.8-2.77-1.99-3.46L4 6h16v2.54zM11 15h2v2h-2zm0-4h2v2h-2zm0-4h2v2h-2z"/></svg>
                   <span class="side-menu__label">{{lang('Cas VBG', 'Menu')}}</span><i class="angle fa fa-angle-right"></i>
               </a>
               <ul class="slide-menu custom-ul">
                   <li><a href="{{route('admin.selfassignticketview')}}" class="slide-item">{{lang('Cas VBG enregistrées', 'Menu')}}</a></li>
                   <li><a href="{{url('/admin/myassignedtickets')}}" class="slide-item">{{lang('Cas VBG assignées', 'Menu')}}</a></li>
                   <li><a href="{{route('admin.myclosedtickets')}}" class="slide-item">{{lang('Cas VBG résolues', 'Menu')}}</a></li>
                   <li><a href="{{route('admin.mysuspendtickets')}}" class="slide-item">{{lang('Cas VBG suspendues', 'Menu')}}</a></li>
               </ul>
           </li>
           @endif
           @if(Auth::user()->dashboard == 'Employee' || Auth::user()->dashboard == null)
           <li class="slide">
               <a class="side-menu__item" data-bs-toggle="slide" href="#">
                   <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M22 10V6c0-1.11-.9-2-2-2H4c-1.1 0-1.99.89-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-2-1.46c-1.19.69-2 1.99-2 3.46s.81 2.77 2 3.46V18H4v-2.54c1.19-.69 2-1.99 2-3.46 0-1.48-.8-2.77-1.99-3.46L4 6h16v2.54zM11 15h2v2h-2zm0-4h2v2h-2zm0-4h2v2h-2z"/></svg>
                   <span class="side-menu__label">{{lang('Tickets', 'Menu')}}</span><i class="angle fa fa-angle-right"></i>
               </a>
               <ul class="slide-menu custom-ul">

                   <li><a href="{{route('admin.recenttickets')}}" class="slide-item">{{lang('Alertes VBG récentes', 'Menu')}}</a></li>
                   <li><a href="{{url('/admin/activeticket')}}" class="slide-item">{{lang('Alertes VBG suivies', 'Menu')}}</a></li>
                   <li><a href="{{route('admin.selfassignticketview')}}" class="slide-item">{{lang('Cas VBG enregistrées', 'Menu')}}</a></li>
                   <li><a href="{{url('/admin/myassignedtickets')}}" class="slide-item">{{lang('Cas VBG assignées', 'Menu')}}</a></li>
                   <li><a href="{{route('admin.myclosedtickets')}}" class="slide-item">{{lang('Cas VBG résolues', 'Menu')}}</a></li>
                   <li><a href="{{route('admin.mysuspendtickets')}}" class="slide-item">{{lang('Cas VBG suspendues', 'Menu')}}</a></li>

               </ul>
           </li>


           @endif

           <!--- Employee menu tickets --->

           <!--
           @if(!empty(Auth::user()->getRoleNames()[0]) && Auth::user()->getRoleNames()[0] == 'superadmin')
           <li class="slide">
               <a class="side-menu__item"  href="{{route('admin.tickettrashed')}}">
                   <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="20" width="20"><path d="M6.5 17q-.625 0-1.062-.438Q5 16.125 5 15.5v-10H4V4h4V3h4v1h4v1.5h-1v10q0 .625-.438 1.062Q14.125 17 13.5 17Zm7-11.5h-7v10h7ZM8 14h1.5V7H8Zm2.5 0H12V7h-1.5Zm-4-8.5v10Z"/></svg>

                   <span class="side-menu__label">{{lang('Cas VBG supprimé')}}</span>
               </a>
           </li>
           @endif
             -->


            @canany(['Customers Access','Categories Access','Reports Access'])
            <li class="side-item side-item-category gn-menu-group">Victimes et classement</li>
            @endcanany
            @can('Customers Access')

            <li class="slide">
                <a class="side-menu__item" href="{{url('/admin/customer')}}">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#000000">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M9 13.75c-2.34 0-7 1.17-7 3.5V19h14v-1.75c0-2.33-4.66-3.5-7-3.5zM4.34 17c.84-.58 2.87-1.25 4.66-1.25s3.82.67 4.66 1.25H4.34zM9 12c1.93 0 3.5-1.57 3.5-3.5S10.93 5 9 5 5.5 6.57 5.5 8.5 7.07 12 9 12zm0-5c.83 0 1.5.67 1.5 1.5S9.83 10 9 10s-1.5-.67-1.5-1.5S8.17 7 9 7zm7.04 6.81c1.16.84 1.96 1.96 1.96 3.44V19h4v-1.75c0-2.02-3.5-3.17-5.96-3.44zM15 12c1.93 0 3.5-1.57 3.5-3.5S16.93 5 15 5c-.54 0-1.04.13-1.5.35.63.89 1 1.98 1 3.15s-.37 2.26-1 3.15c.46.22.96.35 1.5.35z" />
                    </svg>
                    <span class="side-menu__label">{{lang('Victimes')}}</span>
                </a>
            </li>
            @endcan

            @can('Categories Access')

            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="#">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#000000">
                        <path d="M0 0h24v24H0V0z" fill="none"></path>
                        <path
                            d="M12 2l-5.5 9h11L12 2zm0 3.84L13.93 9h-3.87L12 5.84zM17.5 13c-2.49 0-4.5 2.01-4.5 4.5s2.01 4.5 4.5 4.5 4.5-2.01 4.5-4.5-2.01-4.5-4.5-4.5zm0 7c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5zM3 21.5h8v-8H3v8zm2-6h4v4H5v-4z">
                        </path>
                    </svg>
                    <span class="side-menu__label">{{lang('Catégories', 'Menu')}}</span><i
                        class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu custom-ul">
                    @can('Category Access')

                    <li><a href="{{url('/admin/categories')}}" class="slide-item">{{lang('Main Categories',
                            'Menu')}}</a></li>
                    @endcan

                    @can('Subcategory Access')

                    <li><a href="{{url('/admin/subcategories')}}" class="slide-item">{{lang('Sous-catégorie', 'Menu')}}</a>
                    </li>
                    @endcan
                </ul>
            </li>

            @endcan

            @can('Reports Access')

            <li class="slide">
                <a class="side-menu__item" href="{{url('/admin/reports')}}">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M7 17h2v-5H7Zm8 0h2V7h-2Zm-4 0h2v-3h-2Zm0-5h2v-2h-2Zm-6 9q-.825 0-1.413-.587Q3 19.825 3 19V5q0-.825.587-1.413Q4.175 3 5 3h14q.825 0 1.413.587Q21 4.175 21 5v14q0 .825-.587 1.413Q19.825 21 19 21Zm0-2h14V5H5v14ZM5 5v14V5Z"/></svg>
                    <span class="side-menu__label">{{lang('Rapports')}}</span>
                </a>
            </li>
            @endcan


            @canany(['Knowledge Access','Project Access','Department Access'])
            <li class="side-item side-item-category gn-menu-group">Contenus et organisation</li>
            @endcanany
            @can('Knowledge Access')

            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="#">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                        height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                        <g>
                            <rect fill="none" height="24" width="24" />
                        </g>
                        <g>
                            <g />
                            <g>
                                <path
                                    d="M17,19.22H5V7h7V5H5C3.9,5,3,5.9,3,7v12c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2v-7h-2V19.22z" />
                                <path d="M19,2h-2v3h-3c0.01,0.01,0,2,0,2h3v2.99c0.01,0.01,2,0,2,0V7h3V5h-3V2z" />
                                <rect height="2" width="8" x="7" y="9" />
                                <polygon points="7,12 7,14 15,14 15,12 12,12" />
                                <rect height="2" width="8" x="7" y="15" />
                            </g>
                        </g>
                    </svg>
                    <span class="side-menu__label">{{lang('Actualités')}}</span><i
                        class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu custom-ul">

                    @can('Article Access')

                    <li><a href="{{url('/admin/article')}}" class="slide-item">{{lang('Articles', 'Menu')}}</a></li>
                    @endcan

                </ul>
            </li>
            @endcan
            @can('Project Access')

            <li class="slide">
                <a class="side-menu__item" href="{{url('/admin/projects')}}">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                        height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                        <g>
                            <rect fill="none" height="24" width="24" />
                            <g>
                                <path
                                    d="M19,5v14H5V5H19 M19,3H5C3.9,3,3,3.9,3,5v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V5C21,3.9,20.1,3,19,3L19,3z" />
                            </g>
                            <path d="M14,17H7v-2h7V17z M17,13H7v-2h10V13z M17,9H7V7h10V9z" />
                        </g>
                    </svg>
                    <span class="side-menu__label">{{lang('Projets')}}</span>
                </a>
            </li>
            @endcan

            @can('Department Access')

                <li class="slide">
                    <a class="side-menu__item"  href="{{route('department.index')}}">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="20" width="20"><path d="M3 18v-5h2.25V9.25h4V7H7V2h6v5h-2.25v2.25h4V13H17v5h-6v-5h2.25v-2.25h-6.5V13H9v5ZM8.5 5.5h3v-2h-3Zm-4 11h3v-2h-3Zm8 0h3v-2h-3ZM10 5.604ZM7.5 14.5Zm5 0Z"/></svg>
                        <span class="side-menu__label">{{lang('Département')}}</span>
                    </a>
                </li>

            @endcan

            @canany(['Managerole Access'])
            <li class="side-item side-item-category gn-menu-group">Équipe et accès</li>
            @endcanany
            @can('Managerole Access')

            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="#">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                        height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                        <g>
                            <path d="M0,0h24v24H0V0z" fill="none" />
                        </g>
                        <g>
                            <g>
                                <path
                                    d="M4,18v-0.65c0-0.34,0.16-0.66,0.41-0.81C6.1,15.53,8.03,15,10,15c0.03,0,0.05,0,0.08,0.01c0.1-0.7,0.3-1.37,0.59-1.98 C10.45,13.01,10.23,13,10,13c-2.42,0-4.68,0.67-6.61,1.82C2.51,15.34,2,16.32,2,17.35V20h9.26c-0.42-0.6-0.75-1.28-0.97-2H4z" />
                                <path
                                    d="M10,12c2.21,0,4-1.79,4-4s-1.79-4-4-4C7.79,4,6,5.79,6,8S7.79,12,10,12z M10,6c1.1,0,2,0.9,2,2s-0.9,2-2,2 c-1.1,0-2-0.9-2-2S8.9,6,10,6z" />
                                <path
                                    d="M20.75,16c0-0.22-0.03-0.42-0.06-0.63l1.14-1.01l-1-1.73l-1.45,0.49c-0.32-0.27-0.68-0.48-1.08-0.63L18,11h-2l-0.3,1.49 c-0.4,0.15-0.76,0.36-1.08,0.63l-1.45-0.49l-1,1.73l1.14,1.01c-0.03,0.21-0.06,0.41-0.06,0.63s0.03,0.42,0.06,0.63l-1.14,1.01 l1,1.73l1.45-0.49c0.32,0.27,0.68,0.48,1.08,0.63L16,21h2l0.3-1.49c0.4-0.15,0.76-0.36,1.08-0.63l1.45,0.49l1-1.73l-1.14-1.01 C20.72,16.42,20.75,16.22,20.75,16z M17,18c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S18.1,18,17,18z" />
                            </g>
                        </g>
                    </svg>
                    <span class="side-menu__label">{{lang('Gestion des rôles')}}</span><i
                        class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu custom-ul">
                    @can('Roles & Permission Access')

                    <li><a href="{{url('/admin/role')}}" class="slide-item">{{lang('Roles & Permissions', 'Menu')}}</a>
                    </li>
                    @endcan
                    @can('Roles & Permission Create')

                    <li><a href="{{url('/admin/employee/create')}}" class="slide-item">{{lang('Créer Prestataire')}}</a></li>
                    @endcan
                    @can('Employee Access')

                    <li><a href="{{url('/admin/employee')}}" class="slide-item">{{lang('Liste des Prestataires')}}</a>
                    </li>
                    @endcan

                </ul>
            </li>
            @endcan
            @canany(['Landing Page Access','Assistant Access','Newsletter Access','Groups Access','Custom Pages Access'])
            <li class="side-item side-item-category gn-menu-group">Site public et assistant</li>
            @endcanany
            @can('Landing Page Access')

            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="#">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#000000">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M3 17v2h6v-2H3zM3 5v2h10V5H3zm10 16v-2h8v-2h-8v-2h-2v6h2zM7 9v2H3v2h4v2h2V9H7zm14 4v-2H11v2h10zm-6-4h2V7h4V5h-4V3h-2v6z" />
                    </svg>
                    <span class="side-menu__label">{{lang('Landing Page Settings', 'Menu')}}</span><i
                        class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu custom-ul">
                    @can('Banner Access')

                    <li><a href="{{url('/admin/bannersetting')}}" class="slide-item">{{lang('Banner', 'Menu')}}</a></li>
                    @endcan
                    @can('Feature Box Access')

                    <li><a href="{{url('/admin/feature-box')}}" class="slide-item">{{lang('Feature Box', 'Menu')}}</a>
                    </li>
                    @endcan
                    @can('Call To Action Access')

                    <li><a href="{{url('/admin/call-to-action')}}" class="slide-item">{{lang('Call To Action',
                            'Menu')}}</a></li>
                    @endcan
                    @can('Testimonial Access')

                    <li><a href="{{url('/admin/testimonial')}}" class="slide-item">{{lang('Témoignage')}}</a>
                    </li>
                    @endcan
                    @can('Main FAQ Access')
                    <li class="sub-slide"> <a class="sub-side-menu__item" data-bs-toggle="sub-slide"
                            href="javascript:void(0);"><span class="sub-side-menu__label">{{lang('Main FAQ’s', 'Menu')}}</span><i
                                class="sub-angle fa fa-angle-right"></i></a>
                        <ul class="sub-slide-menu">
                            @can('FAQs Access')
                            <li><a class="sub-slide-item" href="{{url('/admin/faq')}}">{{lang('FAQ’s', 'Menu')}}</a></li>
                            @endcan
                            @can('FAQ Category Access')
                            <li><a class="sub-slide-item" href="{{route('faqsub.index')}}">{{lang('Faq Category', 'Menu')}}</a></li>
                            @endcan

                        </ul>
                    </li>
                    @endcan

                </ul>
            </li>
            @endcan

            @can('Assistant Access')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.assistant') }}">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#000000">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12z" />
                    </svg>
                    <span class="side-menu__label">Assistant</span>
                </a>
            </li>
            @endcan

            @can('Newsletter Access')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.newsletter') }}">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#000000">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z" />
                    </svg>
                    <span class="side-menu__label">Newsletter</span>
                </a>
            </li>
            @endcan

            @php $module = Module::all(); @endphp

            @if(in_array('Uhelpupdate', $module))
            @can('Canned Response Access')

            <li class="slide">
                <a class="side-menu__item" href="{{route('admin.cannedmessages')}}">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                        height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                        <g>
                            <rect fill="none" height="24" width="24" />
                        </g>
                        <g>
                            <g>
                                <polygon points="16.6,10.88 15.18,9.46 10.94,13.71 8.82,11.58 7.4,13 10.94,16.54" />
                                <path
                                    d="M19,4H5C3.89,4,3,4.9,3,6v12c0,1.1,0.89,2,2,2h14c1.1,0,2-0.9,2-2V6C21,4.9,20.11,4,19,4z M19,18H5V8h14V18z" />
                            </g>
                        </g>
                    </svg>
                    <span class="side-menu__label">{{lang('Canned Response', 'Menu')}}</span>
                </a>
            </li>
            @endcan
            @can('Envato Access')
            @if(setting('ENVATO_ON') == 'on')

            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="#">
                    <svg class="sidemenu_icon" style="enable-background:new 0 0 512 512; width: 18px; height: 18px;"
                        version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g id="_x38_5-envato">
                            <g>
                                <g>
                                    <g>
                                        <path
                                            d="M401.225,19.381c-17.059-8.406-103.613,1.196-166.01,61.218      c-98.304,98.418-95.947,228.089-95.947,228.089s-3.248,13.335-17.086-6.011c-30.305-38.727-14.438-127.817-12.651-140.23      c2.508-17.494-8.615-17.999-13.243-12.229c-109.514,152.46-10.616,277.288,54.136,316.912      c75.817,46.386,225.358,46.354,284.922-85.231C509.547,218.042,422.609,29.875,401.225,19.381L401.225,19.381z M401.225,19.381" />
                                    </g>
                                </g>
                            </g>
                        </g>
                        <g id="Layer_1" />
                    </svg>
                    <span class="side-menu__label">{{lang('Envato', 'Menu')}}</span><i
                        class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu custom-ul">

                    <li>
                        <a href="{{route('settings.envatosetting')}}" class="slide-item">{{lang('Envato Setting',
                            'Menu')}}</a>
                    </li>
                    @can('Envato API Token Access')

                    <li>
                        <a href="{{route('admin.envatoapitoken')}}" class="slide-item">{{lang('Envato API Token',
                            'Menu')}}</a>
                    </li>
                    @endcan
                    @can('Envato License Details Access')

                    <li>
                        <a href="{{route('admin.envatolicensesearch')}}" class="slide-item">{{lang('Envato License
                            Verification', 'Menu')}}</a>
                    </li>
                    @endcan

                </ul>
            </li>
            @endif
            @endcan

            @endif

            @can('Groups Access')

            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="#">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                        height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                        <rect fill="none" height="24" width="24" />
                        <g>
                            <path
                                d="M4,13c1.1,0,2-0.9,2-2c0-1.1-0.9-2-2-2s-2,0.9-2,2C2,12.1,2.9,13,4,13z M5.13,14.1C4.76,14.04,4.39,14,4,14 c-0.99,0-1.93,0.21-2.78,0.58C0.48,14.9,0,15.62,0,16.43V18l4.5,0v-1.61C4.5,15.56,4.73,14.78,5.13,14.1z M20,13c1.1,0,2-0.9,2-2 c0-1.1-0.9-2-2-2s-2,0.9-2,2C18,12.1,18.9,13,20,13z M24,16.43c0-0.81-0.48-1.53-1.22-1.85C21.93,14.21,20.99,14,20,14 c-0.39,0-0.76,0.04-1.13,0.1c0.4,0.68,0.63,1.46,0.63,2.29V18l4.5,0V16.43z M16.24,13.65c-1.17-0.52-2.61-0.9-4.24-0.9 c-1.63,0-3.07,0.39-4.24,0.9C6.68,14.13,6,15.21,6,16.39V18h12v-1.61C18,15.21,17.32,14.13,16.24,13.65z M8.07,16 c0.09-0.23,0.13-0.39,0.91-0.69c0.97-0.38,1.99-0.56,3.02-0.56s2.05,0.18,3.02,0.56c0.77,0.3,0.81,0.46,0.91,0.69H8.07z M12,8 c0.55,0,1,0.45,1,1s-0.45,1-1,1s-1-0.45-1-1S11.45,8,12,8 M12,6c-1.66,0-3,1.34-3,3c0,1.66,1.34,3,3,3s3-1.34,3-3 C15,7.34,13.66,6,12,6L12,6z" />
                        </g>
                    </svg>
                    <span class="side-menu__label">{{lang('Groupes')}}</span><i
                        class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu custom-ul">
                    @can('Groups Create')

                    <li><a href="{{url('/admin/groups/create')}}" class="slide-item">{{lang('Creer Groupe')}}</a></li>
                    @endcan
                    @can('Groups List Access')

                    <li><a href="{{url('/admin/groups')}}" class="slide-item">{{lang('Liste des Groupes')}}</a></li>
                    @endcan

                </ul>
            </li>
            @endcan

            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="#">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#000000">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z" />
                    </svg>
                    <span class="side-menu__label">{{lang('Notifications', 'Menu')}}</span><i
                        class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu custom-ul">
                    <li><a href="{{route('notificationpage')}}" class="slide-item smark-all">{{lang('All Notifications',
                            'Menu')}}</a></li>

                    @can('Custom Notifications Access')

                    <li><a href="{{route('mail.index')}}" class="slide-item">{{lang('Custom Notifications',
                            'Menu')}}</a></li>
                    @endcan

                </ul>
            </li>
            @can('Custom Pages Access')

            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="#">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#000000">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M11.99 18.54l-7.37-5.73L3 14.07l9 7 9-7-1.63-1.27zM12 16l7.36-5.73L21 9l-9-7-9 7 1.63 1.27L12 16zm0-11.47L17.74 9 12 13.47 6.26 9 12 4.53z" />
                    </svg>
                    <span class="side-menu__label">{{lang('Custom Pages', 'Menu')}}</span><i
                        class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu custom-ul">
                    @can('Pages Access')

                    <li><a href="{{url('/admin/pages')}}" class="slide-item">{{lang('Pages', 'Menu')}}</a></li>
                    @endcan
                    @can('404 Error Page Access')

                    <li><a href="{{url('/admin/error404')}}" class="slide-item">{{lang('404 Error Page', 'Menu')}}</a>
                    </li>
                    @endcan
                    @can('Under Maintanance Page Access')

                    <li><a href="{{url('/admin/maintenancepage')}}" class="slide-item">{{lang('Under Maintenance Page',
                            'Menu')}}</a></li>
                    @endcan

                </ul>
            </li>
            @endcan

            @can('Bussiness Hours Access')
            <li class="slide">
                <a class="side-menu__item"  href="{{route('admin.bussinesshour.index')}}">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                    <span class="side-menu__label">{{lang('Bussiness Hours', 'Menu')}}</span>
                </a>
            </li>
            @endcan
            @can('CustomField Access')
            <li class="slide">
                <a class="side-menu__item"  href="{{route('admin.customfield.index')}}">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="20" width="20"><path d="M3 11.75v-1.5h6v1.5Zm0-3.125v-1.5h9v1.5ZM3 5.5V4h9v1.5ZM13.25 15v-3.25H10v-1.5h3.25V7h1.5v3.25H18v1.5h-3.25V15Z"/></svg>
                    <span class="side-menu__label">{{lang('Formulaire')}}</span>
                </a>
            </li>
            @endcan
            @can('Languages Access')
            <li class="slide">
                <a class="side-menu__item"  href="{{route('admin.languages.index')}}">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="20" width="20"><path d="m9.917 18.333 3.791-10.021h1.73l3.812 10.021H17.5l-.875-2.562h-4.063l-.895 2.562Zm3.166-4.021h2.979l-1.458-4.104h-.062Zm-9.729 1.521-1.187-1.187 4.208-4.208q-.792-.876-1.385-1.813-.594-.937-1.011-1.979h1.729q.396.75.813 1.354.417.604 1.021 1.271.937-1 1.531-2.052.594-1.052.989-2.24H.833V3.312h5.834V1.646h1.666v1.666h5.834v1.667h-2.438q-.417 1.479-1.156 2.875-.74 1.396-1.844 2.625l1.979 2.042-.625 1.708-2.562-2.562Z"/></svg>
                    <span class="side-menu__label">{{lang('Langues')}}</span>
                </a>
            </li>
            @endcan

            @canany(['App Setting Access','Announcements Access','Email Template Access'])
            <li class="side-item side-item-category gn-menu-group">Réglages</li>
            @endcanany
            @can('App Setting Access')

            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="#">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#000000">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M19.43 12.98c.04-.32.07-.64.07-.98 0-.34-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.09-.16-.26-.25-.44-.25-.06 0-.12.01-.17.03l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.06-.02-.12-.03-.18-.03-.17 0-.34.09-.43.25l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98 0 .33.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.09.16.26.25.44.25.06 0 .12-.01.17-.03l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.06.02.12.03.18.03.17 0 .34-.09.43-.25l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zm-1.98-1.71c.04.31.05.52.05.73 0 .21-.02.43-.05.73l-.14 1.13.89.7 1.08.84-.7 1.21-1.27-.51-1.04-.42-.9.68c-.43.32-.84.56-1.25.73l-1.06.43-.16 1.13-.2 1.35h-1.4l-.19-1.35-.16-1.13-1.06-.43c-.43-.18-.83-.41-1.23-.71l-.91-.7-1.06.43-1.27.51-.7-1.21 1.08-.84.89-.7-.14-1.13c-.03-.31-.05-.54-.05-.74s.02-.43.05-.73l.14-1.13-.89-.7-1.08-.84.7-1.21 1.27.51 1.04.42.9-.68c.43-.32.84-.56 1.25-.73l1.06-.43.16-1.13.2-1.35h1.39l.19 1.35.16 1.13 1.06.43c.43.18.83.41 1.23.71l.91.7 1.06-.43 1.27-.51.7 1.21-1.07.85-.89.7.14 1.13zM12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 6c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z" />
                    </svg>
                    <span class="side-menu__label">{{lang('App Setting', 'Menu')}}</span><i
                        class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu custom-ul">
                    @can('General Setting Access')

                    <li><a href="{{url('/admin/general')}}" class="slide-item">{{lang('General Setting', 'Menu')}}</a>
                    </li>
                    @endcan
                    @can('Ticket Setting Access')

                    <li><a href="{{url('/admin/ticketsetting')}}" class="slide-item">{{lang('Ticket Setting',
                            'Menu')}}</a></li>
                    @endcan
                    @can('SEO Access')

                    <li><a href="{{url('/admin/seo')}}" class="slide-item">{{lang('SEO', 'Menu')}}</a></li>
                    @endcan
                    @can('Google Analytics Access')

                    <li><a href="{{url('/admin/googleanalytics')}}" class="slide-item">{{lang('Google Analytics',
                            'Menu')}}</a></li>
                    @endcan
                    @can('Custom JS & CSS Access')

                    <li><a href="{{url('/admin/customcssjssetting')}}" class="slide-item">{{lang('Custom CSS & JS',
                            'Menu')}}</a></li>
                    @endcan
                    @can('Captcha Setting Access')

                    <li><a href="{{url('/admin/captcha')}}" class="slide-item">{{lang('Captcha Setting', 'Menu')}}</a>
                    </li>
                    @endcan
                    @can('Social Logins Access')

                    <li><a href="{{url('/admin/sociallogin')}}" class="slide-item">{{lang('Social Logins', 'Menu')}}</a>
                    </li>
                    @endcan
                    @can('Email Setting Access')

                    <li><a href="{{url('/admin/emailsetting')}}" class="slide-item">{{lang('Email Setting',
                            'Menu')}}</a></li>
                    @endcan
                    @can('Custom Chat Access')

                    <li><a href="{{url('/admin/customchatsetting')}}" class="slide-item">{{lang('External Chat',
                            'Menu')}}</a></li>
                    @endcan
                    @can('SecruitySetting Access')

                    <li><a href="{{url('/admin/securitysetting')}}" class="slide-item">{{lang('Security Setting',
                            'Menu')}}</a></li>
                    @endcan
                    @can('IpBlock Access')

                    <li><a href="{{route('ipblocklist')}}" class="slide-item">{{lang('IP List', 'Menu')}}</a></li>
                    @endcan
                    @can('Emailtoticket Access')

                    <li><a href="{{route('admin.emailtoticket')}}" class="slide-item">{{lang('Email to Tickets',
                            'Menu')}}</a></li>
                    @endcan

                </ul>
            </li>
            @endcan
            @can('Announcements Access')

            <li class="slide">
                <a class="side-menu__item" href="{{url('/admin/announcement')}}">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                        height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                        <g>
                            <rect fill="none" height="24" width="24" />
                        </g>
                        <path d="M18,11c0,0.67,0,1.33,0,2c1.2,0,2.76,0,4,0c0-0.67,0-1.33,0-2C20.76,11,19.2,11,18,11z" />
                        <path
                            d="M16,17.61c0.96,0.71,2.21,1.65,3.2,2.39c0.4-0.53,0.8-1.07,1.2-1.6c-0.99-0.74-2.24-1.68-3.2-2.4 C16.8,16.54,16.4,17.08,16,17.61z" />
                        <path
                            d="M20.4,5.6C20,5.07,19.6,4.53,19.2,4c-0.99,0.74-2.24,1.68-3.2,2.4c0.4,0.53,0.8,1.07,1.2,1.6 C18.16,7.28,19.41,6.35,20.4,5.6z" />
                        <path
                            d="M4,9c-1.1,0-2,0.9-2,2v2c0,1.1,0.9,2,2,2h1v4h2v-4h1l5,3V6L8,9H4z M9.03,10.71L11,9.53v4.94l-1.97-1.18L8.55,13H8H4v-2h4 h0.55L9.03,10.71z" />
                        <path d="M15.5,12c0-1.33-0.58-2.53-1.5-3.35v6.69C14.92,14.53,15.5,13.33,15.5,12z" />
                    </svg>
                    <span class="side-menu__label">{{lang('Announcements', 'Menu')}}</span>
                </a>
            </li>
            @endcan
            @can('Email Template Access')

            <li class="slide">
                <a class="side-menu__item" href="{{url('/admin/emailtemplates')}}">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#000000">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0l-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z" />
                    </svg>
                    <span class="side-menu__label">{{lang('Email Templates', 'Menu')}}</span>
                </a>
            </li>
            @endcan

            <li class="slide">
                <a class="side-menu__item sprukoclearcache" href="javascript:void(0);">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M11 11h2V4q0-.425-.287-.713Q12.425 3 12 3t-.712.287Q11 3.575 11 4Zm-6 4h14v-2H5Zm-1.45 6H6v-2q0-.425.287-.712Q6.575 18 7 18t.713.288Q8 18.575 8 19v2h3v-2q0-.425.288-.712Q11.575 18 12 18t.713.288Q13 18.575 13 19v2h3v-2q0-.425.288-.712Q16.575 18 17 18t.712.288Q18 18.575 18 19v2h2.45l-1-4H4.55l-1 4Zm16.9 2H3.55q-.975 0-1.575-.775t-.35-1.725L3 15v-2q0-.825.587-1.413Q4.175 11 5 11h4V4q0-1.25.875-2.125T12 1q1.25 0 2.125.875T15 4v7h4q.825 0 1.413.587Q21 12.175 21 13v2l1.375 5.5q.325.95-.287 1.725-.613.775-1.638.775ZM19 13H5h14Zm-6-2h-2 2Z"/></svg>
                    <span class="side-menu__label">{{lang('Clear Cache', 'Menu')}}</span>
                </a>
            </li>

            
        </ul>

    </div>
</aside>
<!--aside closed-->
