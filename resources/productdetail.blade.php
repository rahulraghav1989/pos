@extends('web.layouts.app')
<?php header("Access-Control-Allow-Origin: *"); ?>
@section('page-title')
    <title>Townsy: {{ strtoupper($product->product_name) }}</title>
@endsection

@section('page-description')
    <meta name="description" content="{{ $product->description }}">
@endsection

@section('content')

@section('share-script')
    @if ( url('/') == config('app.dev_server_base_url') )
        <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5e86dd4668efcb00129328d2&product=inline-share-buttons&cms=sop' async='async'></script>
        {{--  <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5b3c53a047b80c001196643b&product=custom-share-buttons' async='async'></script>  --}}
    @else
        <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5e86e0d168efcb00129328d3&product=inline-share-buttons&cms=sop' async='async'></script>
    @endif

@endsection
<!-- Global site tag (gtag.js) - Google Ads: 670867499 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-670867499"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-670867499');
</script>

<script type="text/javascript">
    // Notice how this gets configured before we load Font Awesome
    window.FontAwesomeConfig = { autoReplaceSvg: false }
</script>
<style>
    html, body{
    -webkit-text-size-adjust:none !important;
    -ms-text-size-adjust:none !important;
    -moz-text-size-adjust:none !important;
    text-size-adjust:none !important;
    }
    .refresh-bar{
    display: none;
    }
    @media(max-width: 575px){
    .refresh-bar span{
    font-size: 1rem;
    }
    }
    .has-search span {
    position: absolute;
    left: 25px !important;
    top: 12px !important;
    opacity: 0.6;
    font-size: 1em !important;
    }
    @media (min-width: 320px) and (max-width: 767px){
    .has-search .form-control-feedback {
    position: absolute;
    left: 25px !important;
    opacity: 0.6;
    }
    }
    @media (min-width: 992px){
    .has-search span {
    position: absolute;
    left: 25px !important;
    top: 10px !important;
    opacity: 0.6;
    font-size: 1em !important;
    }
    .has-search .form-control-feedback.fa-map-marker-alt {
    left: 12px !important;
    }
    }
    .fa-search{
        color: black;
    }
    @media (min-width: 1024px){
    .has-search span {
    top: 13px !important;
    }
    }
    .popup img {
    width: 85px !important;
    height: 85px !important;
    }
    .fa-check-circle {
        font-size: 13px !important;
        padding: 0px !important;
    }
    .modal-backdrop {
        z-index: -1;
    }

    @media (max-width: 767px){
    #exzoom {
        /*width: 250px !important;
        height: auto;*/
        padding: 0px !important;
        margin-bottom: 20px !important;
    }
    }

    @media (max-width: 325px){
    #exzoom {
        width: 250px !important;
        height: auto;
    }
    }

    @media (min-width: 326px) and (max-width: 414px){
    #exzoom {
        width: 325px !important;
        height: auto;
    }
    }

        @media (min-width: 415px) and (max-width: 500px){
    #exzoom {
        width: 375px !important;
        height: auto;
    }
    }

    @media (min-width: 501px) and (max-width: 600px){
    #exzoom {
        width: 450px !important;
        height: auto;
    }
    }

        @media (min-width: 601px) and (max-width: 700px){
    #exzoom {
        width: 550px !important;
        height: auto;
    }
    }

    @media (min-width: 701px) and (max-width: 767px){
    #exzoom {
        width: 650px !important;
        height: auto;
    }
    }

    @media (min-width: 768px) and (max-width: 880px){
    #exzoom {
    padding-left: 25px !important;
    padding-right: 25px !important;
    width: 300px !important;
    height: 300px !important;
    }
    }

    @media (min-width: 881px) and (max-width: 991px){
    #exzoom {
    padding-left: 25px !important;
    padding-right: 25px !important;
    width: 350px !important;
    height: 350px !important;
    }
    }

    @media (min-width: 992px) and (max-width: 1200px){
    #exzoom {
    padding-left: 25px !important;
    padding-right: 25px !important;
    width: 400px !important;
    height: 400px !important;
    }
    }

    @media (min-width: 1201px) and (max-width: 1500px){
    #exzoom {
    padding-left: 25px !important;
    padding-right: 25px !important;
    width: 500px !important;
    height: 500px !important;
    }
    }
    @media (min-width: 1501px){
    #exzoom {
    padding-left: 25px !important;
    padding-right: 25px !important;
    width: 600px !important;
    height: 600px !important;
    }
    }
    /*.exzoom_img_box{
        width: 250px !important;
        height: 250px !important;
    }

    .exzoom_img_ul{
        width: 250px !important;
        height: 250px !important;
    }*/

    .container {
        max-width: none;}

    @media (max-width:567px){
        .container {
        padding: 15px !important;}
    }

    @media (min-width:567px){
        .container {
        padding-top: 25px !important;
        padding-bottom: 15px !important;
        padding-left: 25px !important;
        padding-right: 25px !important;}
    }


    .container2 { max-width: 960px; }
    .hidden { display: none; }
    * {
        box-sizing: border-box;
    }

    .row > .column {
        padding: 0 8px;
    }

    .row:after {
        content: "";
        display: table;
        clear: both;
    }

    .column {
        float: left;
        width: 25%;
    }

    /* The Modal (background) */
    .modal {
        display: none;
        position: fixed;
        z-index: 999;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: black;
    }

    /* Modal Content */
    .modal-content {
        position: relative;
        background-color: #fefefe;
        margin: auto;
        padding: 0;
        width: 60%;
        max-width: 1200px;
    }

    /* The Close Button */
    .close {
        color: white;
        position: absolute;
        top: 10px;
        right: 25px;
        font-size: 35px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #999;
        text-decoration: none;
        cursor: pointer;
    }

    .mySlides {
        display: none;
    }

    .cursor {
        cursor: pointer;
    }

    /* Next & previous buttons */
    .prev,
    .next {
        cursor: pointer;
        position: absolute;
        top: 50%;
        width: auto;
        padding: 16px;
        margin-top: -50px;
        color: white;
        font-weight: bold;
        font-size: 20px;
        transition: 0.6s ease;
        border-radius: 0 3px 3px 0;
        user-select: none;
        -webkit-user-select: none;
    }

    /* Position the "next button" to the right */
    .next {
        right: 0;
        border-radius: 3px 0 0 3px;
    }

    /* On hover, add a black background color with a little bit see-through */
    .prev:hover,
    .next:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }

    /* Number text (1/3 etc) */
    .numbertext {
        color: #f2f2f2;
        font-size: 12px;
        padding: 8px 12px;
        position: absolute;
        top: 0;
    }

    img {
        margin-bottom: -4px;
    }

    .caption-container {
        text-align: center;
        background-color: black;
        padding: 2px 16px;
        color: white;
    }

    .demo {
        opacity: 0.6;
    }

    .active,
    .demo:hover {
        opacity: 1;
    }

    img.hover-shadow {
        transition: 0.3s;
    }

    .hover-shadow:hover {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    @media screen and (min-width: 1010px) {
        .product_div {
        }
        /*.fa-product-icon {
            margin-top: -16px;
        }*/
        .prev {
            left: -231px;
        }
        .next {
            right: -231px;
        }
        .expand_img {
            width: 100%;
        }
    }

    @media screen and (max-width: 1010px) {
        /*.fa-product-icon {
            margin-top: -423px;
        }*/
        .prev {
            left: -69px;
        }
        .next {
            right: -69px;
        }
        .expand_img {
            width: 155%;
            margin-left: -58px;
        }
    }

        .fa-product-icon .fa-star{
        margin-top: 8px !important;
        padding-top:0px !important;
        padding-bottom: 0px !important;
        padding-right: 0px !important;
        font-size: 29px !important;}

    .st-custom-button .fa-share-alt{
        padding-top:0px !important;
        padding-bottom: 0px !important;
        padding-left: 0px !important;
        padding-right: 5px !important;
        margin-top: 5px !important;
        font-size: 29px !important;}

    @media (max-width: 375px){
        .fa-product-icon .fa-star{
        font-size: 23px !important;}

    .st-custom-button .fa-share-alt{
        font-size: 23px !important;}
    }
    @media (max-width:320px){
    #Smallchat .Layout-expand {
        left: 10px;
    }
    }
    #Smallchat .Layout-expand {
        bottom: 0px;
    }
