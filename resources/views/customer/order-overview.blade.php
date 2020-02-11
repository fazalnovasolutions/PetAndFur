@extends('layouts.customer')
@section('content')
    <p class="h3 text-center" style="margin-top: 30px;">Order <u>{{$order->name}}</u> Overview</p>
    <button id="chat-notify" style="display: none" data-notification="{{route('chat.notifications')}}" data-order_id="{{$order->id}}"></button>
    <div class="row">
        <div class="col-md-8  m-t-20" style="margin-left: auto;margin-right: auto;">
            <div class="row justify-content-center mt-3" >
                <div class="" >
                    <button class="btn btn-rounded btn-green"> <b class="text-white">Get updates by SMS</b></button>


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
                            <div class="card-header bg-lite d-inline-block">
                                <h5 class="d-inline-block" style="vertical-align: sub"><b>Design: {{$order->name}}_{{$index+1}} </b></h5>
                                <button style="float: right" class="btn btn-rounded btn-blue btn-chat-open" data-notification="{{route('chat.notifications')}}" data-route="{{route('chat.get')}}" data-order_id="{{$order->id}}" data-product="{{$product->id}}" data-target="#chat_modal"> <b class="text-white">Chat</b></button>
                            </div>
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
                                                    <div class="pt-1 ml-2" style="background: {{$product->has_changed_style->color}};">
                                                        <h6 class="pr-2 pl-2 pt-1" style="color: white !important;"><b>{{$product->has_changed_style->style}}</b> </h6>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif

                                <div class="row p-1 border-bottom-b-2">
                                    <h5 class="pt-3"> <b>Status :</b> </h5>
                                    @if($product->has_design != null)
                                        @if($product->has_design->status_id == 3)
                                            <div class="mr-1" style="margin-left: 20px;">
                                                <div class="setting_div">
                                                    <span class="mdi mdi-settings text-white display-6"></span>
                                                </div>
                                                <h6 class="text-dark"><b>{{$product->has_design->status}}</b></h6>
                                            </div>
                                        @elseif($product->has_design->status_id == 6)
                                            <div class="mr-1" style="margin-left: 20px;">
                                                <div class="approved_div">
                                                    <span class="mdi mdi-check-circle-outline check_mark"></span>
                                                </div>
                                                <h6 class="text_active"><b>{{$product->has_design->status}}</b></h6>
                                            </div>
                                        @elseif($product->has_design->status_id == 8)
                                            <div class="mr-1" style="margin-left: 20px;">
                                                <div class="cir">
                                                    <span class="rec"></span>
                                                </div>
                                                <h6 class="not_completed"><b>{{$product->has_design->status}}</b></h6>
                                            </div>
                                        @elseif($product->has_design->status_id == 7)
                                            <div class="mr-1" style="margin-left: 20px;">
                                                <div class="update_div">
                                                    <span class="update_icon">!</span>
                                                </div>
                                                <h6 class="updating"><b>{{$product->has_design->status}}</b></h6>
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
{{--                                        @if($product->latest_photo == null)--}}
                                            @if(count(json_decode($product->properties)) > 0)
                                                @foreach(json_decode($product->properties) as $property)
                                                    @if($property->name == '_io_uploads')
                                                        <img src="{{$property->value}}" height="auto" width="100%">
                                                    @endif
                                                @endforeach
                                            @endif
{{--                                        @else--}}
{{--                                            <img src="{{asset('new_photos/'.$product->latest_photo)}}" height="200px" width="200px">--}}
{{--                                        @endif--}}
                                    </div>
                                    <div class="col-md-6">
                                        @if($product->has_design != null)
                                            @if($product->has_design->design != null)
                                                <img src="{{asset('designs/'.$product->has_design->design)}}" height="auto" width="100%">
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <div class="row p-1 justify-content-center">
                                    @if($product->has_design->status != 'Approved')
                                        <button class="btn btn-danger m-2 new_photo_modal_button"  data-product="{{$product->id}}" data-target="#new_photo_upload">New Photo</button>
                                    @endif
                                    @if($product->has_design->status != 'Approved')
                                        <button class="btn btn-warning m-2 new_photo_modal_button" data-product="{{$product->id}}"  data-target="#fix_request_modal{{$index}}"> Request Fix</button>
                                    @endif
                                </div>

                                @if(($product->has_design->status == 'In-Processing'|| $product->has_design->status == 'Update')  && $product->has_design->status != 'Approved')
                                    <div class=" row p-1 justify-content-center">
                                        <a href="{{route('choose.background',$product->id)}}" class="btn btn-success"> Choose Background</a>
                                    </div>
                                @endif
                                @if($product->has_design->status != 'Approved' && $product->has_design->status != 'No Design')
                                    <div class=" row p-1 justify-content-center">
                                        {{--                            <button class="btn btn-success m-3 set-approved" data-id="{{$product->id}}"  data-target="#review-background" data-dismiss="modal" aria-label="Close"><i class="mdi mdi-check-circle font-bold" ></i> Confirm </button>--}}
                                        <button class="btn @if($product->background_id != null) btn-green set-approved @endif text-white" @if($product->background_id == null) disabled @else data-id="{{$product->id}}"  data-target="#review-background{{$index}}" @endif>Approved Your Design </button>
                                    </div>
                                    <div class="modal fade" id="review-background{{$index}}" tabindex="-1" role="dialog" aria-labelledby="add_background" aria-hidden="true">
                                        <div class="modal-dialog " role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">

                                                    <div align="center">
                                                        <div class="approved_div" >
                                                            <span class="mdi mdi-check-circle-outline check_mark"></span>
                                                        </div>
                                                        <h6 class="text_active"><b>Approved!</b></h6>

                                                    </div>
                                                    <div class="mt-2" align="center">
                                                        <h6><b>Rate Your Designer: </b></h6>
                                                    </div>
                                                    <div class="row justify-content-center">
                                                        <div class='rating-stars '>
                                                            <ul id='stars' style="margin-bottom: 5px">
                                                                <li class='star' title='Poor' data-value='1'>
                                                                    <i class='fa fa-star fa-fw'></i>
                                                                </li>
                                                                <li class='star' title='Fair' data-value='2'>
                                                                    <i class='fa fa-star fa-fw '></i>
                                                                </li>
                                                                <li class='star' title='Good' data-value='3'>
                                                                    <i class='fa fa-star fa-fw '></i>
                                                                </li>
                                                                <li class='star' title='Excellent' data-value='4'>
                                                                    <i class='fa fa-star fa-fw '></i>
                                                                </li>
                                                                <li class='star' title='WOW!!!' data-value='5'>
                                                                    <i class='fa fa-star fa-fw '></i>
                                                                </li>
                                                            </ul>

                                                        </div>
                                                    </div>
                                                    <form id="review_form" action="{{route('order.save.review')}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="product" value="{{$product->id}}">
                                                        <input type="hidden" name="rating" id="rating_input" value="">
                                                        <div class=" p-3" align="center">
                                                            <textarea class="form-control" name="review" rows="5"> </textarea>
                                                        </div>
                                                    </form>
                                                    <div class="row justify-content-center">
                                                        <button  class="btn btn-light close m-2" data-dismiss="modal" aria-label="Close"> No Thanks</button>
                                                        <button  class="btn btn-primary review-submit m-2"> Submit Review</button>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <div class="modal fade" id="fix_request_modal{{$index}}" tabindex="-1" role="dialog" aria-labelledby="add_background" aria-hidden="true">
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
                                                        <hr>
                                                        @foreach($product->has_new_photos as $photo)
                                                            <div class="container-msg">
                                                                <img src="{{ asset('new_photos/'.$photo->new_photo) }}" width="100%" height="auto" style="margin-bottom: 5px">
                                                                <div class="text-right">{{date_create($photo->created_at)->format('Y-m-d H:i a')}}</div>
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

        <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="add_background" aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">

                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-md-12 content-drop">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
