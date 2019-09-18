@extends('admin.layouts.app')
@section('head')
<style>
    .fields_wrapper{
        display: none;
    }
    .fields_wrapper--show{
        display: block;
    }
</style>
@endsection
@section('main-content') 
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet">
            <div class="row row-no-padding">
                <div class="col-md-4">
                    <div class="my-2 mx-4">
                        <label>Select Rider:</label>
                        <select class="form-control kt-select2" name="rider_id" class="rider_selector" >
                            @foreach ($riders as $rider)
                            <option value="{{ $rider->id }}">
                                {{ $rider->name }}
                            </option>     
                            @endforeach 
                        </select>
                            
                    </div>
                </div>

                <div class="col-md-6 offset-md-2">
                    <div class="mt-2 mx-4">
                        <label>Show result of:</label>
                        <div class="kt-radio-inline">
                            <label class="kt-radio">
                                    <input type="radio" data-start="{{Carbon\Carbon::now()->subMonths(1)->startOfMonth()->format('Y-m-d')}}" data-end="{{Carbon\Carbon::now()->subMonths(1)->endOfMonth()->format('Y-m-d')}}" name="sort_by" value="week" checked> Last Month
                                <span></span>
                            </label>
                            <label class="kt-radio">
                                <input type="radio" data-start="{{Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}" data-end="{{Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')}}" name="sort_by" value="month"> This Month
                                <span></span>
                            </label>  
                            <label class="kt-radio">
                                <input type="radio" data-start="{{Carbon\Carbon::now()->startOfYear()->format('Y-m-d')}}" data-end="{{Carbon\Carbon::now()->endOfYear()->format('Y-m-d')}}" name="sort_by" value="year"> This Year
                                <span></span>
                            </label>
                            <label class="kt-radio">
                                <input type="radio" name="sort_by" value="custom"> Custom
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mt-2">
                            <div class="kt-portlet__head-actions" id="custom_range" style="display:none">
                                <label for="dr1">Select range</label>
                                <input type="text" id="d1" name="dr1" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <div class="row row-no-padding row-col-separator-xl">
            {{-- <div class="col-md-12 col-lg-6 col-xl-6">
    
                <!--begin::New Orders-->
                <div class="kt-widget24">
                    <div class="kt-widget24__details">
                        <a href="#" class="kt-widget24__info">
                            <h4 class="kt-widget24__title">
                                Opening Balance
                            </h4>
                            
                            <span class="kt-widget24__stats kt-font-danger">
                                {{$opening_balance}}
                            </span>
                        </a>
                    </div>
                    
                </div>
    
                <!--end::New Orders-->
            </div> --}}
            <div class="col-md-12 col-lg-6 col-xl-6">
                <!--begin::New Users-->
                <div class="kt-widget24">
                    <div class="kt-widget24__details">
                        <a href="https://kingridersapp.solutionwin.net/admin/livemap" class="kt-widget24__info">
                            <h4 class="kt-widget24__title">
                                Closing Balance
                            </h4>
                            <span class="kt-widget24__stats kt-font-success" id="closing_balance">
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Rider Account
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar"> 
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="" class="btn btn-danger btn-elevate btn-icon-sm" data-toggle="modal" data-target="#cash_pay_modal" >
                            <i class="la la-money"></i>
                             Add Cash
                        </a>
                         &nbsp;
                        <a href="{{ route('admin.accounts.rider_expense_get') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a> 
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="cash_pay_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">Add Cash to Rider</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="kt-form" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="cash_rider_id">

                        <div class="form-group">
                            <label>Type:</label>
                            {{-- <input  autocomplete="off" list="model" class="form-control @if($errors->has('model')) invalid-field @endif" name="model"  > --}}
                            <select required class="form-control @if($errors->has('d_type')) invalid-field @endif kt-select2-general" name="d_type">
                                <option value="cash_paid">Cash Paid</option>
                                <option value="dr">Debit</option>
                            </select> 
                            @if ($errors->has('d_type'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('d_type')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Month:</label>
                        <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
                            @if ($errors->has('month'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('month') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter Month</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Amount:</label>
                            <input required type="number" step="0.01" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="">
                            @if ($errors->has('amount'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('amount')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Description:</label>
                            <textarea required class="form-control @if($errors->has('desc')) invalid-field @endif" name="desc" cols="3" rows="5"></textarea>
                            @if ($errors->has('desc'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('desc')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    {{-- <div class="modal-footer border-top-0 d-flex justify-content-center">
                    </div> --}}
                </form>
            </div>
            </div>
        </div>
        <div class="kt-portlet__body">
                <!--begin: Datatable -->
            <table class="table table-striped- table-hover table-checkable table-condensed" id="data-table">
                <thead>
                    <tr>
                        {{-- <th>
                            <input type="checkbox" id="select_all" >
                        </th> --}}
                        <th>Date</th>
                        <th>Description</th>
                        <th>Payable to Rider</th>
                        <th>Recieveable from Rider</th>
                        <th>Cash Paid</th> 
                        <th>Running Balance</th>
                    </tr>
                </thead>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
</div>
@endsection
@section('foot')
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
    
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    var table;
    $(function(){
        
        $('.kt-select2').select2({
            placeholder: "Select a rider",
            width:'100%'    
        });

        $('#cash_pay_modal form').on('submit', function(e){
            e.preventDefault();
            var _form = $(this);
            var _cta = _form.find('[type="submit"]');
            _cta.prop('disabled', true).addClass('btn-icon').html('<i class="fa flaticon2-refresh fa-spin"></i>');
            var url = '{{route('admin.accounts.rider_cash_add')}}';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'POST',
                data: _form.serializeArray(),
                success: function(data){
                    _cta.prop('disabled', false).removeClass('btn-icon').html('Submit');
                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record updated successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    table.ajax.reload(null, false);
                },
                error: function(error){
                    _cta.prop('disabled', false).removeClass('btn-icon').html('Submit');
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Unable to update.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });

        $('[name="rider_id"]').on('change', function(){
            var _riderId = $(this).val();
            $('[name="cash_rider_id"]').val(_riderId);
            var _SE = $('[name="sort_by"]:checked');
            var _SortBy = _SE.val();
            var start = _SE.attr('data-start'),
                end = _SE.attr('data-end');
            $('#custom_range').fadeOut('fast');
            if(_SortBy=='custom'){
                $('#custom_range').fadeIn('fast');
                start = $('[name="dr1"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
                end = $('[name="dr1"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
            }
            var _data = {
                range: {
                    start_date: start,
                    end_date: end
                },
                rider_id: _riderId
            };
            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                rider_id: _riderId,
                sort_by: $('[name="sort_by"]:checked').val()
            }
            
            console.log(data);
            biketrack.updateURL(data);
            var url = "{{ url('admin/accounts/rider/account/') }}"+"/"+JSON.stringify(_data) ;
            getData(url);
        });

        $('[name="sort_by"]').on('change', function(){
            var _SortBy = $(this).val();
            var start = $(this).attr('data-start'),
                end = $(this).attr('data-end');
            $('#custom_range').fadeOut('fast');
            if(_SortBy=='custom'){
                $('#custom_range').fadeIn('fast');
                return;
            }
            var _data = {
                range: {
                    start_date: start,
                    end_date: end
                },
                rider_id: $('[name="rider_id"]').val()
            };
            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                rider_id: $('[name="rider_id"]').val(),
                sort_by: $('[name="sort_by"]:checked').val()
            }
            
            console.log(data);
            biketrack.updateURL(data);
            var url = "{{ url('admin/accounts/rider/account/') }}"+"/"+JSON.stringify(_data) ;
            getData(url);
            // var _Url = "{{url('/company/debits/get_salary_deduction/')}}"+"/"+_riderId+''
        });

        $('input[name="dr1"]').daterangepicker({
            opens: 'left', 
            locale: {
                format: 'DD-MM-YYYY'
            }
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));

            
        }).on('apply.daterangepicker', function(ev, picker) {
            dpCallback(picker);
        });

        var dpCallback = function(picker){
            console.log(picker.startDate.format('YYYY-MM-DD'));
            console.log(picker.endDate.format('YYYY-MM-DD'));
            var _data = {
                range: {
                    start_date: picker.startDate.format('YYYY-MM-DD'),
                    end_date: picker.endDate.format('YYYY-MM-DD')
                },
                rider_id: $('[name="rider_id"]').val()
            };
            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                rider_id: $('[name="rider_id"]').val(),
                sort_by: $('[name="sort_by"]:checked').val()
            }
            
            console.log(data);
            biketrack.updateURL(data);
            var url = "{{ url('admin/accounts/rider/account/') }}"+"/"+JSON.stringify(_data) ;
            getData(url);
        }


        


        var getData = function(url){
            console.warn(url)
            table = $('#data-table').DataTable({
                lengthMenu: [[-1], ["All"]],
                destroy: true,
                ordering: false,
                processing: true,
                serverSide: true,
                'language': { 
                    'loadingRecords': '&nbsp;',
                    'processing': $('.loading').show()
                },
                drawCallback:function(data){
                    console.log(data);
                    var response = table.ajax.json();
                    console.log(response);
                    
                    var _ClosingBalance = 0;
                    if(response && typeof response.closing_balance !== "undefined")_ClosingBalance = response.closing_balance;
                    
                    $('#closing_balance').text(_ClosingBalance);
                },
                ajax: url,
                columns: [
                    { data: 'date', name: 'date' },            
                    { data: 'desc', name: 'desc' },
                    { data: 'cr', name: 'cr' },
                    { data: 'dr', name: 'dr' },
                    { data: 'cash_paid', name: 'cash_paid' },
                    { data: 'balance', name: 'balance' },
                    
                ],
                responsive:true,
            });
        }

        var r1d1=biketrack.getUrlParameter('r1d1');
        var r1d2=biketrack.getUrlParameter('r1d2');
        var rider_id=biketrack.getUrlParameter('rider_id');
        var sort_by=biketrack.getUrlParameter('sort_by');
        console.log(r1d1, r1d2, rider_id, sort_by);
        
        if(r1d1!="" && r1d2!="" && rider_id!="" && sort_by!=""){
            $('[name="sort_by"][value="'+sort_by+'"]').prop('checked', true);
            $('#custom_range').hide();
            if(sort_by=="custom"){
                $('#custom_range').fadeIn('fast');
                $('[name="dr1"]')
                .daterangepicker({ startDate: new Date(r1d1).format('mm/dd/yyyy'), endDate: new Date(r1d2).format('mm/dd/yyyy') })
                .on('apply.daterangepicker', function(ev, picker) {
                    dpCallback(picker);
                });
            }
            $('[name="rider_id"]').val(rider_id).trigger('change');
        }
        $('[name="sort_by"]:checked').trigger('change')
    })
    function updateStatus(id)
{
    var url = "{{ url('admin/rider/accounts') }}" + "/" + id + "/updateStatus";
    console.log(url,true);
    swal.fire({
        title: 'Are you sure?',
        text: "You want paid salary!",
        type: 'warning', 
        showCancelButton: true,
        confirmButtonText: 'Yes!'
    }).then(function(result) {
        if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'GET',
                beforeSend: function() {            
                    $('.loading').show();
                },
                complete: function(){
                    $('.loading').hide();
                },
                success: function(data){
                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record updated successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    table.ajax.reload(null, false);
                },
                error: function(error){
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Unable to update.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
}
</script>
@endsection