</style>

<div class="product-area" style="background-color: #f8f8f8;">
    {{--WHITE AREA--}}
    <div class="container">
        <div id="product-info">
            <div class="row col-12 col-md-12 col-lg-12 col-xl-12 mx-auto" style="background-color: white; padding-top: 10px;padding-bottom:10px;">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 exzoom hidden" id="exzoom" style="margin-top: 0px;">
                    <div class="exzoom_img_box mx-auto" onclick="openModal(0)">
                        <ul class='exzoom_img_ul mx-auto'>
                            @if ($productImages->count() >= 1)
                            @foreach ( $productImages as $photokey => $image )
                                <li><img src="{{asset("storage/$image->photo")}}"/></li>
                            @endforeach
                            @else
                                <li><img src="{{asset("img/product-default1.PNG")}}"/></li>
                            @endif
                        </ul>
                    </div>
                    <div class="exzoom_nav"></div>
                    <p class="exzoom_btn">
                        <a href="javascript:void(0);" class="exzoom_prev_btn" style="background-color: #363636;color: white; font-weight: 700;border: #363636;"> < </a>
                        <a href="javascript:void(0);" class="exzoom_next_btn" style="background-color: #363636;color:white; font-weight: 700;border: #363636;"> > </a>
                    </p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 product_div" id="product" style="padding: 0px;">
                    <div class="row col-12" style="margin: 0px !important;padding-right: 0px !important;padding-left: 0px !important;}">
                        <div class="col-9 col-sm-10 col-md-9 col-lg-10 mt-auto" id="product-name" style="padding: 0px;"><p style="font-family: Poppins, sans-serif !important; font-weight: bold;">{{ strtoupper($product->product_name) }}</p></div>
                        <div class="col-3 col-sm-2 col-md-3 col-lg-2 fa-product-icon mb-auto" style="padding: 0px;">
                            <icon>
                                @if (auth()->user() !== null && $product->savedBy == 1)
                                    <i class="product-like fas fa-star fa-lg"></i>
                                @else
                                    <i class="product-like far fa-star fa-lg"></i>@endif
                            </icon>
                            <div data-network="sharethis" class="st-custom-button"><i class="fas fa-share-alt" style="cursor: pointer;color: black;"></i></div>
                            {{--                    <a href="mailto:?subject=Check out this product on Townsy&amp;body=Hey, Check out this product on Townsy.co.in: {{ route('product',$product->id) }}." ><i class="fas fa-share-alt" ></i></a>--}}
                        </div>
                    </div>
                    <div id="product-price"><p style="font-weight: bold;color:green;">₹ {{ $product->price }}</p></div>
                    <div id="merchant-name"><p style="font-family: Poppins, sans-serif !important;font-weight:normal;">Sold By: <a href="{{ route('business',[$product->business_id, request()->route('location')])}}" style="color: blue;">{{ ucwords($product->business_name) }}</a></p></div>
                    <div id="location-name"><p style="font-family: Poppins, sans-serif !important;font-weight:normal; color: #939393 !important;">{{ ucwords($product->address) }}</p></div>
                    {{--<div class="row" style="margin-left:0px !important; margin-right:0px !important;">
                        <div class="">
                            @if (auth()->user() !== null)
                                <a href="{{ route('chat', ['id' => $product->id, 'type' => 'product'])}}">
                                    <button class="btn btn-primary btn-view-seller"><b>CHAT WITH SELLER</b> </button>
                                </a>
                            @else
                                <a href="{{ route('user.show.login') }}">
                                    <button class="btn btn-primary btn-view-seller"><b>CHAT WITH SELLER</b> </button>
                                </a>
                            @endif
                        </div>
                    </div>--}}
                    <div class="form-group">
                        <div id="product-description-label" style="margin-top: 10px !important;">
                        <span style="font-weight: bold;padding: 0 !important;color: #727272 !important;">Product Description:</span></div>
                        <div class="product-description" style="margin-top:0.3rem;">{!!nl2br(str_replace('\n', " \n", $product->description))!!}</div>
                    </div>

                    <div class="text-center">
                        <a id="report-product" href="#" data-toggle="modal"
                           data-target="#reportModal">REPORT PRODUCT</a>
                    </div>
                </div>
                <div ng-app="MesiboWeb" id="mesibowebapp" ng-controller="AppController" style="overflow: hidden;">
                    <div class="container">
                        <div class="row">
                            <div id="Smallchat">
                                <div class="Layout Layout-open Layout-expand Layout-right" style="background-color: #3F51B5;color: rgb(255, 255, 255);opacity: 5;border-radius: 10px;">
                                    <div class="Messenger_messenger">
                                        <div class="Messenger_header" style="background-color:black ; color: rgb(255, 255, 255); box-shadow: 0 3px 8px lightgrey;z-index: 2;">
                                            <div class="circle" style="border: none !important;">
                                                <img ng-src= "@{{selected_user.picture}}" height="43" width="43" id="display_pic" onerror="imgError(this)">
                                            </div>
                                                    <div class="row" style="padding-left: 20px;width: 90%;" >
                                                        <p class="Messenger_prompt" id = 'display_name' style="font-size:18px; padding-top:8px;color: white !important; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;-o-text-overflow: ellipsis; "> @{{selected_user.name}}</p>
                                                        <p class="text-white-small" style=" font-size:11px" id="status_indicator" >@{{selected_user.activity}}</p>
                                                    </div>
                                            <span class="chat_features">
                                                        <span style="padding-left: 10px" class="chat_close">
                                                            <svg viewBox="0 0 12 12" width="14px" height="14px">
                                                                <line stroke="#FFFFFF" x1="11.75" y1="0.25" x2="0.25" y2="11.75"></line>
                                                                <line stroke="#FFFFFF" x1="11.75" y1="11.75" x2="0.25" y2="0.25"></line>
                                                            </svg>
                                                        </span>

                                                    </span>
                                        </div>
                                        <div class="Messenger_content" style="overflow: hidden;">
                                            <div class="Messages" id='scrollMessage'>
                                                <div class="Messages_list" id="msglist">
                                                    <div ng-repeat="m in get_from_db" ng-class="{'outgoing_msg':isSent(m),'incoming_msg':isReceived(m)}">
                                                        <div ng-class="{'sent_msg':isSent(m), 'received_msg':isReceived(m)}">
                                                            {{-- <a ng-show = "isFileMsg(m)" ng-href="@{{isFileMsg(m)? m.fileurl:''}}" target="_blank" style="max-width: 200px;" >
                                                                <div ng-show='isImageMsg(m)' class= "image-holder"><img ng-src= "@{{m.fileurl}}" style="cursor:pointer;" />
                                                                </div>
                                                                <div ng-show='isVideoMsg(m)' class= "image-holder"><video controls="controls" preload="true" ng-src= "@{{m.fileurl}}" style="max-width: 200px;overflow: hidden;cursor:pointer;" />
                                                                </div>
                                                                <div ng-show='isAudioMsg(m)' class= "image-holder"><audio controls="controls" preload="true" ng-src= "@{{m.fileurl}}" style="max-width: 200px;overflow: hidden;cursor:pointer;" />
                                                                </div>
                                                                <div ng-show='isOtherMsg(m)' class= "image-holder"><img ng-src= "images/file/default_file_icon.jpg" style="cursor:pointer;" />
                                                                </div>
                                                            </a> --}}
                                                            <div style="max-width: 200px;overflow-wrap: break-word;">
                                                                @{{m.message}}
                                                            </div>
                                                            <div class="time ml-auto small text-left flex-shrink-0 align-self-end text-muted" style="font-size: 11px;"ng-style="{'float': isSent(m) ? 'right':'left'}" >@{{m.ts * 1000 | date:'HH:mm'}}
                                                                <i ng-class= "getMessageStatusClass(m)" ng-style = "{'color':getMessageStatusColor(m)}"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Anchor to Scroll -->
                                                <div id="messages_end">&nbsp;</div>
                                            </div>
                                            <div class="Input Input-blank" style="box-shadow: 0 8px 8px 8px lightgrey; padding-top: 10px;padding-bottom: 10px;">
                                                <textarea ng-model="input_message_text" ng-keydown="$event.keyCode === 13 && sendMessage()" class="Input_field" placeholder="Send a message..." style="height: 20px;margin-bottom: -10px;height: 30px;max-height: 30px;padding-right: 25px;margin-right:5rem;width: 75%;" id='inputfield'></textarea>


                                                <button class="Input_button Input_button-emoji" style="right: 37px;">
                                                    <div class="Icon" style="width: 18px; height: 18px;">
                                                        <input id="upload" type="file" onchange="angular.element(this).scope().onFileSelect(this)" style="padding-left: : 5px; display: none;">
                                                        <i ng-click="clickUploadFile()" id="file-upload" class="fas fa-paperclip" style="padding-left:5px; color: #black; "></i>
                                                    </div>
                                                </button>


                                                <button class="Input_button Input_button-send" ng-click="sendMessage()"  style="right: 5px;" >
                                                    <div class="Icon" style="width: 18px; height: 18px;">
                                                        <svg width="57px" height="54px" viewBox="1496 193 57 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="width: 18px; height: 18px;">
                                                            <g id="Group-9-Copy-3" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(1523.000000, 220.000000) rotate(-270.000000) translate(-1523.000000, -220.000000) translate(1499.000000, 193.000000)">
                                                                <path d="M5.42994667,44.5306122 L16.5955554,44.5306122 L21.049938,20.423658 C21.6518463,17.1661523 26.3121212,17.1441362 26.9447801,20.3958097 L31.6405465,44.5306122 L42.5313185,44.5306122 L23.9806326,7.0871633 L5.42994667,44.5306122 Z M22.0420732,48.0757124 C21.779222,49.4982538 20.5386331,50.5306122 19.0920112,50.5306122 L1.59009899,50.5306122 C-1.20169244,50.5306122 -2.87079654,47.7697069 -1.64625638,45.2980459 L20.8461928,-0.101616237 C22.1967178,-2.8275701 25.7710778,-2.81438868 27.1150723,-0.101616237 L49.6075215,45.2980459 C50.8414042,47.7885641 49.1422456,50.5306122 46.3613062,50.5306122 L29.1679835,50.5306122 C27.7320366,50.5306122 26.4974445,49.5130766 26.2232033,48.1035608 L24.0760553,37.0678766 L22.0420732,48.0757124 Z" id="sendicon" fill="BLACK" fill-rule="nonzero"></path>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--===============chat on button start===============-->
                                <div class="refresh-bar col-12 text-center" style="z-index: 10;background: rgb(163,163,163);top: 0px;right: 0px;left: 0px;padding: 5px;position: fixed;">
                                <span style="color: black;font-weight: bold;">Please refresh this page to send and receive messages</span>
                                </div>
                                
                                <div class="chat_on" style="bottom: 15px !important; border-radius:0% !important;background-color:transparent !important;box-shadow:0 0 0 0 !important;"> 
                                    <span class="chat_on_icon" style="border-radius:0% !important;background-color:transparent !important;box-shadow:0 0 0 0 !important;">
                                        <div class="popup">
                                            <img style="border-radius:0% !important;" src="{{asset('img/chat1.png')}}"></img>
                                        </div>
                                    </span>
                                </div>
                                <!--=============== chat on  button end===============-->

                                <!--=============== File modal start===============-->
                                <div class="container" style="padding-top: 0px !important;padding-bottom: 0px !important;">
                                    <div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true" >
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h7 class="modal-title" id="fileModalLabel">Selected File: </h7>
                                                    <button type="button" class="close" data-dismiss="modal" ng-click="closeFilePreview();" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body" id="fileBody" style="width: 300px;
  margin: auto;">
                                                    <div class="file-preview-holder mx-auto" style="display: flex;justify-content: center;">
                                                        <img  id = 'image-preview' src="">
                                                        <video id = 'video-preview' controls="controls" preload="true"  src=""></video>
                                                        <!-- Image/video/Docs custom holder elements -->
                                                    </div>
                                                    <div class="justify-self-end align-items-center flex-row d-flex" >
                                                        <div class="mx-auto">
                                                            <input ng-model="input_file_caption" type="text" name="caption" id="file-caption" placeholder="Add caption" style="width:200px; margin:auto" class="flex-grow-1 border-0 px-3 py-2 my-3 rounded shadow-sm">
                                                            <i class="fas fa-paper-plane text-muted px-3" style="cursor:pointer;color: black !important;" ng-click="closeFilePreview();sendFile()"></i>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--=============== File modal end===============-->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="myModal" class="modal">
                <span class="close cursor" onclick="closeModal()">&times;</span>
                <div class="modal-content"style="-webkit-text-size-adjust:auto !important; -ms-text-size-adjust:auto !important;-moz-text-size-adjust:auto !important;text-size-adjust:auto !important;">
                    @foreach ( $productImages as $photo )
                        <div class="mySlides"style="-webkit-text-size-adjust:auto !important; -ms-text-size-adjust:auto !important;-moz-text-size-adjust:auto !important;text-size-adjust:auto !important;">
                            <img style="-webkit-text-size-adjust:auto !important; -ms-text-size-adjust:auto !important;-moz-text-size-adjust:auto !important;text-size-adjust:auto !important;" class="expand_img" src="{{asset("storage/$photo->photo")}}">
                        </div>
                    @endforeach
                    @if(sizeof($productImages) > 1)
                        <a style="color: white !important;" class="prev" onclick="plusSlides(-1)">&#10094;</a>
                        <a style="color: white !important;" class="next" onclick="plusSlides(1)">&#10095;</a>
                    @endif
                </div>
            </div>
            </div>
        </div>
    {{--AD BANNER--}}
    <div class="row align-items-center justify-content-start no-gutters" style="margin-top: 0px;">
            <div class="d-none d-sm-none d-md-block col-md-12 col-lg-12 mx-auto text-center ">
                {{-- advertise content --}}
                @if ( url('/') == config('app.dev_server_base_url') )
                    <div class="advertise-area" style="margin-top:10px !important;margin-bottom:20px !important;background-image: url('{{ asset("img/fav-business-ads.png") }} ')"></div>
                @else
                    <div class="advertise-area mx-auto text-center">
                        <script type="text/javascript" language="javascript">
                              var aax_size='728x90';
                              var aax_pubname = 'townsy2020-21';
                              var aax_src='302';
                        </script>
                        <script type="text/javascript" language="javascript" src="http://c.amazon-adsystem.com/aax2/assoc.js"></script>
                    </div>
                @endif
            </div>
        </div>
