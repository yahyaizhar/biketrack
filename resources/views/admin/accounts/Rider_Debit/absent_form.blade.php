@extends('admin.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Absent Form
                        </h3>
                    </div>
                </div>
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('account.absent_detail_store') }}" method="POST" enctype="multipart/form-data" id="absent_detail">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Select Rider:</label>
                            <select required class="form-control kt-select2-general" name="rider_id" >
                                @foreach ($riders as $rider)
                                @php
                                    $client_rider_id=App\Model\Client\Client_History::where("rider_id",$rider->id)->get()->first();
                                    echo $client_rider_id;
                                @endphp
                                <option value="{{ $rider->id }}">
                                    KR{{$rider->id}} {{ $rider->name }} ({{$client_rider_id['client_rider_id']}})
                                </option>     
                                @endforeach 
                            </select>  
                        </div>
                        <div class="form-group">
                            <label>Absent Date:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('absent_date')) invalid-field @endif" name="absent_date" placeholder="Enter Absent Date" value="">
                            <span class="form-text text-muted">Please enter absent Date</span>
                        </div>
                        <div class="form-group">
                            <label>Absent Reason:</label>
                            <textarea class="form-control" name="absent_reason" placeholder="Reason Behind your Leave"></textarea>
                            <span class="form-text text-muted">Please enter your reason behind your leave</span>
                        </div>
                        <div class="form-group">
                            <label>Email Sent to Client?</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" value="yes" name="email_sent"> Yes
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" value="no" name="email_sent"> No
                                    <span></span>
                                </label>
                            </div>
                            <span class="form-text text-muted">Did you emailed client about your leave.</span>
                        </div>
                        <div class="form-group">
                            <label>Approval Status</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" value="accepted" name="approval_status"> Accepted
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" value="rejected" name="approval_status"> Rejected
                                    <span></span>
                                </label>
                            </div>
                            <span class="form-text text-muted">Approval Status</span>
                        </div>
                        <div class="form-group">
                            <div class="custom-file" style="margin-top: 26px;">
                                <input type="file" name="document_image" class="custom-file-input" id="document_image">
                                <label class="custom-file-label" for="document_image">Choose Attachment</label>
                                <span class="form-text text-muted">Choose Attachment</span>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                        <div class="kt-form__actions ">
                            <span class="payout_message text-danger" style="font-size: 35px;"></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('foot')
    
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $(document).ready(function(){
      $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'});
      $('#absent_detail [name="absent_date"]').on("change",function(){
        var _date=$(this).val();
        var absent_month=new Date(_date).format("mm");
        var absent_date=new Date(_date).format("yyyy-mm-dd");
        var rider_id=$('#absent_detail [name="rider_id"]').val();
        var _Url = "{{url('admin/check_payout')}}"+"/"+absent_month+"/"+rider_id+"/"+absent_date;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : _Url,
                type : 'GET',
                success: function(data){
                    $(".payout_message").html("");
                    $('#absent_detail [type="submit"]').prop("disabled",false);
                    if(data.is_payout=="1"){
                    $('#absent_detail [type="submit"]').prop("disabled",false);
                    if (data.day_status=="present") {
                        $(".payout_message").html("Rider is present on this day ");
                        $('#absent_detail [type="submit"]').prop("disabled",true);
                    }
                    if (data.day_status=="weeklyoff") {
                        $(".payout_message").html("Rider has a weekly off ");
                        $('#absent_detail [type="submit"]').prop("disabled",true);
                    }
                    if (data.day_status=="extraday") {
                        $(".payout_message").html("Rider have an extraday ");
                        $('#absent_detail [type="submit"]').prop("disabled",true);
                    }
                    }
                    if(data.is_payout=="0"){
                    $('#absent_detail [type="submit"]').prop("disabled",false);
                    $(".payout_message").html("Payout against this Rider is not imported");
                    }
                }
            });
      });
      $('#absent_detail [name="absent_date"]').trigger("change");
  });

</script>
@endsection