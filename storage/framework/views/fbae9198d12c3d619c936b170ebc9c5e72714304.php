<?php
    $admin_logo=getSettingsValByName('company_logo');
    $ids     = parentId();
    $authUser=\App\Models\User::find($ids);
 $subscription = \App\Models\Subscription::find($authUser->subscription);
 $routeName=\Request::route()->getName();
?>
<aside class="codex-sidebar sidebar-<?php echo e($settings['sidebar_mode']); ?>">
    <div class="logo-gridwrap">
        
        <div class="sidebar-action"><i data-feather="menu"></i></div>
    </div>
    <div class="icon-logo">
        <a href="<?php echo e(route('home')); ?>">
            <img class="img-fluid"
                 src="<?php echo e(asset(Storage::url('upload/logo')).'/'.$settings['company_favicon']); ?>"
                 alt="theeme-logo">
        </a>
    </div>
    <div class="codex-menuwrapper">
        <ul class="codex-menu custom-scroll" data-simplebar>
            <li class="cdxmenu-title">
                <h5><?php echo e(__('Home')); ?></h5>
            </li>
            <li class="menu-item <?php echo e(in_array($routeName,['dashboard','home',''])?'active':''); ?>">
                <a href="<?php echo e(route('dashboard')); ?>">
                    <div class="icon-item"><i data-feather="home"></i></div>
                    <span><?php echo e(__('Dashboard')); ?></span>
                </a>
            </li>

            <?php if(\Auth::user()->type=='super admin'): ?>
                <?php if(Gate::check('manage user')): ?>
                    <li class="menu-item <?php echo e(in_array($routeName,['users.index'])?'active':''); ?>">
                        <a href="<?php echo e(route('users.index')); ?>">
                            <div class="icon-item"><i data-feather="users"></i></div>
                            <span><?php echo e(__('Users')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
            <?php else: ?>
                <?php if(Gate::check('manage user') || Gate::check('manage role') || Gate::check('manage logged history') ): ?>
                    <li class="menu-item <?php echo e(in_array($routeName,['users.index','logged.history','role.index','role.create','role.edit'])?'active':''); ?>">
                        <a href="javascript:void(0);">
                            <div class="icon-item"><i data-feather="users"></i></div>
                            <span><?php echo e(__('Staff Management')); ?></span><i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="submenu-list"
                            style="display: <?php echo e(in_array($routeName,['users.index','logged.history','role.index','role.create','role.edit'])?'block':'none'); ?>">
                            <?php if(Gate::check('manage user')): ?>
                                <li class="<?php echo e(in_array($routeName,['users.index'])?'active':''); ?>">
                                    <a href="<?php echo e(route('users.index')); ?>"><?php echo e(__('Users')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(Gate::check('manage role')): ?>
                                <li class=" <?php echo e(in_array($routeName,['role.index','role.create','role.edit'])?'active':''); ?>">
                                    <a href="<?php echo e(route('role.index')); ?>">
                                        <?php echo e(__('Roles')); ?>

                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if(Gate::check('manage logged history') && $subscription->enabled_logged_history==1): ?>
                                <li class="<?php echo e(in_array($routeName,['logged.history'])?'active':''); ?>">
                                    <a href="<?php echo e(route('logged.history')); ?>"><?php echo e(__('Logged History')); ?></a>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </li>
                <?php endif; ?>
            <?php endif; ?>

            <?php if(Gate::check('manage property') || Gate::check('manage unit')|| Gate::check('manage tenant') || Gate::check('manage invoice') || Gate::check('manage expense') || Gate::check('manage maintainer') || Gate::check('manage maintenance request') || Gate::check('manage contact') || Gate::check('manage support') || Gate::check('manage note')): ?>
                <li class="cdxmenu-title">
                    <h5><?php echo e(__('Business Management')); ?></h5>
                </li>
                <?php if(Gate::check('manage tenant') || Gate::check('manage property') || Gate::check('manage unit')): ?>
                <li class="menu-item <?php echo e(in_array($routeName,['property.index','property.create','property.edit','property.show','unit.index'])?'active':''); ?>">
                    <a href="javascript:void(0);">
                        <div class="icon-item"><i data-feather="home"></i></div>
                        <span><?php echo e(__('TFA Properties')); ?></span><i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="submenu-list"  style="display: <?php echo e(in_array($routeName,['property.index','property.create','property.edit','property.show','unit.index'])?'block':'none'); ?>">

                        <?php if(Gate::check('manage property')): ?>
                            <li class=" <?php echo e(in_array($routeName,['property.index','property.create','property.edit','property.show'])?'active':''); ?> ">
                                <a href="<?php echo e(route('property.index')); ?>">  <?php echo e(__('Properties')); ?> </a>
                            </li>
                        <?php endif; ?>
                        <?php if(Gate::check('manage unit')): ?>
                            <li class=" <?php echo e(in_array($routeName,['unit.index'])?'active':''); ?> ">
                                <a href="<?php echo e(route('unit.index')); ?>">  <?php echo e(__('Spaces')); ?> </a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </li>
            <?php endif; ?>
                <?php if(Gate::check('manage tenant')): ?>
                    <li class="menu-item <?php echo e(in_array($routeName,['tenant.index','tenant.create','tenant.edit','tenant.show'])?'active':''); ?>">
                        <a href="<?php echo e(route('tenant.index')); ?>">
                            <div class="icon-item"><i data-feather="user"></i></div>
                            <span><?php echo e(__('Tenants')); ?> </span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if(Gate::check('manage tenant')): ?>
                <li class="menu-item <?php echo e(in_array($routeName,['tenantapplication.index','tenantapplication.create','tenantapplication.edit','tenantapplication.show'])?'active':''); ?>">
                    <a href="<?php echo e(route('tenantapplication.index')); ?>">
                        <div class="icon-item"><i data-feather="user"></i></div>
                        <span><?php echo e(__('Tenants Applications')); ?> </span>
                    </a>
                </li>
            <?php endif; ?>
                <?php if(Gate::check('manage contract')): ?>
                <li class="menu-item <?php echo e(in_array($routeName,['contract.index','contract.create','contract.edit','contract.show'])?'active':''); ?>">
                    <a href="<?php echo e(route('contract.index')); ?>">
                        <div class="icon-item"><i data-feather="info"></i></div>
                        <span><?php echo e(__('Contracts')); ?> </span>
                    </a>
                </li>
            <?php endif; ?>
                <?php if(Gate::check('manage maintainer')): ?>
                    <li class="menu-item <?php echo e(in_array($routeName,['maintainer.index'])?'active':''); ?> ">
                        <a href="<?php echo e(route('maintainer.index')); ?>">
                            <div class="icon-item"><i data-feather="user-check"></i></div>
                            <span><?php echo e(__('Maintainers')); ?> </span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(Gate::check('manage maintainer') || Gate::check('manage maintenance request') ): ?>
                    <li class="menu-item <?php echo e(in_array($routeName,['maintenance-request.index','maintenance-request.pending','maintenance-request.inprogress'])?'active':''); ?>">
                        <a href="javascript:void(0);">
                            <div class="icon-item"><i data-feather="tool"></i></div>
                            <span><?php echo e(__('Maintenance')); ?></span><i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="submenu-list"
                            style="display: <?php echo e(in_array($routeName,['maintenance-request.index','maintenance-request.pending','maintenance-request.inprogress'])?'block':'none'); ?>">

                            <?php if(Gate::check('manage maintenance request')): ?>
                                <li class=" <?php echo e(in_array($routeName,['maintenance-request.index'])?'active':''); ?>">
                                    <a href="<?php echo e(route('maintenance-request.index')); ?>"><?php echo e(__('All Requests')); ?> </a>
                                </li>
                            <?php endif; ?>
                            <?php if(Gate::check('manage maintenance request')): ?>
                                <li class=" <?php echo e(in_array($routeName,['maintenance-request.pending'])?'active':''); ?>">
                                    <a href="<?php echo e(route('maintenance-request.pending')); ?>"><?php echo e(__('Pending')); ?> </a>
                                </li>
                            <?php endif; ?>
                            <?php if(Gate::check('manage maintenance request')): ?>
                                <li class=" <?php echo e(in_array($routeName,['maintenance-request.inprogress'])?'active':''); ?>">
                                    <a href="<?php echo e(route('maintenance-request.inprogress')); ?>"><?php echo e(__('In Progress')); ?> </a>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </li>
                <?php endif; ?>

                <?php if(Gate::check('manage invoice') || Gate::check('manage expense') ): ?>
                    <li class="menu-item <?php echo e(in_array($routeName,['invoice.index','invoice.create','invoice.edit','invoice.show','expense.index','transactions.index'])?'active':''); ?>">
                        <a href="javascript:void(0);">
                            <div class="icon-item"><i data-feather="file-text"></i></div>
                            <span><?php echo e(__('Finance')); ?></span><i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="submenu-list"
                            style="display: <?php echo e(in_array($routeName,['invoice.index','invoice.create','invoice.edit','invoice.show','expense.index'])?'block':'none'); ?>">
                            <?php if(Gate::check('manage invoice')): ?>
                                <li class=" <?php echo e(in_array($routeName,['invoice.index','invoice.create','invoice.edit','invoice.show'])?'active':''); ?>">
                                    <a href="<?php echo e(route('invoice.index')); ?>">  <?php echo e(__('Invoices')); ?> </a>
                                </li>
                            <?php endif; ?>
                            <?php if(Gate::check('manage expense')): ?>
                                <li class=" <?php echo e(in_array($routeName,['expense.index'])?'active':''); ?>">
                                    <a href="<?php echo e(route('expense.index')); ?>"> <?php echo e(__('Expense/Bills')); ?>  </a>
                                </li>
                            <?php endif; ?>

                            <?php if(Gate::check('manage expense')): ?>
                            <li class=" <?php echo e(in_array($routeName,['transactions.index'])?'active':''); ?>">
                                <a href="<?php echo e(route('transactions.index')); ?>"> <?php echo e(__('Transactions')); ?>  </a>
                            </li>
                        <?php endif; ?>

                        </ul>
                    </li>
                <?php endif; ?>

                <?php if(Gate::check('manage contact')): ?>
                <li class="menu-item <?php echo e(in_array($routeName,['asset.index'])?'active':''); ?>">
                    <a href="<?php echo e(route('asset.index')); ?>">
                        <div class="icon-item"><i data-feather="phone-call"></i></div>
                        <span><?php echo e(__('Assets Managements')); ?></span>
                    </a>
                </li>
            <?php endif; ?>
                <?php if(Gate::check('manage invoice') || Gate::check('manage expense') ): ?>
                <li class="menu-item <?php echo e(in_array($routeName,['report.index','report.create','report.edit','report.show'])?'active':''); ?>">
                    <a href="javascript:void(0);">
                        <div class="icon-item"><i data-feather="file-text"></i></div>
                        <span><?php echo e(__('General Report')); ?></span><i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="submenu-list"
                        style="display: <?php echo e(in_array($routeName,['report.index','report.create','report.edit','report.show'])?'block':'none'); ?>">
                        <?php if(Gate::check('manage invoice')): ?>
                            <li class=" <?php echo e(in_array($routeName,['report.index','report.create','report.edit','report.show'])?'active':''); ?>">
                                <a href="<?php echo e(route('report.index')); ?>">  <?php echo e(__('Tenant Report')); ?> </a>
                            </li>
                        <?php endif; ?>
                        <?php if(Gate::check('manage expense')): ?>
                            <li class=" <?php echo e(in_array($routeName,['report.index'])?'active':''); ?>">
                                <a href="<?php echo e(route('report.index')); ?>"> <?php echo e(__('Meintanance Report')); ?>  </a>
                            </li>
                        <?php endif; ?>

                        <?php if(Gate::check('manage expense')): ?>
                        <li class=" <?php echo e(in_array($routeName,['report.index'])?'active':''); ?>">
                            <a href="<?php echo e(route('report.index')); ?>"> <?php echo e(__('Property Report')); ?>  </a>
                        </li>
                    <?php endif; ?>

                    </ul>
                </li>
            <?php endif; ?>

                <?php if(Gate::check('manage contact')): ?>
                    <li class="menu-item <?php echo e(in_array($routeName,['contact.index'])?'active':''); ?>">
                        <a href="<?php echo e(route('contact.index')); ?>">
                            <div class="icon-item"><i data-feather="phone-call"></i></div>
                            <span><?php echo e(__('Contacts')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(Gate::check('manage note')): ?>
                    <li class="menu-item <?php echo e(in_array($routeName,['note.index'])?'active':''); ?> ">
                        <a href="<?php echo e(route('note.index')); ?>">
                            <div class="icon-item"><i data-feather="file-text"></i></div>
                            <span><?php echo e(__('Note')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(Gate::check('manage note')): ?>
                <li class="menu-item <?php echo e(in_array($routeName,['feedback.index'])?'active':''); ?> ">
                    <a href="<?php echo e(route('feedback.index')); ?>">
                        <div class="icon-item"><i data-feather="file-text"></i></div>
                        <span><?php echo e(__('Feedback')); ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php endif; ?>

            <?php if(Gate::check('manage types')): ?>
                <li class="cdxmenu-title">
                    <h5><?php echo e(__('Setup')); ?></h5>
                </li>
                <?php if(Gate::check('manage types')): ?>
                    <li class="menu-item <?php echo e(in_array($routeName,['type.index'])?'active':''); ?>">
                        <a href="<?php echo e(route('type.index')); ?>">
                            <div class="icon-item"><i data-feather="file-text"></i></div>
                            <span><?php echo e(__('Types')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
            <?php if(Gate::check('manage pricing packages') || Gate::check('manage pricing transation') || Gate::check('manage account settings') || Gate::check('manage password settings') || Gate::check('manage general settings') || Gate::check('manage company settings')): ?>
                <li class="cdxmenu-title">
                    <h5><?php echo e(__('System Settings')); ?></h5>
                </li>
                <?php if(Gate::check('manage pricing packages') || Gate::check('manage pricing transation')): ?>
                    <li class="menu-item <?php echo e(in_array($routeName,['subscriptions.index','subscriptions.show','subscription.transaction'])?'active':''); ?>">
                        <a href="javascript:void(0);">
                            <div class="icon-item"><i data-feather="database"></i></div>
                            <span><?php echo e(__('Pricing')); ?></span><i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="submenu-list"
                            style="display: <?php echo e(in_array($routeName,['subscriptions.index','subscriptions.show','subscription.transaction'])?'block':'none'); ?>">
                            <?php if(Gate::check('manage pricing packages')): ?>
                                <li class="<?php echo e(in_array($routeName,['subscriptions.index','subscriptions.show'])?'active':''); ?>">
                                    <a href="<?php echo e(route('subscriptions.index')); ?>"><?php echo e(__('Packages')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(Gate::check('manage pricing transation')): ?>
                                <li class="<?php echo e(in_array($routeName,['subscription.transaction'])?'active':''); ?> ">
                                    <a href="<?php echo e(route('subscription.transaction')); ?>"><?php echo e(__('Transactions')); ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if(Gate::check('manage coupon') || Gate::check('manage coupon history')): ?>
                    <li class="menu-item <?php echo e(in_array($routeName,['coupons.index','coupons.history'])?'active':''); ?>">
                        <a href="javascript:void(0);">
                            <div class="icon-item"><i data-feather="gift"></i></div>
                            <span><?php echo e(__('Coupons')); ?></span><i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="submenu-list"
                            style="display: <?php echo e(in_array($routeName,['coupons.index','coupons.history'])?'block':'none'); ?>">
                            <?php if(Gate::check('manage coupon')): ?>
                                <li class="<?php echo e(in_array($routeName,['coupons.index'])?'active':''); ?>">
                                    <a href="<?php echo e(route('coupons.index')); ?>"><?php echo e(__('All Coupon')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(Gate::check('manage coupon history')): ?>
                                <li class="<?php echo e(in_array($routeName,['coupons.history'])?'active':''); ?>">
                                    <a href="<?php echo e(route('coupons.history')); ?>"><?php echo e(__('Coupon History')); ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if(Gate::check('manage account settings') || Gate::check('manage password settings') || Gate::check('manage general settings') || Gate::check('manage company settings') || Gate::check('manage seo settings') || Gate::check('manage google recaptcha settings')): ?>
                    <li class="menu-item <?php echo e(in_array($routeName,['setting.account','setting.password','setting.general','setting.company','setting.smtp','setting.payment','setting.site.seo','setting.google.recaptcha'])?'active':''); ?>">
                        <a href="javascript:void(0);">
                            <div class="icon-item"><i data-feather="settings"></i></div>
                            <span><?php echo e(__('Settings')); ?></span><i class="fa fa-angle-down"></i></a>
                        <ul class="submenu-list "
                            style="display: <?php echo e(in_array($routeName,['setting.account','setting.password','setting.general','setting.company','setting.smtp','setting.payment','setting.site.seo','setting.google.recaptcha'])?'block':'none'); ?>">
                            <?php if(Gate::check('manage account settings')): ?>
                                <li class="<?php echo e(in_array($routeName,['setting.account'])?'active':''); ?> ">
                                    <a href="<?php echo e(route('setting.account')); ?>"><?php echo e(__('Account Setting')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(Gate::check('manage password settings')): ?>
                                <li class="<?php echo e(in_array($routeName,['setting.password'])?'active':''); ?>">
                                    <a href="<?php echo e(route('setting.password')); ?>"><?php echo e(__('Password Setting')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(Gate::check('manage general settings')): ?>
                                <li class="<?php echo e(in_array($routeName,['setting.general'])?'active':''); ?> ">
                                    <a href="<?php echo e(route('setting.general')); ?>"><?php echo e(__('General Setting')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(Gate::check('manage company settings')): ?>
                                <li class="<?php echo e(in_array($routeName,['setting.company'])?'active':''); ?>">
                                    <a href="<?php echo e(route('setting.company')); ?>"><?php echo e(__('Company Setting')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(Gate::check('manage email settings')): ?>
                                <li class="<?php echo e(in_array($routeName,['setting.smtp'])?'active':''); ?> ">
                                    <a href="<?php echo e(route('setting.smtp')); ?>"><?php echo e(__('Email Setting')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(Gate::check('manage payment settings')): ?>
                                <li class="<?php echo e(in_array($routeName,['setting.payment'])?'active':''); ?> ">
                                    <a href="<?php echo e(route('setting.payment')); ?>"><?php echo e(__('Payment Setting')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(Gate::check('manage seo settings')): ?>
                                <li class="<?php echo e(in_array($routeName,['setting.site.seo'])?'active':''); ?> ">
                                    <a href="<?php echo e(route('setting.site.seo')); ?>"><?php echo e(__('Site SEO Setting')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(Gate::check('manage google recaptcha settings')): ?>
                                <li class="<?php echo e(in_array($routeName,['setting.google.recaptcha'])?'active':''); ?> ">
                                    <a href="<?php echo e(route('setting.google.recaptcha')); ?>"><?php echo e(__('ReCaptcha Setting')); ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

            <?php endif; ?>


        </ul>
    </div>
</aside>
<!-- sidebar end-->
<?php /**PATH C:\xampp\htdocs\project\propertymanagement\resources\views/admin/menu.blade.php ENDPATH**/ ?>