<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Tenant Create')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/vendors/dropzone/dropzone.js')); ?>"></script>
    <script>
        var dropzone = new Dropzone('#demo-upload', {
            previewTemplate: document.querySelector('.preview-dropzon').innerHTML,
            parallelUploads: 10,
            thumbnailHeight: 120,
            thumbnailWidth: 120,
            maxFilesize: 10,
            filesizeBase: 1000,
            autoProcessQueue: false,
            thumbnail: function (file, dataUrl) {
                if (file.previewElement) {
                    file.previewElement.classList.remove("dz-file-preview");
                    var images = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
                    for (var i = 0; i < images.length; i++) {
                        var thumbnailElement = images[i];
                        thumbnailElement.alt = file.name;
                        thumbnailElement.src = dataUrl;
                    }
                    setTimeout(function () {
                        file.previewElement.classList.add("dz-image-preview");
                    }, 1);
                }
            }

        });
        $('#tenant-submit').on('click', function () {
            "use strict";
            $('#tenant-submit').attr('disabled', true);
            var fd = new FormData();
            // var file = document.getElementById('profile').files[0];

            // var files = $('#demo-upload').get(0).dropzone.getAcceptedFiles();
            // $.each(files, function (key, file) {
            //     fd.append('tenant_images[' + key + ']', $('#demo-upload')[0].dropzone
            //         .getAcceptedFiles()[key]); // attach dropzone image element
            // });
            // fd.append('profile', file);
            var other_data = $('#tenant_form').serializeArray();
            $.each(other_data, function (key, input) {
                fd.append(input.name, input.value);
            });
            $.ajax({
                url: "<?php echo e(route('tenant.store')); ?>",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: fd,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data) {
                    if (data.status == "success") {
                        $('#tenant-submit').attr('disabled', true);
                        toastrs(data.status, data.msg, data.status);
                        var url = '<?php echo e(route("tenant.index")); ?>';
                        setTimeout(() => {
                            window.location.href = url;
                        }, "1000");

                    } else {
                        toastrs('Error', data.msg, 'error');
                        $('#tenant-submit').attr('disabled', false);
                    }
                },
                error: function (data) {
                    $('#tenant-submit').attr('disabled', false);
                    if (data.error) {
                        toastrs('Error', data.error, 'error');
                    } else {
                        toastrs('Error', data, 'error');
                    }
                },
            });
        });

        $('#property').on('change', function () {
            "use strict";
            var property_id=$(this).val();
            var url = '<?php echo e(route("property.unit", ":id")); ?>';
            url = url.replace(':id', property_id);
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    property_id:property_id,
                },
                contentType: false,
                processData: false,
                type: 'GET',
                success: function (data) {
                    $('.unit').empty();
                    var unit = `<select class="form-control hidesearch unit" id="unit" name="unit"></select>`;
                    $('.unit_div').html(unit);

                    $.each(data, function(key, value) {
                        $('.unit').append('<option value="' + key + '">' + value +'</option>');
                    });
                    $('.hidesearch').select2({
                        minimumResultsForSearch: -1
                    });
                },

            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>"><h1><?php echo e(__('Dashboard')); ?></h1></a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('tenant.index')); ?>"><?php echo e(__('Tenant')); ?></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Create')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php echo e(Form::open(array('url'=>'tenant','method'=>'post', 'files' => true, "id"=>"tenant_form"))); ?>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5><?php echo e(__('Personal Details')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('first_name',__('First Name'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::text('first_name',null,array('class'=>'form-control','placeholder'=>__('Enter First Name')))); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('last_name',__('Last Name'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::text('last_name',null,array('class'=>'form-control','placeholder'=>__('Enter Last Name')))); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('email',__('Email'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Email')))); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('password',__('Password'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter Password')))); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('phone_number',__('Phone Number'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::text('phone_number',null,array('class'=>'form-control','placeholder'=>__('Enter Phone Number')))); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('title',__('Title'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter Title')))); ?>

                            </div>
                            <div class="form-group">
                                <?php echo e(Form::label('bank_statement',__('Bank Statement'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::file('bank_statement',array('class'=>'form-control'))); ?>

                            </div>

                            <div class="form-group">
                                <?php echo e(Form::label('contract',__('Previous Lease Contract'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::file('contract',array('class'=>'form-control'))); ?>

                            </div>
                            <div class="form-group">
                                <?php echo e(Form::label('memorandum',__('Memorandum of Association'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::file('memorandum',array('class'=>'form-control'))); ?>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5><?php echo e(__('Other Details')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('business_name',__('Business Name'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::text('business_name',null,array('class'=>'form-control','placeholder'=>__('Enter Business name')))); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('business_number',__('Business Registration Number'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::text('business_number',null,array('class'=>'form-control','placeholder'=>__('Enter Business Registration Number')))); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('tax_payer_identification',__('Tax Payer Identification'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::text('tax_payer_identification',null,array('class'=>'form-control','placeholder'=>__('Enter Tax Payer Identification')))); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('contact_information',__('Contact Information'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::text('contact_information',null,array('class'=>'form-control','placeholder'=>__('Enter Contact Information')))); ?>

                            </div>
                            <div class="form-group ">
                                <?php echo e(Form::label('company_name',__('Company Name'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::textarea('company_name',null,array('class'=>'form-control','rows'=>5,'placeholder'=>__('Enter Company Name')))); ?>

                            </div>
                            <div class="form-group">
                                <?php echo e(Form::label('trading_licence',__('Valid Trading Licence'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::file('trading_licence',array('class'=>'form-control'))); ?>

                            </div>
                            <div class="form-group">
                                <?php echo e(Form::label('application_flow',__('Approval Flow of Application'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::file('application_flow',array('class'=>'form-control'))); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5><?php echo e(__('Property Details')); ?></h5>
                </div>
                <div class="card-body">
                        <div class="info-group">
                            <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('property',__('Property'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::select('property',$property,null,array('class'=>'form-control hidesearch','id'=>'property'))); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('unit',__('Unit'),array('class'=>'form-label'))); ?>

                                <div class="unit_div">
                                    <select class="form-control hidesearch unit" id="unit" name="unit">
                                        <option value=""><?php echo e(__('Select Unit')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('lease_start_date',__('Start Date'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::date('lease_start_date',null,array('class'=>'form-control','placeholder'=>__('Enter lease start date')))); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('lease_end_date',__('End Date'),array('class'=>'form-label'))); ?>

                                <?php echo e(Form::date('lease_end_date',null,array('class'=>'form-control','placeholder'=>__('Enter lease end date')))); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5><?php echo e(__('Documents')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="dropzone needsclick" id='demo-upload' action="#">
                        <div class="dz-message needsclick">
                            <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                            <h3><?php echo e(__('Drop files here or click to upload.')); ?></h3>
                        </div>
                    </div>
                    <div class="preview-dropzon" style="display: none;">
                        <div class="dz-preview dz-file-preview">
                            <div class="dz-image"><img data-dz-thumbnail="" src="" alt=""></div>
                            <div class="dz-details">
                                <div class="dz-size"><span data-dz-size=""></span></div>
                                <div class="dz-filename"><span data-dz-name=""></span></div>
                            </div>
                            <div class="dz-progress"><span class="dz-upload"
                                                           data-dz-uploadprogress="">                    </span></div>
                            <div class="dz-success-mark"><i class="fa fa-check" aria-hidden="true"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="group-button text-end">
                <?php echo e(Form::submit(__('Create'),array('class'=>'btn btn-primary btn-rounded','id'=>'tenant-submit'))); ?>

            </div>
        </div>
    </div>
    <?php echo e(Form::close()); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/propertymanagement/resources/views/tenant/create.blade.php ENDPATH**/ ?>