</div>
{{-- REPORT MODAL --}}
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="z-index:9999900">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content report-content">
            <form action="{{ route('user.report') }}" method="post" id="report-form">
                @csrf
                <div class="modal-body text-center" style="background: #f2f2f2;">
                    <div><span class="modal-title" id="report-title">REPORT PRODUCT</span></div>
                    <div class="submit-report">
                        <input type="hidden" id="product_id" value="{{ $product->id }}" name="product_id">
                        <input type="hidden" value="{{ $product->business_id }}" name="business_id">
                        <div id="report-radio">
                            <label class="radio-container form-check-label " style="font-size:15px !important; padding-left:28px !important;">Illegal Item
                                <input type="radio" class="form-check-input" name="reason" value="Illegal Item">
                                <span class="checkmark"></span>
                            </label>
                            <label class="radio-container form-check-label" style="font-size:15px !important; padding-left:28px !important;">Offensive Content
                                <input type="radio" class="form-check-input" name="reason" value="Offensive Content">
                                <span class="checkmark"></span>
                            </label>
                            <label class="radio-container form-check-label" style="font-size:15px !important; padding-left:28px !important;">Possible Fraud
                                <input type="radio" class="form-check-input" name="reason" value="Possible Fraud">
                                <span class="checkmark"></span>
                            </label>
                            <label class="radio-container form-check-label" style="font-size:15px !important; padding-left:28px !important;">Other
                                <input type="radio" class="form-check-input" name="reason" value="Other">
                                <span class="checkmark"></span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="" id="detail-text-lable" style="padding-left:13px">DETAILS</label>
                            <textarea class="form-control mx-auto" style="width:95% !important" rows=3 name="description"></textarea>
                        </div>

                    </div>
                </div>
                <div class="">
                    <input type="submit" class="btn btn-submit-report" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div>

