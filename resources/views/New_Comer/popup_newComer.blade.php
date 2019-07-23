<div ><button id="tooltip" data-tooltip-content="#tooltip_content">New Comers Details</button></div>



<html lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
     #name{
    margin-top:5px;
          }
     
     </style>
<link rel="stylesheet" type="text/css" href="{{ asset('css/tooltipster.bundle.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/tooltipster-sideTip-shadow.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/tooltipster-sideTip-borderless.min') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/tooltipster-sideTip-light.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/tooltipster-sideTip-noir.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/tooltipster-sideTip-punk.min.css') }}" />

 <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.js"></script>
 <script type="text/javascript" src="{{ asset('js/tooltipster.bundle.min.js') }}"></script>
 <script>
    $(document).ready(function() {
        $('#tooltip').tooltipster({
    trigger: 'click',
    theme: 'tooltipster-borderless'
 

});
$('#tooltip').tooltipster({
    contentCloning: true
});
    });
  
</script>
  </head>
  <body>
    <div class="container"  id="tooltip_content">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6  column col-sm-offset-0 col-md-offset-2 col-lg-offset-3">
 

    <!-- Form Name -->
                <legend>{{$newComer->name}}-Details</legend>
                


    <!-- Text input-->
    <div>
      <label class="col-md-4 control-label" >Name</label>  
      <div class="col-md-8" >
         <h6 id="name">{{$newComer->name}}</h6>  
      </div>
    </div>

    <div>
        <label class="col-md-4 control-label" >Phone Number</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->phone_number}}</h6>  
        </div>
      </div>

      <div>
        <label class="col-md-4 control-label" >Nationality</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->nationality}}</h6>  
        </div>
      </div>

      <div>
        <label class="col-md-4 control-label" >Source Of Contact</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->source_of_contact}}</h6>  
        </div>
      </div>

      <div>
        <label class="col-md-4 control-label" >Experience</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->experiance}}</h6>  
        </div>
      </div>

      <div>
        <label class="col-md-4 control-label" >Experience Input</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->experience_input}}</h6>  
        </div>
      </div>

      <div>
        <label class="col-md-4 control-label" >Passport Status</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->passport_status}}</h6>  
        </div>
      </div>

      <div>
        <label class="col-md-4 control-label" >Passport Reason</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->passport_reason}}</h6>  
        </div>
      </div>

    

      <div>
        <label class="col-md-4 control-label" >Kingriders Interview</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->kingriders_interview}}</h6>  
        </div>
      </div>

      <div>
        <label class="col-md-4 control-label" >Interview</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->interview}}</h6>  
        </div>
      </div>

      <div>
        <label class="col-md-4 control-label" >Interview Status</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->interview_status}}</h6>  
        </div>
      </div>

      <div>
        <label class="col-md-4 control-label" >Interview Date</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->interview_date}}</h6>  
        </div>
      </div>

      <div>
        <label class="col-md-4 control-label" >Interview By</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->interview_By}}</h6>  
        </div>
      </div>

      <div>
        <label class="col-md-4 control-label" >Joining Date</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->joining_date}}</h6>  
        </div>
      </div>

      <div>
        <label class="col-md-4 control-label" >Why Rejected</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->why_rejected}}</h6>  
        </div>
      </div>
      <div>
        <label class="col-md-4 control-label" >Overall Remarks</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->overall_remarks}}</h6>  
        </div>
      </div>
      <div>
        <label class="col-md-4 control-label" >Peiority</label>  
        <div class="col-md-8" >
           <h6 id="name">{{$newComer->priority}}</h6>  
        </div>
      </div>



</div>
</div>
    </div> 
</body>

</html>