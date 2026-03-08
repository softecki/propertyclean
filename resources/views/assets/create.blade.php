@extends('layouts.app')
@section('page-title')
    {{__('Tenant Create')}}
@endsection
@push('script-page')
    <script src="{{ asset('assets/js/vendors/dropzone/dropzone.js') }}"></script>
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
                url: "{{route('tenant.store')}}",
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
                        var url = '{{ route("tenant.index") }}';
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
            var url = '{{ route("property.unit", ":id") }}';
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
@endpush
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('tenant.index')}}">{{__('Contract')}}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Create')}}</a>
        </li>
    </ul>
@endsection
@section('content')
    {{Form::open(array('url'=>'contract','method'=>'post', 'enctype' => "multipart/form-data","id"=>"tenant_form"))}}
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{__('Contract Details')}}</h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                {{Form::label('tenant_name',__('Tenant Name'),array('class'=>'form-label'))}}
                                {{Form::text('tenant_name',null,array('class'=>'form-control','placeholder'=>__('Filbert Nyakunga')))}}
                            </div>

                            <div class="form-group col-lg-6 col-md-6">
                                {{Form::label('tenure',__('Lease Tenure'),array('class'=>'form-label'))}}
                                {{Form::text('tenure',null,array('class'=>'form-control','placeholder'=>__('Enter Lease Tenure')))}}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{Form::label('amount',__('Amount'),array('class'=>'form-label'))}}
                                {{Form::password('amount',array('class'=>'form-control','placeholder'=>__('Enter Amount')))}}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{Form::label('lease_rate',__('Lease Rate'),array('class'=>'form-label'))}}
                                {{Form::text('lease_rate',null,array('class'=>'form-control','placeholder'=>__('Enter Lease Rate')))}}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{Form::label('increments',__('Increments'),array('class'=>'form-label'))}}
                                {{Form::number('increments',null,array('class'=>'form-control','placeholder'=>__('Enter Increments')))}}
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{__('Other Details')}}</h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                {{Form::label('payment_cycle',__('Payment Cycle'),array('class'=>'form-label'))}}
                                {{Form::text('payment_cycle',null,array('class'=>'form-control','placeholder'=>__('Enter Payment Cycle')))}}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{Form::label('penalty',__('Penalty'),array('class'=>'form-label'))}}
                                {{Form::text('penalty',null,array('class'=>'form-control','placeholder'=>__('Enter Penalty')))}}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{Form::label('discount',__('Discount'),array('class'=>'form-label'))}}
                                {{Form::text('discount',null,array('class'=>'form-control','placeholder'=>__('Enter Discount')))}}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{Form::label('contract_status',__('Contract Status'),array('class'=>'form-label'))}}
                                {{Form::text('contract_status',null,array('class'=>'form-control','placeholder'=>__('Enter Contract Status')))}}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">


            </div>
        </div>

        <div class="col-lg-12">
            <div class="group-button text-end">
                {{Form::submit(__('Create'),array('class'=>'btn btn-primary btn-rounded','id'=>'tenant-submit'))}}
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection

