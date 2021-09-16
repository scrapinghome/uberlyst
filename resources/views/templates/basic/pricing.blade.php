@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-100 pb-100">
    <div class="container">
        @if($subscription)
        <div class="row gy-4 pb-50">
            <div class="col-md-12">
                <h2>You are currently subscribed to "{{$subscription}}" package</h2>
            </div>
        </div>
        @endif
      <div class="row gy-4 pb-50">
        <div class="col-md-4">
          <div class=" rounded mt-5">
            <div class="pricing-tab">
                <h3 class="title">VIP</h3>
                <div class="pricing">
                    <span class="doller">
                        $ 
                    </span>
                    <span class="price">
                        99.99
                    </span>
                    <span class="period">
                        /Yearly
                    </span>
                    <br><br>
                    <a href="{{route('user.subscription', 'per_year')}}" class="select-btn">Select</a>
                </div>
                <hr>
                <div class="pricing-body">
                    <p>Membership Savings - $20 value</p>
                    <p>Post Unlimited</p>
                    <p>Sponsor Ads</p>
                    <p>Reviews</p>
                    <p>Free Banner- $500 value</p>
                    <p>Banner Design- $75 value</p>
                    <p>Link to website</p>
                    <p>Verification badge- $125 value</p>
                    <p>Priority Support- $49</p>
                    <p style="font-weight: bold">$769 Savings</p>
                </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class=" rounded" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
            <div style="background-color: #43ced2 !important; text-align: center; padding: 10px; color: white; text-weight: bold;">Best Value</div>
            <div class="pricing-tab">
                <h3 class="title">Pro</h3>
                <div class="pricing">
                    <span class="doller">
                        $ 
                    </span>
                    <span class="price">
                        49.99
                    </span>
                    <span class="period">
                        /6 months
                    </span>
                    <br><br>
                    <a href="{{route('user.subscription', 'per_six_month')}}" class="select-btn selected">Select</a>
                </div>
                <hr>
                <div class="pricing-body">
                    <p>Membership Savings - $10 value</p>
                    <p>Post Unlimited</p>
                    <p>Sponsor Ads</p>
                    <p>Reviews</p>
                    <p>Discounted Ad Space - $300</p>
                    <p style="font-weight: bold">$210 Savings</p>
                    <p> &nbsp; </p><p> &nbsp; </p><p> &nbsp; </p><p> &nbsp; </p>
                </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class=" rounded mt-5">
            <div class="pricing-tab">
                <h3 class="title">Starters</h3>
                <div class="pricing">
                    <span class="doller">
                         $
                    </span>
                    <span class="price">
                        9.99
                    </span>
                    <span class="period">
                       /Month
                    </span>
                    <br><br>
                    <a href="{{route('user.subscription', 'per_month')}}" class="select-btn">Select</a>
                </div>
                <hr>
                <div class="pricing-body">
                    <p>Post Unlimited</p>
                    <p>Sponsor Ads</p>
                    <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@stop

@push('style')

<style>
    .rounded{
        background-color: #f0f5fc;
    }
    
    .pricing-tab{
        
    }
    
    .pricing-body{
        padding: 40px;
    }
    
    .pricing{
        text-align: center;
        padding: 15px 0px;
    }
    
    .title{
        text-align: center;
        padding-top: 35px;
    }
    
    .price{
        font-size: 35px;
        font-weight: bold;
    }
    
    .select-btn{
        padding: 5px 25px;
        border: 2px solid #43ced2;
        border-radius: 25px;
        font-size: 14px;
        color: #43ced2;
        transition: all 500ms ease;
    }
    
    .select-btn:hover, .selected{
        color: white;
        background-color: #43ced2;
    }
    
    hr{
        width: 70%;
        margin: 10px auto;
    }
    
</style>

@endpush