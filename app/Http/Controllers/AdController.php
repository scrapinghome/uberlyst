<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AdList;
use App\Models\AdImage;
use App\Models\Category;
use App\Models\District;
use App\Models\Division;
use App\Models\AdPromote;
use App\Models\SubCategory;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;

class AdController extends Controller
{
    public function __construct() {
        $this->activeTemplate = activeTemplate();
    }

    public function createAd()
    {
        $page_title = 'Post new ad';
        return view($this->activeTemplate.'user.ads.postAdType',compact('page_title'));
    }

    public function selectCategory($type)
    {
        $page_title = 'Select Category';
        if($type == 'sell'){
            $flag = 1;
        } 
        
        else if($type == 'rent') {
            $flag = 2;
        }
        
        else if($type == 'jobs') {
            $flag = 3;
        }
        
        else if($type == 'community') {
            $flag = 4;
        }
        
        else{
            $notify[]=['error','Sorry type couldn\'t found'];
            return back()->withNotify($notify);
        }
        
        $category_ids = SubCategory::where('type', $flag)->pluck('category_id');
        
        $categories = Category::whereIn('id', $category_ids)->where('status',1)->with(['subcategories' => function($query) use ($flag) {
            $query->where('type', $flag)->where('status', 1);
        }])->get();
        
        return view($this->activeTemplate.'user.ads.postAdCategory',compact('page_title','categories','type','flag'));
    }

    public function selectLocation($type,$subcat)
    {
        $page_title = 'Select Location';
        if($type == 'sell' || $type == 'rent' || $type == 'jobs' || $type == 'community'){
            $locations = Division::where('status',1)->with('districts')->get();
            return view($this->activeTemplate.'user.ads.postAdLocation',compact('page_title','locations','type','subcat'));
           
        }
        $notify[]=['error','Sorry type couldn\'t found'];
        return back()->withNotify($notify);
       
    }
    
    public function showAdForm($type,$subcat,$location)
    {
        $page_title = 'Post Ad';
        if($type =='sell' || $type == 'rent' || $type == 'jobs' || $type == 'community'){
            $subcategory = SubCategory::where('status',1)->where('slug',$subcat)->first();
            $district = District::where('status',1)->where('slug',$location)->first();

            if(!$subcategory || !$district){
                $notify[]=['error','Sorry category or location currently not available or not found'];
                return back()->withNotify($notify);
            }
            
            $subscription_per_month = !!DB::table('subscriptions')
            ->where('user_id', auth()->id())
            ->where('type', 'per_month')
            ->whereDate('expire_at', '>', now()->toDateTimeString())
            ->count();
            
            $subscription_per_six_month = !!DB::table('subscriptions')
            ->where('user_id', auth()->id())
            ->where('type', 'per_six_month')
            ->whereDate('expire_at', '>', now()->toDateTimeString())
            ->count();    
            
            $subscription_per_year = !!DB::table('subscriptions')
            ->where('user_id', auth()->id())
            ->where('type', 'per_year')
            ->whereDate('expire_at', '>', now()->toDateTimeString())
            ->count();
            
            return view($this->activeTemplate.'user.ads.postAdForm',compact('page_title','subcategory','district','type', 'subscription_per_month', 'subscription_per_six_month', 'subscription_per_year'));
          
        }
        $notify[]=['error','Sorry type couldn\'t found'];
        return back()->withNotify($notify);
        
    }
    
     public function submit_buyer_request(Request $request, $id)
    {
        /*$request->validate([
            'name' => 'required|unique:sub_categories',
            'type' => 'required|in:1,2',
           
        ]);*/
        
        $details= DB::table('ad_lists')->where('id', $id)->get();
        
         DB::table('buyer_requests')->insert([
                    'ad_id' => $id,
                    'buyer_name' => $request->Name,
                    'buyer_phone'=> $request->Phone,
                    'buyer_email'=> $request->email,
                    'buyer_message'=> $request->Message,
                    'add_url'=> $details[0]->ad_url,
                    'add_email'=> $details[0]->ad_email
                ]);
        //$buyer_requests = new buyer_requests();
        //$buyer_requests->ad_id = $id;
        //$buyer_requests->category_id = $request->category_id;
       // $buyer_requests->buyer_name = $request->Name;
        //$buyer_requests->type = $request->type == 1 ? 1:2;
        //$buyer_requests->status = $request->status ? 1:0;
        //$category->save();
        $notify[]=['success','Sub Category Created Successfully'];
        return back()->withNotify($notify);
    }
    
    
    
