@extends('admin.layouts.app')
@section('head')

@endsection
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-hotel"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                       Edit Route detail
                    </h3>
                </div>
            </div>
        <form class="kt-form" method="POST" action="{{Route('admin.update_route',$webroute->id)}}">
            @csrf
            <input type="hidden" name="id" value="{{$webroute->id}}">
                <div class="kt-portlet__body">
                    <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" id="category" class="form-control" value="{{$webroute->category}}">
                    </div>
                    <div class="form-group">
                    <label>Label</label>
                    <input type="text" name="label" id="label" class="form-control" value="{{$webroute->label}}"> 
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control">
                            <option @if($webroute->type == 'view') Selected @endif value="view">View</option>
                            <option @if($webroute->type == 'add') Selected @endif value="add">Add</option>
                            <option @if($webroute->type == 'eud') Selected @endif value="eud">Edit/Update/Delete</option>
                            <option @if($webroute->type == 'others') Selected @endif value="others">Others</option>
                        </select>
                    </div>
                    <div class="form-group">
                    <label>Route name</label>
                    <input type="text" name="route_name" id="route_name" class="form-control" value="{{$webroute->route_name}}">
                    </div>
                    <div class="form-group"> 
                    <label>Route description</label>
                    <textarea  name="route_description" id="route_description" class="form-control">{{$webroute->route_description}}</textarea>
                    </div> 
                    <button class="btn btn-success" name="submit" type="submit">Submit</button>

                </div>
        </form>
        </div>
    </div>
@endsection
