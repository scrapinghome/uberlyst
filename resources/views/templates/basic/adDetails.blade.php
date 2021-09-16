@extends($activeTemplate.'layouts.frontend')

@push('style')
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />-->

<style>
    
    /* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-dialog {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
    
</style>


        <style>
           .ad-details-widget .full-star {
                    color: gold;
                    font-size: 30px;
                    padding: 10px 3px 10px 0;
                }
                
                .ad-details-widget .fas {
                    font-size: 30px;
                    padding: 10px 3px 10px 0;
                }
                
            /*.star-container {*/
            /*padding-left: 0;*/
            /*  display: flex;*/
            /*  width: 218px;*/
            /*  flex-direction: row-reverse;*/
            /*}*/
            
            /*.star-container .star {*/
            /*}*/
            
            /*.star-container .star:before {*/
            /*  content: "\f005";*/
            /*  font-family: fontAwesome;*/
            /*  font-size: 40px;*/
            /*  position: relative;*/
            /*  display: block;*/
            /*  color: #aaa;*/
            /*}*/
            
            /*.star-container .star:after {*/
            /*  content: "\f005";*/
            /*  font-family: fontAwesome;*/
            /*  position: absolute;*/
            /*  top: 1078px;*/
            /*  font-size: 40px;*/
            /*  color: gold;*/
            /*  opacity: 0;*/
            /*}*/
            
            /*@media (max-width: 480px) {*/
            /*    .star-container .star:after {*/
            /*      content: "\f005";*/
            /*      font-family: fontAwesome;*/
            /*      position: absolute;*/
            /*      top: 128px;*/
            /*      font-size: 40px;*/
            /*      color: gold;*/
            /*      opacity: 0;*/
            /*    }*/
            /*}*/
            
            /*.star-container .star:hover:after,*/
            /*.star-container .star:hover ~ .star:after,*/
            /*.star-container .star.star__checked:after,*/
            /*.star-container .star.star__checked ~ .star:after{*/
            /*  opacity: 1;*/
            /*}*/
        </style>
        
@endpush


