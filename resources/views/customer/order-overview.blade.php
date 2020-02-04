@extends('layouts.customer')
@section('content')
    <p class="h3 text-center" style="margin-top: 30px;">Order <u>{{$order->name}}</u> Overview</p>
    <div class="row">
        <div class="col-md-8  m-t-20" style="margin-left: auto;margin-right: auto;">
            <div class="row justify-content-center mt-3" >
                <div class="" >
                    <button class="btn btn-rounded btn-green"> <b class="text-white">Get updates by SMS</b></button>
                    <button class="btn btn-rounded btn-blue"> <b class="text-white">Chat with your designer</b></button>

                </div>
            </div>
            @if(session()->has('msg'))
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert" style="margin: 0">{{session('msg')}}</div>
                    </div>
                </div>
            @endif
            <div class="row justify-content-center mt-2">
                <div class="col-md-8">
                    @foreach($order->has_products as $index => $product)
                        <div class="card p-2">
                            <div class="card-header bg-lite"> <h5><b>Design: {{$order->name}}_{{$index+1}} </b></h5></div>
                            <div class="card-block">
                                <div class="row">
                                    <h5> {{$product->title}} - {{$product->variant_title}}</h5>
                                </div>
                                @if(count(json_decode($product->properties)) > 0)
                                    @foreach(json_decode($product->properties) as $property)
                                        @if($property->name != '_io_uploads')
                                            @if($product->has_changed_style == null)
                                                <div class="row p-1 ">
                                                    <h5 class="pt-1"> <b>{{$property->name}} :</b> </h5>
                                                    <div class="pt-1 ml-2 btn-blue ">
                                                        <h6 class="pr-2 pl-2 pt-1"><b>{{$property->value}}</b> </h6>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row p-1 ">
                                                    <h5 class="pt-1"> <b>Style :</b> </h5>
                                                    <div class="pt-1 ml-2" style="background: {{$product->has_changed_style->color}};color: white">
                                                        <h6 class="pr-2 pl-2 pt-1"><b>{{$product->has_changed_style->style}}</b> </h6>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif

                                <div class="row p-1 border-bottom-b-2">
                                    <h5 class="pt-3"> <b>Status :</b> </h5>
                                    @if($order->has_design_details != null)
                                        @if($order->has_design_details->status_id == 4)
                                            <div class="mr-1" style="margin-left: 20px;">
                                                <div class="setting_div">
                                                    <span class="mdi mdi-settings text-white display-6"></span>
                                                </div>
                                                <h6 class="text-dark"><b>{{$order->has_design_details->status}}</b></h6>
                                            </div>
                                        @elseif($order->has_design_details->status_id == 7)
                                            <div class="mr-1" style="margin-left: 20px;">
                                                <div class="approved_div">
                                                    <span class="mdi mdi-check-circle-outline check_mark"></span>
                                                </div>
                                                <h6 class="text_active"><b>{{$order->has_design_details->status}}</b></h6>
                                            </div>
                                        @elseif($order->has_design_details->status_id == 9)
                                            <div class="mr-1" style="margin-left: 20px;">
                                                <div class="cir">
                                                    <span class="rec"></span>
                                                </div>
                                                <h6 class="not_completed"><b>{{$order->has_design_details->status}}</b></h6>
                                            </div>
                                        @elseif($order->has_design_details->status_id == 8)
                                            <div class="mr-1" style="margin-left: 20px;">
                                                <div class="update_div">
                                                    <span class="update_icon">!</span>
                                                </div>
                                                <h6 class="updating"><b>{{$order->has_design_details->status}}</b></h6>
                                            </div>
                                        @endif

                                    @else
                                        <div class="mr-1" style="margin-left: 20px;">
                                            <div class="cir">
                                                <span class="rec"></span>
                                            </div>
                                            <h6 class="not_completed"><b>No Design</b></h6>
                                        </div>
                                    @endif
                                </div>

                                <div class="row p-1 border-bottom-b-2">
                                    <div class="col-md-6">
                                        @if($product->latest_photo == null)
                                            @if(count(json_decode($product->properties)) > 0)
                                                @foreach(json_decode($product->properties) as $property)
                                                    @if($property->name == '_io_uploads')
                                                        <img src="{{$property->value}}" height="200px" width="200px">
                                                    @endif
                                                @endforeach
                                            @endif
                                        @else
                                            <img src="{{asset('new_photos/'.$product->latest_photo)}}" height="200px" width="200px">
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        @if($product->has_design != null)
                                            @if($product->has_design->design != null)
                                                <img src="{{asset('designs/'.$product->has_design->design)}}" height="200px" width="200px">
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <div class="row p-1 justify-content-center">
                                    @if($product->has_design->status != 'Approved')
                                        <button class="btn btn-danger m-2 new_photo_modal_button"  data-product="{{$product->id}}" data-target="#new_photo_upload">New Photo</button>
                                    @endif
                                    @if($product->has_design->status != 'Approved')
                                        <button class="btn btn-warning m-2 new_photo_modal_button" data-product="{{$product->id}}"  data-target="#fix_request_modal"> Request Fix</button>
                                    @endif
                                </div>

                                @if($product->has_design->status == 'In-Processing' && $product->has_design->status != 'Approved')
                                    <div class=" row p-1 justify-content-center">
                                        <a href="{{route('choose.background',$product->id)}}" class="btn btn-success"> Choose Background</a>
                                    </div>
                                @endif
                                @if($product->has_design->status != 'Approved' && $product->has_design->status != 'No Design')
                                    <div class=" row p-1 justify-content-center">
                                        <button class="btn btn-green text-white">Approved Your Design </button>
                                    </div>
                                @endif

                            </div>

                        </div>
                    @endforeach
                </div>

            </div>

        </div>
        <div class="modal fade" id="new_photo_upload" tabindex="-1" role="dialog" aria-labelledby="add_background" aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row justify-content-center mt-4">
                            <div class="col-md-12">
                                <form id="new_photo_upload_form" action="{{route('customer.order.new_photo')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="order" value="{{$order->id}}">
                                    <input type="hidden" name="product" value="" class="order_product_id">
                                    <div class="form-group">
                                        <div class="input-group input-file">
                                            <input type="file" accept="image/*" name="new_photo" class="new_photo_input" style="opacity: 0;display: none">
                                            <input type="text" class="form-control" placeholder='Choose a file...' />
                                            <span class="input-group-btn">
                                            <button class="btn btn-warning btn-choose" type="button">Browse</button>
                                        </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row justify-content-center ">
                            <div class="mail-buttons">
                                <button class="btn btn-success m-3 new_photo_upload_button"><i class="mdi mdi-check font-bold" ></i> Upload</button>
                                <button class="btn btn-danger m-3" class="close" data-dismiss="modal" aria-label="Close"><i class="mdi mdi-close"></i> Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="fix_request_modal" tabindex="-1" role="dialog" aria-labelledby="add_background" aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row justify-content-center mt-4">
                            <div class="col-md-12">
                                <form id="fix_request_form" action="{{route('customer.order.request')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="order" value="{{$order->id}}">
                                    <input type="hidden" name="product" value="" class="order_product_id">
                                    <div class="requests">
                                        @foreach($product->has_request_fixes as $request)
                                        <div class="container-msg">
                                            <p>{{$request->msg}}</p>
                                            <div class="text-right">{{date_create($request->created_at)->format('Y-m-d H:i a')}}</div>
                                        </div>
                                            @endforeach
                                    </div>
                                    <div class="textarea-container">
                                        <textarea required style="width: 100%;" class="form-control request_fix" name="request_fix" id="" placeholder="Write Your Fix Request"></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row justify-content-center ">
                            <div class="mail-buttons">
                                <button class="btn btn-success m-3 request_upload_button"><i class="mdi mdi-check font-bold" ></i> Send </button>
                                <button class="btn btn-danger m-3" class="close" data-dismiss="modal" aria-label="Close"><i class="mdi mdi-close"></i> Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
