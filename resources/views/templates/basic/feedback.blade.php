@extends($activeTemplate.'layouts.frontend')
@push('style')

<style>
.star-container-feedback {
  padding-left: 0;
  display: flex;
  width: 280px;
  flex-direction: row-reverse;
}

.star-container-feedback .star {
}

.star-container-feedback .star:before {
  content: "\f005";
  font-family: fontAwesome;
  font-size: 60px;
  position: relative;
  display: block;
  color: #aaa;
}

.star-container-feedback .star:after {
  content: "\f005";
  font-family: fontAwesome;
  position: absolute;
  top: 99px;
  font-size: 60px;
  color: gold;
  opacity: 0;
}

@media (max-width: 480px) {
    .star-container-feedback .star:after {
  content: "\f005";
  font-family: fontAwesome;
  position: absolute;
  top: 126px;
  font-size: 60px;
  color: gold;
  opacity: 0;
}
}
            
.star-container-feedback .star:hover:after,
.star-container-feedback .star:hover ~ .star:after,
.star-container-feedback .star.star__checked:after,
.star-container-feedback .star.star__checked ~ .star:after{
  opacity: 1;
}
</style>

@endpush

@section('content')
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
<section class="pt-100 pb-100">
    <div class="container">
      <div class="row gy-4 pb-50">
        <div class="col-md-8 offset-md-2">
          <div class="card">
              <div class="card-header">
                  @php
                  $seller_id = isset($_GET['sellerid']) ? $_GET['sellerid'] : null;
                  $seller = App\Models\User::find($seller_id);
                  @endphp
                  <h3>Give Your Feedback to <strong>{{ $seller ? $seller->fullname : null }}</strong> @if($seller) <i style="color:#43ced2 !important;" class="fas fa-check-circle"></i> @endif </h3>
              </div>
              
              <div class="card-body">
                  <form action="/feedback" method="post">
                    @csrf

                    <div class="row">
                        <div class="form-group">
                            <label for="lastname" class="col-form-label">@lang('Customer reviews'):</label><br>
                            <div class="star-container-feedback">
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                             </div>
                             <!--<h1 class="rating">0/5</h1>-->

                            <input type="hidden" class="form--control" name="seller_id" value="{{ isset($_GET['sellerid']) ? $_GET['sellerid'] : null }}">
                            <input type="hidden" class="form--control rating" id="rating" name="rating">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="phone" class="col-form-label">@lang('Comment'):</label>
                            <textarea type="text" class="form--control pranto-control" id="phone" name="comment" placeholder="Comment"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row pt-3">
                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn  btn--base btn-md w-100">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
              </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@stop



@push('script')

    <script>
       let stars = document.querySelectorAll(".star");
document.querySelector(".star-container-feedback").addEventListener("click", starRating);
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