@stop
@section('js')
<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js')}}"></script>
<script>
    function openModal(n) {
        document.getElementById("myModal").style.display = "block";
        showSlides(n+1);
    }

    function closeModal() {
        document.getElementById("myModal").style.display = "none";
    }

    var slideIndex = 1;

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        if (n >= slides.length) {
            slideIndex = 1
        }

        if (n < 1) {
            slideIndex = slides.length
        }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }

        slides[slideIndex-1].style.display = "block";
    }

    $(function () {
        $("#report-form").validate({
            rules: {
                reason: "required"
            },
            messages: {
                reason: "Please select atleast one reason."
            },
            errorPlacement: function (error, element) {
                if (element.attr("type") == "radio") {
                    error.insertAfter($('#report-radio'));
                }
            },
            submitHandler: function (form, e) {
                e.preventDefault();
                let fd = new FormData();
                fd.append('product_id', $('#product_id').val())
                if ($("#report-form").valid()) {
                    form.submit();
                } else {
                    return false; // keep dialog open
                }
            },
        });
    });

    $(document).ready(function () {

        $(document).on("click", ".product-like", function () {
            let fd = new FormData();
            fd.append('product_id', $('#product_id').val())

            if ($(this).attr('class') == 'product-like far fa-star fa-lg') {
                fd.append('save', 1)
            } else {
                fd.append('save', 0)
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('user.product.star') }}",
                data: fd,
                method: 'POST',
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (response) {

                    if (!response.status) {
                        $('.product-like').removeClass('fas')    //remove
                        $('.product-like').addClass('far')
                    } else {
                        $('.product-like').removeClass('far')   // save
                        $('.product-like').addClass('fas')
                    }

                },
                error: function (data) {
                    if (data.status == 401) {
                        window.location.href = '{{ route("user.login") }}';
                    }
                },
            })

        });

    });

</script>
<script type="text/javascript">
    $("#exzoom").exzoom();
    $("#exzoom").removeClass('hidden');

</script>
<script>
    var MESIBO_ACCESS_TOKEN = "<?php if(auth()->user() !== null) { echo auth()->user()->chat_access_token; } else { echo ''; }?>";
    /* App ID used to create a user token. */
    var MESIBO_APP_ID = "8555";

    /* If you are hosting Mesibo backend on your own server, change this accordingly.
     * Refer https://github.com/mesibo/messenger-app-backend
     */
    const MESIBO_API_URL = "https://app.mesibo.com/api.php";

    /* Default images */
    var MESIBO_DEFAULT_PROFILE_IMAGE = "images/profile/default-profile-icon.jpg";
    const MESIBO_DEFAULT_GROUP_IMAGE = "images/profile/default-group-icon.jpg";

    /* File url sources */
    var MESIBO_DOWNLOAD_URL = "https://appimages.mesibo.com/";
    var MESIBO_UPLOAD_URL = "https://s3.mesibo.com/api.php";

    /************************ Messenger Config Start *****************************/

    /* Toggle for using phone Login*/
    const isLoginEnabled = true;
    /* Toggle for synchronizing contacts*/
    var isContactSync = false;
    if(!isContactSync){
        /*If you do not perform contact sync, define local contacts to be loaded
         * in list of available users
         */
        var MESIBO_LOCAL_CONTACTS =[]
    }

    /* (Optional) Phone numbers of contacts who are using mesibo,
     * can be used for synchronization in getcontacts API
     */
    const MESIBO_PHONES_ARRAY=[];

    /*Optional link preview*/
    const isLinkPreview = false; //Set to false if link preview not required
    const LINK_PREVIEW_SERVICE = "http://api.linkpreview.net/";
    const LINK_PREVIEW_KEY = "acef6d3286d3f67ac615879d42408a23"; // Access Key
    const LINK_DEFAULT_IMAGE = "images/file/default-link-icon.jpg"

    /************************ Messenger Config End *****************************/

    /************************ Popup Config Start *****************************/

    /* Set Display Avatar and destination address for popup */
    const POPUP_DISPLAY_NAME = "<?php echo $product->business_name; ?>"
    <?php if(isset($business_photo->photo)) { ?>
        const POPUP_DISPLAY_PICTURE = "<?php echo "/storage/" . $business_photo->photo; ?>"
    <?php } else { ?>
        const POPUP_DISPLAY_PICTURE = "<?php echo $business_photo; ?>"
    <?php }?>
    /* A destination where the popup demo app will send message or make calls */
    const POPUP_DESTINATION_USER = '<?php echo $product->business_name; ?>';

    /************************ Popup Config End *****************************/


    /* Debug Mode Configuration */
    isDebug = true ;// toggle this to turn on / off for global control
    if (isDebug) var MesiboLog = console.log.bind(window.console);
    else var MesiboLog = function() {}