    public function storeAd(Request $request)
    {
            $images = $request->image;
            $allowedExts = array('jpg','jpeg','png');
            $rules = [
                'title' => 'required',
                // 'condition' => 'required|in:1,2',
                'description' => 'required',
                'price' => 'required|numeric|gt:0',
                'phone' => 'required',
                'prev_image'=>['required','image','max:2048',new FileTypeValidate(['jpg','jpeg','png'])],

            ];
            

            
            // if ($request->image) {
            //     $notify[]=['error','At least 1 image is required'];
            //     return back()->withNotify($notify);
            // }

            if ($images && count($images) > 5) {
                $notify[]=['error','Maximum 5 images can be uploaded'];
                return back()->withNotify($notify);
            }
            
            if ($images) {
                foreach ($images as $file) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    if (($file->getSize() / 1000000) > 2) {
                        $notify[]=['error','Images MAX  2MB ALLOW!'];
                        return back()->withNotify($notify);
                    }
                    if (!in_array($ext, $allowedExts)) {
                        $notify[]=['error','Only  jpg, jpeg, png files are allowed'];
                        return back()->withNotify($notify);
                    }
                }
            }

            
           $subcat = SubCategory::findOrFail($request->subcategory_id);
           $district = District::findOrFail($request->district_id);
           
           $subscription_per_month = !!DB::table('subscriptions')
            ->where('user_id', auth()->id())
            ->where('type', 'per_month')
            ->whereDate('expire_at', '>', now()->toDateTimeString())
            ->count();

           $subscription_per_six_month = !!DB::table('subscriptions')
            ->where('user_id', auth()->id())
            ->where('type', 'per_six_month')
            ->whereDate('expire_at', '>', now()->toDateTimeString())
            ->count();

           $subscription_per_year = !!DB::table('subscriptions')
            ->where('user_id', auth()->id())
            ->where('type', 'per_year')
            ->whereDate('expire_at', '>', now()->toDateTimeString())
            ->count();

            $create_subscription = false;
           if(($subcat->per_month || $subcat->one_time || $subcat->per_six_month || $subcat->per_year) && (!$subscription_per_month && !$subscription_per_six_month && !$subscription_per_year)){
               $payment_type = $request->pay_type ?? 'one_time';
               $user = User::findOrFail(auth()->id());

               if($payment_type == 'per_month' && $subcat->per_month){
                //    Get payment for one month
                if($user->balance < $subcat->per_month){
                    return (new UserController)->subscription($payment_type);
                    $notify[]=['error','Insufficient Balance. Please deposit balance or subscribe.'];
                    return back()->withNotify($notify);
                }

                // User has balance now cut and store
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = $subcat->per_month;
                $transaction->post_balance = getAmount($user->balance);
                $transaction->charge = getAmount(0);
                $transaction->trx_type = '-';
                $transaction->details = 'Payment from Balance for ad';
                $transaction->trx = getTrx();
                $transaction->save();
                $user->balance -= $subcat->per_month;
                $user->save();

                DB::table('subscriptions')->insert([
                    'user_id' => $user->id,
                    'ad_id' => null,
                    'type' => 'per_month',
                    'expire_at' => now()->addDays(30),
                    'created_at' => now(),
                ]);
               }

               if($payment_type == 'per_six_month' && $subcat->per_six_month){
                //    Get payment for one month
                if($user->balance < $subcat->per_six_month){
                    return (new UserController)->subscription($payment_type);
                    $notify[]=['error','Insufficient Balance. Please deposit balance or subscribe.'];
                    return back()->withNotify($notify);
                }

                // User has balance now cut and store
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = $subcat->per_six_month;
                $transaction->post_balance = getAmount($user->balance);
                $transaction->charge = getAmount(0);
                $transaction->trx_type = '-';
                $transaction->details = 'Payment from Balance for ad';
                $transaction->trx = getTrx();
                $transaction->save();
                $user->balance -= $subcat->per_six_month;
                $user->save();

                DB::table('subscriptions')->insert([
                    'user_id' => $user->id,
                    'ad_id' => null,
                    'type' => 'per_six_month',
                    'expire_at' => now()->addDays(180),
                    'created_at' => now(),
                ]);
               }

               if($payment_type == 'per_year' && $subcat->per_year){
                //    Get payment for one month
                if($user->balance < $subcat->per_year){
                    return (new UserController)->subscription($payment_type);
                    $notify[]=['error','Insufficient Balance. Please deposit balance or subscribe.'];
                    return back()->withNotify($notify);
                }

                // User has balance now cut and store
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = $subcat->per_year;
                $transaction->post_balance = getAmount($user->balance);
                $transaction->charge = getAmount(0);
                $transaction->trx_type = '-';
                $transaction->details = 'Payment from Balance for ad';
                $transaction->trx = getTrx();
                $transaction->save();
                $user->balance -= $subcat->per_year;
                $user->save();

                DB::table('subscriptions')->insert([
                    'user_id' => $user->id,
                    'ad_id' => null,
                    'type' => 'per_year',
                    'expire_at' => now()->addDays(360),
                    'created_at' => now(),
                ]);
               }

               if($payment_type == 'one_time' && $subcat->one_time){
                //    Get payment for one time
                if($user->balance < $subcat->one_time){
                    return (new UserController)->subscription($payment_type);
                    $notify[]=['error','Insufficient Balance. Please deposit balance or subscribe.'];
                    return back()->withNotify($notify);
                }

                // User has balance now cut and store
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = $subcat->one_time;
                $transaction->post_balance = getAmount($user->balance);
                $transaction->charge = getAmount(0);
                $transaction->trx_type = '-';
                $transaction->details = 'Payment from Balance for ad';
                $transaction->trx = getTrx();
                $transaction->save();
                $user->balance -= $subcat->one_time;
                $user->save();

                $create_subscription = true;

               }
           }
           
           

