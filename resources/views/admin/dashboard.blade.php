@extends('layouts.admin')
@section('content')

    <div class="row page-titles">
        <div class="col-md-9 col-8 align-self-center">
            <h3><strong>Management</strong></h3>

        </div>
        <div class="col-md-3 " align="center">
            <button class="btn btn-rounded btn-purple " type="button" id="" data-toggle="modal" data-target="#exampleModal">
                Add Designer
            </button>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Designer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.designer.save') }}" method="POST">
                    <div class="modal-body">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input class="form-control" name="name" type="text" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Text Color</label>
                                        <input class="form-control" name="color" type="color" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Background Color</label>
                                        <input class="form-control" name="background_color" type="color" required>
                                    </div>
                                </div>

                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Save changes</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 pl-5 pt-2 pr-0">
                    <h4>Manualy designer picker </h4>
                    <div class="row ">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="pr-4">Designer</span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item change_designer" >Chesce</a>
                                <a class="dropdown-item change_designer" >Maria</a>
                                <a class="dropdown-item change_designer" >Asaf</a>
                                <a class="dropdown-item change_designer" >Ihsan</a>
                                <a class="dropdown-item change_designer">Dhy</a>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="pr-4">Start Order</span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item change_designer" >Chesce</a>
                                <a class="dropdown-item change_designer" >Maria</a>

                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="pr-4">End Order</span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item change_designer" >Chesce</a>
                                <a class="dropdown-item change_designer" >Maria</a>

                            </div>
                        </div>
                        <div>
                            <button class="btn btn-secondary">Apply</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 pl-0">
                    <div class="row">
                        <div class="col-md-4 pl-0">
                            <div class="form-group">
                                <label><b>Starting</b></label>
                                <input class="form-control" type="date">
                            </div>
                        </div>
                        <div class="col-md-4 pr-0">
                            <div class="form-group">
                                <label><b>Ending</b></label>
                                <input class="form-control" type="date">
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>

    </div>

    @foreach($designers as $designer)
    <div class="pl-5">
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-6">
                    <span class="badge badge-pill bdg-sucss mt-1" style="border-color:{{ $designer->background_color }};background-color:{{ $designer->background_color }};color:{{ $designer->color }};">{{ $designer->name }}</span>
                </div>
                <div class="col-md-3">
                    <label class="switching">
                        <input type="checkbox" >
                        <span class="slides rounds"></span>
                        {{--Active--}}
                    </label>
                </div>
              <div class="col-md-3 pt-2">
                  <h6 class="status text_active"><b>Active</b></h6>
              </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <h5 class="card-header"><b>Reviews</b></h5>
                    <div class="card-block" >
                        <div class="row">
                            <div class="col-md-8" align="right">
                                <span class="fa fa-star fa-b checked"></span>
                                <span class="fa fa-star fa-b checked"></span>
                                <span class="fa fa-star  fa-b checked"></span>
                                <span class="fa fa-star fa-b checked"></span>
                                <span class="fa fa-star fa-b "></span>
                            </div>
                            <div class="col-md-4">
                                <h4 class="rating"><b>(4.8)</b></h4>
                            </div>
                        </div>
                        <div class="row mt-5" align="center">
                            <div class="col-md-12" align="center">
                                <div class="dropdown">
                                    <button class="btn btn-rounded btn-blue text-center text-white" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="">Designer</span>
                                    </button>
                                    <div class="dropdown-menu edit_menu" aria-labelledby="dropdownMenuButton">
                                        <div class="row ">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star"></span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6>1 hour ago</h6>
                                                    </div>
                                                    <div class="col-md-4 row">
                                                        <span class="mdi mdi-checkbox-marked-circle"></span>
                                                        <h6 class="text-primary p-1">Johnsons</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 ">
                                                <h6 class="pt-3 pb-3 pl-3">Recommended to all my friends pass</h6>
                                                {{--<span class="border-bottom"></span>--}}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row ">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star"></span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6>1 hour ago</h6>
                                                    </div>
                                                    <div class="col-md-4 row">
                                                        <span class="mdi mdi-checkbox-marked-circle"></span>
                                                        <h6 class="text-primary p-1">Johnsons</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 ">
                                                <h6 class="pt-3 pb-3 pl-3">Recommended to all my friends pass</h6>
                                                {{--<span class="border-bottom"></span>--}}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row ">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star"></span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6>1 hour ago</h6>
                                                    </div>
                                                    <div class="col-md-4 row">
                                                        <span class="mdi mdi-checkbox-marked-circle"></span>
                                                        <h6 class="text-primary p-1">Johnsons</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 ">
                                                <h6 class="pt-3 pb-3 pl-3">Recommended to all my friends pass</h6>
                                                {{--<span class="border-bottom"></span>--}}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row ">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star"></span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6>1 hour ago</h6>
                                                    </div>
                                                    <div class="col-md-4 row">
                                                        <span class="mdi mdi-checkbox-marked-circle"></span>
                                                        <h6 class="text-primary p-1">Johnsons</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 ">
                                                <h6 class="pt-3 pb-3 pl-3">Recommended to all my friends pass</h6>
                                                {{--<span class="border-bottom"></span>--}}
                                            </div>
                                        </div>
                                        <hr>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <h5 class="card-header"><b>Designs</b></h5>
                       <div class="card-block ">
                           <div class="design-orders" align="center">
                               <h3><b>21.2 Orders Per Day</b></h3>
                           </div>
                       </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @endsection
