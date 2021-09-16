@extends($activeTemplate.'layouts.master')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm  border-0">
           <div class="card-header bg--sec">
                Become a Verified Seller
           </div>
           <div class="card-body">
            <form class="register" action="" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row justify-content-center align-items-center mt-3">
                    <div class="col-xl6 col-lg-6 col-md-6 col-sm-6">
                        <div class="form-group">
                            <div class="image-upload">
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview"  style="background-image: url({{ getImage(imagePath()['profile']['user']['path'].'/'. $user->id_card,imagePath()['profile']['user']['size']) }})">
                                            <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div style="margin: 20px 0 20px 0;">
                                        <small class="mt-2 text-facebook">A scan or photo of your ID card.</small>
                                    </div>
                                    <div class="avatar-edit">
                                        <input type="file" class="profilePicUpload" name="id_card" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                        <label for="profilePicUpload1"  class="bg--base text-white">@lang('Upload Image')</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><div class="col-xl6 col-lg-6 col-md-6 col-sm-6">
                        <div class="form-group">
                            <div class="image-upload">
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview"  style="background-image: url({{ getImage(imagePath()['profile']['user']['path'].'/'. $user->id_card_with_sign,imagePath()['profile']['user']['size']) }})">
                                            <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div style="margin: 15px 0 0 0;">
                                        <small class="mt-2 text-facebook">A photo of you holding the ID card and a sign saying: « uber lyst », your username and the date of the day.</small>
                                    </div> 
                                    <div class="avatar-edit">
                                        <input type="file" class="profilePicUpload" name="id_card_with_sign" id="profilePicUpload2" accept=".png, .jpg, .jpeg">
                                        <label for="profilePicUpload2"  class="bg--base text-white">@lang('Upload Image')</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row pt-3">
                    <div class="col-sm-12 text-center">
                        <button type="submit" class="btn  btn--base btn-md w-100">Submit</button>
                    </div>
                </div>
            </form>
           </div>
        </div>
    </div>
</div>
    
@endsection