            $fields = $subcat->fields;
            if (!empty($fields)) {
                foreach ($fields as $field) {
                    if ($field->required == 1) {
                        $rules["$field->name"] = 'required';
                    }
                }
            }
            
            
            $request->validate($rules,['prev_image.required'=>'Preview Image is required','prev_image.image'=>'Preview Image has to be image type','prev_image.max'=>'Preview Image can not be greater than 2 MB']);
         
            $extraFields = [];
            foreach ($subcat->fields as $field) {
              $fieldName = $field->name;
              if ($request["$fieldName"]) {
                $extraFields["$fieldName"] = $request["$fieldName"];
              }
            }

           $ad = new AdList();
           $ad->user_id = auth()->id();
           $ad->category_id = $subcat->category->id;
           $ad->subcategory_id = $subcat->id;
           $ad->division = $district->division->name;
           $ad->district = $district->name;
           $ad->title = $request->title;
           $ad->slug = Str::slug($request->title).'-'.rand(411,799);
           $ad->use_condition = $request->condition;
           $ad->description = $request->description;
           $ad->price = $request->price;
           $ad->type = $subcat->type;
           $ad->negotiable = $request->negotiable ? 1:0;
           $ad->contact_num = $request->phone;
           $ad->hide_contact = $request->hidenumber ? 1:0;
           $ad->fields = json_decode(json_encode(($extraFields  ?? [])));
          
          try {
              
           if($request->prev_image){
               
             //$ad->prev_image = uploadImage($request->prev_image,'assets/images/item_image/','200x200',null,null);
             $ad->prev_image = uploadImage($request->prev_image,'assets/images/item_image/','856x520',null,null);
             
           }
           
          }catch(\Throwable $th){
                info($th->getMessage());
          }
            
           $ad->save();
           
           if($request->image){
               foreach($request->image as $image){
                   $img = new AdImage();
                   $img->ad_id = $ad->id;
                   $img->image = uploadImage($image,'assets/images/item_image/','856x520',null,null);
                   $img->save();
               }
           }

           if($create_subscription){
            DB::table('subscriptions')->insert([
                'user_id' => $user->id,
                'ad_id' => $ad->id,
                'type' => 'one_time',
                'expire_at' => null,
                'created_at' => now(),
            ]);
           }

            $adminNotification = new AdminNotification();
            $adminNotification->user_id = auth()->id();
            $adminNotification->title = auth()->user()->username.' Posted A New Ad';
            $adminNotification->click_url = urlPath('admin.ads.pending');
            $adminNotification->save();
           
