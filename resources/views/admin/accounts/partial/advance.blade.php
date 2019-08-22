
<form class="kt-form" action="{{ route('admin.insert_company_debit')}}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
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
    <div class="form-group">
            <label>Total Amount:</label>
            <input type="text" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="">
            @if ($errors->has('amount'))
                <span class="invalid-response" role="alert">
                    <strong>
                        {{ $errors->first('amount') }}
                    </strong>
                </span>
            @else
                <span class="form-text text-muted">Enter your advance amount.</span>
            @endif
        </div>
        <div class="form-group">
                <label>How rider pay back?</label>
                <div class="kt-radio-inline">
                    <label class="kt-radio">
                        <input type="radio" name="advance_deducted_by" value="salary">Deduct from salary.
                        <span></span>
                    </label>
                    <label class="kt-radio">
                            <input type="radio" name="advance_deducted_by" value="cash">Pay it by cash.
                        <span></span>
                    </label>
                </div>
                
            </div>
            <div class="form-group">
                    <label>Other Notes:</label>
                    <textarea type="text" class="form-control @if($errors->has('advance_notes')) invalid-field @endif" rows="6" cols="8" name="advance_notes" placeholder="Enter Your Notes">
                    
                    </textarea>
                        @if ($errors->has('advance_notes'))
                        <span class="invalid-response" role="alert">
                            <strong>
                                {{ $errors->first('advance_notes') }}
                            </strong>
                        </span>
                    @else
                        <span class="form-text text-muted">Leave your notes.</span>
                    @endif
                </div>
                <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            {{-- <span class="kt-margin-l-10">or <a href="{{ route('admin.riders.index') }}" class="kt-link kt-font-bold">Cancel</a></span> --}}
                        </div>
                    </div>
    

    {{-- enter your own fields from here --}}
    

</form>
@section('partial_foot_advance')
    
<script>
    $(function(){
        console.log('from advance');
        
    });
</script>

@endsection