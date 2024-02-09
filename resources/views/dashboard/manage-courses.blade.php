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
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Courses</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Edit Course</span>
            </div>
        </div>
    </div>
    <div class="page-details mt-3">
        @if (Session::has('success'))
        <div id="ui_notifIt" class="success" style="width: 400px; opacity: 1; right: 10px;"><p><b>Success:</b> Well done Details Submitted Successfully</p></div>
        @endif
        <form id="editCourseForm" action="{{ route('course-update', $courseId->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="bg-white main-shadow p-4 border">
                <h4 class="mb-4">Course Info</h4>
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label for="cortitle">Course title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="cortitle" name="name"
                            value="{{ $courseId->name }}" placeholder="Enter Course Title" required>
                        @error('name')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="price">Price <span class="text-danger">*</span></label>
                        <input type="text" class="form-control numeric" id="price" value="{{ $courseId->price }}"
                            placeholder="Enter Course Price" name="price">
                        <label for="freecou" class="mt-3">free <span class="text-danger">*</span></label>
                        <input type="checkbox" name="free" @if ($courseId->free) checked @endif
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
    
                        @if ($courseId->image)
                            <input type="hidden" name="imageExist" value="true" />
                            <div class="border p-2 d-inline-block mt-2">
                                <img src="{{ $courseId->image }}" width="100" height="100" />
                            </div>
                        @else
                            No Image
                        @endif
                        @error('image')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    @admin
                    @if($can_add_authorize)
                    <div class="form-group col-lg-6">
                        <label class="text-danger">The chosen users will be able to add and modify lessons on this course </label>
                        <select class="form-control select2" name="users[]" id="users" multiple>
                            @foreach($users as $user)
                            <option value="{{$user->id}}" @if(in_array($user->id,$selectedUsers)) selected @endif>{{$user->email}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    @endadmin
                </div>
                <div class="form-group mt-4">
                    <label for="description">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" cols="30" name="description" rows="10">{{ $courseId->description }}</textarea>
                    @error('description')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>



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
            <div class="bg-white main-shadow p-4 border mt-3 sortable">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-4">Lectures</h4>
                    <div class="buttons-lectures mb-4">
                        <a data-effect="effect-scale" data-toggle="modal" href="#section">
                            <button type="button" class="btn btn-primary">Add Section
                        </button>
                        </a>
                        @if (count($courseId->section) > 0)
                            <a data-effect="effect-scale" data-toggle="modal" href="#lectureadd">
                                <button type="button" class="btn btn-danger" >Add Lesson
                                </button>
                            </a>

                        @endif
                    </div>
                </div>
                <?php $sectionCount = 1; ?>
                <?php $lectCount = 1; ?>
                <?php $lessonCount = 1; ?>
                <?php $index = 1; ?>

                @foreach ($courseId->section as $sec)
                    <div class="section-lecture mb-3">
                        <form class="remove" method="post" action="{{ route('delete-section', $sec->id) }}">
                            @csrf
                        </form>
                        <div class="position-relative d-flex justify-content-between align-items-center bg-light border mb-2 p-3">
                            <h5 class="m-0"><span class="fw-bold">
                                     ({{ $sec->section_name }})</span></h5>
                                    <div class="modal fade" id="section-{{ $sec->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Section</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('update-section', $sec->id) }}"
                                                        method="post">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="sectionnameedit">title <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                id="sectionnameedit" value="{{ $sec->section_name }}"
                                                                placeholder="Section Name" name="section_name"
                                                                required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                changes
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
    
                                            </div>
                                        </div>
                                    </div>
                            <div class="section-butt">
                                <a data-effect="effect-scale" data-toggle="modal" href="#section-{{ $sec->id }}">
                                    <button class="btn btn-primary rounded-0 text-light p-1" type="button">
                                        <i class="fa fa-edit">
                                        </i>
                                    </button>
                                </a>
                                <button class="btn btn-danger rounded-0 text-light p-1 removeLesson" type="button">
                                    <i class="fa fa-trash text-light removesection">
                                        <form class="remove" method="post"
                                            action="{{ route('delete-section', $sec->id) }}">
                                            @csrf
                                        </form>
                                    </i>
                                </button>
                            </div>
                        </div>
                        <div class="p-1 position-relative d-flex justify-content-between align-items-center card-draggable d-none">
                        </div>
                        @foreach ($sec->lesson->sortBy('order') as $les)

                            <div class="border p-3 position-relative mb-3 d-flex justify-content-between align-items-center card-draggable" data-id="{{ $les->id }}">
                                {{-- <img src="{{asset('images/SVG/media-play.svg')}}" width="20" height="20" alt="" --}}
                                {{-- style="cursor: pointer"> --}}
                                <div>
                                    <img src="{{ asset('images/SVG/media-play.svg') }}" width="20"
                                    height="20" alt="" style="cursor: pointer" data-toggle="modal"
                                    data-target="#video-{{ $les->id }}">
                                    <span class="fw-bold">{{ $les->lesson_name }}</span>
                                </div>
                                <div class="lecture-butt">
                                    @if($les->pdf_attach)
                                    <a href="{{$les->pdf_attach}}" target="_blank"><i class="fa fa-paperclip text-success" title="Show attached file"></i></a>
                                    @endif
                                    <a data-effect="effect-scale" data-toggle="modal" href="#lesson-{{ $les->id }}">
                                    <button class="btn rounded-0 text-primary p-1" type="button">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    </a>
                                    <button class="btn rounded-0 text-danger p-1 removeLesson" type="button">
                                        <i class="fa fa-trash">
                                            <form action="{{ route('delete-lesson', $les->id) }}" method="post"
                                                class="remove">
                                                @csrf
                                            </form>
                                        </i>
                                    </button>
                                </div>
                                {{-- modal video --}}
                                <div class="modal fade" id="video-{{ $les->id }}"
                                    aria-labelledby="exampleModalToggleLabel" tabindex="-1" aria-hidden="true"
                                    style="display: none;">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <iframe width="100%" height="315" src="{{ $les->url }}"
                                                    title="YouTube video player" frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen=""></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- modal video --}}
                                <!-- modal edit lesson here -->

                                <div class="modal fade" id="lesson-{{ $les->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Lesson</h5>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('update-lesson', $les->id) }}" enctype="multipart/form-data"
                                                    method="post">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="lessonnameedit">Lesson title <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                            id="lessonnameedit" name="lesson_name"
                                                            value="{{ $les->lesson_name }}"
                                                            placeholder="Lesson Name" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="sectionselect">Section <span
                                                                class="text-danger">*</span></label>
                                                        <select name="section_id" id="sectionselect"
                                                            class="form-control">

                                                            @foreach ($courseId->section as $sec)
                                                                <option value="{{ $sec->id }}" @if($sec->id==$les->section_id) selected @endif>
                                                                    {{ $sec->section_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="videourl">Video URL <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="url" class="form-control"
                                                            id="videourl" placeholder="URL"
                                                            value="{{ $les->url }}" required>
                                                        @error('url')
                                                            <small
                                                                class="form-text text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="videourl">Duration <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="duration"
                                                            class="form-control" id="duration"
                                                            placeholder="Duration" value="{{ $les->duration }}">
                                                        @error('duration')
                                                            <small
                                                                class="form-text text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="mcq_url">MCQ Link </label>
                                                        <input type="url" name="mcq_url" class="form-control"
                                                            id="mcq_url" placeholder="MCQ Link"
                                                            value="{{ $les->mcq_url }}">
                                                        @error('mcq_url')
                                                            <small
                                                                class="form-text text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="pdf_attach">PDF File </label>
                                                        <div class="d-flex justify-content-between">
                                                            <div class="position-relative">
                                                                <input type="file" name="pdf_attach" class="custom-file-input" id="pdf_attach"
                                                                placeholder="pdf attach" accept="application/pdf">
                                                                <label class="custom-file-label" for="pdf_attach">Choose file</label>
                                                                @error('pdf_attach')
                                                                    <small
                                                                        class="form-text text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="">
                                                                @if($les->pdf_attach)
                                                                <a class="btn btn-success w-100" target="_blank" href="{{$les->pdf_attach }}">Show attached file</a>
                                                                @else
                                                                    No attachments
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">Save
                                                            changes
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- modal edit lesson here -->
                            </div>
                        @endforeach
                    </div>
                @endforeach
                <button class="btn btn-primary w-100 my-2 editcourse" type="submit" id="editCourse">Edit
                    Course
                </button>
            </div>
        </form>

        <!-- modals here -->


        <!-- modal add section here -->

        <div class="modal fade" id="section" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Section</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('create-section') }}" method="post">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $courseId->id }}">
                            <label for="sectionname">Section title <span class="text-danger">*</span></label>
                            <input type="text" name="section_name" class="form-control" id="sectionname"
                                placeholder="Section Name" required>
                            @error('section_name')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                </button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- modal add section here -->
        <!-- modal add lesson here -->
		{{-- <div class="modal" id="modaldemo8">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content modal-content-demo">
					<div class="modal-header">
						<h6 class="modal-title">Modal Header</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<h6>Modal Body</h6>
						<p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
					</div>
					<div class="modal-footer">
						<button class="btn ripple btn-primary" type="button">Save changes</button>
						<button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
					</div>
				</div>
			</div>
		</div> --}}

        <div class="modal fade" id="lectureadd" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Lesson</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('create-lesson') }}" method="post" enctype="multipart/form-data" >
                            @csrf
                            {{-- <input type="hidden" name="section_id" value="{{$sec->id}}"> --}}
                            <div class="form-group">
                                <label for="lessonname">Lesson title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="lesson_name" id="lessonname"
                                    placeholder="Lesson Name" required>
                                @error('lesson_name')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="sectionselect" class="d-block">Section <span class="text-danger">*</span></label>
                                <select name="section_id" id="sectionselect" class="form-control select2-no-search">
                                    @foreach ($courseId->section as $sec)
                                        <option value="{{ $sec->id }}">{{ $sec->section_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="videourl">Video URL <span class="text-danger">*</span></label>
                                <input type="text" name="url" class="form-control" id="videourl"
                                    placeholder="URL" required>
                                @error('url')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="videourl">Duration <span class="text-danger">*</span></label>
                                <input type="text" name="duration" class="form-control" id="duration"
                                    placeholder="Duration">
                                @error('duration')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="mcq_url">MCQ Link </label>
                                <input type="url" name="mcq_url" class="form-control" id="mcq_url"
                                    placeholder="MCQ Link">
                                @error('mcq_url')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="pdf_attach">PDF File </label>
                                <div class="position-relative">
                               

                                    <input type="file" name="pdf_attach" class="custom-file-input" id="pdf_attach"
                                    placeholder="pdf attach" accept="application/pdf">
                                    <label class="custom-file-label" for="pdf_attach">Choose file</label>
                                </div>
                                @error('pdf_attach')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                </button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- modals here -->
    </div>
@endsection

@section('js')
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

    <script src="{{URL::asset('js/dashboard/new/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{URL::asset('js/dashboard/new/plugins/jquery.maskedinput/jquery.maskedinput.js')}}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{URL::asset('js/dashboard/new/plugins/spectrum-colorpicker/spectrum.js')}}"></script>
    <!-- Internal Select2.min js -->
    <script src="{{URL::asset('js/dashboard/new/plugins/select2/js/select2.min.js')}}"></script>
    <!--Internal Ion.rangeSlider.min js -->
    <script src="{{URL::asset('js/dashboard/new/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
    <!--Internal  jquery-simple-datetimepicker js -->
    <script src="{{URL::asset('js/dashboard/new/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')}}"></script>
    <!-- Ionicons js -->
    <script src="{{URL::asset('js/dashboard/new/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js')}}"></script>
    <!--Internal  pickerjs js -->
    <script src="{{URL::asset('js/dashboard/new/plugins/pickerjs/picker.min.js')}}"></script>
    <!-- Internal form-elements js -->
    <script src="{{URL::asset('js/dashboard/new/js/form-elements.js')}}"></script>
    <script src="{{ asset('js/dashboard/course.js') }}"></script>
    <script src="{{URL::asset('js/dashboard/new/js/modal.js')}}"></script>
    <script src="{{URL::asset('js/dashboard/new/plugins/darggable/jquery-ui-darggable.min.js')}}"></script>
    <script src="{{URL::asset('js/dashboard/new/plugins/darggable/darggable.js')}}"></script>
    
@endsection

