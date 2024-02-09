@extends('layouts.layout')
@section('page_css')
    <style>
        .search-form i {
            left: 15px;
            top: 11px;
        }
    </style>
@endsection
@section('content')
    @include('layouts.navbar')
    @include('layouts.banner')
    {{-- <div class="container mt-5">
        <h3>{{$title}}</h3>
        <div class="row">
            @foreach($data as $item)
                <div class="col-lg-3 my-3">
                    <div class="card course-item-card m-auto" style="width: 230px;">
                        <a href="{{url('course-view/'.$item->id)}}" class="text-decoration-none text-dark">
                            <img src="{{$item->image}}" class="card-img-top course-img"
                                 alt="...">
                            <div class="card-body py-3 border-top">
                                <h5 class="card-title font-family text-start mb-3" dir="ltr">{{$item ->name}}</h5>
                                <p class="font-family text-start mb-2"
                                   dir="ltr">{{\Str::words($item -> description , 15)}}</p>
                                <div class="d-flex justify-content-between mt-4">
                                    <p class="font-family text-end m-0 text-success" dir="ltr">
                                        <span class="fw-bold">
                                        @if($item ->free == true )
                                                <span>مجاني</span>
                                            @else
                                                {{$item ->price}} EGP
                                            @endif
                                        </span>
                                    </p>
                                    <span class="text-end font-family text-danger" dir="ltr"><i
                                            class="fa fa-graduation-cap"
                                            aria-hidden="true"></i>{{$item->users_count}}</span>

                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach

        </div>
    </div> --}}
    <section class="course-area pt-115 pb-110">
        <div class="container">
           <div class="row">
              <div class="col-md-12">
                 <div class="section-title mb-65">
                    <h2 class="tp-section-title mb-20">Explore Popular Courses</h2>
                 </div>
              </div>
           </div>
           <div class="row justify-content-center">
            @foreach($data as $key => $item)
            @if($key < 6)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="tpcourse mb-40">
                   <div class="tpcourse__thumb p-relative w-img fix">
                      <a href="{{url('course-view/'.$item->id)}}"><img src="{{$item->image}}" alt="course-thumb"></a>
                   </div>
                   <div class="tpcourse__content">
                      <div class="tpcourse__avatar d-flex align-items-center mb-20">
                         <h4 class="tpcourse__title"><a href="{{url('course-view/'.$item->id)}}">{{$item->name}}</a></h4>
                      </div>
                      <div class="tpcourse__meta pb-15 mb-20">
                         <ul class="d-flex align-items-center">
                            {{-- <li><img src="{{asset('images/icon/c-meta-01.png')}}" alt="meta-icon"> <span>{{$item->name}} Classes</span></li> --}}
                            <li><img src="{{asset('images/icon/c-meta-02.png')}}" alt="meta-icon"> <span>{{$item->users_count}} Students</span></li>
                            <li><img src="{{asset('images/icon/c-meta-03.png')}}" alt="meta-icon"> <span>{{$item->rate}}</span></li>
                         </ul>
                      </div>
                      <div class="tpcourse__category d-flex align-items-center justify-content-between">
                         <h5 class="tpcourse__course-price">
                            @if($item ->free == true )
                            <span>مجاني</span>
                        @else
                            {{$item ->price}} EGP
                        @endif
                         </h5>
                      </div>
                   </div>
                </div>
             </div>
            @else
               @break
            @endif
            @endforeach
           </div>
           <div class="row text-center">
              <div class="col-lg-12">
                 <div class="course-btn mt-20"><a class="tp-btn" href="{{route('courses')}}">Browse All Courses</a></div>
              </div>
           </div>
        </div>
     </section>

     <section class="tp-counter-area bg-bottom grey-bg pt-120 pb-60" data-background="assets/img/bg/shape-bg-1.png" style="background-image: url(&quot;assets/img/bg/shape-bg-1.png&quot;);">
        <div class="container">
           <div class="row">
              <div class="col-xl-3 col-md-6">
                 <div class="counter-item mb-60 text-center">
                    <div class="counter-item__icon mb-25">
                       <i class="fi fi-rr-user"></i>
                    </div>
                    <div class="counter-item__content">
                       <h4 class="counter-item__title"><span class="counter">102</span>K</h4>
                       <p>Worldwide Students</p>
                    </div>
                 </div>
              </div>
              <div class="col-xl-3 col-md-6">
                 <div class="counter-item mb-60 text-center">
                    <div class="counter-item__icon mb-25">
                       <i class="fi fi-rr-document"></i>
                    </div>
                    <div class="counter-item__content">
                       <h4 class="counter-item__title"><span class="counter">8</span>+</h4>
                       <p>Years Experience</p>
                    </div>
                 </div>
              </div>
              <div class="col-xl-3 col-md-6">
                 <div class="counter-item mb-60 text-center">
                    <div class="counter-item__icon mb-25">
                       <i class="fi fi-rr-apps"></i>
                    </div>
                    <div class="counter-item__content">
                       <h4 class="counter-item__title"><span class="counter">271</span>+</h4>
                       <p>Professional Courses</p>
                    </div>
                 </div>
              </div>
              <div class="col-xl-3 col-md-6">
                 <div class="counter-item mb-60 text-center">
                    <div class="counter-item__icon mb-25">
                       <i class="fi fi-rr-star"></i>
                    </div>
                    <div class="counter-item__content">
                       <h4 class="counter-item__title"><span class="counter">1.7</span>K+</h4>
                       <p>Beautiful Review</p>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </section>

     <section class="course-area pt-115 pb-110">
        <div class="container">
           <div class="row">
              <div class="col-md-12">
                 <div class="section-title mb-65">
                    <h2 class="tp-section-title mb-20">Latest Courses</h2>
                 </div>
              </div>
           </div>
           <div class="row justify-content-center">
            @foreach($data as $key => $item)
            @if($key < 3)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="tpcourse mb-40">
                   <div class="tpcourse__thumb p-relative w-img fix">
                      <a href="{{url('course-view/'.$item->id)}}"><img src="{{$item->image}}" alt="course-thumb"></a>
                   </div>
                   <div class="tpcourse__content">
                      <div class="tpcourse__avatar d-flex align-items-center mb-20">
                         <h4 class="tpcourse__title"><a href="{{url('course-view/'.$item->id)}}">{{$item->name}}</a></h4>
                      </div>
                      <div class="tpcourse__meta pb-15 mb-20">
                         <ul class="d-flex align-items-center">
                            {{-- <li><img src="{{asset('images/icon/c-meta-01.png')}}" alt="meta-icon"> <span>{{$item->name}} Classes</span></li> --}}
                            <li><img src="{{asset('images/icon/c-meta-02.png')}}" alt="meta-icon"> <span>{{$item->users_count}} Students</span></li>
                            <li><img src="{{asset('images/icon/c-meta-03.png')}}" alt="meta-icon"> <span>{{$item->rate}}</span></li>
                         </ul>
                      </div>
                      <div class="tpcourse__category d-flex align-items-center justify-content-between">
                         <h5 class="tpcourse__course-price">
                            @if($item ->free == true )
                                <span>مجاني</span>
                            @else
                                {{$item ->price}} EGP
                            @endif
                         </h5>
                      </div>
                   </div>
                </div>
             </div>
            @else
               @break
            @endif
            @endforeach
           </div>
        </div>
     </section>
     
@endsection

