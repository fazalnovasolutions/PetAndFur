<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,900" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<section class="header">
    <div class="container">
        <nav class="navbar navbar-default">
            <div>
                <div class="navbar-header">
                    <a class="navbar-brand"><img src="//cdn.shopify.com/s/files/1/0045/3902/3489/files/LOGOS_FOR_DEBUT_410x.png?v=1590070144" alt="logo-img"></a>
                </div>
            </div>
        </nav>
    </div>
</section>
<section class="creation-process">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <p>Look up your order in our creation process</p>
            </div>
        </div>
    </div>
</section>
<div class="container api_err_msg hide_apierr_msg">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <div class="alert alert-danger">
                <a href="#" class="close api_err_cls">&times;</a>
                Try again later!! Currently Service is Unavailable.
            </div>
        </div>
    </div>
</div>
<section class="order-sec">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12 order-inner-f">
                <div class="order-inner">
                    <h3>Look up order</h3>
                    <span class="order-num">Order Number {{$order->name ?? ''}}</span>
                    <form method="get" action="{{ route('verify_order_no') }}">

                    <div class="form-group">
                        @if(session('error'))
                            <div class="alert alert-danger ">
                                <button type="button" class="close" data-dismiss="alert" ><i class="icon-cross"></i></button>
                                <span class="vd_alert-icon"><i class="fa fa-exclamation-circle vd_red"></i></span> {{ session('error') }}</div>
                        @endif
                        <input type="text" class="form-control ordernumber" name="ordernumber" placeholder="Order Number"
                               value="{{old('ordernumber',$order->name	 ?? '')}}"
                               required="required">
                        <span class="spn-err-msg">Please input the order number.</span>
                        <button type="submit" class="btn btn-primary find-order-btn">Find Order</button>
                    </div>
                    </form>

                </div>
            </div>

        @if(isset($order->id))
            @if(count($order->has_products)>0)

                <?php $count=0; ?>
                @foreach ($order->has_products as $index => $key)
                        <?php $count++;
                        if($count == 2)
                            {
                        ?>

                        <div class="col-md-4 col-sm-4 col-xs-12 ">
                        </div>
                        <?php
                            }?>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <br>
              <center>  <h4>{{$key->title.'-'.$key->variant_title}}</h4></center>
                <div class="order-inner order-steps-lists">
                    <ul>
             @if($order->has_designer != null)
                            <li class="step-order-chk" style="background-color: #00cc00;""><a href="javascript:void(0);">photo/order check</a></li>
                        @else
                        <li class="step-order-chk"><a href="javascript:void(0);">photo/order check</a></li>
                        @endif
                        {{--                        <div class="step-inner-cont">--}}
{{--                            <p>Your order details and photos are being reviewed before being sent to our artists. This usually takes less than 2 days. If there's an issue with your photo, we'll email you as soon as possible.</p>--}}
{{--                        </div>--}}

                 @if($key->has_design->status == 'In-Processing' || $key->has_design->status == 'Approved' )

                     <li class="step-order-chk" style="background-color: #00cc00;""><a href="javascript:void(0);">artwork creation</a></li>
                 @else
                     <li class="step-artwork-create"><a href="javascript:void(0);">artwork creation</a></li>
                 @endif
{{--                        <div class="step-inner-cont">--}}
{{--                            <p>Your masterpiece is in progress! Our talented team of artists are turning your pet photo in to an amazing one of a kind pet portrait. This is usually the longest part of the production process... It's definitely worth the wait!</p>--}}
{{--                        </div>--}}

                 @if(  $key->has_design->status == 'Update'  || $key->has_design->status == 'Approved')

                     <li class="step-order-chk" style="background-color: #00cc00;""><a href="javascript:void(0);">artwork review/revision</a></li>
                 @else
                     <li class="step-artwork-revise"><a href="javascript:void(0);">artwork review/revision</a></li>
                 @endif
{{--                        <div class="step-inner-cont">--}}
{{--                            <p>This part of the process covers our in-house art review as well as customer-purchased artwork reviews. If any changes/edits are needed, your artists will revise your artwork.</p>--}}
{{--                        </div>--}}

                 @if( $key->has_design->status == 'Approved')

                     <li class="step-order-chk" style="background-color: #00cc00;""><a href="javascript:void(0);">Approved</a></li>
                 @else
                     <li class="step-print-prepare"><a href="javascript:void(0);">Approved</a></li>
                 @endif
{{--                        <div class="step-inner-cont">--}}
{{--                            <p>Not too long now! We're finalizing everything in your order before sending it to print. This takes 1-2 days on average.</p>--}}
{{--                        </div>--}}
{{--                        <li class="step-print-shop"><a href="javascript:void(0);">print and ship</a></li>--}}
{{--                        <div class="step-inner-cont">--}}
{{--                            <p>Your order is being printed and shipped! Either your order is currently in print right now, or it has already printed and shipped out to your address. You'll be emailed a tracking number quite soon. To track shipped orders, use our parcel tracking <a href="http://printypets.aftership.com/" target="_blank">page</a>.</p>--}}
{{--                        </div>--}}
                    </ul>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12 p0">
{{--                        <div class="order-img" style="  position: absolute;--}}
{{--                        left: 0;--}}
{{--                        right: 0;--}}
{{--        top: 0;--}}
{{--        bottom: 0;--}}
{{--        background: url('https://familybooster.com/image/cache/catalog/products/no-image-800x800.jpg');--}}
{{--        background-position: center;--}}
{{--        background-size: cover;--}}
{{--      "></div>--}}
                @if( $key->has_design->status !='No Design')

                    <img  src="{{asset('designs/'.$key->has_design->design)}}" height="500px" width="100%">

                @else
                    <?php
                    $properties = json_decode($key->properties, true);
                    ?>

                        @if($properties)
                            @foreach($properties as $property)
                                @if($property['name'] == 'Upload Image' || $property['name'] == '_Uploaded Image')
                                    <img src="{{$property['value']}}" height="500px" width="100%" />
                                @endif
                            @endforeach
                        @endif

                @endif

                </div>

                    @endforeach
                    @endif

            @endif
{{--        <div class="row">--}}
{{--            <div class="col-md-12 col-sm-12 col-xs-12 support-sec">--}}
{{--                <p>If you would like any further information, or if the app is not working properly, please contact us at support@printypets.com</p>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>

    </div>

</section>
<!-- footer -->

<div class="loading_img hide_loader_img">
    <div class="inner-loading">
        <img src="//cdn.shopify.com/s/files/1/0040/5317/2339/t/36/assets/loading.gif?v=17537448609303225599"/>
    </div>
</div>
<style type="text/css">
    .api_err_msg.hide_apierr_msg{
        display:none;
    }
    .api_err_msg{
        padding: 0;
        margin-top: 25px;
    }
    .loading_img.hide_loader_img{
        display:none;
    }
    .loading_img{
        position: fixed;
        left: 0px;
        right: 0px;
        top: 0px;
        bottom: 0px;
        z-index: 999999;
        background: rgba(255, 255, 255, 0.75);
        text-align: center;
        height: 100vh;
        width: 100%;
        display: table;
        margin: 0 auto;
    }
    .inner-loading{
        display: table-cell;
        vertical-align: middle;
    }
    /*------------header sec ------------*/
    .header .navbar{
        margin-bottom: 0;
    }
    .header .navbar-header {
        float: left;
        width: 100%;
        display: inline-block;
    }
    .header .navbar-default {
        background-color: transparent;
        border-color: transparent;
    }
    .header .navbar-brand > img {
        display: inline-block;
        width: 100%;
    }
    .header a.navbar-brand {
        width: 250px;
        display: block;
        margin: 0 auto;
        float: none;
        height: auto;
    }
    /*------------creation process------------*/
    .creation-process{
        background-color: #efefef;
        font-family: 'Lato',sans-serif;
    }
    .creation-process p {
        margin: 0;
        padding: 10px 0px;
        font-size: 16px;
        text-transform: capitalize;
        /*     letter-spacing: 1px; */
    }
    /*------------order sec------------*/
    section.order-sec {
        padding-top: 30px ;
        font-family: 'Lato',sans-serif;
    }
    .order-inner {
        width: 100%;
        height: 100%;
        max-height: 557px;

        position: relative;
    }
    .order-inner-f {
        border: 1px solid #ddd;
    }
    .order-inner-f h3 {
        font-weight: 600;
    }
    .order-inner-f  span.order-num {
        padding: 10px 0px;

        width: 120px;
        border-bottom: 3px solid #3e74ad;
        margin-bottom: 0px;
        text-align: center;
        font-size: 16px;
        color: #333;
        font-weight: 600;
    }
    .order-inner-f  .form-group {
        border-top: 1px solid #ddd;
        padding-top: 30px;
    }
    .order-inner-f a {
        color: #3290f3;
        font-size: 16px;
        position: relative;
    }

    .order-inner-f a:focus, .order-inner-f a:hover{
        text-decoration: none;
        color: #3290f3;
    }
    .order-inner-f a:after{
        content: "";
        background: #3290f3;
        width: 100%;
        height: 1px;
        position: absolute;
        top: auto;
        bottom: -4px;
        left: 0;
        right: auto;
    }
    .order-inner-f p {
        margin: 0;
        font-weight: bold;
        font-size: 16px;
        padding-top: 12px;
        color: #333;
    }
    .order-inner-f input.form-control {
        border-radius: 0;
        height: 45px;
    }
    .order-inner-f button.btn.btn-primary.find-order-btn {
        width: 100%;
        border-radius: 0;
        margin-top: 20px;
        padding: 11px 0px;
        font-size: 17px;
        letter-spacing: 1px;
        text-transform: capitalize;
        background-color: #3e74ad;
    }
    /*.order-img {*/
    /*    position: absolute;*/
    /*    left: 0;*/
    /*    right: 0;*/
    /*    top: 0;*/
    /*    bottom: 0;*/
    /*    background: url(//cdn.shopify.com/s/files/1/0040/5317/2339/t/36/assets/promotion_banner.jpg?v=4993193380752143210);*/
    /*    background-position: center;*/
    /*    background-size: cover;*/

    /*}*/
    /*.order-inner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }*/
    .order-inner h3{
        margin:20px 0 10px !important;
        color: #333 !important;
        font-family: Avenir Next;
    }
    .order-inner ul {
        padding: 0;
        text-align: center;
        margin: 0;
        list-style: none;
    }
    .order-inner ul li {
        text-transform: uppercase;
        background: #fffbb1;
        position: relative;
    }
    @media screen and (min-width:768px){
        .order-inner ul li {
            margin-top: 52px;
        }
        .order-inner ul li:after {
            content: "";
            background: #ddd;
            width: 2px;
            height: 100%;
            position: absolute;
            top: auto;
            bottom: 50px;
            left: 172px;
            right: auto;
            display: inline-block;
        }
    }
    .order-inner ul li:first-child::after{
        display: none;
    }
    .order-inner ul li:last-child{
        margin-bottom: 0;
    }
    .order-inner ul li a {
        color: #333;
        padding: 11px 0px !important;
        display: inline-block;
        font-size: 18px;
        border-bottom: none !important;
    }
    .order-inner ul li a:focus, .order-inner ul li a:hover{
        text-decoration: none;
    }
    .support-sec{
        padding: 15px;
        border: 1px solid #ddd;
        margin-top: 25px;
    }
    .support-sec p {
        margin: 0;
        font-size: 16px;
        letter-spacing: 1px;
        color: #333;
        text-align: center;
    }
    .spn-err-msg{
        color: rgb(230,0,15);
        font-size: 15px;
        margin-top: 5px;
        float: left;
        display:none;
        padding: 0 1%;
    }
    .err-shown{
        border-color:rgb(230,0,15) !important;
    }
    /*footer section*/
    section.footer {
        padding: 20px 0px;
        margin-bottom: 20px;
    }
    section.footer a i.fa {
        font-size: 20px;
        color: #666;
    }
    .foot-right{
        text-align: right;
    }
    .foot-right a{
        font-size: 17px;
    }
    .foot-right a:hover,.foot-right a:focus{
        text-decoration:none;
    }
    .step-order-chk.active_list, .step-artwork-create.active_list, .step-artwork-revise.active_list, .step-print-prepare.active_list, .step-print-shop.active_list{
        background: #3290f3;
    }
    .step-inner-cont {
        /*     position: static; */
        background: #fff;
        z-index: 11;
        width: 100%;
        font-size: 12px;
        display: none;
    }
    .step-inner-cont p{
        color: #333;
        padding-top: 5px;
        margin: 0px;
    }
    .step-inner-cont.show-inner-msg{
        display:inline-block;
    }

    @media screen and (max-width:1199px){
        .order-inner ul li:after{left:140px;}
    }
    @media screen and (max-width:990px){
        .order-inner ul li a{font-size:14px;padding:16px 0px !important;}
        .order-inner ul li:after{left:110px;}
    }
    @media screen and (max-width:767px){
        .order-inner ul li:after {left: calc(51% - 10px);}
        .foot-right , .foot-left{text-align:center;}
        /*   .order-inner{max-height:none;} */
        .order-sec{padding:0px 30px;padding-top:60px;}
        .order-inner-f a{margin-bottom:30px;display:inline-block;margin-top:10px;font-size:14px;}
        .order-inner ul li a{font-size:16px;}
        .creation-process p{font-size:15px;}
        a.site-header__logo-link{font-size:15px;}
        .list-out-box{padding:0px;}
        .order-inner-f .order-inner, .list-out-box .order-inner{height: auto;}
        .order-inner-f{ margin-bottom:20px;}
        .list-out-box{display: none;margin-bottom:20px;padding:5px;background:#fffbb1;}
        .step-inner-cont{background:#fffbb1;}
        .p0{ padding:0;}
    }
    .hideEle{
        display:none;
    }

</style>
<script>

    $('img').each(function (index) {
        var src = $(this).attr('src');
        // console.log(src)
        if (src.search("&uu=") !== -1) {
            var url = new URL(src);
            var id = url.searchParams.get("id");
            var c = url.searchParams.get("uu");
            // console.log(c);
            if(c !== null){
                var new_src = 'https://cdn.getuploadkit.com/'+c+'/';
                console.log(new_src)
                $(this).attr('src',new_src);
            }

        }
    });
</script>
</body>
</html>
