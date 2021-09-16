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
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Slug')</th>
                                <th scope="col">@lang('Type')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Fields')</th>
                                <th scope="col">One Time</th>
                                <th scope="col">Per Month</th>
                                <th scope="col">Per 6 Month</th>
                                <th scope="col">Per Year</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($subCategories as $category)
                            <tr>
                                <td data-label="@lang('Name')">
                                     {{$category->name}}
                                </td>
                                <td data-label="@lang('Slug')">{{$category->slug}}</td>
                                <td data-label="@lang('Type')">
                                    @if ($category->type == 1)
                                    <span class="text--small badge font-weight-normal badge--success">@lang('sell')</span>
                                    @elseif ($category->type == 2)
                                    <span class="text--small badge font-weight-normal badge--primary">@lang('rent')</span>
                                    @elseif ($category->type == 3)
                                    <span class="text--small badge font-weight-normal badge--success">@lang('jobs')</span>
                                    @elseif ($category->type == 4)
                                    <span class="text--small badge font-weight-normal badge--primary">@lang('community')</span>
                                    @endif
                                <td data-label="@lang('Status')">
                                    @if ($category->status == 1)
                                    <span class="text--small badge font-weight-normal badge--success">@lang('active')</span>
                                    @else
                                    <span class="text--small badge font-weight-normal badge--warning">@lang('inactive')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Fields')"><a href="{{route('admin.subcategories.fields',$category->id)}}" class="icon-btn btn--dark"> <i class="las la-eye"></i> @lang('see')</a></td>
                                <td data-label="One Time">{{($category->one_time ?? false) ? '$' . $category->one_time : 'Free' }}</td>
                                <td data-label="Per Month">{{($category->per_month ?? false) ? '$' . $category->per_month : 'Free'}}</td>
                                <td data-label="Per Month">{{($category->per_six_month ?? false) ? '$' . $category->per_six_month : 'Free'}}</td>
                                <td data-label="Per Month">{{($category->per_year ?? false) ? '$' . $category->per_year : 'Free'}}</td>
                                <td data-label="@lang('Action')">
                                    <a href="javascript:void(0)" data-subcategory="{{$category}}" data-route="{{route('admin.subcategories.update',$category->id)}}" class="icon-btn edit" data-toggle="tooltip" title="@lang('edit sub-category')">
                                        <i class="las la-edit text--shadow"></i>
                                    </a>
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
                    {{paginateLinks($subCategories)}}
                </div>
            </div><!-- card end -->
        </div>


         <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{route('admin.subcategories.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg--primary">
                    <h5 class="modal-title text-white" id="exampleModalLabel">@lang('Add Sub Category')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                        <div class="modal-body">
                            <input type="hidden" name="category_id" value="{{$cat->id}}">
                            <div class="form-group">
                                <label >@lang('Name')</label>
                                <input type="text" class="form-control" name="name"  placeholder="@lang('Name')">
                            </div>
                            <div class="form-group">
                                <label>@lang('Select Type')*</label>
                                <select  class="form-control type" name="type">
                                    <option value="1">@lang('Sell')</option>
                                    <option value="2">@lang('Rent')</option>
                                    <option value="3">@lang('Jobs')</option>
                                    <option value="4">@lang('Community')</option>
                                </select>
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
    </div>
      
         <!-- edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <input type="hidden" name="category_id" value="{{$cat->id}}">
                        <div class="form-group">
                            <label >@lang('Name')</label>
                            <input type="text" class="form-control" name="name"  placeholder="@lang('Name')">
                        </div>
                        <div class="form-group">
                            <label for="my-select">@lang('Select Type')*</label>
                            <select id="my-select" class="form-control type" name="type">
                                <option value="1">@lang('Sell')</option>
                                <option value="2">@lang('Rent')</option>
                                <option value="3">@lang('Jobs')</option>
                                <option value="4">@lang('Community')</option>
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
      </div>

    </div>
    
    
    
   
    
    
    
    
    
    
@endsection



@push('breadcrumb-plugins')

  <a href="{{route('admin.categories')}}" class="btn btn--dark mr-3 mt-2"> <i class="las la-backward"></i>
    @lang('Go Back')
  </a>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn--primary mr-3 mt-2" data-toggle="modal" data-target="#addModal">
     <i class="las la-plus"></i> @lang('Add Sub-Category')
    </button>
    
    <form action="{{route('admin.categories')}}" method="GET" class="form-inline float-sm-right bg--white mt-2">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Search by name')" value="" autocomplete="off">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush


@push('script')
    <script>
        (function ($) {
            'use strict'
            $('.edit').on('click',function () { 
                var subcategory = $(this).data('subcategory')
                var route = $(this).data('route')

                $('#editModal').find('input[name=name]').val(subcategory.name)
                $('#editModal').find('select[name=type]').val(subcategory.type)
                $('#editModal').find('input[name=one_time]').val(subcategory.one_time)
                $('#editModal').find('input[name=per_month]').val(subcategory.per_month)
                $('#editModal').find('input[name=per_six_month]').val(subcategory.per_six_month)
                $('#editModal').find('input[name=per_year]').val(subcategory.per_year)
               
                $('#editModal').find('form').attr('action',route)
                if(subcategory.status == 1){
                    $('#editModal').find('input[name=status]').bootstrapToggle('on')
                }
                $('#editModal').modal('show')
                
            });
          
        })(jQuery);

    </script>

@endpush


