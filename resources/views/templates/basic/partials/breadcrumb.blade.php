@php
    $bg = getContent('breadcrumb.content',true)->data_values;
@endphp

<style>
    .inner-hero{
        margin-top: 75px;
    }
    
    @media only screen and (max-width: 600px) {
      .inner-hero{
            margin-top: 62px;
        }
    }
    
</style>


<section class="inner-hero bg_img" style="background-image: url({{getImage('assets/images/frontend/breadcrumb/'.$bg->background_image)}});">
    <div class="container">
    </div>
  </section>