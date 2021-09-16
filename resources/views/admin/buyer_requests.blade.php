@extends('admin.layouts.app')

@section('panel')

    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th scope="col">@lang('Buyer Message')</th>
                                <th scope="col">@lang('AD URL')</th>
                                <th scope="col">@lang('AD EMAIL')</th>
                                <th scope="col">@lang('AD ID')</th>
                                <th scope="col">@lang('status')</th>
                                <th scope="col">@lang('Buyer Name')</th>
                                <th scope="col">@lang('Buyer Email/Phone')</th>
                                <th scope="col">@lang('Buyer Phone')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($replies as $reply)
                            <tr>
                                <td data-label="@lang('Title')">
                                    <div class="user">
                                        <!--div class="thumb"><img  alt="image"></div-->
                                        <span class="name"><a class="text-secondary"  target="_blank">{{$reply->buyer_message}}</a></span> <br>
                                    </div>
                                   
                                </td>
                                <td data-label="@lang('User')">{{$reply->add_url}}</td>
                                <td data-label="@lang('Category/Subcategory')">
                                    <span class="text--warning font-weight-bold">{{$reply->add_email}}</span> <br>
                                    <span class="text--primary"></span>
                                </td>
                              
                                <td data-label="@lang('Price')">{{$reply->ad_id}}</td>
                                
                                
                                <td data-label="@lang('Status')">
                                    @if ($reply->status == 1)
                                    <span class="text--small badge font-weight-normal badge--success">@lang('Published')</span>
                                    @elseif($reply->status == 2)
                                    <span class="text--small badge font-weight-normal badge--warning">@lang('Unpublished')</span>
                                    @else
                                    <span class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span>
                                    @endif
                                </td>
                                
                               <td data-label="@lang('Category/Subcategory')">
                                    <span class="text--warning font-weight-bold">{{$reply->buyer_name}}</span> <br>
                                    <span class="text--primary"></span>
                                </td>
                                
                                <td data-label="@lang('Category/Subcategory')">
                                    <span class="text--warning font-weight-bold">{{$reply->buyer_phone}}</span> <br>
                                    <span class="text--primary"></span>
                                </td>
                                
                                <td data-label="@lang('Category/Subcategory')">
                                    <span class="text--warning font-weight-bold">{{$reply->buyer_email}}</span> <br>
                                    <span class="text--primary"></span>
                                </td>
                                
                            </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ $empty_message }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{paginateLinks($replies)}}
                </div>
            </div><!-- card end -->
        </div>


    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <button type="button" class="close ml-auto m-3" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
            <form action="" method="POST">
                @csrf
                <div class="modal-body text-center">
                    
                    <i class="las la-exclamation-circle text--warning modal-icon display-2 mb-15"></i>
                    <h4 class="text--secondary stat-msg mb-15">@lang('Are you sure want to unpublish?')</h4>

                </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                <button type="submit"  class="btn btn--warning del">@lang('Unpublish')</button>
            </div>
            
            </form>
        </div>
    </div>
            </div>
@endsection



@push('breadcrumb-plugins')
    
<form action="" method="GET" class="form-inline float-sm-right bg--white">
    <div class="input-group has_append">
        <input type="text" name="search" class="form-control" placeholder="@lang('title, category')" value="{{$search??''}}">
        <div class="input-group-append">
            <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </div>
</form>

@endpush

    
@push('script')
     <script>
            'use strict';
            (function ($) {
                $('.confirm').on('click',function(){
                    var route = $(this).data('route')
                    var modal = $('#confirmModal');
                    var publish = $(this).data('publish')
                    if(publish == 1){
                        $('#confirmModal').find('.modal-icon').removeClass('text--warning').addClass('text--success')
                        $('#confirmModal').find('.del').removeClass('btn--warning').addClass('btn--success').text('Publish')
                        $('#confirmModal').find('.stat-msg').text('Are you sure want to publish?')
                    }
                    modal.find('form').attr('action',route)
                    modal.modal('show');
                })
            })(jQuery);
     </script>
@endpush