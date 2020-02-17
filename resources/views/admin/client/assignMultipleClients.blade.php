@extends('admin.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Assign Clients - {{ $rider->name }}
                        </h3>
                    </div>
                </div>
                @include('client.includes.message')
            <form class="kt-form" action="{{route('admin.insert_multiple_clients',$rider->id)}}" method="POST" enctype="multipart/form-data" id="multiple_clients">
                    {{-- {{ method_field('PUT') }} --}}
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Clients: </label>
                            <div>
                                @php
                                    $client_history=App\Model\Client\Client_History::where("rider_id",$rider->id)->where("status","active")->get();
                                @endphp
                                <select required class="form-control kt-select2 bk-select2 " id="kt_select2_3" name="clients[]" multiple="multiple">
                                @foreach ($clients as $client)
                                    @php
                                        $is_check=false;
                                    @endphp
                                    @foreach ($client_history as $item)
                                        @if ($item->client_id==$client->id)
                                            @php
                                                $is_check=true;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @if (!$is_check)
                                        <option value="{{ $client->id }}" data-client='{!! json_encode($client) !!}'>
                                            {{ $client->name }} 
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <div class="clients_html row"></div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('foot')
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
      $(document).ready(function(){
          $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'}); 
          $("#multiple_clients [name='clients[]']").on("change",function(e){
              console.log(e);
              $(".clients_html").html("");
              append_row();
          });
      });
      function append_row(){
        var q=[];
        $("#multiple_clients [name='clients[]'] option:selected").each(function(){
            var _val=$(this).val();
            var selected_op = $("#multiple_clients [name='clients[]']").find('option[value="'+_val+'"]');
            var client=JSON.parse(selected_op.attr('data-client'));
            var row_data={};
            row_data.id = _val;
            row_data.name = client.name;
            q.push(row_data);
        });
        console.log(q);
        Object.keys(q).forEach(function(i,j){
            markup='';
            if (q!=[]) {
                var client_id=q[i].id;
                var client_name=q[i].name;
                var data_month=new Date().format('mmm dd, yyyy');
                markup += '<div class="coloumn">  '  + 
'                               <input type="hidden" name="client_data['+j+'][client_id]" value="'+client_id+'"><div class="form-group col-md-12">  '  + 
 '                                   <label>Client:</label>  '  + 
 '                                   <input type="text" readonly class="form-control" name="client_data['+j+'][client_name]" placeholder="Enter Month" value="'+client_name+'">  '  + 
 '                               </div>  '  + 
 '                               <div class="form-group col-md-12">  '  + 
 '                                   <label>Date Client Assign to Rider:</label>  '  + 
 '                                   <input type="text" data-month="'+data_month+'" required readonly class="month_picker form-control" name="client_data['+j+'][assign_date]" placeholder="Enter Month" value="">  '  + 
 '                               </div>  '  + 
 '                               <div class="form-group col-md-12">  '  + 
 '                                   <label>How many days rider work for this client:</label>  '  + 
 '                                   <input type="number" required  class="form-control" name="client_data['+j+'][rider_working_days]" placeholder="Enter Days" value="">  '  + 
 '                               </div>  '  + 
 '                          </div>  ' ; 
            }
            $(".clients_html").append(markup);
            biketrack.refresh_global();
        });
      }
    </script>
@endsection