<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Maintenance Request')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>"><h1><?php echo e(__('Dashboard')); ?></h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Maintenance Request')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('card-action-btn'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create maintenance request')): ?>
        <a class="btn btn-primary btn-sm ml-20 customModal" href="#" data-size="lg"
           data-url="<?php echo e(route('maintenance-request.create')); ?>"
           data-title="<?php echo e(__('Create Maintenance Request')); ?>"> <i
                class="ti-plus mr-5"></i><?php echo e(__('Create Maintenance Request')); ?></a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="display dataTable cell-border datatbl-advance">
                        <thead>
                        <tr>
                            <th><?php echo e(__('Property')); ?></th>
                            <th><?php echo e(__('Unit')); ?></th>
                            <th><?php echo e(__('Issue')); ?></th>
                            <th><?php echo e(__('Maintainer')); ?></th>
                            <th><?php echo e(__('Request Date')); ?></th>
                            <th><?php echo e(__('Status')); ?></th>
                            <th><?php echo e(__('Attachment')); ?></th>
                            <?php if(Gate::check('edit maintenance request') || Gate::check('delete maintenance request') || Gate::check('show maintenance request')): ?>
                                <th class="text-right"><?php echo e(__('Action')); ?></th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $maintenanceRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr role="row">
                                <td> <?php echo e(!empty($request->properties)?$request->properties->name:'-'); ?> </td>
                                <td> <?php echo e(!empty($request->units)?$request->units->name:'-'); ?> </td>
                                <td> <?php echo e(!empty($request->types)?$request->types->title:'-'); ?> </td>
                                <td> <?php echo e(!empty($request->maintainers)?$request->maintainers->name:'-'); ?> </td>
                                <td> <?php echo e(dateFormat($request->request_date)); ?> </td>
                                <td>
                                    <?php if($request->status=='pending'): ?>
                                        <span
                                            class="badge badge-warning"> <?php echo e(\App\Models\MaintenanceRequest::$status[$request->status]); ?></span>
                                    <?php elseif($request->status=='in_progress'): ?>
                                        <span
                                            class="badge badge-info"> <?php echo e(\App\Models\MaintenanceRequest::$status[$request->status]); ?></span>
                                    <?php else: ?>
                                        <span
                                            class="badge badge-primary"> <?php echo e(\App\Models\MaintenanceRequest::$status[$request->status]); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($request->issue_attachment)): ?>
                                        <a href="<?php echo e(asset(Storage::url('upload/issue_attachment')).'/'.$request->issue_attachment); ?>"
                                           download="download"><i data-feather="download"></i></a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <?php if(Gate::check('edit maintenance request') || Gate::check('delete maintenance request') || Gate::check('show maintenance request')): ?>
                                    <td class="text-right">
                                        <div class="cart-action">
                                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['maintenance-request.destroy', $request->id]]); ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show maintenance request')): ?>
                                                <a class="text-warning customModal" data-size="lg"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-original-title="<?php echo e(__('View')); ?>" href="#"
                                                   data-url="<?php echo e(route('maintenance-request.show',$request->id)); ?>"
                                                   data-title="<?php echo e(__('Maintenance Request Details')); ?>"> <i
                                                        data-feather="eye"></i></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit maintenance request')): ?>
                                                <a class="text-success customModal" data-size="lg"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-original-title="<?php echo e(__('Edit')); ?>" href="#"
                                                   data-url="<?php echo e(route('maintenance-request.edit',$request->id)); ?>"
                                                   data-title="<?php echo e(__('Maintenance Request')); ?>"> <i
                                                        data-feather="edit"></i></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete maintenance request')): ?>
                                                <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                   data-bs-original-title="<?php echo e(__('Detete')); ?>" href="#"> <i
                                                        data-feather="trash-2"></i></a>
                                            <?php endif; ?>
                                            <?php if(\Auth::user()->type=='maintainer'): ?>
                                                <a class="text-success customModal" data-size="lg"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-original-title="<?php echo e(__('Status Update')); ?>" href="#"
                                                   data-url="<?php echo e(route('maintenance-request.action',$request->id)); ?>"
                                                   data-title="<?php echo e(__('Maintenance Request Status')); ?>"> <i data-feather="check-square"></i></a>
                                            <?php endif; ?>
                                            <?php echo Form::close(); ?>

                                        </div>

                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/propertymanagement/resources/views/maintenance_request/index.blade.php ENDPATH**/ ?>