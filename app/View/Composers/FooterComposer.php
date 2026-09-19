<?php

namespace App\View\Composers;

use App\Models\Setting;
use Illuminate\View\View;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

Class FooterComposer 
{
    public function compose(View $view): void 
    {
        $data = footerCategoryContent();
        $firstCategory = $data['firstCategory'];
        $secondCategory = $data['secondCategory'];
        $thirdCategory = $data['thirdCategory']; // Not used in the current code
        $fourthCategory = $data['fourthCategory'];
        $fifthCategory = $data['fifthCategory'];
        $ActiveCoupon = $data['ActiveCoupon'];

        $facebook = Setting::select('id','value')->where('key','Social.facebook')->first();
        $instagram = Setting::select('id','value')->where('key','Social.instagram')->first(); 
        $pinterst = Setting::select('id','value')->where('key','Social.pinterest')->first(); 
        $youtube = Setting::select('id','value')->where('key','Social.youtube')->first(); 
        $cart = Cart::with('product')->where('user_id', Auth::guard('customer')->id())->get(); 
        $view->with([
            'firstCategory'=>$firstCategory,
            'secondCategory'=>$secondCategory,
            'thirdCategory'=>$thirdCategory,
            'fourthCategory'=>$fourthCategory,
            'fifthCategory'=>$fifthCategory,
            'ActiveCoupon'=>$ActiveCoupon,
            'facebook'=>$facebook,
            'instagram' =>$instagram,
            'pinterst'=>$pinterst,
            'youtube'=>$youtube,
            'cart'=>$cart 
        ]);
        
    }
}