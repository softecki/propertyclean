@php
    $admin_logo=getSettingsValByName('company_logo');
    $ids     = parentId();
    $authUser=\App\Models\User::find($ids);
 $subscription = \App\Models\Subscription::find($authUser->subscription);
 $routeName=\Request::route()->getName();
@endphp
<aside class="codex-sidebar sidebar-{{$settings['sidebar_mode']}}">
    <div class="logo-gridwrap">
        {{-- <a class="codexbrand-logo" href="{{route('home')}}">
            <img class="img-fluid"
                 src="{{asset(Storage::url('upload/logo/')).'/'.(isset($admin_logo) && !empty($admin_logo)?$admin_logo:'logo.png')}}"
                 alt="theeme-logo">
        </a>
        <a class="codex-darklogo" href="{{route('home')}}">
            <img class="img-fluid"
                 src="{{asset(Storage::url('upload/logo/')).'/'.(isset($admin_logo) && !empty($admin_logo)?$admin_logo:'logo.png')}}"
                 alt="theeme-logo"></a> --}}
        <div class="sidebar-action"><i data-feather="menu"></i></div>
    </div>
    <div class="icon-logo">
        <a href="{{route('home')}}">
            <img class="img-fluid"
                 src="{{asset(Storage::url('upload/logo')).'/'.$settings['company_favicon']}}"
                 alt="theeme-logo">
        </a>
    </div>
    <div class="codex-menuwrapper">
        <ul class="codex-menu custom-scroll" data-simplebar>
            <li class="cdxmenu-title">
                <h5>{{__('Home')}}</h5>
            </li>
            <li class="menu-item {{in_array($routeName,['dashboard','home',''])?'active':''}}">
                <a href="{{route('dashboard')}}">
                    <div class="icon-item"><i data-feather="home"></i></div>
                    <span>{{__('Dashboard')}}</span>
                </a>
            </li>

            @if(\Auth::user()->type=='super admin')
                @if(Gate::check('manage user'))
                    <li class="menu-item {{in_array($routeName,['users.index'])?'active':''}}">
                        <a href="{{route('users.index')}}">
                            <div class="icon-item"><i data-feather="users"></i></div>
                            <span>{{__('Users')}}</span>
                        </a>
                    </li>
                @endif
            @else
                @if(Gate::check('manage user') || Gate::check('manage role') || Gate::check('manage logged history') )
                    <li class="menu-item {{in_array($routeName,['users.index','logged.history','role.index','role.create','role.edit'])?'active':''}}">
                        <a href="javascript:void(0);">
                            <div class="icon-item"><i data-feather="users"></i></div>
                            <span>{{__('Staff Management')}}</span><i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="submenu-list"
                            style="display: {{in_array($routeName,['users.index','logged.history','role.index','role.create','role.edit'])?'block':'none'}}">
                            @if(Gate::check('manage user'))
                                <li class="{{in_array($routeName,['users.index'])?'active':''}}">
                                    <a href="{{route('users.index')}}">{{__('Users')}}</a>
                                </li>
                            @endif
                            @if(Gate::check('manage role'))
                                <li class=" {{in_array($routeName,['role.index','role.create','role.edit'])?'active':''}}">
                                    <a href="{{route('role.index')}}">
                                        {{__('Roles')}}
                                    </a>
                                </li>
                            @endif
                            @if(Gate::check('manage logged history') && $subscription->enabled_logged_history==1)
                                <li class="{{in_array($routeName,['logged.history'])?'active':''}}">
                                    <a href="{{route('logged.history')}}">{{__('Logged History')}}</a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif
            @endif

            @if(Gate::check('manage property') || Gate::check('manage unit')|| Gate::check('manage tenant') || Gate::check('manage invoice') || Gate::check('manage expense') || Gate::check('manage maintainer') || Gate::check('manage maintenance request') || Gate::check('manage contact') || Gate::check('manage support') || Gate::check('manage note'))
                <li class="cdxmenu-title">
                    <h5>{{__('Business Management')}}</h5>
                </li>
                @if(Gate::check('manage tenant') || Gate::check('manage property') || Gate::check('manage unit'))
                <li class="menu-item {{in_array($routeName,['property.index','property.create','property.edit','property.show','unit.index'])?'active':''}}">
                    <a href="javascript:void(0);">
                        <div class="icon-item"><i data-feather="home"></i></div>
                        <span>{{__('TFA Properties')}}</span><i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="submenu-list"  style="display: {{in_array($routeName,['property.index','property.create','property.edit','property.show','unit.index'])?'block':'none'}}">

                        @if(Gate::check('manage property'))
                            <li class=" {{in_array($routeName,['property.index','property.create','property.edit','property.show'])?'active':''}} ">
                                <a href="{{route('property.index')}}">  {{__('Properties')}} </a>
                            </li>
                        @endif
                        @if(Gate::check('manage unit'))
                            <li class=" {{in_array($routeName,['unit.index'])?'active':''}} ">
                                <a href="{{route('unit.index')}}">  {{__('Spaces')}} </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif
                @if(Gate::check('manage tenant'))
                    <li class="menu-item {{in_array($routeName,['tenant.index','tenant.create','tenant.edit','tenant.show'])?'active':''}}">
                        <a href="{{route('tenant.index')}}">
                            <div class="icon-item"><i data-feather="user"></i></div>
                            <span>{{__('Tenants')}} </span>
                        </a>
                    </li>
                @endif
                @if(Gate::check('manage tenant'))
                <li class="menu-item {{in_array($routeName,['tenantapplication.index','tenantapplication.create','tenantapplication.edit','tenantapplication.show'])?'active':''}}">
                    <a href="{{route('tenantapplication.index')}}">
                        <div class="icon-item"><i data-feather="user"></i></div>
                        <span>{{__('Tenants Applications')}} </span>
                    </a>
                </li>
            @endif
                @if(Gate::check('manage contract'))
                <li class="menu-item {{in_array($routeName,['contract.index','contract.create','contract.edit','contract.show'])?'active':''}}">
                    <a href="{{route('contract.index')}}">
                        <div class="icon-item"><i data-feather="info"></i></div>
                        <span>{{__('Contracts')}} </span>
                    </a>
                </li>
            @endif
                @if(Gate::check('manage maintainer'))
                    <li class="menu-item {{in_array($routeName,['maintainer.index'])?'active':''}} ">
                        <a href="{{route('maintainer.index')}}">
                            <div class="icon-item"><i data-feather="user-check"></i></div>
                            <span>{{__('Maintainers')}} </span>
                        </a>
                    </li>
                @endif

                @if(Gate::check('manage maintainer') || Gate::check('manage maintenance request') )
                    <li class="menu-item {{in_array($routeName,['maintenance-request.index','maintenance-request.pending','maintenance-request.inprogress'])?'active':''}}">
                        <a href="javascript:void(0);">
                            <div class="icon-item"><i data-feather="tool"></i></div>
                            <span>{{__('Maintenance')}}</span><i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="submenu-list"
                            style="display: {{in_array($routeName,['maintenance-request.index','maintenance-request.pending','maintenance-request.inprogress'])?'block':'none'}}">

                            @if(Gate::check('manage maintenance request'))
                                <li class=" {{in_array($routeName,['maintenance-request.index'])?'active':''}}">
                                    <a href="{{route('maintenance-request.index')}}">{{__('All Requests')}} </a>
                                </li>
                            @endif
                            @if(Gate::check('manage maintenance request'))
                                <li class=" {{in_array($routeName,['maintenance-request.pending'])?'active':''}}">
                                    <a href="{{route('maintenance-request.pending')}}">{{__('Pending')}} </a>
                                </li>
                            @endif
                            @if(Gate::check('manage maintenance request'))
                                <li class=" {{in_array($routeName,['maintenance-request.inprogress'])?'active':''}}">
                                    <a href="{{route('maintenance-request.inprogress')}}">{{__('In Progress')}} </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif

                @if(Gate::check('manage invoice') || Gate::check('manage expense') )
                    <li class="menu-item {{in_array($routeName,['invoice.index','invoice.create','invoice.edit','invoice.show','expense.index','transactions.index'])?'active':''}}">
                        <a href="javascript:void(0);">
                            <div class="icon-item"><i data-feather="file-text"></i></div>
                            <span>{{__('Finance')}}</span><i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="submenu-list"
                            style="display: {{in_array($routeName,['invoice.index','invoice.create','invoice.edit','invoice.show','expense.index'])?'block':'none'}}">
                            @if(Gate::check('manage invoice'))
                                <li class=" {{in_array($routeName,['invoice.index','invoice.create','invoice.edit','invoice.show'])?'active':''}}">
                                    <a href="{{route('invoice.index')}}">  {{__('Invoices')}} </a>
                                </li>
                            @endif
                            @if(Gate::check('manage expense'))
                                <li class=" {{in_array($routeName,['expense.index'])?'active':''}}">
                                    <a href="{{route('expense.index')}}"> {{__('Expense/Bills')}}  </a>
                                </li>
                            @endif

                            @if(Gate::check('manage expense'))
                            <li class=" {{in_array($routeName,['transactions.index'])?'active':''}}">
                                <a href="{{route('transactions.index')}}"> {{__('Transactions')}}  </a>
                            </li>
                        @endif

                        </ul>
                    </li>
                @endif

                @if(Gate::check('manage contact'))
                <li class="menu-item {{in_array($routeName,['asset.index'])?'active':''}}">
                    <a href="{{route('asset.index')}}">
                        <div class="icon-item"><i data-feather="phone-call"></i></div>
                        <span>{{__('Assets Managements')}}</span>
                    </a>
                </li>
            @endif
                @if(Gate::check('manage invoice') || Gate::check('manage expense') )
                <li class="menu-item {{in_array($routeName,['report.index','report.create','report.edit','report.show'])?'active':''}}">
                    <a href="javascript:void(0);">
                        <div class="icon-item"><i data-feather="file-text"></i></div>
                        <span>{{__('General Report')}}</span><i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="submenu-list"
                        style="display: {{in_array($routeName,['report.index','report.create','report.edit','report.show'])?'block':'none'}}">
                        @if(Gate::check('manage invoice'))
                            <li class=" {{in_array($routeName,['report.index','report.create','report.edit','report.show'])?'active':''}}">
                                <a href="{{route('report.index')}}">  {{__('Tenant Report')}} </a>
                            </li>
                        @endif
                        @if(Gate::check('manage expense'))
                            <li class=" {{in_array($routeName,['report.index'])?'active':''}}">
                                <a href="{{route('report.index')}}"> {{__('Meintanance Report')}}  </a>
                            </li>
                        @endif

                        @if(Gate::check('manage expense'))
                        <li class=" {{in_array($routeName,['report.index'])?'active':''}}">
                            <a href="{{route('report.index')}}"> {{__('Property Report')}}  </a>
                        </li>
                    @endif

                    </ul>
                </li>
            @endif

                @if(Gate::check('manage contact'))
                    <li class="menu-item {{in_array($routeName,['contact.index'])?'active':''}}">
                        <a href="{{route('contact.index')}}">
                            <div class="icon-item"><i data-feather="phone-call"></i></div>
                            <span>{{__('Contacts')}}</span>
                        </a>
                    </li>
                @endif

                @if(Gate::check('manage note'))
                    <li class="menu-item {{in_array($routeName,['note.index'])?'active':''}} ">
                        <a href="{{route('note.index')}}">
                            <div class="icon-item"><i data-feather="file-text"></i></div>
                            <span>{{__('Note')}}</span>
                        </a>
                    </li>
                @endif

                @if(Gate::check('manage note'))
                <li class="menu-item {{in_array($routeName,['feedback.index'])?'active':''}} ">
                    <a href="{{route('feedback.index')}}">
                        <div class="icon-item"><i data-feather="file-text"></i></div>
                        <span>{{__('Feedback')}}</span>
                    </a>
                </li>
            @endif
            @endif

            @if(\Auth::check())
                <li class="cdxmenu-title">
                    <h5>{{__('Phase Modules')}}</h5>
                </li>
                <li class="menu-item {{in_array($routeName,['phase.dashboard'])?'active':''}}">
                    <a href="{{route('phase.dashboard')}}">
                        <div class="icon-item"><i data-feather="grid"></i></div>
                        <span>{{__('Phase Dashboard')}}</span>
                    </a>
                </li>
                <li class="menu-item {{in_array($routeName,['phase.land'])?'active':''}}">
                    <a href="{{route('phase.land')}}">
                        <div class="icon-item"><i data-feather="map"></i></div>
                        <span>{{__('Land Master')}}</span>
                    </a>
                </li>
                <li class="menu-item {{in_array($routeName,['phase.parties'])?'active':''}}">
                    <a href="{{route('phase.parties')}}">
                        <div class="icon-item"><i data-feather="users"></i></div>
                        <span>{{__('Parties (Buyer/Seller/Agent)')}}</span>
                    </a>
                </li>
                <li class="menu-item {{in_array($routeName,['phase.sales'])?'active':''}}">
                    <a href="{{route('phase.sales')}}">
                        <div class="icon-item"><i data-feather="shopping-cart"></i></div>
                        <span>{{__('Sales & Collections')}}</span>
                    </a>
                </li>
                <li class="menu-item {{in_array($routeName,['phase.finance'])?'active':''}}">
                    <a href="{{route('phase.finance')}}">
                        <div class="icon-item"><i data-feather="dollar-sign"></i></div>
                        <span>{{__('Finance & Accrual')}}</span>
                    </a>
                </li>
                <li class="menu-item {{in_array($routeName,['phase.operations'])?'active':''}}">
                    <a href="{{route('phase.operations')}}">
                        <div class="icon-item"><i data-feather="tool"></i></div>
                        <span>{{__('Operations & Assets')}}</span>
                    </a>
                </li>
                <li class="menu-item {{in_array($routeName,['phase.communications','phase.threads.show'])?'active':''}}">
                    <a href="{{route('phase.communications')}}">
                        <div class="icon-item"><i data-feather="mail"></i></div>
                        <span>{{__('Portal & Communications')}}</span>
                    </a>
                </li>
                <li class="menu-item {{in_array($routeName,['phase.reports'])?'active':''}}">
                    <a href="{{route('phase.reports')}}">
                        <div class="icon-item"><i data-feather="bar-chart-2"></i></div>
                        <span>{{__('Phase Reports')}}</span>
                    </a>
                </li>
            @endif

            @if(Gate::check('manage types'))
                <li class="cdxmenu-title">
                    <h5>{{__('Setup')}}</h5>
                </li>
                @if(Gate::check('manage types'))
                    <li class="menu-item {{in_array($routeName,['type.index'])?'active':''}}">
                        <a href="{{route('type.index')}}">
                            <div class="icon-item"><i data-feather="file-text"></i></div>
                            <span>{{__('Types')}}</span>
                        </a>
                    </li>
                @endif
            @endif
            @if(Gate::check('manage pricing packages') || Gate::check('manage pricing transation') || Gate::check('manage account settings') || Gate::check('manage password settings') || Gate::check('manage general settings') || Gate::check('manage company settings'))
                <li class="cdxmenu-title">
                    <h5>{{__('System Settings')}}</h5>
                </li>
                @if(Gate::check('manage pricing packages') || Gate::check('manage pricing transation'))
                    <li class="menu-item {{in_array($routeName,['subscriptions.index','subscriptions.show','subscription.transaction'])?'active':''}}">
                        <a href="javascript:void(0);">
                            <div class="icon-item"><i data-feather="database"></i></div>
                            <span>{{__('Pricing')}}</span><i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="submenu-list"
                            style="display: {{in_array($routeName,['subscriptions.index','subscriptions.show','subscription.transaction'])?'block':'none'}}">
                            @if(Gate::check('manage pricing packages'))
                                <li class="{{in_array($routeName,['subscriptions.index','subscriptions.show'])?'active':''}}">
                                    <a href="{{route('subscriptions.index')}}">{{__('Packages')}}</a>
                                </li>
                            @endif
                            @if(Gate::check('manage pricing transation'))
                                <li class="{{in_array($routeName,['subscription.transaction'])?'active':''}} ">
                                    <a href="{{route('subscription.transaction')}}">{{__('Transactions')}}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(Gate::check('manage coupon') || Gate::check('manage coupon history'))
                    <li class="menu-item {{in_array($routeName,['coupons.index','coupons.history'])?'active':''}}">
                        <a href="javascript:void(0);">
                            <div class="icon-item"><i data-feather="gift"></i></div>
                            <span>{{__('Coupons')}}</span><i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="submenu-list"
                            style="display: {{in_array($routeName,['coupons.index','coupons.history'])?'block':'none'}}">
                            @if(Gate::check('manage coupon'))
                                <li class="{{in_array($routeName,['coupons.index'])?'active':''}}">
                                    <a href="{{route('coupons.index')}}">{{__('All Coupon')}}</a>
                                </li>
                            @endif
                            @if(Gate::check('manage coupon history'))
                                <li class="{{in_array($routeName,['coupons.history'])?'active':''}}">
                                    <a href="{{route('coupons.history')}}">{{__('Coupon History')}}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if(Gate::check('manage account settings') || Gate::check('manage password settings') || Gate::check('manage general settings') || Gate::check('manage company settings') || Gate::check('manage seo settings') || Gate::check('manage google recaptcha settings'))
                    <li class="menu-item {{in_array($routeName,['setting.account','setting.password','setting.general','setting.company','setting.smtp','setting.payment','setting.site.seo','setting.google.recaptcha'])?'active':''}}">
                        <a href="javascript:void(0);">
                            <div class="icon-item"><i data-feather="settings"></i></div>
                            <span>{{__('Settings')}}</span><i class="fa fa-angle-down"></i></a>
                        <ul class="submenu-list "
                            style="display: {{in_array($routeName,['setting.account','setting.password','setting.general','setting.company','setting.smtp','setting.payment','setting.site.seo','setting.google.recaptcha'])?'block':'none'}}">
                            @if(Gate::check('manage account settings'))
                                <li class="{{in_array($routeName,['setting.account'])?'active':''}} ">
                                    <a href="{{route('setting.account')}}">{{__('Account Setting')}}</a>
                                </li>
                            @endif
                            @if(Gate::check('manage password settings'))
                                <li class="{{in_array($routeName,['setting.password'])?'active':''}}">
                                    <a href="{{route('setting.password')}}">{{__('Password Setting')}}</a>
                                </li>
                            @endif
                            @if(Gate::check('manage general settings'))
                                <li class="{{in_array($routeName,['setting.general'])?'active':''}} ">
                                    <a href="{{route('setting.general')}}">{{__('General Setting')}}</a>
                                </li>
                            @endif
                            @if(Gate::check('manage company settings'))
                                <li class="{{in_array($routeName,['setting.company'])?'active':''}}">
                                    <a href="{{route('setting.company')}}">{{__('Company Setting')}}</a>
                                </li>
                            @endif
                            @if(Gate::check('manage email settings'))
                                <li class="{{in_array($routeName,['setting.smtp'])?'active':''}} ">
                                    <a href="{{route('setting.smtp')}}">{{__('Email Setting')}}</a>
                                </li>
                            @endif
                            @if(Gate::check('manage payment settings'))
                                <li class="{{in_array($routeName,['setting.payment'])?'active':''}} ">
                                    <a href="{{route('setting.payment')}}">{{__('Payment Setting')}}</a>
                                </li>
                            @endif
                            @if(Gate::check('manage seo settings'))
                                <li class="{{in_array($routeName,['setting.site.seo'])?'active':''}} ">
                                    <a href="{{route('setting.site.seo')}}">{{__('Site SEO Setting')}}</a>
                                </li>
                            @endif
                            @if(Gate::check('manage google recaptcha settings'))
                                <li class="{{in_array($routeName,['setting.google.recaptcha'])?'active':''}} ">
                                    <a href="{{route('setting.google.recaptcha')}}">{{__('ReCaptcha Setting')}}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

            @endif


        </ul>
    </div>
</aside>
<!-- sidebar end-->