           $notify[]=['success','Ad posted successfully'];
           return back()->withNotify($notify);
     }

     public function adList(Request $request)
     {
         $search = $request->search;
         if($search){
            $page_title = "Search Result of $search";
            $ads = AdList::where('status',1)->where('user_id',auth()->id())->where('title','like',"%$search%")->paginate(getPaginate());
         } else {

             $page_title = "Ad Lists";
             $ads = AdList::where('status',1)->where('user_id',auth()->id())->latest()->paginate(getPaginate());
         }
         return view($this->activeTemplate.'user.ads.adList',compact('ads','page_title','search'));
     }
     
     
     
      public function update_testt(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|unique:ad_id,title,'.$id,
            'type' => 'required|in:1,2',
            
        ]);

        $ad = ad_lists::findOrFail($id);
        $ad->title = $request->title;
        $ad->slug = $request->slug;
        /*$category->slug = Str::slug($request->name);
        $category->type = $request->type == 1 ? 1:2;
        $category->status = $request->status ? 1:0;
        $category->one_time = floatval($request->one_time ?? 0);
        $category->per_month = floatval($request->per_month ?? 0);
        $category->per_six_month = floatval($request->per_six_month ?? 0);
        $category->per_year = floatval($request->per_year ?? 0);*/
        

        $category->save();
        $notify[]=['success','Sub Category Updated Successfully'];
        return back()->withNotify($notify);
    }
     

     public function editAd($id)
     {
        $page_title = "Edit Ad";
        $ad = AdList::where('id',$id)->where('user_id',auth()->id())->first();
        if(!$ad){
            $notify[]=['error','Sorry! invalid request'];
            return back()->withNotify($notify);
        }
        $adFields = json_decode(json_encode($ad->fields),true); 
       
        return view($this->activeTemplate.'user.ads.editAd',compact('ad','page_title','adFields'));
     }

     public function updateAd(Request $request,$id)
     {
        $images = $request->image;
        $allowedExts = array('jpg','jpeg','png');
        $rules = [
            'title' => 'required',
            'condition' => 'required|in:1,2',
            'description' => 'required',
            'price' => 'required|numeric|gt:0',
            'phone' => 'required',
            'prev_image'=>['image','max:2048',new FileTypeValidate(['jpg','jpeg','png'])],
           
            
        ];

        if($images != null){
            foreach ($images as $file) {
                $ext = strtolower($file->getClientOriginalExtension());
                if (($file->getSize() / 1000000) > 5) {
                    $notify[]=['error','Images MAX  5MB ALLOW!'];
                    return back()->withNotify($notify);
                }
                if (!in_array($ext, $allowedExts)) {
                    $notify[]=['error','Only  jpg, jpeg, png files are allowed'];
                    return back()->withNotify($notify);
                }
            }
        }

        if ($images!=null && count($images) > 5) {
            $notify[]=['error','Maximum 5 images can be uploaded'];
            return back()->withNotify($notify);
        }

        $ad = AdList::findOrFail($id);
        $subcat = $ad->subcategory;

        $fields = $ad->subcategory->fields;
        if (!empty($fields)) {
            foreach ($fields as $field) {
                if ($field->required == 1) {
                    $rules["$field->name"] = 'required';
                }
            }
        }
      
        $request->validate($rules,['prev_image.required'=>'Preview Image is required','prev_image.image'=>'Preview Image has to be image type','prev_image.max'=>'Preview Image can not be greater than 2 MB']);

        $extraFields = [];
        foreach ($subcat->fields as $field) {
            $fieldName = $field->name;
           if ($request["$fieldName"]) {
              $extraFields["$fieldName"] = $request["$fieldName"];
            }
        } 
        
        $ad->title = $request->title;
        $ad->slug = Str::slug($request->title).rand(411,799);
        $ad->use_condition = $request->condition;
        $ad->description = $request->description;
        $ad->price = $request->price;
        $ad->negotiable = $request->negotiable ? 1:0;
        $ad->contact_num = $request->phone;
        $ad->hide_contact = $request->hidenumber ? 1:0;
        $ad->fields = json_decode(json_encode($extraFields))??[];
       
        if($request->prev_image){
          $old = $ad->prev_image ?? null;  
          $ad->prev_image = uploadImage($request->prev_image,'assets/images/item_image/','200x200',$old,null);
        }
        $ad->save();
        
        if($images){
            foreach($images as $key => $image){
                $img = AdImage::firstOrNew(['id'=>$key]);
                $img->ad_id = $ad->id;
                $old = $img->image ?? null;
                $img->image = uploadImage($image,'assets/images/item_image/','800x400',$old,null);
                $img->update();
            }
        }
        
        $notify[]=['success','Ad updated successfully'];
        return back()->withNotify($notify);
    
    }

    public function removeAd($id)
    {
        $ad = AdList::findOrFail($id);
        AdImage::where('ad_id',$ad->id)->delete();
        AdPromote::where('ad_id',$ad->id)->delete();
        $ad->delete();
        $notify[]=['success','Ad removed successfully'];
        return back()->withNotify($notify);
    }
    
}
