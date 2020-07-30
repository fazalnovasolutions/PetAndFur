@extends('layouts.customer')

@section('content')
    <h4 class=" text-center" style="margin-top: 30px;"><b> Choose Your Background</b></h4>
    <div class="row ">
        <div class="col-md-4 ml-5">
            <div class="row justify-content-center">
                <h5 class="pt-1"> <b>Background style :</b> </h5>
                <div class="pt-1 ml-2" >
{{--                    <h6 class="pr-2 pl-2 pt-1 text-white"><b>{{$style}}</b> </h6>--}}

                    <select class="select">
                        @foreach($categories as $cat)
                            @if($cat->has_backgrounds)
                        <option value="{{$cat->id}}" >{{$cat->name}}</option>
                        @endif
                                @endforeach
                    </select>

                </div>
            </div>
        </div>

    </div>
    <div class="row mt-5">
        <input type="hidden" id="slick-count" value="{{count($category->has_backgrounds)-1}}">
        <div id="back-slider" class="pl-5 ">
            <!-- The slideshow -->
            <div class="custom-slider" id="slider-custom">
{{--                @foreach($category->has_backgrounds as $b)--}}
{{--                <div style="margin: 0px 20px; width: 85% !important;cursor: pointer" class="background-div">--}}
{{--                    <img data-id="{{$b->id}}" data-name="{{$b->name}}" src="{{asset($b->image)}}"  alt="Babe Pink">--}}
{{--                </div>--}}
{{--                    @endforeach--}}
            </div>
{{--            <div class="background_title">@if($product->has_background != null) {{$product->has_background->name}} @else--}}
{{--                    @foreach($category->has_backgrounds as $index=> $b)--}}
{{--                        @if($index == 0)--}}
{{--                            {{$b->name}}--}}
{{--                            @endif--}}
{{--                    @endforeach--}}
{{--                @endif</div>--}}

        </div>

    </div>
    <div class="row justify-content-center " >


       <div class="col-md-12 col-xs-12 col-sm-12  border-bottom-b-1 b-t-1">
           @if(session('success'))
               <div class="alert alert-success alert-dismissable" role="alert">
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
                   <p class="mb-0">{{session('success')}}</p>
               </div>
           @endif
           <div class="p-3" align="center">
               <button class="btn btn-rounded btn-success p-3 " style="margin:5px;" onclick="window.location.href='{{route('customer.check')}}'"> Go Back</button>
               <button class="btn btn-rounded btn-green  text-white p-3 "  style="margin:5px;" data-toggle="modal" data-target="#confirm-background">Approve artwork</button>
               </div>

       </div>
    </div>
    <div class="modal fade" id="confirm-background" tabindex="-1" role="dialog" aria-labelledby="add_background" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row justify-content-center mt-4">
                        <div class="">
                            <h6><b>Are you sure you want to save the background?</b></h6>
                            <form id="background_save_form" action="{{route('order.save.background')}}" method="POST">
                                @csrf
                                <input type="hidden" name="product" value="{{$product->id}}">
                                <input type="hidden" id="background-category" name="category" value="{{$product->background_id}}">
                            </form>
                        </div>
                    </div>
                    <div class="row justify-content-center ">
                        <div class="mail-buttons">
                            <button class="btn btn-success m-3" data-dismiss="modal" aria-label="Close"> <i class="mdi mdi-check"></i>  No </button>

{{--                            <button class="btn btn-success m-3 set-approved" data-id="{{$product->id}}"  data-target="#review-background" data-dismiss="modal" aria-label="Close"><i class="mdi mdi-check-circle font-bold" ></i> Confirm </button>--}}
                            <button class="btn btn-green text-white background_save_button m-3" ><i class="mdi mdi-check-circle font-bold" ></i> Confirm</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-md-12">
            <div class="image-contain" style="@if($product->has_background != null)
                background-image: url({{asset($product->has_background->image)}});
            @else
            @foreach($category->has_backgrounds as $index=> $b)
                @if($index == 0)
                background-image: url({{asset($b->image)}});
                @endif
                @endforeach
            @endif

                background-repeat: no-repeat;
                background-size: cover;
                max-width: 400px;
                margin: auto;
                background-position: center center;
                " >

                        <img  src="{{asset('designs/'.$product->has_design->design)}}" height="auto" width="100%">

            </div>
{{--            @if($product->has_background != null)--}}
{{--                <img id="design_background" src="{{asset($product->has_background->image)}}" width="945px">--}}
{{--                @else--}}
{{--                <img id="design_background" src="{{asset('material/background-images/Colorful.jpg')}}" width="945px">--}}
{{--                @endif--}}

{{--            @if($product->latest_photo == null)--}}
{{--                @if(count(json_decode($product->properties)) > 0)--}}
{{--                    @foreach(json_decode($product->properties) as $property)--}}
{{--                        @if($property->name == '_io_uploads')--}}
{{--                            <img id="design_image" src="{{$property->value}}" >--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
{{--                @endif--}}
{{--            @else--}}

{{--                <img  src="{{asset('new_photos/'.$product->latest_photo)}}">--}}
{{--            @endif--}}

        </div>

    </div>


    @endsection
