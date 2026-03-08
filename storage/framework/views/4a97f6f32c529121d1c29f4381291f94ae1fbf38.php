<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Expense')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>"><h1><?php echo e(__('Dashboard')); ?></h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Expense')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('card-action-btn'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create expense')): ?>
        <a class="btn btn-primary btn-sm ml-20 customModal" href="#" data-size="lg"
           data-url="<?php echo e(route('expense.create')); ?>"
           data-title="<?php echo e(__('Create Expense')); ?>"> <i class="ti-plus mr-5"></i><?php echo e(__('Create Expense')); ?></a>
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
                            <th><?php echo e(__('Expense')); ?></th>
                            <th><?php echo e(__('Title')); ?></th>
                            <th><?php echo e(__('Property')); ?></th>
                            <th><?php echo e(__('Unit')); ?></th>
                            <th><?php echo e(__('Type')); ?></th>
                            <th><?php echo e(__('Date')); ?></th>
                            <th><?php echo e(__('Amount')); ?></th>
                            <th><?php echo e(__('Receipt')); ?></th>
                            <?php if(Gate::check('edit expense') || Gate::check('delete expense') || Gate::check('show expense')): ?>
                                <th class="text-right"><?php echo e(__('Action')); ?></th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr role="row">
                                <td><?php echo e(expensePrefix().$expense->expense_id); ?> </td>
                                <td> <?php echo e($expense->title); ?> </td>
                                <td> <?php echo e(!empty($expense->properties)?$expense->properties->name:'-'); ?> </td>
                                <td> <?php echo e(!empty($expense->units)?$expense->units->name:'-'); ?> </td>
                                <td> <?php echo e(!empty($expense->types)?$expense->types->title:'-'); ?> </td>
                                <td> <?php echo e(dateFormat($expense->date)); ?> </td>
                                <td> <?php echo e(priceFormat($expense->amount)); ?> </td>
                                <td>
                                    <?php if(!empty($expense->receipt)): ?>
                                        <a href="<?php echo e(asset(Storage::url('upload/receipt')).'/'.$expense->receipt); ?>"
                                           download="download"><i data-feather="download"></i></a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <?php if(Gate::check('edit expense') || Gate::check('delete expense') || Gate::check('show expense')): ?>
                                    <td class="text-right">
                                        <div class="cart-action">
                                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['expense.destroy', $expense->id]]); ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show expense')): ?>
                                                <a class="text-warning customModal" data-size="lg"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-original-title="<?php echo e(__('View')); ?>" href="#"
                                                   data-url="<?php echo e(route('expense.show',$expense->id)); ?>"
                                                   data-title="<?php echo e(__('Expense Details')); ?>"> <i
                                                        data-feather="eye"></i></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit expense')): ?>
                                                <a class="text-success customModal" data-size="lg"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-original-title="<?php echo e(__('Edit')); ?>" href="#"
                                                   data-url="<?php echo e(route('expense.edit',$expense->id)); ?>"
                                                   data-title="<?php echo e(__('Edit Expense')); ?>"> <i data-feather="edit"></i></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete expense')): ?>
                                                <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                   data-bs-original-title="<?php echo e(__('Detete')); ?>" href="#"> <i
                                                        data-feather="trash-2"></i></a>
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


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/propertymanagement/resources/views/expense/index.blade.php ENDPATH**/ ?>