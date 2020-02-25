@extends('layouts.admin')
@section('content')

    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            @if($order->id-1 != 0)
            <span class="mdi mdi-arrow-left display-6"  onclick="window.location.href='{{route('order.detail', $order->id)}}?type=previous'" ></span>
            @endif
                @if($order->id+1 <= count(\App\Order::all()))
                    <span class="mdi mdi-arrow-right display-6"  onclick="window.location.href='{{route('order.detail', $order->id)}}?type=next'" ></span>
                @endif
        </div>

    </div>
{{--    {{dd(session()->all())}}--}}
    <div class="row pl-5">
        <div class="col-sm-2 col-md-1">
            <h3 ><b>Order </b></h3>
        </div>
        <div class=" col-sm-4 col-md-3 row">
            <h3 ><b>{{ $order->name }}</b></h3>
            @if($order->has_designer != null)
                <div>
                    <span class="badge badge-pill ml-4" style="background: {{$order->has_designer->background_color}}; color: {{$order->has_designer->color}}">{{$order->has_designer->name}}</span>
                </div>
            @endif
        </div>
        <div class=" col-sm-8 col-md-3">
            <div class="dropdown">
                @if($order->has_additional_details != null)
                    @if($order->has_additional_details->status_id == 1)   <h5 style="cursor: pointer;padding: 10px;width: max-content" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="pt-1 pb-1 alerting">Not Completed <i class="m-l-5 fa fa-chevron-down"></i></h5> @elseif($order->has_additional_details->status_id == 2)   <h5 style="cursor: pointer ;padding: 10px;background: green;color: white;width:max-content "  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="pt-1 pb-1" >Completed <i class="m-l-5 fa fa-chevron-down"></i></h5> @endif </span>

                    <div  class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item text-primary change_status" data-type="order-inner" data-id="{{$order->id}}" data-route="{{route('admin.orders.change_status')}}" data-method="GET" data-status-id="1">Not Completed</a>
                        <a class="dropdown-item text-primary change_status" data-type="order-inner" data-id="{{$order->id}}" data-route="{{route('admin.orders.change_status')}}" data-method="GET" data-status-id="2">Completed</a>
                    </div>
                    @endif
            </div>
        </div>
    </div>
    <div class="row pt-4">
        <div class="col-sm-6  col-md-5">
            <div class="card">
                <div class="card-block">
                    <div class="flexing">
                        <div class="col-md-10">
                            <h6><b>Notes</b></h6>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit_notes">Edit</button>
                        </div>
                    </div>
                    <div class="flexing ">
                        @if($order->note)
                            <p>{{ $order->note }}</p>
                        @else
                            <p>No notes from customer</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-7" >
            <div class="row">
                <div class="col-md-4 offset-8 text-center">
                    <div class="pb-2">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 offset-6">
                    <div class="pb-2">
                        <button class="btn btn-rounded text-white btn-danger" type="button" data-toggle="modal" data-target="#send-mail"> Send Email Update</button>
{{--                        <button class="btn btn-rounded @if($order->sms_feature == 1) btn-green @else btn-grey @endif">Send SMS</button>--}}
                    </div>
                </div>
            </div>
            <div class="row pt-2">
                <div class="col-md-5 offset-6">
                    <div class="pb-2">
                        <button style="margin-top: 30px"  class="btn btn-md btn-rounded btn-blue btn-chat-open" data-notification="{{route('chat.notifications')}}" data-route="{{route('chat.get')}}" data-order_id="{{$order->id}}" {{--data-product="{{$product->id}}"--}} data-target="#chat_modal"> <b class="text-white">Customer Chat</b></button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="send-mail" tabindex="-1" role="dialog" aria-labelledby="add_background" aria-hidden="true">
                <div class="modal-dialog " role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="heading">
                                <h6><b>Confirmation to send the email update for Order {{$order->name}}!</b></h6>
                            </div>
                            <div class="mail-buttons">
                                <button data-dismiss="modal" aria-label="Close" class="btn btn-danger m-3">Not Now</button>
                                <button class="btn btn-primary m-3 send-email" data-dismiss="modal" aria-label="Close"o data-id="{{$order->id}}" data-route="{{route('email.send')}}">Send Email</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <button id="chat-notify" style="display: none" data-notification="{{route('chat.notifications')}}" data-order_id="{{$order->id}}"></button>

    <div class="row">
        <div class="col-md-12">
            <div class="card p-3">
                @foreach($order->has_products->reverse() as $index => $product)
                    <?php $product_index = $index;
                    $index = count($order->has_products) - $index -1;
                    ?>
                    <div class="row p-3">
                        <div class="col-sm-6 col-md-6 p-0 card">
                            <div class="card-header bg-lite" style="padding-bottom: 26px"> <b>Design: {{ $order->name }}_{{$index+1}}</b></div>
                            <div class="row">
                                <div class="col-sm-6 col-md-7 border-right">
                                    <div class="tittle p-3">
                                        <h5 ><b>{{ $product->name }}</b></h5>
                                    </div>

                                    <?php
                                    $properties = json_decode($product->properties, true);

                                    ?>
                                    @if($properties)
                                        @foreach($properties as $property)
                                            @if($property['name'] == 'Style' || $property['name'] == 'Style2')
                                                <div class="row m-3">
                                                    <h6 class="pt-1"> Style : </h6>
                                                    @if($product->has_changed_style != null)
                                                        <div class="pt-1 ml-2" style="background: {{$product->has_changed_style->color}}">
                                                            <h6 class="pr-2 pl-2 text-white pt-1"><b>{{ $product->has_changed_style->style }}</b> </h6>
                                                        </div>
                                                    @else
                                                        @if(count($categories) > 0)
                                                            @foreach($categories as $category)
                                                                @if($category->name ==  $property->value)
                                                                    <div class="pt-1 ml-2 " style="background:{{$category->color}}">
                                                                        <h6 class="pr-2 pl-2 text-white pt-1"><b>{{ $property->value}}</b> </h6>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <div class="pt-1 ml-2 btn-blue">
                                                                <h6 class="pr-2 pl-2 pt-1"><b>{{ $property->value}}</b> </h6>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            @endif

                                            @if($property['name'] == 'How Many Pets?')
                                                <div class="row m-3">
                                                    <h6 class="pt-1"> Pets : </h6>
                                                    <div class="pt-1 ml-2  ">
                                                        <h6 class="pr-2 pl-2 pt-1"><b>{{ $property['value'] }}</b> </h6>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if($properties)
                                        @foreach($properties as $property)
                                            @if($property['name'] == '_io_uploads' || $property['name'] == '_Uploaded Image')
                                                <div class="row  m-3">
                                                    <div class="col-sm-12 col-sm-6 justify-content-center" >
                                                        <a class="btn btn-rounded btn-purple"  target="_blank" href="{{ $property['value'] }}">Download Pet Photo</a>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif

                                </div>
                                @if($properties)
                                    @foreach($properties as $property)
                                        @if($property['name'] == '_io_uploads' || $property['name'] == '_Uploaded Image')
                                            <div class=" col-sm-6 col-md-5" align="center">
                                                <div class="mt-4 pr-2">
                                                    <img src="{{ $property['value'] }}" width="100%" height="auto" style="margin-bottom: 15px">
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif

                            </div>

                        </div>
                        <div class=" col-sm-6 col-md-6 p-0 card">
                            <div class="card-header bg-lite">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <button class="btn upload-design-button btn-rounded btn-success">Add Design</button>
                                    </div>
                                    <form style="display: none" class="upload-design" action="{{route('admin.order.line-item.design.upload')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input accept="image/*" style="opacity: 0" type="file" name="design" class="design-file" >
                                        <input type="hidden" name="order_id" value="{{$order->id}}">
                                        <input type="hidden" name="product_id" value="{{$product->id}}">
                                    </form>

                                    <div class="col-sm-6 col-md-6" align="right">
                                        <div class="">
                                            <div class="form-group" style="margin: 0">
                                                <select class="form-control style-change" name="style" style="margin: 0">
                                                    <option value="">--Change Style--</option>
                                                    @foreach($categories as $category)
                                                        <option @if($product->has_changed_style !=  null) @if($product->has_changed_style->category_id == $category->id) selected @endif @endif value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <form style="display: none" class="change_style_form" action="{{route('admin.order.line-item.change.style')}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="category" class="category_input">
                                                <input type="hidden" name="order_id" value="{{$order->id}}">
                                                <input type="hidden" name="product_id" value="{{$product->id}}">
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="flexing">
                                <div class="col-md-6">
                                    <div class="pb-3">
                                        @if($product->has_design != null)
                                            @if($product->has_design->design != null)
                                                <i style="top: 0;left: 94%;cursor: pointer;position: relative;z-index: 99;" onclick="window.location.href='{{route('admin.order.product.delete.design')}}?order={{$order->id}}&&product={{$product->id}}'" class=" delete-design mdi mdi-close-circle"></i>
                                                @if($product->has_background != null)
                                                    <div class="image-contain" style="@if($product->has_background != null)
                                                        background-image: url({{asset($product->has_background->image)}});
                                                    @else
                                                        background-image: url({{asset('material/background-images/Colorful.jpg')}});
                                                    @endif
                                                        background-repeat: no-repeat;
                                                        background-size: cover;
                                                        max-width: 400px;
                                                        margin: auto;
                                                        background-position: center center;
                                                        " >
                                                        @if($product->has_design != null)
                                                            @if($product->has_design->design != null)
                                                                <img  src="{{asset('designs/'.$product->has_design->design)}}" height="auto" width="100%">
                                                            @endif
                                                        @endif
                                                    </div>

                                                @else
                                                    @if($properties)
                                                        @php
                                                            $style = '';
                                                            if($product->has_changed_style !=  null){
                                                            $style = $product->has_changed_style->style;
                                                            }
                                                            else{
                                                               foreach ($properties as $property){
                                                            if($property['name'] == 'Style' || $property['name'] == 'Style2'){
                                                            $style = $property['value'];
                                                            }
                                                            }
                                                            }

                                                        @endphp
                                                        @foreach($categories as $cat)
                                                            @if($cat->name == $style)
                                                                @foreach($cat->has_backgrounds as $index => $b)
                                                                    @if($index == 0)
                                                                        <div class="image-contain" style="
                                                                            background-image: url({{asset($b->image)}});
                                                                            background-repeat: no-repeat;
                                                                            background-size: cover;
                                                                            max-width: 400px;
                                                                            margin: auto;
                                                                            background-position: center center;
                                                                            " >
                                                                            @if($product->has_design != null)
                                                                                @if($product->has_design->design != null)
                                                                                    <img  src="{{asset('designs/'.$product->has_design->design)}}" height="auto" width="100%">
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-center mt-5">
                                        @if($product->has_design != null)
                                            @if($product->has_design->status_id == 3)
                                                <div class="setting_div">
                                                    <span class="mdi mdi-settings text-white display-6"></span>
                                                </div>
                                                <h6 class="settings_iicon"><b>{{$product->has_design->status}}</b></h6>
                                            @elseif($product->has_design->status_id == 6)
                                                <div class="approved_div">
                                                    <span class="mdi mdi-check-circle-outline check_mark"></span>
                                                </div>
                                                <h6 class="approved"><b>{{$product->has_design->status}}</b></h6>
                                                <h6 class="approved"><b>{{date_create($product->approved_date)->format('Y-m-d')}}</b></h6>
                                            @elseif($product->has_design->status_id == 8)
                                                <div class="cir">
                                                    <span class="rec"></span>
                                                </div>
                                                <h6 class="not_completed"><b>{{$product->has_design->status}}</b></h6>
                                            @elseif($product->has_design->status_id == 7)
                                                <div class="update_div">
                                                    <span class="update_icon">!</span>
                                                </div>
                                                <h6 class="updating"><b>{{$product->has_design->status}}</b></h6>
                                            @endif

                                        @else
                                            <div class="cir">
                                                <span class="rec"></span>
                                            </div>
                                            <h6 class="not_completed"><b>No Design</b></h6>
                                        @endif

                                        @if(count($product->has_request_fixes) > 0)
                                            <div class="modal_button" data-target="#fix_request_modal{{$product_index}}">
                                                <div>
                                                    <span class="mdi mdi-file-check fix_request modal_button" data-target="#fix_request_modal{{$product_index}}"></span>
                                                </div>
                                                <h6 class="fix_text modal_button" data-target="#fix_request_modal{{$product_index}}"><b>FIX REQUEST</b></h6>
                                            </div>
                                        @endif

                                        @if(count($product->has_new_photos) > 0)
                                            <div class="dropdown">
                                                <h6 style="cursor: pointer;margin: 0" data-toggle="dropdown">
                                                    <span class="mdi mdi-camera photo"></span>
                                                </h6>
                                                <h6 style="cursor: pointer" class="photo_text" data-toggle="dropdown"><b>New Photo</b></h6>
                                                <div  class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item text-primary modal_button" data-target="#fix_request_modal{{$product_index}}">Preview</a>

                                                    @foreach($product->has_new_photos()->orderBy('created_at', 'desc')->get() as $index => $photo)
                                                        @if($index == 0)
                                                            <a class="dropdown-item text-primary"  target="_blank" href="{{ asset('new_photos/'.$photo->new_photo) }}">Download</a>
                                                        @endif
                                                    @endforeach

                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                        @if($product->design_count > 1)
                            @foreach($product->has_many_designs()->where('design','!=',$product->has_design->design)->get() as $index=> $design)
                                <div class=" col-sm-6 col-md-6 p-0 card" style="opacity: 0"></div>

                                <div class=" col-sm-6 col-md-6 p-0 card">
                                    <div class="card-header bg-lite" style="padding-bottom: 26px"> <b>Design: {{ $order->name }}_{{(count($order->has_products) - $product_index-1)+1}}</b>
                                        <button style="float: right" onclick="window.location.href='{{route('admin.order.product.extra.delete.design',$design->id)}}'" class="btn btn-sm btn-red"><i class="mdi mdi-close-circle"></i> Delete</button>
                                    </div>
                                    <div class="flexing">
                                        <div class="col-md-6">
                                            <div class="mt-4 pb-3">
                                                @if($design->has_background != null)
                                                    <div class="image-contain" style="@if($design->has_background != null)
                                                        background-image: url({{asset($design->has_background->image)}});
                                                    @else
                                                        background-image: url({{asset('material/background-images/Colorful.jpg')}});
                                                    @endif
                                                        background-repeat: no-repeat;
                                                        background-size: cover;
                                                        max-width: 400px;
                                                        margin: auto;
                                                        background-position: center center;
                                                        " >
                                                        <img  src="{{asset('designs/'.$design->design)}}" height="auto" width="100%">
                                                    </div>
                                                @else
                                                    @if($properties)
                                                        @php
                                                            $style = '';
                                                            if($product->has_changed_style !=  null){
                                                            $style = $product->has_changed_style->style;
                                                            }
                                                            else{
                                                               foreach ($properties as $property){
                                                            if($property['name'] == 'Style' || $property['name'] == 'Style2'){
                                                            $style = $property['value'];
                                                            }
                                                            }
                                                            }
                                                        @endphp
                                                        @foreach($categories as $cat)
                                                            @if($cat->name == $style)
                                                                @foreach($cat->has_backgrounds as $index => $b)
                                                                    @if($index == 0)
                                                                        <div class="image-contain" style="
                                                                            background-image: url({{asset($b->image)}});
                                                                            background-repeat: no-repeat;
                                                                            background-size: cover;
                                                                            max-width: 400px;
                                                                            margin: auto;
                                                                            background-position: center center;
                                                                            " >
                                                                            <img  src="{{asset('designs/'.$design->design)}}" height="auto" width="100%">
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="modal fade" id="fix_request_modal{{$product_index}}" tabindex="-1" role="dialog" aria-labelledby="add_background" aria-hidden="true">
                        <div class="modal-dialog " role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="row justify-content-center mt-4">
                                        <div class="col-md-12">
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
                                        </div>
                                    </div>
                                    <div class="row justify-content-center ">
                                        <div class="mail-buttons">
                                            <button class="btn btn-danger m-3" class="close" data-dismiss="modal" aria-label="Close"><i class="mdi mdi-close"></i> Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="modal fade" id="edit_notes" tabindex="-1" role="dialog" aria-labelledby="add_background" aria-hidden="true">
                <div class="modal-dialog " role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row justify-content-center mt-4">
                                <form action="{{route('order.notes.update')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{$order->id}}">
                                    <label for="Notes">Notes</label>
                                    <textarea id="Notes" required placeholder="Notes for Order {{$order->name}}" name="notes" class="form-control" cols="30" rows="4">{{$order->note}}</textarea>
                                    <input type="submit" class="btn btn-success" value="Save">
                                </form>
                            </div>
                            <div class="row justify-content-center ">
                                <div class="mail-buttons">
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
    </div>

@endsection
