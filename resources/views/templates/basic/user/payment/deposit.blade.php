@extends($activeTemplate.'layouts.master')

@section('content')
   
        <div class="row">
            @foreach($gatewayCurrency as $data)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card card-deposit border-0 shadow">
                        <h5 class="card-header bg--sec text-center">{{__($data->name)}}
                        </h5>
                        <div class="card-body card-body-deposit">
                            <img src="{{$data->methodImage()}}" class="card-img-top" alt="{{__($data->name)}}" class="w-100">
                        </div>
                        <div class="card-footer bg--sec text-center">
                            <a href="javascript:void(0)"  data-id="{{$data->id}}" data-resource="{{$data}}"
                               data-min_amount="{{getAmount($data->min_amount)}}"
                               data-max_amount="{{getAmount($data->max_amount)}}"
                               data-base_symbol="{{$data->baseSymbol()}}"
                               data-fix_charge="{{getAmount($data->fixed_charge)}}"
                               data-percent_charge="{{getAmount($data->percent_charge)}}" class=" text-white deposit" data-toggle="modal" data-target="#depositModal">
                                @lang('Payment Now')</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
@stop



@push('script')
<div class="modal fade" id="subscriptionModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
               <div class="d-flex justify-content-end"><button type="button" class="btn-close text-right m-3" data-bs-dismiss="modal" aria-label="Close"></div>
          
               </button>
                <div class="modal-body text-center">
                        <h3 class="text--secondary mb-3">Please Read this!</h3>
                        <small> To subscribe any package, you need to deposit first and keep your balance more than the package amount always to auto renew the package.</small>
                    </div>
                <div class="modal-footer justify-content-center">
                  <a class="btn btn--primary" data-bs-dismiss="modal" style="padding: 10px 40px;">Deposit</a>
                </div>
              
              </div>
            </div>
        </div>
    <script>
        "use strict";
        $(document).ready(function(){
            $('.deposit').on('click', function () {
                
                var id = $(this).data('id');
                var result = $(this).data('resource');
                var minAmount = $(this).data('min_amount');
                var maxAmount = $(this).data('max_amount');
                var baseSymbol = "{{__($general->cur_text)}}";
                var fixCharge = $(this).data('fix_charge');
                var percentCharge = $(this).data('percent_charge');

                var depositLimit = `@lang('Deposit Limit'): ${minAmount} - ${maxAmount}  ${baseSymbol}`;
                $('.depositLimit').text(depositLimit);
                var depositCharge = `@lang('Charge'): ${fixCharge} ${baseSymbol}  ${(0 < percentCharge) ? ' + ' +percentCharge + ' % ' : ''}`;
                $('.depositCharge').text(depositCharge);
                $('.method-name').text(`@lang('Payment By ') ${result.name}`);
                $('.currency-addon').text(baseSymbol);


                $('.edit-currency').val(result.currency);
                $('.edit-method-code').val(result.method_code);
                $("#depositModal").modal('show')
            });
            
            $("#subscriptionModal").modal('show')
        });
    </script>
@endpush
