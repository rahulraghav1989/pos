@extends('main')

@section('content')
        @include('includes.topbar')
        <!-- Top Bar End -->

        <!-- ========== Left Sidebar Start ========== -->
        @include('includes.sidebar')
        <div class="container" style="margin-top: 10%;">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-block">
                            <div class="text-center p-3">
                              
                                    <h1 class="error-page mt-4"><span>Not Allowed!</span></h1>
                                <h4 class="mb-4 mt-5">Sorry, you don't have permission to view this page</h4>
                                <!-- <p class="mb-4">It will be as simple as Occidental in fact, it will Occidental <br> to an English person</p> -->
                                
                            </div>
        
                        </div>
                    </div>
                                        
                </div>
            </div>
            <!-- end row -->
            <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
        </div>
 @endsection