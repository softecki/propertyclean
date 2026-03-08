<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Property Details')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-class'); ?>
    product-detail-page
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>"><h1><?php echo e(__('Dashboard')); ?></h1></a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('property.index')); ?>"><?php echo e(__('Property')); ?></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Details')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create unit')): ?>
        <div class="row">
            <div class="col-sm-12 text-end">
                <a href="#" class="btn btn-primary btn-sm customModal" data-title="<?php echo e(__('Add Unit')); ?>"
                   data-url="<?php echo e(route('unit.create',$property->id)); ?>" data-size="lg"> <i
                        class="ti-plus mr-5"></i><?php echo e(__('Add Unit')); ?></a>
            </div>
        </div>
    <?php endif; ?>
    <div class="row mt-10">
        <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xl-3 col-md-6 cdx-xxl-50 cdx-xl-50">
                <div class="card contact-card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body">
                                <h4><?php echo e($unit->name); ?></h4>
                            </div>
                            <?php if(Gate::check('edit unit') || Gate::check('delete unit')): ?>
                                <div class="user-setting">
                                    <div class="action-menu">
                                        <div class="action-toggle"><i data-feather="more-vertical"></i></div>
                                        <ul class="action-dropdown">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit unit')): ?>
                                                <li>
                                                    <a class="customModal" href="#"
                                                       data-url="<?php echo e(route('unit.edit',[$property->id,$unit->id])); ?>"
                                                       data-title="<?php echo e(__('Edit Unit')); ?>" data-size="lg"> <i
                                                            data-feather="edit"> </i><?php echo e(__('Edit Unit')); ?></a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete unit')): ?>
                                                <li>
                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['unit.destroy', $property->id,$unit->id],'id'=>'unit-'.$unit->id]); ?>

                                                    <a href="#" class="confirm_dialog"> <i
                                                            data-feather="trash"></i><?php echo e(__('Delete Unit')); ?></a>
                                                    <?php echo Form::close(); ?>


                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="user-detail">
                            <ul class="info-list">
                                <li><span><?php echo e(__('Bedroom')); ?> :- </span><?php echo e($unit->bedroom); ?> </li>
                                <li><span><?php echo e(__('Kitchen')); ?> :- </span><?php echo e($unit->kitchen); ?></li>
                                <li><span><?php echo e(__('Bath')); ?> :- </span><?php echo e($unit->baths); ?></li>
                                <li><span><?php echo e(__('Rent Type')); ?> :- </span><?php echo e($unit->rent_type); ?></li>
                                <li><span><?php echo e(__('Rent')); ?> :- </span><?php echo e(priceFormat($unit->rent)); ?></li>
                                <?php if($unit->rent_type=='custom'): ?>
                                    <li>
                                        <span><?php echo e(__('Start Date')); ?> :- </span><?php echo e(dateFormat($unit->start_date)); ?>

                                    </li>
                                    <li>
                                        <span><?php echo e(__('End Date')); ?> :- </span><?php echo e(dateFormat($unit->end_date)); ?>

                                    </li>
                                    <li>
                                        <span><?php echo e(__('Payment Due Date')); ?> :- </span><?php echo e(dateFormat($unit->payment_due_date)); ?>

                                    </li>
                                <?php else: ?>
                                    <li><span><?php echo e(__('Rent Duration')); ?> :- </span><?php echo e($unit->rent_duration); ?></li>
                                <?php endif; ?>
                                <li><span><?php echo e(__('Deposit Type')); ?> :- </span><?php echo e($unit->deposit_type); ?></li>
                                <li>
                                    <span><?php echo e(__('Deposit Amount')); ?> :- </span><?php echo e(($unit->deposit_type=='fixed')?priceFormat($unit->deposit_amount):$unit->deposit_amount.'%'); ?>

                                </li>
                                <li><span><?php echo e(__('Late Fee Type')); ?> :- </span><?php echo e($unit->late_fee_type); ?></li>
                                <li>
                                    <span><?php echo e(__('Late Fee Amount')); ?> :- </span><?php echo e(($unit->deposit_type=='fixed')?priceFormat($unit->late_fee_amount):$unit->late_fee_amount.'%'); ?>

                                </li>
                                <li>
                                    <span><?php echo e(__('Incident Receipt Amount')); ?> :- </span><?php echo e(priceFormat($unit->incident_receipt_amount)); ?>

                                </li>
                            </ul>
                            <div class="user-action">
                                <p class="text-light"><?php echo e($unit->notes); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="row">
        <div class="col-md-5 cdx-xl-45">
            <div class="product-card">
                <div class="product-for">
                    <?php $__currentLoopData = $property->propertyImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!empty($image) && !empty($image->image)): ?>
                            <?php  $img= $image->image; ?>
                        <?php else: ?>
                            <?php  $img= 'default.jpg'; ?>
                        <?php endif; ?>
                        <div>
                            <div class="product-imgwrap">
                                <img class="img-fluid" src="<?php echo e(asset(Storage::url('upload/property')).'/'.$img); ?>" alt="">
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="product-to">
                    <?php $__currentLoopData = $property->propertyImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!empty($image) && !empty($image->image)): ?>
                            <?php  $img= $image->image; ?>
                        <?php else: ?>
                            <?php  $img= 'default.jpg'; ?>
                        <?php endif; ?>
                        <div>
                            <div class="product-imgwrap">
                                <img class="img-fluid" src="<?php echo e(asset(Storage::url('upload/property')).'/'.$img); ?>" alt="">
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <div class="col-md-7 cdx-xl-55 cdxpro-detail">
            <div class="product-card">
                <div class="detail-group">
                    <div class="media">
                        <div>
                            <h2><?php echo e($property->name); ?></h2>
                            <h6 class="text-light">
                                <div class="date-info">
                                    <span class="badge badge-primary" data-bs-toggle="tooltip"
                                          data-bs-original-title="<?php echo e(__('Type')); ?>"><?php echo e(\App\Models\Property::$Type[$property->type]); ?></span>
                                </div>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="detail-group">
                    <h6><?php echo e(__('Property Details')); ?></h6>
                    <p class="mb-10"><?php echo e($property->description); ?></p>

                </div>
                <div class="detail-group">
                    <h6><?php echo e(__('Property Address')); ?></h6>
                    <p class="mb-10"><?php echo e($property->address); ?></p>
                    <p class="mb-10"><?php echo e($property->city.', '.$property->state.', '.$property->country); ?></p>
                    <p class="mb-10"><?php echo e($property->zip_code); ?></p>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/propertymanagement/resources/views/property/show.blade.php ENDPATH**/ ?>