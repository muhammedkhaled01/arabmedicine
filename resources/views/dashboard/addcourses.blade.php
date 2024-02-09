@extends('dashboard.layouts.layout')
@section('css')
    <style>
        .stars li{
            cursor: pointer;
        }
    </style>
    <link href="{{URL::asset('css/dashboard/new/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <!--Internal  Datetimepicker-slider css -->
    <link href="{{URL::asset('css/dashboard/new/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/dashboard/new/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/dashboard/new/plugins/pickerjs/picker.min.css')}}" rel="stylesheet">
    <!-- Internal Spectrum-colorpicker css -->
    <link href="{{URL::asset('css/dashboard/new/plugins/spectrum-colorpicker/spectrum.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/dashboard/new/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" type="text/css"/>
    <!---Internal Fancy uploader css-->
    <link href="{{URL::asset('css/dashboard/new/plugins/fancyuploder/fancy_fileupload.css')}}" rel="stylesheet" />
    <!---Internal  Darggable css-->
    <link href="{{URL::asset('css/dashboard/new/plugins/darggable/jquery-ui-darggable.css')}}" rel="stylesheet">

@endsection

@section('content-dashboard')
    {{-- @include('dashboard.layouts.navbar') --}}

            <div class="breadcrumb-header justify-content-between">
                <div class="my-auto">
                    <div class="d-flex">
                        <h4 class="content-title mb-0 my-auto">Courses</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ View Courses</span>
                    </div>
                </div>
			</div>
            <div class="">

                @if(Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        {{Session::get('success')}}
                    </div>
                @endif
                {{-- <form action="{{route('create-course')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white main-shadow py-4 px-5 border">
                        <h4 class="mb-4">Course Info</h4>
                        <div class="form-group">
                            <label for="cortitle">Course title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="cortitle"
                                   placeholder="Enter Course Title" value="{{old('name')}}" required>
                            @error('name')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group mt-4">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" id="description" cols="30"
                                      rows="10" required></textarea>
                            @error('description')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="bg-white main-shadow py-4 px-5 border my-4">
                        <h4 class="mb-4">Course Price</h4>
                        <div class="form-group">
                            <label for="price">Price <span class="text-danger">*</span></label>
                            <input type="text" name="price" class="form-control numeric" id="price"
                                   placeholder="Enter Course Price">
                            <label for="freecou" class="mt-3">free <span class="text-danger">*</span></label>
                            <input type="checkbox" name="free"  id="freecou" >
                            @error('price')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="bg-white main-shadow py-4 px-5 border my-4">
                        <h4 class="mb-4">Course thumbnail</h4>
                        <div class="form-group">
                            <label for="thumbnail">thumbnail <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" id="thumbnail"
                                   placeholder="Enter Course Price">
                            @error('image')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 mt-2 mb-4 " type="submit">Add Course</button>
                </form> --}}
                <form enctype="multipart/form-data" action="{{route('create-course')}}"  method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white main-shadow p-4 border">
                        <h4 class="mb-4">Course Info</h4>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label for="cortitle">Course title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cortitle" name="name"
                                    placeholder="Enter Course Title" required>
                                @error('name')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="price">Price <span class="text-danger">*</span></label>
                                <input type="text" class="form-control numeric" id="price"
                                    placeholder="Enter Course Price" name="price">
                                <label for="freecou" class="mt-3">free <span class="text-danger">*</span></label>
                                <input type="checkbox" name="free"
                                    id="freecou">
                                @error('price')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="thumbnail">thumbnail <span class="text-danger">*</span></label>
                                {{-- <input type="file" name="image" class="form-control" id="thumbnail"
                                    placeholder="Enter Course Price" required> --}}
                                <input type="file" class="dropify" name="image" id="thumbnail" data-height="200" />
                                @error('image')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="description">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" cols="30" name="description" rows="10"></textarea>
                                @error('description')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
        
        
                        <button class="btn btn-primary w-100 mt-2 " type="submit">Add Course</button>
                    </div>
                    {{-- <div class="bg-white main-shadow p-4 border my-4">
                        <h4 class="mb-4">Course Price</h4>
                        
                    </div> --}}
                    {{-- <div class="bg-white main-shadow p-4 border my-4">
                        <h4 class="mb-4">Course thumbnail</h4>
        
                    </div> --}}
                       {{-- @if($can_add_authorize)
                    <div class="bg-white main-shadow p-4 border my-4">
                        <h4 class="mb-4">Add Users</h4>
        
                    </div>
                    @endif --}}
                </form>
        
            </div>
@endsection
@section('page_js')
    <script src="{{asset('js/dashboard/course.js')}}"></script>

    <script src="{{URL::asset('js/dashboard/new/plugins/fileuploads/js/fileupload.js')}}"></script>
    <script src="{{URL::asset('js/dashboard/new/plugins/fileuploads/js/file-upload.js')}}"></script>
    <!--Internal Fancy uploader js-->
    <script src="{{URL::asset('js/dashboard/new/plugins/fancyuploder/jquery.ui.widget.js')}}"></script>
    <script src="{{URL::asset('js/dashboard/new/plugins/fancyuploder/jquery.fileupload.js')}}"></script>
    <script src="{{URL::asset('js/dashboard/new/plugins/fancyuploder/jquery.iframe-transport.js')}}"></script>
    <script src="{{URL::asset('js/dashboard/new/plugins/fancyuploder/jquery.fancy-fileupload.js')}}"></script>
    <script src="{{URL::asset('js/dashboard/new/plugins/fancyuploder/fancy-uploader.js')}}"></script>
    <!--Internal  Form-elements js-->
    <script src="{{URL::asset('js/dashboard/new/js/advanced-form-elements.js')}}"></script>

    <!-- Internal Select2.min js -->
    <script src="{{URL::asset('js/dashboard/new/plugins/select2/js/select2.min.js')}}"></script>
    <!--Internal Ion.rangeSlider.min js -->
    <!--Internal  jquery-simple-datetimepicker js -->
    <!-- Internal form-elements js -->
    <script src="{{URL::asset('js/dashboard/new/js/form-elements.js')}}"></script>
    <script src="{{ asset('js/dashboard/course.js') }}"></script>
    <script src="{{URL::asset('js/dashboard/new/js/modal.js')}}"></script>

@endsection
