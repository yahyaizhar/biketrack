@section('head')

@endsection
<form class="kt-form" action="{{ route('account.added_salary')}}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group">
        <label>Month:</label>
        <input type="text" id="datepicker" readonly class="form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
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
        <input type="text" readonly class="form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
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

</form>
@section('partial_foot')
    
<script>
    $(function(){
        console.log('from partisal');
        $('#datepicker').fdatepicker({ 
            format: 'MM yyyy', 
            startView:3,
            minView:3,
            maxView:4
        });
    });
</script>

@endsection