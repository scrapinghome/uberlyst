@extends($activeTemplate.'layouts.master')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm  border-0">
           <div class="card-header bg--sec">
                Subscription Plan
           </div>
           <div class="card-body">
            @if(!$subscription_plan)
            <h3>You did not subscribe any plan</h3>
            <a class="btn btn-sm" style="margin-top: 10px; background: #002046; color: #fff;" href="/our-pricing">Subscribe Now</a>
            @elseif($subscription_plan && $subscription_plan->type == 'per_month')
                <h3><strong>Your are on the Starters subscription plan</strong></h3><br>
                <table class="table" style="border: 2px solid #cccccca1">
                  <tbody>
                    <tr>
                      <td>Start Date</td>
                      <td>End Date</td>
                      <td>Status</td>
                      <!--<td>Actions</td>-->
                    </tr>
                    <tr style="background-color: #cccccca1">
                      <td>{{ date("d M Y", strtotime($subscription_plan->created_at)) }}</td>
                      <td>{{ date("d M Y", strtotime($subscription_plan->expire_at)) }}</td>
                      <td><span class="badge bg-success">Active</span></td>
                      <!--<td><a href="#" class="badge bg-danger">Cancel</a></td>-->
                    </tr>
                  </tbody>
                </table>
                
                <p>This subscription plan runs for 30 days from the date of enrollment.
                    You will continue to have access to your account during that time.</p>
                <a class="btn btn-sm" style="margin-top: 10px; background: #002046; color: #fff;" href="/our-pricing">Upgrade Plan</a>

            @elseif($subscription_plan && $subscription_plan->type == 'per_six_month')
                <h3><strong>Your are on the PRO subscription plan</strong></h3><br>
                <table class="table" style="border: 2px solid #cccccca1">
                  <tbody>
                    <tr>
                      <td>Start Date</td>
                      <td>End Date</td>
                      <td>Status</td>
                      <!--<td>Actions</td>-->
                    </tr>
                    <tr style="background-color: #cccccca1">
                      <td>{{ date("d M Y", strtotime($subscription_plan->created_at)) }}</td>
                      <td>{{ date("d M Y", strtotime($subscription_plan->expire_at)) }}</td>
                      <td><span class="badge bg-success">Active</span></td>
                      <!--<td><a href="#" class="badge bg-danger">Cancel</a></td>-->
                    </tr>
                  </tbody>
                </table>
                
                <p>This subscription plan runs for 6 months from the date of enrollment.
                    You will continue to have access to your account during that time.</p>
                <a class="btn btn-sm" style="margin-top: 10px; background: #002046; color: #fff;" href="/our-pricing">Upgrade Plan</a>

            @elseif($subscription_plan && $subscription_plan->type == 'per_year')
                <h3><strong>Your are on the VIP subscription plan</strong></h3><br>
                <table class="table" style="border: 2px solid #cccccca1">
                  <tbody>
                    <tr>
                      <td>Start Date</td>
                      <td>End Date</td>
                      <td>Status</td>
                      <!--<td>Actions</td>-->
                    </tr>
                    <tr style="background-color: #cccccca1">
                      <td>{{ date("d M Y", strtotime($subscription_plan->created_at)) }}</td>
                      <td>{{ date("d M Y", strtotime($subscription_plan->expire_at)) }}</td>
                      <td><span class="badge bg-success">Active</span></td>
                      <!--<td><a href="#" class="badge bg-danger">Cancel</a></td>-->
                    </tr>
                  </tbody>
                </table>
                
                <p>This subscription plan runs for one year from the date of enrollment.
                    You will continue to have access to your account during that time.</p>
            @endif
           </div>
        </div>
    </div>
</div>
    
@endsection


