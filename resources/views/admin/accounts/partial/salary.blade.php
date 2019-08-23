@php
    $total_salary=27000;
    
@endphp
<form class="kt-form" action="{{ route('admin.insert_company_debit')}}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group">
        <label>Month:</label>
        <input type="text" readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
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

    {{-- enter your own fields from here --}}
    <div class="form-group">
        <label>Rider:</label>
        <select class="form-control kt-select2" name="rider_id" >
            @foreach ($riders as $rider)
            <option value="{{ $rider->id }}">
                {{ $rider->name }}
            </option>     
            @endforeach 
        </select>
            
    </div>
    <div class="form-group">
        <label>Total Salary:</label>
        <input type="text" class="form-control @if($errors->has('month')) invalid-field @endif" name="total_salary" value="{{$total_salary}}">
        @if ($errors->has('total_salary'))
            <span class="invalid-response" role="alert">
                <strong>
                    {{ $errors->first('total_salary') }}
                </strong>
            </span>
        @else
            <span class="form-text text-muted">Total salary</span>
        @endif
            
    </div>
    <div class="form-group">
        
            
    </div>
    <div class="form-group">
        <label>Gross Salary:</label>
        <input type="text" class="form-control @if($errors->has('month')) invalid-field @endif" name="gross_salary" value="">
        @if ($errors->has('gross_salary'))
            <span class="invalid-response" role="alert">
                <strong>
                    {{ $errors->first('gross_salary') }}
                </strong>
            </span>
        @else
            <span class="form-text text-muted">Gross salary</span>
        @endif
            
    </div>
    <div class="form-group">
        <label>Salary Recieved:</label>
        <input type="text" class="form-control @if($errors->has('month')) invalid-field @endif" name="recieved_salary" value="">
        @if ($errors->has('recieved_salary'))
            <span class="invalid-response" role="alert">
                <strong>
                    {{ $errors->first('recieved_salary') }}
                </strong>
            </span>
        @else
            <span class="form-text text-muted">Recieved salary</span>
        @endif
            
    </div>
    <div class="form-group">
        <label>Salary Remaining:</label>
        <input type="text" class="form-control @if($errors->has('month')) invalid-field @endif" name="remaining_salary" value="">
        @if ($errors->has('remaining_salary'))
            <span class="invalid-response" role="alert">
                <strong>
                    {{ $errors->first('remaining_salary') }}
                </strong>
            </span>
        @else
            <span class="form-text text-muted">Remaining salary</span>
        @endif
            
    </div>

    <div class="kt-portlet__foot">
        <div class="kt-form__actions kt-form__actions--right">
            <button type="submit" class="btn btn-primary">Submit</button>
            {{-- <span class="kt-margin-l-10">or <a href="{{ route('admin.riders.index') }}" class="kt-link kt-font-bold">Cancel</a></span> --}}
        </div>
    </div>

</form>
@section('partial_foot_salary')
    
<script>
    $(function(){
        console.log('from partial salary');
        $('#salary [name="recieved_salary"]').on('change input', function(){
            var _gross_salary = parseFloat($('#salary [name="gross_salary"]').val().trim());
            var _recieved_salary = parseFloat($(this).val().trim());
            $('#salary [name="remaining_salary"]').val(_gross_salary-_recieved_salary);
        });
       $('#salary [name="rider_id"],#salary [name="month"]').on('change', function(){
           var _riderid = $('[name="rider_id"]').val();
           var _month = $('[name="month"]').val();
           
           if(_riderid==''||_month=='')return;
           _month = new Date(_month).format('m');
           console.log(_riderid, _month);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                url:"{{url('admin/accounts/company/debits/get_salary_deduction/')}}"+'/'+_month+'/'+_riderid,
                method: "GET"
            })
            .done(function(data) {  
                console.log(data);
                $('#salary [name="gross_salary"], #salary [name="recieved_salary"]').val(data.gross_salary).trigger('change');

            });
       });
    });
</script>

@endsection