@section('content')
<section class="pt-50 pb-50">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <ul class="page-link-inline-menu">
            <li>@lang('Home')</li>
            <li>@lang('All ads')</li>
            <li>{{$ad->division}}</li>
            <li>{{$ad->district}}</li>
            <li>{{$ad->subcategory->category->name}}</li>
            <li>{{ ucwords($ad->subcategory->name) }}</li>
          </ul>
        </div>
      </div>
      <div class="category-details-wrapper">
        <div class="row mb-4">
          <div class="col-lg-8">
            <h3 class="ad-details-title mb-2">{{__($ad->title)}}</h3>
            <ul class="meta-list">
              <li>
                <i class="las la-clock"></i>
                <span>{{diffForHumans($ad->created_at)}}</span>
              </li>
              <li>
                <i class="las la-user"></i>
                <a href="javascript:void(0)">{{ ucwords($ad->user->fullname) }}</a> @if($ad->user->profile_verified) &nbsp; <i style="color:#43ced2 !important;" class="fas fa-check-circle"></i> @endif
              </li>
              <li>
                <i class="las la-map-marker"></i>
                  @if ($ad->district && $ad->division)
                    <span>{{$ad->district}}, {{$ad->division}}</span>
                  @elseif ($ad->division)
                    <span>{{$ad->division}}</span>
                  @else
                  <span>{{$ad->district}}</span>
                  @endif
              </li>
            </ul>
          </div>
          <div class="col-md-5 mt-md-0 mt-3">
          </div>
        </div>
        <div class="row justify-content-between">
          <div class="col-lg-8">
            <div class="ad-details-content-wrapper">
              <div class="ad-details-thumb-area">
                <h5 class="ad-details-price">{{$general->cur_sym}}{{getAmount($ad->price)}}</h5>
                <div class="main-thumb-slide">
                   
                <div class="main-thumb" id="changeImage">
                    <img src="{{getImage('assets/images/item_image/'.$ad->prev_image)}}" alt="image">
                    <a href="{{getImage('assets/images/item_image/'.$ad->prev_image)}}" class="fullview-image" data-rel="lightcase:myCollection:slideshow"></a>
                </div>

                </div>
                 <!--main-thumb-slider end -->
                <div class="ad-details-nav-slider mt-4">
                    @foreach ($ad->images as $image)
                    <div class="ad-details-nav-thumb">
                        <img onclick="changeImage(this)" src="{{getImage('assets/images/item_image/'.$image->image,'200x200')}}" alt="image">
                    </div>
                    @endforeach

                </div>
                <!-- ad-details-nav-slider end -->
              </div>
             
              <div class="ad-details-content">
                <h4>@lang('Ad Overview')</h4>
                <ul class="caption-list-two mt-3">
                  @if($ad->use_condition)
                    <li>
                        <span class="caption">@lang('Condition')</span>
                        <span class="value">{{($ad->use_condition == 1) ? 'New':'Used'}}</span>
                    </li>
                  @endif
                  
                  <li>
                    <span class="caption">@lang('Type')</span>
                    @if ($ad->type == 1)
                    <span class="value">Sell</span>
                    @elseif ($ad->type == 2)
                    <span class="value">Rent</span>
                    @elseif ($ad->type == 3)
                    <span class="value">Jobs</span>
                    @elseif ($ad->type == 4)
                    <span class="value">Community</span>
                    @endif
                  </li>
                  @if (isset($fields))
                  @foreach ($fields as $key => $field)
                    @if(is_array($field))
                       <li>
                           <span class="caption">{{__(ucwords(str_replace('_',' ',$key)))}}</span>
                            <span class="value">
                                @foreach ($field as $k => $item)
                                    {{$item}} @if(!$loop->last) ,@endif
                                @endforeach
                            </span>
                        </li>
                    @else
                        <li>
                            <span class="caption">{{__(ucwords(str_replace('_',' ',$key)))}}</span>
                            <span class="value">{{$field}}</span>
                        </li>
                    @endif
                    
                  @endforeach
                  @endif
                </ul>
             

                <h4 class="mt-5">@lang('Description')</h4>
                <p class="mt-2">
                  @php
                      echo $ad->description;
                  @endphp
                </p>
                <hr>

              </div>
            
              <button class="ad-details-show-btn mt-3">
                <span class="text-one">@lang('Show Details')</span>
                <span class="text-two">@lang('Show Less')</span>
              </button>

            </div>

            <div class="my-5 text-center">
              @php
                    echo advertisements('970x90');
              @endphp

            </div>

            @if ($ad->relatedProducts()->count() > 1)
            <h4 class="mt-5">@lang('Related Ads')</h4>
            <div class="related-ad-slider mt-3">
              @forelse ($ad->relatedProducts() as $item)
                  @php
                      $slug = $item->subcategory->slug;
                  @endphp
                @if ($item->id != $ad->id)
                <div class="single-slide">
                  <div class="list-item related--ad">
                    <div class="list-item__thumb">
                      <a href="{{route('ad.details',$item->slug)}}"><img src="{{getImage('assets/images/item_image/'.$item->prev_image,'256x230')}}" alt="image"></a>
                    </div>
                    <div class="list-item__wrapper">
                      <div class="list-item__content">
                        <a class="cat-title" href="{{url('/ads/')."/$slug"."?location=".request()->input('location')}}" class="category"><i class="las la-tag"></i> {{__($item->subcategory->name)}}</a>
                        <h6 class="title" data-toggle="tooltip" title="{{__($item->title)}}"><a  href="{{route('ad.details',$item->slug)}}">{{__($item->title)}}</a></h6>
                      </div>
                      <div class="list-item__footer mt-2">
                        <div class="price">{{$general->cur_sym}}{{getAmount($item->price)}}</div>
                      </div>
                    </div>
                  </div><!-- list-item end -->
                </div><!-- single-slide end -->
                @endif
               @empty
                <h6 class="mt-5">@lang('No Related Ads')</h6>
              @endforelse
             
            </div>
            @endif
          </div>
          <div class="col-lg-4 col-xxl-3 mt-lg-0 mt-5">
            <div class="ad-details-sidebar">
              <div class="ad-save">
                @auth
                <div class="mb-4">
                    @if(auth()->id() != $ad->user_id)
                      @if ( !auth()->user()->userFavourite($ad->id))
                        <button type="button" data-userid="{{auth()->id()}}" data-adid="{{$ad->id}}" class="ad-save__btn save"><i class="las la-bookmark"></i> @lang('Save Ad')</button>
                      @else
                        <button type="button" class="ad-save__btn bg--base text-white"><i class="las la-check"></i> @lang('Saved')</button>
                      @endif
                    @endif
                  </div>
                @endauth

                @guest
                <div class="mb-4">
                  <a href="{{route('user.login')}}"  class="ad-save__btn text-dark"><i class="las la-bookmark"></i> @lang('Save Ad')</a>
                </div>
                @endguest
              </div>

              <div class="ad-details-widget">
                <h6 class="ad-details-widget__title">@lang('Seller Details')</h6>
                <div class="ad-details-widget__body">
                  <ul class="user-info-list">
                    <li>
                      <div class="icon">
                        <i class="las la-user"></i>
                      </div>
                      <div class="content">
                        <span class="caption">@lang('For sale by')</span>
                        <h6 class="value">{{ ucwords($ad->user->fullname) }} @if($ad->user->profile_verified) <i style="color:#43ced2 !important; font-size: 18px; line-height: 0px;" class="fas fa-check-circle"></i> @endif</h6>
                      </div>
                    </li>
                    <li>
                      <div class="icon">
                        <i class="las la-map-marker"></i>
                      </div>
                      <div class="content">
                        <span class="caption">@lang('Location')</span>
                          @if ($ad->division)
                            <h6 class="value">{{$ad->division}}</h6>
                          @else
                          <h6 class="value">{{$ad->district}}</h6>
                          @endif
                      </div>
                    </li>
                    <li class="has--link">
                      
                      <div class="icon">
                        <i class="las la-phone-volume"></i>
                      </div>

                      @if ($ad->hide_contact == 1)
                      <div class="content">
                        <a href="javascript:void(0)" class="btn btn-sm btn--dark show"><i class="las la-eye"></i> @lang('Show Contact')</a>
                        <span class="caption hide d-none">@lang('Contact Number')</span>
                        <h6 class="value hide-value d-none">{{$ad->contact_num}}</h6>
                      </div>
                      @else
                      <div class="content">
                        <span class="caption">@lang('Contact Number')</span>
                        <h6 class="value">{{$ad->contact_num}}</h6>
                      </div>
                      @endif
                    </li>
                    
                    <!-- to add reply feature -->
                    <li>
                      <div class="icon">
                        <i class="las la-map-marker"></i>
                      </div>
                      <div class="content">
                          <button type="button" class="btn btn-md btn--base d-lg-inline-flex align-items-center m-1" id="modalBtn" data-toggle="modal" data-target="#exampleModalCenter">
                        <!--span class="caption">@lang('Location')</span-->
                          <h6  class="value">Contact seller</h6>
                          </button>
                      </div>
                    </li>
                    
                  </ul>
                </div>
              </div>
              

                <!-- Add Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{route('buyer_request.submit',$ad->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg--primary">
                    <h5 class="modal-title text-white" id="exampleModalLabel">@lang('Contact seller')</h5>
                    
                    </div>
                        <div class="modal-body">
                            <input type="hidden" name="category_id" value="{{$ad->id}}">
                            <div class="form-group">
                                <label >@lang('Name')</label>
                                <input type="text" class="form-control" name="Name"  placeholder="@lang('Your name')">
                            </div>
                            <div class="form-group">
                                <label >@lang('Phone')</label>
                                <input type="text" class="form-control" name="Phone"  placeholder="@lang('Your phone')">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">@lang('Email') </label>
                                <input type="email" class="form-control" name="email" placeholder="@lang('Your email')">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">@lang('Message') </label>
                                <textarea name="Message" class="form-control" placeholder="@lang('Enter Your message to seller')"></textarea>
                            </div>
                            
                        </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn--dark" id="close" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--primary">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

          
           

              <div class="ad-details-widget mt-4">
                <h6 class="ad-details-widget__title"><i class="las la-external-link-alt"></i> @lang('Share')</h6>
                <div class="ad-details-widget__body">
                    <ul class="post__share">
                      <li><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{urlencode(url()->current()) }}"><i class="lab la-facebook-f"></i></a></li>
                   
                      <li><a target="_blank" href="http://pinterest.com/pin/create/button/?url={{urlencode(url()->current()) }}&description={{ __($ad->title) }}&media={{ getImage('assets/images/item_image/'.$ad->prev_image) }}"><i class="lab la-pinterest"></i></a></li>
                     
                      <li><a target="_blank" href="https://twitter.com/intent/tweet?text=my share text&amp;url={{urlencode(url()->current()) }}"><i class="lab la-twitter"></i></a></li>
                    </ul>
                </div>
              </div>


              <div class="ad-details-widget mt-4">
                <h6 class="ad-details-widget__title"> @lang('Customer Reviews')</h6>
                <div class="ad-details-widget__body customer-review">
                    <h4 style="text-align: center;">Customer Reviews</h4>
                    @if ($ad->user->reviews->count() > 0)
                    <span><i class="full-star fas fa-star"></i><i class="full-star fas fa-star"></i><i class="full-star fas fa-star"></i><i class="full-star fas fa-star"></i><i class="full-star fas fa-star"></i> <small style="vertical-align: text-bottom;">{{ $ad->user->reviews->sum('rating') / $ad->user->reviews->count() }} out of 5</small></span> 
                    @else
                    <span><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i> <small style="vertical-align: text-bottom;"></small></span> 
                    @endif
                    <p style="text-align: center;">{{ $ad->user->reviews->count()}} customer ratings</p>
                    <!--<h6>Review this ad</h6>-->
                    <!--<p>Share your thoughts with other customers</p>-->
                    <!--<button style="margin-top: 15px;" class="btn btn-sm btn--primary w-100 write-review"> @lang('Rate Us')</button>-->
                    <!--<a href="#" class="btn btn-sm btn-default">Write a customer review</a>-->
                </div>
                <!--<div class="ad-details-widget__body customer-rating d-none">-->
                <!--    <form action="/feedback" method="post">-->
                <!--    @csrf-->
                <!--    <div class="row">-->
                <!--        <div class="form-group">-->
                <!--            <div class="star-container">-->
                <!--                <div class="star"></div>-->
                <!--                <div class="star"></div>-->
                <!--                <div class="star"></div>-->
                <!--                <div class="star"></div>-->
                <!--                <div class="star"></div>-->
                <!--             </div>-->
                             <!--<h1 class="rating">0/5</h1>-->

                <!--            <input type="hidden" class="form--control" name="ad_id" value={{ $ad->id }}>-->
                <!--            <input type="hidden" class="form--control rating" id="rating" name="rating">-->
                <!--        </div>-->
                <!--    </div>-->
                    
                <!--    <div class="form-group row">-->
                <!--        <div class="col-sm-12 text-center">-->
                <!--            <button type="submit" class="btn  btn--base btn-sm w-100">Submit</button>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</form>-->
                <!--</div>-->
              </div>


              <div class="ad-details-content-footer mt-4">
                <div class="left m-lg-0 m-2">

              @if ($ad->featured == 1)
                <a href="javascript:void(0)" class="btn btn-md btn--success w-100"><i class="las la-bullhorn"></i> @lang('Featured')</a>
              @elseif($ad->promoted())
                <a href="javascript:void(0)" class="btn btn-md btn--warning w-100"><i class="las la-bullhorn"></i> @lang('Requested')</a>
              @else
                <a href="{{route('user.promote.ad.packages',$ad->slug)}}" class="btn btn-md btn--primary w-100"><i class="las la-bullhorn"></i> @lang('Promote this ad')</a>
              @endif
                
              </div>

              @auth
              @if ($ad->user != auth()->user())
              <div class="right m-2 m-lg-0 mt-lg-3">
                      @if ($ad->userReport(auth()->id()))
                        <a href="javascript:void(0)"  class="btn btn-md btn--dark w-100"><i class="las la-flag"></i></i> @lang('Reported')</a>
                      @else
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#adReportModal" class="btn btn-md btn--danger w-100"><i class="las la-flag"></i></i> @lang('Report this ad')</a>
                      @endif
                    </div>
                    @endif
                @endauth
                @guest
                <div class="right m-2 m-lg-0 mt-lg-3">
                  <a href="{{route('user.login')}}" class="btn btn-md btn--danger w-100"><i class="las la-flag"></i></i> @lang('Report this ad')</a>
                </div>
                @endguest
              </div>

              <div class="mt-4 text-center d-sm-none d-lg-block">
                @php
                    echo advertisements('300x600');
                @endphp
              </div>

              <div class="mt-4 text-center d-sm-none d-lg-block">
                @php
                    echo advertisements('300x250');
                @endphp
              </div>
              <div class="d-none d-sm-block d-lg-none mt-4 text-center">
                @php
                    echo advertisements('970x90');
                @endphp
              </div>
            </div><!-- ad-details-sidebar end -->
          </div>
        </div>
      </div>
      
      
      
      
         <!-- edit Modal -->
    <!--div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg--primary">
                    <h5 class="modal-title text-white" id="exampleModalLabel">@lang('Edit Sub Category')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="category_id" value="{{$ad->id}}">
                        <div class="form-group">
                            <label >@lang('Name')</label>
                            <input type="text" class="form-control" name="name"  placeholder="@lang('Name')">
                        </div>
                        <div class="form-group">
                            <label for="my-select">@lang('Select Type')*</label>
                            <select id="my-select" class="form-control type" name="type">
                                <option value="1">@lang('Sell')</option>
                                <option value="2">@lang('Rent')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label >One Time Payment</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="one_time"  placeholder="One Time Payment">
                        </div>
                        
                        <div class="form-group">
                            <label >Per Month Payment</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="per_month"  placeholder="Per Month Payment">
                        </div>
                        <div class="form-group">
                            <label >Per 6 Months Payment</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="per_six_month"  placeholder="Per 6 Months Payment">
                        </div>
                        <div class="form-group">
                            <label >Per Year Payment</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="per_year"  placeholder="Per Year Payment">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Status') </label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" name="status">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
      </div-->
      
      
      
    </div>
    
    
    
     <!--  to add reply feature -->
    
    
  </section>
@endsection

@push('script-lib')
    <script src="{{asset($activeTemplateTrue.'js/axios.js')}}"></script>
@endpush

@push('script')

<script>
  'use strict';
    $('.show').on('click',function () { 
        $(this).addClass('d-none')
        $('.hide').removeClass('d-none')
        $('.hide-value').removeClass('d-none')
    })

    $('.save').on('click',function () { 
        var userid = $(this).data('userid')
        var adId = $(this).data('adid')
       
        var data = {
          userid:userid,
          adId:adId
        }
        var route = "{{route('user.save.ad')}}"
        axios.post(route,data).then(function (res) { 
          if(res.data.adId ||res.data.userid)
          {
            $.each(res.data, function (i, val) { 
               notify('error',val)
            });
          } else{
            notify('success',res.data.success)
          }
          
        })
     })
    $('.advert').on('click',function () { 
        var ad_id = $(this).data('advertid')
        var data = {
          ad_id:ad_id
        }
        var route = "{{route('ad.click')}}"
        axios.post(route,data).then(function (res) { })
     })
</script>

<script>
    $( document ).ready(function() {
        $('.write-review').on('click',function () { 
            $('.customer-review').addClass('d-none')
            $('.customer-rating').removeClass('d-none');
        })
    });
</script>

<script>
    // $(document).click(function(e) {
    //     var container = $(".customer-rating");
    
    //     if (!container.is(e.target) && container.has(e.target).length === 0) {
    //                 alert('asfdf');

    //         $('.customer-review').show();
    //         $('.customer-rating').hide();
    //     }
    // });
</script>

<script>
    
    function changeImage(reference){
        let imgLink = reference.src;
        let imgDiv = $("#changeImage");
        
        imgDiv[0].children[0].src = imgLink;
        imgDiv[0].children[1].href = imgLink;
    }
</script>

<script>
    // Get the modal
    var modal = document.getElementById("exampleModalCenter");
    
    // Get the button that opens the modal
    var btn = document.getElementById("modalBtn");
    
    // Get the <span> element that closes the modal
    var span = document.getElementById("close");
    var span2 = document.getElementsByClassName("close");
    
    // When the user clicks on the button, open the modal
    btn.onclick = function() {
      modal.style.display = "block";
      modal.style.opacity = 1;
    }
    
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
      modal.style.opacity = 0;
    }
    
    
    span2.onclick = function() {
      modal.style.display = "none";
      modal.style.opacity = 0;
    }
    
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
</script>

    <script>
       let stars = document.querySelectorAll(".star");
document.querySelector(".star-container").addEventListener("click", starRating);
let rating = document.querySelector(".rating");

function starRating(e) {
  stars.forEach((star) => star.classList.remove("star__checked"));
  const i = [...stars].indexOf(e.target);
  if (i > -1) {
    stars[i].classList.add("star__checked");
    // rating.textContent = `${stars.length - i}/5`;
    document.getElementById("rating").setAttribute('value', stars.length - i);
  } else {
    // rating.textContent = `${0}/5`;
    document.getElementById("rating").setAttribute('value', 0);
  }
}

    </script>

@endpush





@push('additionalSeo')
  @includeif($activeTemplate.'partials.additionalSeo',['ad'=>$ad])
@endpush