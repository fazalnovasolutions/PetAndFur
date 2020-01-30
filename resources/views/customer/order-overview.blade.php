@extends('layouts.customer')
@section('content')
    <p class="h3 text-center" style="margin-top: 30px;">Order <u>#2844</u> Overview</p>
    <div class="row">
        <div class="col-md-8  m-t-20" style="margin-left: auto;margin-right: auto;">
            <div class="row justify-content-center mt-3" >
                <div class="" >
                    <button class="btn btn-rounded btn-green"> <b class="text-white">Get updates by SMS</b></button>
                    <button class="btn btn-rounded btn-blue"> <b class="text-white">Chat with your designer</b></button>

                </div>

            </div>
            <div class="row justify-content-center mt-2">
                <div class="col-md-8">
                    <div class="card p-2">
                        <div class="card-header bg-lite"> <h5><b>Design: #2624_1</b></h5></div>
                        <div class="card-block">
                            <div class="row">
                                <h5> Custom Pet Canvas Large Size: 16" x 20 "</h5>
                            </div>
                            <div class="row p-1 ">
                                <h5 class="pt-1"> <b>Style :</b> </h5>
                                <div class="pt-1 ml-2 btn-blue ">
                                    <h6 class="pr-2 pl-2 pt-1"><b>Paint Splatter</b> </h6>
                                </div>
                            </div>

                            <div class="row p-1 border-bottom-b-2">

                                <h5 class="pt-3"> <b>Status :</b> </h5>
                               <div class="mr-1">
                                   <div class="approved_div">
                                       <span class="mdi mdi-check-circle-outline check_mark"></span>
                                   </div>
                                   <h6 class="text_active"><b>Approved</b></h6>
                               </div>

                                <div class="mr-1">
                                    <div class="cir">
                                        <span class="rec"></span>
                                    </div>
                                    <h6 class="not_completed"><b>No Design</b></h6>
                                </div>

                                <div class="mr-1">
                                    <div class="setting_div">
                                        <span class="mdi mdi-settings settings_icon"></span>
                                    </div>
                                    <h6 class="in_progress"><b>In Progress</b></h6>
                                </div>

                                <div class="row">
                                   <div class="col-md-12 align-self-end">
                                       <h6> <b>18.1.20</b></h6>
                                   </div>
                                </div>

                            </div>

                            <div class="row p-1 border-bottom-b-2">
                                <div class="col-md-6">
                                    <img src="{{asset('material/background-images/pet.jpg')}}" height="200px" width="200px">
                                </div>
                                <div class="col-md-6">
                                    <img src="{{asset('material/background-images/sketch.png')}}" height="200px" width="200px">

                                </div>

                            </div>

                            <div class="row p-1 justify-content-center">
                                <button class="btn btn-danger m-2"> New Photo</button>
                                <button class="btn btn-warning m-2"> Request Fix</button>
                            </div>

                            <div class=" row p-1 justify-content-center">
                                <a href="{{route('choose.background')}}" class="btn btn-success"> Choose Background</a>
                            </div>

                            <div class=" row p-1 justify-content-center">
                                <button class="btn btn-green text-white">Approved Your Design </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
    @endsection
