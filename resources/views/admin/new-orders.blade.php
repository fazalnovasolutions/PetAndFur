@extends('layouts.admin')
@section('content')
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3><strong>New Orders</strong></h3>
        </div>
                <div class="col-md-7 text-right">
                    <a class="btn btn-warning" href="{{route('orders.sync')}}"> Sync All New Orders </a>
                </div>
    </div>
    <div class="row">
        <div class="col-md-2 pl-0">
            <div class="flexing">
                <div class=" dropdowns actionbox">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="pr-5">Actions</span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" >Sync</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="orders">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table v-middle" id="order_table">
                            <thead>
                            <tr class="bg-light ">
                                <th class="border-top-0" style="text-align: left !important;">ID</th>
                                <th class="border-top-0" style="text-align: left !important;">Date</th>
                                <th class="border-top-0" style="text-align: left !important;">Email</th>
                                <th class="border-top-0" style="text-align: left !important;">Amount</th>
                                <th class="border-top-0">Action</th>
                                <th class="border-top-0">Order Status</th>
                            </tr>

                            </thead>
                            <tbody id="myTable">
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        {{ $order->name }}
                                    </td>
                                    <td> {{ date_create($order->created_at)->format('Y-m-d H:i a') }}</td>
                                    <td >{{ $order->email }}</td>
                                    <td> {{$order->total_price}} {{$order->currency}}</td>
                                    <td align="center">
                                        @if($order->sync == 'no')
                                            <button onclick="window.location.href='{{route('orders.sync.order',$order->id)}}'" class="btn btn-sm btn-warning "><i class="text-white font-16 mdi mdi-sync"></i></button>
                                        @endif
                                    </td>
                                    @if($order->sync == 'yes')
                                    <td align="center" class="order-status-td" style="background: green;color:#ffff">
                                        <div class="dropdown">
                                            <span style="cursor: pointer;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Synchronised </span>
                                        </div>
                                    </td>
                                    @else
                                        <td align="center" class="order-status-td not-synced" style="background: red;color:#ffff">
                                            <div class="dropdown">
                                                <span onclick="window.location.href='{{route('orders.sync.order',$order->id)}}'" style="cursor: pointer;float: right" class="pr-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Need Synchronisation </span>
                                            </div>

                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center" style="display: flex;justify-content: center;align-items: center;margin: 10px;">
                        <nav aria-label="...">
                            <ul class="pagination">
                                <li class="page-item @if($page == 1) disabled @endif">
                                    <a class="page-link" href="{{route('orders.new')}}?page={{$page-1}}" tabindex="-1">Previous</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="{{route('orders.new')}}?page={{$page+1}}">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