</script>

<script>
    var user_id = {{ $product->uid ?? 1 }};
    var src_id = {{ Auth::User()->uid ?? 1 }};
    var user_email = "{{$product->email}}";
    var current_link = "{{url()->current()}}";
    var product_desc = "{{$product->product_name.'- Rs '.$product->price }}";
    const MAX_MESSAGES_READ_SUMMARY = 10000;

    //The number of messages loaded into the message area in one read call
    const MAX_MESSAGES_READ = 10000;

    var LOGGED_OUT = false;
    
    
    angular.module('MesiboWeb', [])
        .controller('AppController', ['$scope', '$http', '$window', '$anchorScroll', function ($scope, $http, $window, $anchorScroll) {

            $scope.summarySession = {};
            $scope.messageSession = {};

            $scope.available_users =  [];
            $scope.selected_user = {};
            $scope.selected_user_count = 0;
            $scope.self_user = {};

            $scope.mesibo = {};

            //Main UI
            $scope.profile_settings_show = false;
            $scope.users_panel_show = false;
            $scope.message_area_show = false;
            $scope.blank_message_area_show = true;

            //Input Area
            $scope.input_message_text ="";
            $scope.link_preview = "https://townsy.in/product/691/MjMuMTA2ODE4OSY3Mi41NzkxODU3";

            //Calls
            $scope.is_answer_call = false;
            $scope.is_video_call = false;
            $scope.is_voice_call = true;
            $scope.call_status = "Call Status: ";
            $scope.call_alert_message = "";

            //Files
            $scope.selected_file = {};
            $scope.input_file_caption = "";

            $scope.refresh = function(){
                $scope.$applyAsync();
            }

            $scope.scrollToLastMsg = function() {
                $scope.$$postDigest(function () {
                    $anchorScroll("messages_end");
                });
            }


            $scope.getMesibo = function(){
                return $scope.mesibo;
            }

            $scope.showAvailableUsers = function() {
                MesiboLog('showAvailableUsers');
                $scope.users_panel_show = true;
                $scope.refresh();
            }

            $scope.hideAvailableUsers = function() {
                MesiboLog('hideAvailableUsers');
                $scope.users_panel_show = false;
                $scope.refresh();
            }

            $scope.setAvailableUsers = function(user_list) {
                $scope.available_users = user_list;
                $scope.refresh();
            }

            $scope.showProfileSettings = function() {
                MesiboLog("showProfileSettings");
                $scope.profile_settings_show = true;
                $scope.refresh();
            };

            $scope.hideProfileSettings = function() {
                MesiboLog("hideProfileSettings");
                $scope.profile_settings_show = false;
                $scope.refresh();
            };


            $scope.isSent = function(msg){
                if(msg.src == src_id){
                    return true;
                }
                else{
                    return false;
                }
                return isSentMessage(msg.status);
            }

            $scope.isReceived = function(msg){
                if(msg.src == user_id){
                    return true;
                }
                else{
                    return false;
                }
                return !isSentMessage(msg.status);
            }

            $scope.displayMessageArea = function(){
                MesiboLog('displayMessageArea');
                $scope.message_area_show = true;
                $scope.blank_message_area_show = false;
                $scope.refresh();
            }

            /** At the start, load a certain number of messages from history
             * Update accordingly while scrolling to top
             **/
            $scope.initMessageArea = function(user, msg_count){
                if(!isValid(user))
                    return -1;

                // MesiboLog("initMessageArea", user);
                $scope.resetMessageSession();
                $scope.selected_user = user;
                $scope.sessionReadMessages(user, msg_count);


            }

            $scope.generateMessageArea = function(contact){
                MesiboLog(contact);
                if(!isValid(contact))
                    return -1;

                if(isValid($scope.selected_user)){
                    var same_selection = false;
                    if(isGroup(contact))
                        same_selection = ($scope.selected_user.groupid == contact.groupid);
                    else
                        same_selection = ($scope.selected_user.address == contact.address);

                    if(same_selection)
                        return 0;
                }

                $scope.selected_user = contact;

                // MesiboLog($scope.selected_user);
                $scope.initMessageArea($scope.selected_user, MAX_MESSAGES_READ);
                $scope.displayMessageArea();
            }


            $scope.resetMessageSession = function(){
                $scope.messageSession = {};
                $scope.refresh();
            }

            $scope.setSelectedUser = function(user){
                $scope.selected_user = user;
                $scope.refresh()
            }

            $scope.onKeydown = function(event) {
                console.log(event);
            }

            $scope.isGroup = function(user) {
                return isGroup(user);
            }


            $scope.getUserPicture = function(user){
                // MesiboLog('user',user);
                    <?php if (isset($product)) {
                    if (isset($business_photo->photo)) {
                        $business_array[] = array($product->business_name, $business_photo->photo);
                    } else {
                        $business_array[] = array($product->business_name, $business_photo);
                    }
                    ?>
                var business_name_set =<?php echo json_encode($business_array );?>;
                business_name_set.forEach(function (business_name) {
                    if(user.address == business_name[0]) {
                        user.picture = "/storage/"+ business_name[1];
                        MESIBO_DEFAULT_PROFILE_IMAGE = "/storage/"+ business_name[1];
                    }
                })
                <?php }?>
                if(!isValid(user) || (undefined == user.picture)){
                    picture = $scope.isGroup(user) ? MESIBO_DEFAULT_GROUP_IMAGE:MESIBO_DEFAULT_PROFILE_IMAGE;
                    return picture;
                }

                var picture = user.picture;

                if(!isValidImage(picture)){
                    picture = $scope.isGroup(user) ? MESIBO_DEFAULT_GROUP_IMAGE:MESIBO_DEFAULT_PROFILE_IMAGE;
                    return picture;
                }

                if(isValidString(picture) && isValidImage(picture) && isValidString(MESIBO_DOWNLOAD_URL)){
                    if((picture!= MESIBO_DEFAULT_PROFILE_IMAGE) && (picture!=MESIBO_DEFAULT_GROUP_IMAGE))
                        picture = MESIBO_DOWNLOAD_URL + picture;
                }

                return picture;
            }

            $scope.getUserName = function(user){
                // MesiboLog("getUserName", user);
                if(!isValid(user))
                    return "";

                var name = user.name;
                if(!isValidString(name)){
                    name = "Invalid name";
                    if($scope.isGroup(user))
                        name = "Group_"+user.groupid ;
                    else
                    if(isValidString(user.address))
                        name = user.address;
                }

                return name;
            }

            $scope.getUserLastMessage = function(user){
                if(!isValid(user))
                    return "";

                var lastMessage = user.lastMessage;
                if(!isValid(lastMessage))
                    return "";

                if(lastMessage.filetype)
                    return getFileTypeDescription(lastMessage);

                var message = lastMessage.message;
                if(!isValidString(message))
                    return "";

                return message;
            }

            $scope.getUserLastMessageTime = function(user){
                if(!isValid(user))
                    return "";

                var lastMessage = user.lastMessage;
                if(!isValid(lastMessage))
                    return "";

                var date = lastMessage.date;
                if(!isValid(lastMessage.date))
                    return "";

                var date_ = date.date;
                if(!isValidString(date_))
                    return "";

                var time = date.time;
                if(!isValidString(time))
                    return "";

                if(date_ != 'Today')
                    time = date_;

                return time;
            }

            $scope.getMessageStatusClass = function(m){
                if(!isValid(m))
                    return "";

                if($scope.isReceived(m)){
                    return "";
                }

                var status = m.status;
                var status_class = getStatusClass(status);
                if(!isValidString(status_class))
                    return -1;

                return status_class;
            }

            $scope.setLinkPreview = function(lp){
                $scope.link_preview = lp;
                $scope.refresh();
            }

            $scope.closeLinkPreview = function(){
                $scope.link_preview = null;
                $scope.refresh();
            }

            $scope.inputTextChanged = async function(){
                MesiboLog('inputTextChanged');
                //if enabled config isLinkPreview
                if(isLinkPreview){
                    //xx Bug xx: If link_preview is already present doesn't update
                    if(isValid($scope.link_preview) && isValidString($scope.link_preview.url)){
                        var newUrl = getUrlInText($scope.input_message_text);
                        if(newUrl == $scope.link_preview.url)
                            return; //Make no changes to existing preview
                    }

                    var urlInMessage = getUrlInText($scope.input_message_text);
                    if(isValidString(urlInMessage)){
                        MesiboLog("Fetching preview for", urlInMessage)
                        var lp = await $scope.file.getLinkPreviewJson(urlInMessage, LINK_PREVIEW_SERVICE, LINK_PREVIEW_KEY);
                        // var lp = getSampleLinkPreview(); /*For testing */
                        if(isValid(lp)){
                            MesiboLog(lp);
                            $scope.setLinkPreview(lp);
                            $scope.refresh();
                        }
                    }
                    else
                        $scope.link_preview = null;
                }
            }



            $scope.getMessageStatusColor = function(m){
                // MesiboLog("getMessageStatusColor", m);
                if(!isValid(m))
                    return "";

                if($scope.isReceived(m))
                    return "";

                var status = m.status;
                var status_color = getStatusColor(status);
                if(!isValidString(status_color))
                    return "";

                return status_color;
            }

            $scope.logout = function(){
                $scope.mesibo.stop();
                deleteTokenInStorage();
                $('#logoutModal').modal("show");
            }

            $scope.getFileIcon = function(f){
                return getFileIcon(f);
            }

            $scope.sessionReadSummary = function(){
                $scope.summarySession = $scope.mesibo.readDbSession(null, 0, null,
                    function on_messages(m) {
                        MesiboLog("sessionReadSummary complete");
                        MesiboLog($scope.summarySession.getMessages());
                        if($scope.summarySession.getMessages().length > 0){
                            var init_user = $scope.summarySession.getMessages()[0];
                            $scope.generateMessageArea(init_user);
                            $scope.selected_user = init_user;
                        } else {
                            $scope.showAvailableUsers();
                        }
                        $scope.refresh()
                    });
                if(!isValid($scope.summarySession)){
                    MesiboLog("Invalid summarySession");
                    return -1;
                }

                $scope.summarySession.enableSummary(true);
                $scope.summarySession.read(MAX_MESSAGES_READ_SUMMARY);
            }



            $scope.sessionReadMessages = function(user, count){
                // MesiboLog("sessionReadMessages", user);
                var peer = user.address;
                var groupid = user.groupid;

                // MesiboLog("readMessages "+ peer+ " "+" groupid "+ groupid+ " "+ count);
                var data = {
                    uid: user_id,
                    src: src_id,
                };
                MesiboLog(data);
                
                var url = '/api/messages';
                $http.post(url, JSON.stringify(data)).then(function (response) {
                   $scope.get_from_db = response.data.data;
                    $scope.scrollToLastMsg();
                    var obj = response.data.data;
                //MesiboLog(current_link);
                var is_exist = 0;
                //var prodduct_page_url = product_desc + "\n\n" + current_link;
                var prodduct_page_url = product_desc + "\n\n" + current_link;
                
                Object.keys(obj).forEach(function(key) {
                    var n = obj[key]['message'].localeCompare(current_link);
                  if (n == 0 && is_exist == 0) {
                    
                    is_exist = 1;
                    }
                });
                //MesiboLog(is_exist);
                    //MesiboLog(response.data.data);
                }, function (response) {
                    $scope.get_from_db = response.data.data;
                    //MesiboLog(response.data.data);
                });
                $scope.messageSession =  $scope.mesibo.readDbSession(peer, groupid, null,
                    function on_messages(m) {
                        MesiboLog("sessionReadMessages complete");
                        //MesiboLog($scope.messageSession.getMessages());
                        //MesiboLog($scope.messageSession.getMessages());
                        $scope.refresh();
                        $scope.scrollToLastMsg();

                    });


                if(!isValid($scope.messageSession)){
                    MesiboLog("Invalid messageSession");
                    return -1;
                }

                $scope.messageSession.enableReadReceipt(true);
                $scope.messageSession.read(count);
                $scope.scrollToLastMsg();
            }


            $scope.onMessage = async function(m, data) {
                MesiboLog("$scope.prototype.onMessage", m, data);

                // If you get a message from a new contact, the name will be ""
                // So, you need to add it as a contact and synchronize with backend
                // var user = m.user;
                // if(isValid(user) && isContactSync){
                //     var uname = user.name;
                //     if("" == uname){
                //         if(!isGroup(user))
                //             $scope.app.fetchContacts(MESIBO_ACCESS_TOKEN, 0, [m.address]);
                //     }

                // }

                //Update profile details
                if (1 == m.type) {
                    if(m.message == "")
                        return -1;

                    var updated_user = JSON.parse(m.message);
                    if(!isValid(data))
                        return -1;
                    MesiboLog("Update contact", updated_user);
                    var c = {};
                    c.address = updated_user.phone;
                    c.groupid = parseInt(updated_user.gid);
                    c.picture = updated_user.photo;
                    c.name = updated_user.name;
                    c.status = updated_user.status;
                    c.ts = parseInt(updated_user.ts);

                    MesiboLog("Update contact", c);
                    var rv = $scope.mesibo.setContact(c);
                    $scope.refresh();

                    return 0;
                }
                var data = {
                    uid: user_id,
                    src: src_id,
                };
                //MesiboLog(data);
                var url = '/api/messages';
                $http.post(url, JSON.stringify(data)).then(function (response) {
                   $scope.get_from_db = response.data.data;
                    $scope.scrollToLastMsg();
                    //MesiboLog(response.data.data);
                }, function (response) {
                    $scope.get_from_db = response.data.data;
                    //MesiboLog(response.data.data);
                });
                $scope.refresh();

                return 0;
            };

            //Send text message to peer(selected user) by reading text from input area
            $scope.sendMessage = function() {
                MesiboLog('sendMessage');
                   // MesiboLog($scope.input_message_text);
                var value = $scope.input_message_text;
                if (!isValidString(value))
                    return -1;

                var data = {
                    uid: user_id,
                    src: src_id,
                };
                var url = '/api/messages';
                $http.post(url, JSON.stringify(data)).then(function (response) {
                   $scope.get_from_db = response.data.data;

                var obj = response.data.data;
                //MesiboLog(current_link);
                var is_exist = 0;
                //var prodduct_page_url = product_desc + "\n" + current_link;
                Object.keys(obj).forEach(function(key) {
                    var n = obj[key]['message'].localeCompare(current_link);
                  if (n == 0 && is_exist == 0) {
                    
                    is_exist = 1;
                    }
                });

                //MesiboLog(is_exist);
                if(is_exist != 1)
                {
                    var prodduct_page_url = current_link;
                //MesiboLog('usrl send');
                    //var prodduct_page_url = current_link;
                    var messageParams = {}
                    messageParams.id = $scope.mesibo.random();
                    messageParams.peer = user_email;
                    messageParams.groupid = $scope.selected_user.groupid;
                    messageParams.flag = MESIBO_FLAG_DEFAULT;
                    messageParams.message = prodduct_page_url;
                    $scope.mesibo.sendMessage(messageParams, messageParams.id, messageParams.message);

                    var product_detail = product_desc;
                    var messageParams = {}
                    messageParams.id = $scope.mesibo.random();
                    messageParams.peer = user_email;
                    messageParams.groupid = $scope.selected_user.groupid;
                    messageParams.flag = MESIBO_FLAG_DEFAULT;
                    messageParams.message = product_detail;
                    $scope.mesibo.sendMessage(messageParams, messageParams.id, messageParams.message);
                    //$scope.file.sendMessageWithUrlPreview($scope.link_preview,messageParams, messageParams.id, messageParams.message);
                }
                
                    $scope.scrollToLastMsg();
                    //MesiboLog(response.data.data);
                }, function (response) {
                    $scope.get_from_db = response.data.data;
                    //MesiboLog(response.data.data);
                });
                
                
                //MesiboLog($scope.selected_user);
                var messageParams = {}
                messageParams.id = $scope.mesibo.random();
                messageParams.peer = user_email;
                messageParams.groupid = $scope.selected_user.groupid;
                messageParams.flag = MESIBO_FLAG_DEFAULT;
                messageParams.message = value;

                if(isLinkPreview && isValid($scope.link_preview)){
                    //If link preview is enabled in configuration
                    var urlInMessage = getUrlInText(messageParams.message);
                    if(isValidString(urlInMessage)){
                        $scope.file.sendMessageWithUrlPreview($scope.link_preview, messageParams);
                    }
                }
                else
                    $scope.mesibo.sendMessage(messageParams, messageParams.id, messageParams.message);
                // MesiboLog(messageParams, messageParams.id, messageParams.message);

                var data = {
                    uid: user_id,
                    src: src_id,
                };
                MesiboLog(data);
                var url = '/api/messages';
                $http.post(url, JSON.stringify(data)).then(function (response) {
                   $scope.get_from_db = response.data.data;
                    $scope.scrollToLastMsg();
                    //MesiboLog(response.data.data);
                }, function (response) {
                    $scope.get_from_db = response.data.data;
                    //MesiboLog(response.data.data);
                });
                //MesiboLog($scope.selected_user);
                var url = '/save_chat', data = $scope.selected_user;

                $http.post(url, JSON.stringify(data)).then(function (response) {
                    //console.log('response', response);
                }, function (response) {

                });
                $scope.input_message_text = "";
                $scope.refresh();
                $scope.scrollToLastMsg();
                return 0;
            }

            $scope.makeVideoCall = function(){
                $scope.is_video_call = true;
                $scope.call.videoCall();
                $scope.refresh();
            }

            $scope.makeVoiceCall = function(){
                $scope.is_voice_call = true;
                $scope.call.voiceCall();
                $scope.refresh();
            }

            $scope.hangupCall = function(){
                $('#answerModal').modal("hide");
                $scope.mesibo.hangup(0);
                $scope.is_answer_call = false;
                $scope.refresh();
            }

            $scope.answerCall = function(){
                $scope.is_answer_call = true;
                $scope.call.answer();
                $scope.refresh();
            }

            $scope.showRinging = function(){
                $('#answerModal').modal({
                    show: true
                });
            }

            $scope.hangupVideoCall = function(){
                $('#videoModal').modal("hide");
                $scope.is_video_call = false;
                $scope.call.hangup();
                $scope.refresh();
            }

            $scope.hangupAudioCall = function(){
                $('#voiceModal').modal("hide");
                $scope.is_voice_call = false;
                $scope.call.hangup();
                $scope.refresh();
            }

            $scope.showVideoCall = function(){
                $('#videoModal').modal("show");
                $scope.is_video_call = true;
                $scope.refresh();
            }

            $scope.showVoiceCall = function(){
                $('#voiceModal').modal("show");
                $scope.is_voice_call = true;
                $scope.refresh();
            }

            $scope.clickUploadFile = function(){
                setTimeout(function () {
                    angular.element('#upload').trigger('click');
                }, 0);
            }

            $scope.onFileSelect = function(element){
                $scope.$apply(function(scope) {
                    var file = element.files[0];
                    if(!isValid(file)){
                        MesiboLog("Invalid file");
                        return -1;
                    }

                    MesiboLog("Selected File =====>", file);

                    $scope.selected_file = file;
                    $scope.showFilePreview(file);
                    MesiboLog('Reset', element.value);
                    element.value = '';

                });
            }

            $scope.showFilePreview = function(f) {
                var reader = new FileReader();
                $('#image-preview').attr('src', "");
                $('#video-preview').attr('src', "");
                $('#video-preview').hide();

                reader.onload = function(e) {
                    if(isValidFileType(f.name, 'image'))
                        $('#image-preview').attr('src', e.target.result);
                    else if(isValidFileType(f.name, 'video')){
                        $('#video-preview').attr('src', e.target.result);
                        $('#video-preview').show();
                    }
                }

                reader.readAsDataURL(f);

                var s = document.getElementById("fileModalLabel");
                if (s) {
                    s.innerText = "Selected File " + f.name;
                }

                $('#fileModal').modal("show");
            }

            $scope.closeFilePreview = function() {
                $('#fileModal').modal("hide");
                //Clear selected file button attr
            }

            $scope.sendFile = function(){
                $scope.file.uploadSendFile();
            }

            $scope.isFileMsg = function(m){
                return isValid(m.filetype);
            }

            $scope.hostnameFromUrl = function(pUrl){
                if(!isValidString(pUrl))
                    return "";
                var hostname = pUrl.replace('http://','').replace('https://','').split(/[/?#]/)[0];
                if(!isValidString(hostname))
                    return "";

                return hostname;
            }

            //Message contains URL Preview
            $scope.isUrlMsg = function(m){
                return ($scope.isFileMsg(m) && !isValidString(m.fileurl));
            }

            $scope.isImageMsg = function(m){
                if(!$scope.isFileMsg(m))
                    return false;
                return (MESIBO_FILETYPE_IMAGE == m.filetype);
            }

            $scope.isVideoMsg = function(m){
                if(! $scope.isFileMsg(m))
                    return false;
                return (MESIBO_FILETYPE_VIDEO == m.filetype);
            }


            $scope.isAudioMsg = function(m){
                if(! $scope.isFileMsg(m))
                    return false;
                return (MESIBO_FILETYPE_AUDIO == m.filetype);
            }

            $scope.isOtherMsg = function(m){
                if(! $scope.isFileMsg(m))
                    return false;
                return (m.filetype >= MESIBO_FILETYPE_LOCATION);
            }

            $scope.OnConnectionStatus = function(status, value){
                if(MESIBO_STATUS_SIGNOUT == status)
                {   
                    if(!($(".chat_on").is(":visible"))){
                        $(".refresh-bar").slideDown(300);
                        $scope.logout();
                    }else{
                        LOGGED_OUT = true;
                        $scope.logout();
                    }
                }

                var s ="";
                switch(status){
                    case MESIBO_STATUS_ONLINE:
                        s = "";
                        break;
                    case MESIBO_STATUS_CONNECTING:
                        s = "Connecting..";
                        break;
                    default:
                        s = "Not Connected";
                }

                $scope.self_user.connection_status = s;
                $scope.refresh();
            }

            $scope.onMessageStatus = function(m){
                $scope.refresh();
            }

            $scope.onCall = function(callid, from, video){
                if(video){
                    $scope.is_video_call = true;
                    $scope.mesibo.setupVideoCall("localVideo", "remoteVideo", true);
                }
                else{
                    $scope.is_voice_call = true;
                    $scope.mesibo.setupVoiceCall("audioPlayer");
                }

                $scope.call_alert_message = "Incoming "+(video ? "Video" : "Voice")+" call from: "+from;
                $scope.is_answer_call = true;

                $scope.showRinging();
            }

            $scope.onCallStatus =function(callid, status){

                var s = "";

                switch (status) {
                    case MESIBO_CALLSTATUS_RINGING:
                        s = "Ringing";
                        break;

                    case MESIBO_CALLSTATUS_ANSWER:
                        s = "Answered";
                        break;

                    case MESIBO_CALLSTATUS_BUSY:
                        s = "Busy";
                        break;

                    case MESIBO_CALLSTATUS_NOANSWER:
                        s = "No Answer";
                        break;

                    case MESIBO_CALLSTATUS_INVALIDDEST:
                        s = "Invalid Destination";
                        break;

                    case MESIBO_CALLSTATUS_UNREACHABLE:
                        s = "Unreachable";
                        break;

                    case MESIBO_CALLSTATUS_OFFLINE:
                        s = "Offline";
                        break;

                    case MESIBO_CALLSTATUS_COMPLETE:
                        s = "Complete";
                        break;
                }

                if(s)
                    $scope.call_status = "Call Status: " + s;
                $scope.refresh();

                if (status & MESIBO_CALLSTATUS_COMPLETE) {
                    if ($scope.is_video_call)
                        $scope.hangupVideoCall();
                    else
                        $scope.hangupAudioCall();
                }
            }

            $scope.init_messenger = function(){
                MesiboLog("init_messenger called");
                $scope.sessionReadSummary();
                $scope.app = new MesiboApp($scope);
                $scope.call = new MesiboCall($scope);
                $scope.file = new MesiboFile($scope);
                if(isContactSync)
                    $scope.app.fetchContacts(MESIBO_ACCESS_TOKEN, 0, MESIBO_PHONES_ARRAY);
                else
                    $scope.setAvailableUsers(MESIBO_LOCAL_CONTACTS);
            }

            $scope.init_popup = function(){
                MesiboLog("init_popup called");
                $scope.selected_user.name = POPUP_DISPLAY_NAME;
                $scope.selected_user.picture = POPUP_DISPLAY_PICTURE;
                $scope.selected_user.address = POPUP_DESTINATION_USER;
                $scope.selected_user.groupid = 0;
                $scope.selected_user.activity = "";

                $scope.call = new MesiboCall($scope);
                $scope.file = new MesiboFile($scope);

                $scope.sessionReadMessages($scope.selected_user, 10000);
                $scope.scrollToLastMsg();
            }

            $scope.initMesibo = function(demo_app_name){
                $scope.mesibo = new Mesibo();
                $scope.mesiboNotify = new MesiboNotify($scope);

                //Initialize Mesibo
                $scope.mesibo.setAppName(MESIBO_APP_ID);
                $scope.mesibo.setCredentials(MESIBO_ACCESS_TOKEN);
                $scope.mesibo.setListener($scope.mesiboNotify);
                $scope.mesibo.setDatabase("mesibo.db");
                $scope.mesibo.start();

                //Initialize Application
                if(demo_app_name == "messenger")
                    $scope.init_messenger();

                if(demo_app_name == "popup"){
                    //Contact synchronization is not required for popup
                    isContactSync = false;
                    $scope.init_popup();
                }
            }

        }]);



</script>
<script type="text/javascript">
    $(document).ready(function(){
        angular.element(document.getElementById('mesibowebapp')).scope().initMesibo('popup');
        $(".refresh-bar").removeClass("none");
        $(".refresh-bar").hide();
        $(".chat_on").click(function(){
            <?php if (auth()->user() !== null) { ?>
            $(".Layout").toggle();
            $(".chat_on").hide(300);
            if(LOGGED_OUT){$(".refresh-bar").slideDown(300);}
            <?php } else { ?>
                window.location.href = "{{ route('user.show.login')}}";
            <?php }?>
        });

        $(".chat_close").click(function(){
            $(".refresh-bar").slideUp(300);
            $(".Layout").hide();
            $(".chat_on").show(300);
            $("div.Layout").scrollTop(5000);
            var div = document.getElementById('#Smallchat');
            div.scrollTop = 600;
            /*var objDiv = document.getElementById("msglist");
            objDiv.scrollTop = objDiv.scrollHeight;*/
        });

    });
</script>
@endsection
