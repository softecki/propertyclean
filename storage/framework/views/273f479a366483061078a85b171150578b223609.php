<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Maintainer')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>"><h1><?php echo e(__('Dashboard')); ?></h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Maintainer')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('card-action-btn'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create maintainer')): ?>
        <a class="btn btn-primary btn-sm ml-20 customModal" href="#" data-size="lg"
           data-url="<?php echo e(route('maintainer.create')); ?>"
           data-title="<?php echo e(__('Create Maintainer')); ?>"> <i class="ti-plus mr-5"></i><?php echo e(__('Create Maintainer')); ?></a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php $__currentLoopData = $maintainers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $maintainer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xl-3 col-md-6 cdx-xxl-50 cdx-xl-50 ">
                <div class="card custom contact-card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="user-imgwrapper">
                                <img class="img-fluid rounded-50"
                                     src="<?php echo e((!empty($maintainer->user) && !empty($maintainer->user->profile))? asset(Storage::url("upload/profile/".$maintainer->user->profile)): asset(Storage::url("upload/profile/avatar.png"))); ?>"
                                     alt="">
                            </div>
                            <div class="media-body">
                                <a class="customModal" href="#" data-size="md"
                                   data-url="<?php echo e(route('maintainer.edit',$maintainer->id)); ?>"  data-title="<?php echo e(__('Edit Maintainer')); ?>">
                                    <h4><?php echo e(!empty($maintainer->user)?ucfirst($maintainer->user->first_name.' '.$maintainer->user->last_name):'-'); ?></h4>
                                    <h6 class="text-light"><?php echo e(!empty($maintainer->user)?$maintainer->user->email:'-'); ?></h6>
                                </a>
                            </div>
                            <?php if(Gate::check('edit maintainer') || Gate::check('delete maintainer') || Gate::check('show maintainer')): ?>
                                <div class="user-setting">
                                    <div class="action-menu">
                                        <div class="action-toggle"><i data-feather="more-vertical"></i></div>
                                        <ul class="action-dropdown">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit maintainer')): ?>
                                                <li>
                                                    <a class="customModal" href="#" data-size="lg"
                                                       data-url="<?php echo e(route('maintainer.edit',$maintainer->id)); ?>"
                                                       data-title="<?php echo e(__('Edit Maintainer')); ?>"> <i
                                                            data-feather="edit"> </i><?php echo e(__('Edit Maintainer')); ?></a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete maintainer')): ?>
                                                <li>
                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['maintainer.destroy', $maintainer->id],'id'=>'tenant-'.$maintainer->id]); ?>

                                                    <a href="#" class="confirm_dialog"> <i
                                                            data-feather="trash"></i><?php echo e(__('Delete Maintainer')); ?></a>
                                                    <?php echo Form::close(); ?>

                                                </li>
                                            <?php endif; ?>

                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="user-detail">
                            <h5 class="text-primary mb-10"><i class="fa fa-info-circle mr-10"></i><?php echo e(__('Infomation')); ?>

                            </h5>
                            <ul class="info-list">
                                <li><span><?php echo e(__('Phone')); ?> : </span><?php echo e(!empty($maintainer->phone_number)?$maintainer->user->phone_number:'-'); ?> </li>

                                <li>
                                    <span><?php echo e(__('Type')); ?> : </span><?php echo e(!empty($maintainer->types)?$maintainer->types->title:'-'); ?>

                                </li>
                                <li>
                                    <span><?php echo e(__('Created Date')); ?> : </span><?php echo e(dateFormat($maintainer->created_at)); ?>

                                </li>
                                <li>
                                    <span><?php echo e(__('Property')); ?> : </span><br>
                                    <?php $__currentLoopData = $maintainer->properties(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($property->name); ?><br>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/propertymanagement/resources/views/maintainer/index.blade.php ENDPATH**/ ?>