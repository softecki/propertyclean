@extends('layouts.app')
@section('page-title')
    {{__('Dashboard')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>

    </ul>
@endsection

@push('script-page')
    <script>
        var options = {

            series: [{
                name: "{{__('Income')}}",
                type: 'column',
                data: {!! json_encode($result['incomeExpenseByMonth']['income']) !!},
            }, {
                name: " {{__('Expense')}}",
                type: 'area',
                data: {!! json_encode($result['incomeExpenseByMonth']['expense']) !!},
            }],
            chart: {
                height: 452,
                type: 'line',
                toolbar:{
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            legend:{
                show:false
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: [0,0],
                curve: 'smooth',
            },
            plotOptions: {
                bar: {
                    columnWidth:"20%",
                    startingShape:"rounded",
                    endingShape: "rounded",
                }
            },
            fill:{
                opacity:[1, 0.08],
                gradient:{
                    type:"horizontal",
                    opacityFrom:0.5,
                    opacityTo:0.1,
                    stops: [100, 100, 100]
                }
            },
            colors: [Codexdmeki.themeprimary,Codexdmeki.themesecondary],
            states: {
                normal: {
                    filter: {
                        type: 'darken',
                        value: 1,
                    }
                },
                hover: {
                    filter: {
                        type: 'darken',
                        value: 1,
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'darken',
                        value: 1,
                    }
                },
            },
            grid:{
                strokeDashArray: 2,
            },

            yaxis:{
                tickAmount: 10 ,
                labels:{
                    formatter: function (y) {
                        return  "{{$result['settings']['CURRENCY_SYMBOL']}}" + y.toFixed(0);
                    },
                    style: {
                        colors: '#262626',
                        fontSize: '14px',
                        fontWeight: 500,
                        fontFamily: 'Roboto, sans-serif'
                    }
                },
            },
            xaxis: {
                categories: {!! json_encode($result['incomeExpenseByMonth']['label']) !!} ,
                axisTicks: {
                    show:false
                },
                axisBorder:{
                    show:false
                },
                labels:{
                    style: {
                        colors: '#262626',
                        fontSize: '14px',
                        fontWeight: 500,
                        fontFamily: 'Roboto, sans-serif'
                    },
                },
            },
            responsive:[
                {
                    breakpoint: 1441,
                    options:{
                        chart:{
                            height: 445
                        }
                    },
                },
                {
                    breakpoint: 1366,
                    options:{
                        chart:{
                            height: 320
                        }
                    },
                },
            ]
        };
        var chart = new ApexCharts(document.querySelector("#incomeExpense"), options);
        chart.render();
    </script>
@endpush
@php
$settings=settings();

@endphp
@section('content')
    <div class="row">
        <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
            <div class="card sale-revenue">
                <div class="card-header">
                    <h4>{{__('Total Property')}}</h4>
                </div>
                <div class="card-body progressCounter">
                    <h2>
                        <span class="count">{{$result['totalProperty']}}</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
            <div class="card sale-revenue">
                <div class="card-header">
                    <h4>{{__('Total Unit')}}</h4>
                </div>
                <div class="card-body progressCounter">
                    <h2>
                        <span class="count">{{$result['totalUnit']}}</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
            <div class="card sale-revenue">
                <div class="card-header">
                    <h4>{{__('Total Invoice')}}</h4>
                </div>
                <div class="card-body progressCounter">
                    <h2>{{$settings['CURRENCY_SYMBOL']}}<span class="count">{{$result['totalIncome']}}</span> </h2>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
            <div class="card sale-revenue">
                <div class="card-header">
                    <h4>{{__('Total Expense')}}</h4>
                </div>
                <div class="card-body progressCounter">
                    <h2>{{$settings['CURRENCY_SYMBOL']}}<span class="count">{{$result['totalExpense']}}</span> </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-12 cdx-xxl-50">
            <div class="card overall-revenuetbl">
                <div class="card-header">
                    <h4>{{__('Income Vs Expense')}}</h4>

                </div>
                <div class="card-body">
                    <div id="incomeExpense"></div>
                </div>
            </div>
        </div>

        <div class="col-xxl-12 cdx-xxl-50">
            <div class="card overall-revenuetbl">
                <div class="card-header">
                    <h4>{{__('Recently Transactions')}}</h4>

                </div>
                <div class="card-body">
                    <div>
                        <table class="table">
                            <thead>
                                <th>No</th>
                                <th>Tenant Name</th>
                                <th>Phone No</th>
                                <th>Space</th>
                                <th>Amount(TZS)</th>
                                <th>Date</th>
                            </thead>
                            <tbody class="tbody">
                                <tr>
                                    <td>1</td>
                                    <td>Joseph Michael</td>
                                    <td>0765448372</td>
                                    <td>Room 001, TFA Arusha</td>
                                    <td>2,400,000</td>
                                    <td>14/08/2024</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>John Michael</td>
                                    <td>0765448372</td>
                                    <td>Room 003, TFA Arusha</td>
                                    <td>4,400,000</td>
                                    <td>14/08/2024</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Moses Andrea</td>
                                    <td>0765448372</td>
                                    <td>Room 004, TFA Arusha</td>
                                    <td>3,400,000</td>
                                    <td>14/08/2024</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>GSM </td>
                                    <td>0765448372</td>
                                    <td>Room 005, TFA Arusha</td>
                                    <td>1,400,000</td>
                                    <td>14/08/2024</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
