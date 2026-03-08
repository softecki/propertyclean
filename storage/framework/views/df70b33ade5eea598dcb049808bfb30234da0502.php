<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Invoice')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>"><h1><?php echo e(__('Dashboard')); ?></h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Invoice')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('card-action-btn'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create invoice')): ?>
        <a class="btn btn-primary btn-sm ml-20" href="<?php echo e(route('invoice.create')); ?>"> <i
                class="ti-plus mr-5"></i><?php echo e(__('Create Invoice')); ?></a>
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
                            <th><?php echo e(__('Invoice')); ?></th>
                            <th><?php echo e(__('Property')); ?></th>
                            <th><?php echo e(__('Unit')); ?></th>
                            <th><?php echo e(__('Invoice Month')); ?></th>
                            <th><?php echo e(__('End Date')); ?></th>
                            <th><?php echo e(__('Amount')); ?></th>
                            <th><?php echo e(__('Status')); ?></th>
                            <?php if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice')): ?>
                                <th class="text-right"><?php echo e(__('Action')); ?></th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr role="row">
                                <td><?php echo e(invoicePrefix().$invoice->invoice_id); ?> </td>
                                <td><?php echo e(!empty($invoice->properties)?$invoice->properties->name:'-'); ?> </td>
                                <td><?php echo e(!empty($invoice->units)?$invoice->units->name:'-'); ?>  </td>
                                <td><?php echo e(date('F Y',strtotime($invoice->invoice_month))); ?> </td>
                                <td><?php echo e(dateFormat($invoice->end_date)); ?> </td>
                                <td><?php echo e(priceFormat($invoice->getInvoiceSubTotalAmount())); ?></td>
                                <td>
                                    <?php if($invoice->status=='open'): ?>
                                        <span
                                            class="badge badge-primary"><?php echo e(\App\Models\Invoice::$status[$invoice->status]); ?></span>
                                    <?php elseif($invoice->status=='paid'): ?>
                                        <span
                                            class="badge badge-success"><?php echo e(\App\Models\Invoice::$status[$invoice->status]); ?></span>
                                    <?php elseif($invoice->status=='partial_paid'): ?>
                                        <span
                                            class="badge badge-warning"><?php echo e(\App\Models\Invoice::$status[$invoice->status]); ?></span>
                                    <?php endif; ?>
                                </td>
                                <?php if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice')): ?>
                                    <td class="text-right">
                                        <div class="cart-action">
                                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id]]); ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show invoice')): ?>
                                                <a class="text-warning" href="<?php echo e(route('invoice.show',$invoice->id)); ?>"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-original-title="<?php echo e(__('View')); ?>"> <i
                                                        data-feather="eye"></i></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit invoice')): ?>
                                                <a class="text-success" href="<?php echo e(route('invoice.edit',$invoice->id)); ?>"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-original-title="<?php echo e(__('Edit')); ?>"> <i data-feather="edit"></i></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete invoice')): ?>
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


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/propertymanagement/resources/views/invoice/index.blade.php ENDPATH**/ ?>