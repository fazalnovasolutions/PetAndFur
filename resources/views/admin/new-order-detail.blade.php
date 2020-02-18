@extends('layouts.admin')
@section('content')

    <div class="row m-t-15">
        <div class="col-sm-2 col-md-1">
            <h3 ><b>Order </b></h3>
        </div>
        <div class=" col-sm-6 col-md-6 row">
            <h3 ><b>{{ $order->name }}</b></h3>
            <div>
                <span class="badge badge-pill ml-4" style="background: {{$designer->background_color}}; color: {{$designer->color}}">{{$designer->name}}</span>
            </div>
            @if($exist == false)
            <button onclick="window.location.href='{{route('orders.sync.order',[
                                                'id' =>$order->id,
                                                'designer_id'=>$designer->id
                                                ])}}'"
                    class="btn btn-sm font-14 text-center justify-content-center btn-warning m-l-20">
                <i class="text-white font-20 mdi mdi-sync"></i> Sync New Order
            </button>
                @else
                <button class="btn btn-sm font-14 text-center justify-content-center btn-success m-l-20">
                    <i class="text-white font-20 mdi mdi-checkbox-marked-circle"></i> Synced
                </button>
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
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card p-3">
                @foreach($order->line_items as $index => $product)
                    <div class="row p-3">
                        <div class="col-sm-12 col-md-12 p-0 card">
                            <div class="card-header bg-lite" style="padding-bottom: 26px"> <b>Design: {{ $order->name }}_{{$index+1}}</b></div>
                            <div class="row">
                                <div class="col-sm-6 col-md-7 border-right">
                                    <div class="tittle p-3">
                                        <h5 ><b>{{ $product->name }}</b></h5>
                                    </div>

                                    @foreach($product->properties as $property)
                                        @if($property->name == 'Style')
                                            <div class="row m-3">
                                                <h6 class="pt-1"> Style : </h6>
                                                <div class="pt-1 ml-2 btn-blue ">
                                                    <h6 class="pr-2 pl-2 pt-1"><b>{{ $property->value}}</b> </h6>
                                                </div>
                                            </div>
                                        @endif

                                        @if($property->name == 'How Many Pets?')
                                            <div class="row m-3">
                                                <h6 class="pt-1"> Pets : </h6>
                                                <div class="pt-1 ml-2  ">
                                                    <h6 class="pr-2 pl-2 pt-1"><b>{{ $property->value }}</b> </h6>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach


                                    @foreach($product->properties as $property)
                                        @if($property->name == '_io_uploads')
                                            <div class="row  m-3">
                                                <div class="col-sm-12 col-sm-6 justify-content-center" >
                                                    <a class="btn btn-rounded btn-purple"  target="_blank" href="{{ $property->value }}">Download Pet Photo</a>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach

                                </div>


                                @foreach($product->properties as $property)
                                    @if($property->name == '_io_uploads')
                                        <div class=" col-sm-6 col-md-5" align="center">
                                            <div class="mt-4 pr-2">
                                                <img src="{{ $property->value }}" width="100%" height="auto" style="margin-bottom: 15px">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach


                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
