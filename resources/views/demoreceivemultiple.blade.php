@extends('main')

@section('content')
	@include('includes.topbar')

    @include('includes.sidebar')
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
    <style type="text/css">
    	.hide{display: none;}
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
                                    <h4 class="mt-0 header-title">Demo Receive:{{$getpoitem->receiveInvoiceID}} | Docket:{{$docket}}</h4>
                                    <p class="sub-title">You are receiving Demo:{{$getpoitem->receiveInvoiceID}}</p>
                                    <p class="sub-title" style="color: #000;">Enter {{$producttype->producttypename}} For: {{$getpoitem->poreceiveproduct['productname']}}</p>
                                    <form action="{{route('addmultidemo')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="imeitoenter" value="{{$count}}">
                                        <input type="hidden" name="docketnumber" value="{{$docket}}">
                                        <input type="hidden" name="receivedquantity" value="{{$count}}">
                                        <input type="hidden" name="storeid" value="{{$storeid}}">
                                        <input type="hidden" name="drorderitemID" value="{{$getpoitem->drorderitemID}}">
                                        @for($i=1; $i<=$count; $i++)
                                        <div class="form-group">
                                            <label>Enter {{$producttype->producttypename}} {{$i}}</label>
                                            <input type="text" name="imei[]" class="form-control" required="" placeholder="Type something">
                                        </div>
                                        @endfor
                                        <div class="form-group">
                                            <div>
                                                <button type="submit" class="btn btn-primary waves-effect waves-light">
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
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
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