@php
    $total_salary=27000;
    $total_deduction = 0;
    $salary_deducted = \App\Model\Expense\Company_CD::whereMonth('month', 7)
    ->where(['type' => 'dr', 'type_db' =>'advance', 'advance_deducted_by'=>'salary'])
    ->sum('amount');
    $total_deduction+=$salary_deducted;

    $gross_salary = $total_salary - $total_deduction;
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
        <input type="text" class="form-control @if($errors->has('month')) invalid-field @endif" name="gross_salary" value="{{$gross_salary}}">
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
       
    });
</script>

@endsection