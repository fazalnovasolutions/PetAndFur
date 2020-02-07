@extends('layouts.admin')
@section('content')

    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">

            <span class="mdi mdi-arrow-left display-6"></span>
            <span class="mdi mdi-arrow-right display-6"></span>
        </div>

    </div>
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
        <div class=" col-sm-4 col-md-2">
            @if($order->has_additional_details != null)
                @if($order->has_additional_details->status_id == 1)
                    <div class="status" align="center">
                        <h5 class="pt-1 pb-1 alerting">Not Completed</h5>
                    </div>
                @else
                    <div class="status" align="center">
                        <h5 class="pt-1 pb-1" style="background: green;color: white">Completed</h5>
                    </div>
                @endif
            @endif
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

        <div class="col-sm-6 col-md-7 row justify-content-center" >
            <div class="align-self-end pb-4"  >
                <button class="btn btn-rounded btn-green">Send SMS</button>

                <button class="btn btn-rounded btn-success" type="button" id=""> Send Email Update</button>
                <button class="btn btn-rounded btn-blue">Customer Chat</button>
            </div>

            <button style="display: none" type="button" id="" data-toggle="modal" data-target="#send-mail"> </button>

            <div class="modal fade" id="send-mail" tabindex="-1" role="dialog" aria-labelledby="add_background" aria-hidden="true">
                <div class="modal-dialog " role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="heading">
                                <h6><b>Don't forget to send the email update!</b></h6>
                            </div>
                            <div class="mail-buttons">
                                <button class="btn btn-danger m-3">Not Now</button>
                                <button class="btn btn-primary m-3">Send Email</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card p-3">

                @foreach($order->has_products as $index => $product)
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
                                            @if($property['name'] == 'Style')
                                                <div class="row m-3">
                                                    <h6 class="pt-1"> Style : </h6>
                                                    @if($product->has_changed_style != null)
                                                        <div class="pt-1 ml-2" style="background: {{$product->has_changed_style->color}}">
                                                            <h6 class="pr-2 pl-2 text-white pt-1"><b>{{ $product->has_changed_style->style }}</b> </h6>
                                                        </div>
                                                    @else
                                                        <div class="pt-1 ml-2 btn-blue ">
                                                            <h6 class="pr-2 pl-2 pt-1"><b>{{ $property['value'] }}</b> </h6>
                                                        </div>
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
                                            @if($property['name'] == '_io_uploads')
                                                <div class="row  m-3">
                                                    <div class="col-sm-12 col-sm-6 justify-content-center" >
                                                        <a class="btn btn-rounded btn-purple"  target="_blank" href="{{ $property['value'] }}">Download Pet Photo</a>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif

                                </div>
                                {{--                                @if($product->latest_photo == null)--}}
                                @if($properties)
                                    @foreach($properties as $property)
                                        @if($property['name'] == '_io_uploads')
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
                                    <div class="mt-4 pb-3">
                                        @if($product->has_design != null)
                                            @if($product->has_design->design != null)
                                                <i style="float: right;cursor: pointer" onclick="window.location.href='{{route('admin.order.product.delete.design')}}?order={{$order->id}}&&product={{$product->id}}'" class=" delete-design mdi mdi-close-circle"></i>
                                                @if($product->has_background != null)
                                                    <img  id="design_background" src="{{asset($product->has_background->image)}}" width="100%" height="auto">
                                                    <img id="design_image" style="bottom: 16px !important;" src="{{asset('designs/'.$product->has_design->design)}}" width="100%" height="auto">

                                                @else
                                                    @if($properties)
                                                        @php
                                                            $style = '';
                                                            foreach ($properties as $property){
                                                            if($property['name'] == 'Style'){
                                                            $style = $property['value'];
                                                            }
                                                            }
                                                        @endphp
                                                        @foreach($categories as $cat)
                                                            @if($cat->name == $style)
                                                                @foreach($cat->has_backgrounds as $index => $b)
                                                                    @if($index == 0)
                                                                        <img  id="design_background" src="{{asset($b->image)}}" width="100%" height="auto">
                                                                        <img id="design_image" style="bottom: 16px !important;" src="{{asset('designs/'.$product->has_design->design)}}" width="100%" height="auto">
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
                                                <h6 class="text-dark"><b>{{$product->has_design->status}}</b></h6>
                                            @elseif($product->has_design->status_id == 6)
                                                <div class="approved_div">
                                                    <span class="mdi mdi-check-circle-outline check_mark"></span>
                                                </div>
                                                <h6 class="text_active"><b>{{$product->has_design->status}}</b></h6>
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
                                            <div class="modal_button" data-target="#fix_request_modal{{$index}}">
                                                <div>
                                                    <span class="mdi mdi-file-check fix_request modal_button" data-target="#fix_request_modal{{$index}}"></span>
                                                </div>
                                                <h6 class="fix_text modal_button" data-target="#fix_request_modal{{$index}}"><b>FIX REQUEST</b></h6>
                                            </div>
                                        @endif

                                        @if(count($product->has_new_photos) > 0)
                                            <div class="modal_button" data-target="#fix_request_modal{{$index}}">
                                                <div>
                                                    <span class="mdi mdi-camera photo modal_button" data-target="#fix_request_modal{{$index}}"></span>
                                                </div>
                                                <h6 class="photo_text modal_button" data-target="#fix_request_modal{{$index}}"><b>New Photo</b></h6>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="modal fade" id="fix_request_modal{{$index}}" tabindex="-1" role="dialog" aria-labelledby="add_background" aria-hidden="true">
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

        </div>
    </div>
@endsection
