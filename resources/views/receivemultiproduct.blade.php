@extends('main')

@section('content')
	@include('includes.topbar')

    @include('includes.sidebar')
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
    	function isNumber(evt) {
		    evt = (evt) ? evt : window.event;
		    var charCode = (evt.which) ? evt.which : evt.keyCode;
		    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		        return false;
		    }
		    return true;
		}
    </script>
    <script type="text/javascript">
        /*$(document).ready(function() {
          if($(".imei").hasClass("has-error"))
          {
            console.log('disabled');
            $('#submit').attr('disabled', 'disabled');
          }
          else
          {
            $('#submit').attr('disabled', false);
          }
        });*/
        /*$(document).ready(function(){

         if($("#imei").find('.has-error'))
         {
            $('#submit').prop('disabled', 'disabled');
         }
         else
         {
            $('#submit').prop('disabled', false);
         }
        });*/
        $(document).ready(function(){
          $("form").submit(function(){
            var myVar = '';
            var myVar = $("#imei").find('.has-error').val();
            console.log(myVar);
            if(myVar != null)
            {
               alert("errors found "+myVar+"");
              return false;
            }
          });
        });
    </script>
    <style type="text/css">
    	.hide{display: none;}
        .has-error{
            border: thin solid #ef0d0d;
        }
    </style>
    <div id="wrapper">
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="page-title-box">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h4 class="page-title">Purchase Order</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
                                    <li class="breadcrumb-item active">Purchase Order</li>
                                </ol>
                            </div>
                        </div> <!-- end row -->
                    </div>
                    <!-- end page-title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
                            	@if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            {{$error}}
                                        </div>
                                    @endforeach
                                @endif
                                <script type="text/javascript">
                                    function isNumber(evt) {
                                        evt = (evt) ? evt : window.event;
                                        var charCode = (evt.which) ? evt.which : evt.keyCode;
                                        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                                            return false;
                                        }
                                        return true;
                                    }
                                </script>
                                <div class="card-body">
                                    <h4 class="mt-0 header-title">PO:{{$getpoitem->ponumber}} | Docket:{{$docket}}</h4>
                                    <p class="sub-title">You are receiving PO:{{$getpoitem->ponumber}}</p>
                                    <p class="sub-title" style="color: #000;">Enter {{$producttype->producttypename}} For: {{$getpoitem->poreceiveproduct['productname']}}</p>
                                    <form action="{{route('addmultiproduct')}}" method="post" id="imei">
                                        @csrf
                                        <input type="hidden" name="imeitoenter" value="{{$count}}">
                                        <input type="hidden" name="docketnumber" value="{{$docket}}">
                                        <input type="hidden" name="receivedquantity" value="{{$count}}">
                                        <input type="hidden" name="storeid" value="{{$storeid}}">
                                        <input type="hidden" name="poitemid" value="{{$getpoitem->poitemID}}">
                                        @for($i=1; $i<=$count; $i++)
                                        <div class="form-group">
                                            <label>Enter {{$producttype->producttypename}} {{$i}}</label>
                                            @if($producttype->productrestrictiontype == 1)
                                            <input type="text" name="imei[]" id="number" class="form-control checkvalid{{$i}}" required="" placeholder="Type something" minlength="{{$producttype->productrestrictionword}}" maxlength="{{$producttype->productrestrictionword}}" onkeypress="return isNumber(event)">
                                            <span class="error_email{{$i}}" id="error_email"></span>
                                            <script>
                                            $(document).ready(function(){

                                             $('.checkvalid{{$i}}').keyup(function(){
                                              var error_email = '';
                                              var imei = $('.checkvalid{{$i}}').val();
                                              var _token = $('input[name="_token"]').val();
                                              var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                              $.ajax({
                                                url:"{{ route('ajaxcheckimei') }}",
                                                method:"POST",
                                                data:{imei:imei, _token:_token},
                                                success:function(result)
                                                {
                                                 if(result == 'unique')
                                                 {
                                                  $('.error_email{{$i}}').html('<label class="text-success">IMEI Looking Good</label>');
                                                  $('.checkvalid{{$i}}').removeClass('has-error');
                                                  /*$('.submit').attr('disabled', false);*/
                                                 }
                                                 else
                                                 {
                                                  $('.error_email{{$i}}').html('<label class="text-danger">IMEI Already Is With Us.</label>');
                                                  $('.checkvalid{{$i}}').addClass('has-error');
                                                  /*$('.submit').attr('disabled', 'disabled');*/
                                                 }
                                                }
                                               })
                                             });
                                             
                                            });
                                            </script>
                                              @if($producttype->addtionalproducttype == 1)
                                              <br>
                                              <label>Enter {{$producttype->add_producttypename}} {{$i}}</label>
                                              <input type="text" name="sim[]" id="number" class="form-control checkvalidsim{{$i}}" required="" placeholder="Type something" minlength="{{$producttype->add_productrestrictionword}}" maxlength="{{$producttype->add_productrestrictionword}}" onkeypress="return isNumber(event)">
                                              <span class="error_emailsim{{$i}}" id="error_email"></span>
                                              <script>
                                            $(document).ready(function(){

                                             $('.checkvalidsim{{$i}}').keyup(function(){
                                              var error_email = '';
                                              var imei = $('.checkvalidsim{{$i}}').val();
                                              var _token = $('input[name="_token"]').val();
                                              var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                              $.ajax({
                                                url:"{{ route('ajaxchecksim') }}",
                                                method:"POST",
                                                data:{imei:imei, _token:_token},
                                                success:function(result)
                                                {
                                                 if(result == 'unique')
                                                 {
                                                  $('.error_emailsim{{$i}}').html('<label class="text-success">Sim Number Looking Good</label>');
                                                  $('.checkvalidsim{{$i}}').removeClass('has-error');
                                                  /*$('.submit').attr('disabled', false);*/
                                                 }
                                                 else
                                                 {
                                                  $('.error_emailsim{{$i}}').html('<label class="text-danger">Sim Number Already Is With Us.</label>');
                                                  $('.checkvalidsim{{$i}}').addClass('has-error');
                                                  /*$('.submit').attr('disabled', 'disabled');*/
                                                 }
                                                }
                                               })
                                             });
                                             
                                            });
                                            </script>
                                              @endif
                                            @else
                                            <input type="text" name="imei[]" class="form-control checkvalid{{$i}}" required="" placeholder="Type something" minlength="{{$producttype->productrestrictionword}}" maxlength="{{$producttype->productrestrictionword}}">
                                            <span class="error_email{{$i}}" id="error_email"></span>
                                            <script>
                                            $(document).ready(function(){

                                             $('.checkvalid{{$i}}').keyup(function(){
                                              var error_email = '';
                                              var imei = $('.checkvalid{{$i}}').val();
                                              var _token = $('input[name="_token"]').val();
                                              var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                              $.ajax({
                                                url:"{{ route('ajaxcheckimei') }}",
                                                method:"POST",
                                                data:{imei:imei, _token:_token},
                                                success:function(result)
                                                {
                                                 if(result == 'unique')
                                                 {
                                                  $('.error_email{{$i}}').html('<label class="text-success">IMEI Looking Good</label>');
                                                  $('.checkvalid{{$i}}').removeClass('has-error');
                                                  /*$('.submit').attr('disabled', false);*/
                                                 }
                                                 else
                                                 {
                                                  $('.error_email{{$i}}').html('<label class="text-danger">IMEI Already Is With Us.</label>');
                                                  $('.checkvalid{{$i}}').addClass('has-error');
                                                  /*$('.submit').attr('disabled', 'disabled');*/
                                                 }
                                                }
                                               })
                                             });
                                             
                                            });
                                            </script>
                                              @if($producttype->addtionalproducttype == 1)
                                              <br>
                                              <label>Enter {{$producttype->add_producttypename}} {{$i}}</label>
                                              <input type="text" name="sim[]" id="number" class="form-control checkvalidsim{{$i}}" required="" placeholder="Type something" minlength="{{$producttype->add_productrestrictionword}}" maxlength="{{$producttype->add_productrestrictionword}}" onkeypress="return isNumber(event)">
                                              <span class="error_emailsim{{$i}}" id="error_email"></span>
                                              <script>
                                              $(document).ready(function(){

                                               $('.checkvalidsim{{$i}}').keyup(function(){
                                                var error_email = '';
                                                var imei = $('.checkvalidsim{{$i}}').val();
                                                var _token = $('input[name="_token"]').val();
                                                var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                                $.ajax({
                                                  url:"{{ route('ajaxchecksim') }}",
                                                  method:"POST",
                                                  data:{imei:imei, _token:_token},
                                                  success:function(result)
                                                  {
                                                   if(result == 'unique')
                                                   {
                                                    $('.error_emailsim{{$i}}').html('<label class="text-success">Sim Number Looking Good</label>');
                                                    $('.checkvalidsim{{$i}}').removeClass('has-error');
                                                    /*$('.submit').attr('disabled', false);*/
                                                   }
                                                   else
                                                   {
                                                    $('.error_emailsim{{$i}}').html('<label class="text-danger">Sim Number Already Is With Us.</label>');
                                                    $('.checkvalidsim{{$i}}').addClass('has-error');
                                                    /*$('.submit').attr('disabled', 'disabled');*/
                                                   }
                                                  }
                                                 })
                                               });
                                               
                                              });
                                              </script>
                                              @endif
                                            @endif
                                        </div>
                                        @endfor
                                        <div class="form-group">
                                            <div>
                                                <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light submit">
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                    </form>
    
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
                <!-- container-fluid -->

            </div>
            <!-- content -->

            @include('includes.footer')

        </div>
    </div>
    <script src="{{ asset('posview') }}/assets/js/po-calculation.js"></script>
    
    <!-- <script type="text/javascript">
	    $(document).ready(function() {
	      $(".add-more").click(function(){ 
	          var html = $(".copy").html();
	          $(".after-add-more").after(html);
	      });

	      $("body").on("click",".remove",function(){ 
	          $(this).parents(".removeable").remove();
	      });
	    });
	</script> -->
@endsection
        