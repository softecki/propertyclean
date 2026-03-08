<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Tenant')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>"><h1><?php echo e(__('Dashboard')); ?></h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Tenant')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('card-action-btn'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create tenant')): ?>
        <a class="btn btn-primary btn-sm ml-20" href="<?php echo e(route('tenant.create')); ?>" data-size="md"> <i
                class="ti-plus mr-5"></i><?php echo e(__('Create Tenant')); ?></a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>


<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Link</th>
            <th scope="col">Title</th>
            <th scope="col">Busness Name</th>
            <th scope="col">Businnes number</th>
            <th scope="col">Tax Payer Identification</th>
            <th scope="col">Contact Information</th>
            <th scope="col">Property </th>
            <th scope="col">Room </th>
            <th scope="col">Action</th>
            <!-- Add more headers as needed -->
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <tr class="bg-white">
            <td>   <div class="imgwrapper">
                <a class="hover-link" href="<?php echo e(route('property.show',$property->id)); ?>"><i
                    data-feather="link"></i></a>
                
                </div>
                    </td>
            <td>
                <div>
                    <?php echo e($property->title); ?>

                </div>
            </td>
            <td>
                <div>
                    <?php echo e($property->business_name); ?>

                </div>
        </td>
        <td>
            <div>
                <?php echo e($property->business_number); ?>

            </div>
        </td>
        <td>
            <div>
                <?php echo e($property->tax_payer_identification); ?>

            </div>
        </td>
        <td>
            <div>
                <?php echo e($property->contact_information); ?>

            </div>
        </td>
        <td>
            <div>
                <?php echo e($property->property); ?>

            </div>
        </td>
        <td>
            <div class="">
                <?php echo e($property->unit); ?>

            </div>
        </td>
        <td>

            <?php echo Form::open(['method' => 'DELETE', 'route' => ['property.destroy', $property->id]]); ?>

            <div class="date-info">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit property')): ?>
                <a class="text-success" data-bs-toggle="tooltip"
                   data-bs-original-title="<?php echo e(__('Contract')); ?>"
                   href="<?php echo e(route('contract.create',$property->id)); ?>"> <i
                        data-feather="key"></i></a>
            <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit property')): ?>
                <a class="text-success" data-bs-toggle="tooltip"
                   data-bs-original-title="<?php echo e(__('View')); ?>"
                   href="<?php echo e(route('tenant.edit',$property->id)); ?>"> <i
                        data-feather="eye"></i></a>
            <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit property')): ?>
                    <a class="text-success" data-bs-toggle="tooltip"
                       data-bs-original-title="<?php echo e(__('Edit')); ?>"
                       href="<?php echo e(route('tenant.edit',$property->id)); ?>"> <i
                            data-feather="edit"></i></a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete property')): ?>
                    <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                       data-bs-original-title="<?php echo e(__('Detete')); ?>" href="#"> <i
                            data-feather="trash-2"></i></a>
                <?php endif; ?>
            </div>
            <?php echo Form::close(); ?>

        </td>
            <!-- Add more data columns as needed -->
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <!-- Add more rows as needed -->
    </tbody>
</table>



<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/propertymanagement/resources/views/tenant/index.blade.php ENDPATH**/ ?>