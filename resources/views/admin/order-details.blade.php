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
            <h6 class="pt-1"><b>Order </b></h6>
        </div>
        <div class=" col-sm-4 col-md-2 row">
            <h6 class="pt-1"><b>{{ $order->name }}</b></h6>
           <div>
               <span class="badge badge-pill bdg-success designer ml-4">Chesce</span>
           </div>
        </div>
        <div class=" col-sm-4 col-md-2">
            <div class="status" align="center">
                <h5 class="pt-1 pb-1 alerting">Not Completed</h5>
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
                            <a href="">Edit</a>
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

                @foreach($order->has_products as $product)
                    <div class="row p-3">
                        <div class="col-sm-6 col-md-6 p-0 card">
                            <div class="card-header bg-lite"> <b>Design: {{ $order->name }}_1</b></div>
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
                                        <div class="pt-1 ml-2 btn-blue ">
                                            <h6 class="pr-2 pl-2 pt-1"><b>{{ $property['value'] }}</b> </h6>
                                        </div>
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
                                            @if($property['name'] == 'uploads')
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
                                        @if($property['name'] == 'uploads')
                                <div class=" col-sm-6 col-md-5" align="right">
                                        <div class="mt-4 pr-2">
                                            <img src="{{ $property['value'] }}" width="100px" height="150px">
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
                                      <button class="btn btn-rounded btn-success">Add Design</button>
                                  </div>

                                   <div class="col-sm-6 col-md-6" align="right">
                                      <div class="">
                                                <div class="form-group">
                                                    <select class="form-control" name="style">
                                                        <option value="">--Change Style--</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                      </div>
                                   </div>
                               </div>

                            </div>
                            <div class="flexing">
                                <div class="col-md-6">
                                    <div class="mt-4 pb-3">
                                        <img src="{{asset('material/background-images/sketch.png')}}" width="120px" height="150px">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="text-center mt-5">
                                      <div class="approved_div">
                                          <span class="mdi mdi-check-circle-outline check_mark"></span>
                                      </div>
                                      <h6 class="text_active"><b>Approved</b></h6>
                                  </div>
                                </div>

                            </div>

                        </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
@endsection
