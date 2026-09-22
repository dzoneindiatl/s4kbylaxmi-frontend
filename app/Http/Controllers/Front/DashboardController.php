<?php

namespace App\Http\Controllers\Front;

use Share;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Country;
use App\Models\Wishlist;
use App\Models\OrderItem;
use App\Models\UserReview;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\{State, OrderStatus, WalletHistory};
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\AuthRequest;
use App\Models\ProductVariantCombination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Redirect, Session, Config, DB, Response, Str;
use App\Models\ProductVariantCombinationImage;
use App\Models\Contact;
use App\Models\RefundRequest;



class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {

        if (Auth::guard('customer')->check()) {
            $user = User::where('id', Auth::guard('customer')->user()->id)->first();
            $redeemedamount = WalletHistory::where('user_id', $user->id)->where('type', 'debit')->sum('amount');
            $totalrefund = WalletHistory::where('user_id', $user->id)
                ->where('type', 'credit')
                ->where('description', 'like', '%refund%')
                ->sum('amount');
            $userAddressDetails = UserAddress::where('user_id', Auth::guard('customer')->user()->id)->get();
            $countries = Country::where('is_active', 1)->pluck('name', 'id');
            $states = State::where('country_id', 101)->where('is_active', 1)->pluck('name', 'id');
            $allStatuses = OrderStatus::orderBy('step')->where('active', 1)->get();
            $referralLink = url('?referral=' . Auth::guard('customer')->user()->user_referral_code);

            $orderDetails = Order::with('items', 'items.productGraphics')->where('user_id', Auth::guard('customer')->user()->id)->orderBy('id', 'desc')->get();
            $orderCurentDetails = Order::with('items', 'items.productGraphics')->where('user_id', Auth::guard('customer')->user()->id)->whereNotIn('status',array('delivered','cancelled','cancelled_by_customer','return-rejected'))->orderBy('id', 'desc')->get();
            $orderPastDetails = Order::with('items', 'items.productGraphics')->where('user_id', Auth::guard('customer')->user()->id)
            ->whereIn('status',array('delivered','cancelled','cancelled_by_customer','return-rejected'))->orderBy('id', 'desc')->get();

            $shareTitle = 'Join me on Vasvi! Use my referral link.';

            $share_buttons = \Jorenvh\Share\ShareFacade::page($referralLink, $shareTitle)
                ->facebook()
                ->twitter()
                ->linkedin()
                ->whatsapp()
                ->telegram()
                ->reddit();

            return view('front.modules.dashboard.index', compact('user', 'userAddressDetails', 'countries', 'states', 'orderDetails','orderCurentDetails','orderPastDetails', 'share_buttons', 'allStatuses', 'redeemedamount', 'totalrefund'));
        } else {
            return redirect()->route('home.index');
        }
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with(['error' => 'Somethig went wrong', 'error_msg' => $e->getMessage()]);
        }
    }


    public function userDashboard(Request $request)
    {
        try {
            $user = User::where('id', Auth::guard('customer')->user()->id)->first();
            $userAddressDetails = UserAddress::where('user_id', Auth::guard('customer')->user()->id)->get();
            $countries = Country::where('is_active', 1)->pluck('name', 'id');
            $states = State::where('country_id', 101)->where('is_active', 1)->pluck('name', 'id');
            $orderDetails = Order::with('items', 'items.productGraphics')->where('user_id', Auth::guard('customer')->user()->id)->orderBy('id', 'desc')->get();
            $allStatuses = OrderStatus::orderBy('step')->where('active', 1)->get();
            $share_buttons = \Share::page(
                'https://www.laravelclick.com/post/laravel-10-social-media-share-buttons-integration-tutorial',
                'How to Add Social Media Share Button in Laravel 10 App?'
            )->facebook()->twitter()->linkedin()->whatsapp()->telegram()->reddit();

            return view('front.modules.dashboard.index', compact('user', 'userAddressDetails', 'countries', 'states', 'orderDetails', 'share_buttons', 'allStatuses'));
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with(['error' => 'Somethig went wrong', 'error_msg' => $e->getMessage()]);
        }
    }
    public function addresses(Request $request)
    {
        try {
            $userAddresses = UserAddress::where('user_id', Auth::guard('customer')->user()->id)->get();

            return view('front.modules.dashboard.addresses', compact('userAddresses'));
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with(['error' => 'Somethig went wrong', 'error_msg' => $e->getMessage()]);
        }
    }
    public function orders(Request $request)
    {
        // try {
        $active_orders = OrderItem::whereNotIn('status', ['delivered', 'cancelled', 'returned'])
            ->leftjoin('orders', 'orders.id', 'order_items.order_id')
            ->leftjoin('product_variant_combinations', 'product_variant_combinations.id', 'order_items.product_id')
            ->leftjoin('products', 'product_variant_combinations.product_id', 'products.id')
            ->leftjoin('user_addresses', 'user_addresses.id', 'orders.address_id')
            ->where('orders.user_id', Auth::guard('customer')->user()->id)
            ->select('order_items.*', 'orders.order_number', 'orders.currency_code', 'products.name', 'user_addresses.name', 'user_addresses.email', 'user_addresses.phone_number', 'user_addresses.country_id_id', 'user_addresses.address', 'user_addresses.postal_code', 'user_addresses.city_id', 'user_addresses.state_id', 'user_addresses.landmark')
            ->get();
        $active_orders_count = $active_orders->count();
        $active_orders = $active_orders->toArray();

        if (!empty($active_orders)) {
            foreach ($active_orders as &$active_order) {
                $active_order['product_image'] = ProductVariantCombinationImage::where('product_variant_combination_images.product_variant_combination_id', $active_order['product_id'])->leftJoin('product_images', 'product_images.id', 'product_variant_combination_images.product_image_id')->select('product_images.image')->first();
                if (!empty($active_order['product_image'])) {
                    $productImage = (!empty($active_order['product_image'])) ? Config('constant.PRODUCT_IMAGE_URL') . $active_order['product_image']['image'] : Config('constant.IMAGE_URL') . "noimage.png";
                    $active_order['product_image'] = $productImage;
                }
            }
        }

        $delivered_orders = OrderItem::where('status', 'delivered')
            ->leftjoin('orders', 'orders.id', 'order_items.order_id')
            ->leftjoin('product_variant_combinations', 'product_variant_combinations.id', 'order_items.product_id')
            ->leftjoin('products', 'product_variant_combinations.product_id', 'products.id')
            ->leftjoin('user_addresses', 'user_addresses.id', 'orders.address_id')
            ->where('orders.user_id', Auth::guard('customer')->user()->id)
            ->select('order_items.*', 'orders.order_number', 'orders.currency_code', 'products.name', 'user_addresses.name', 'user_addresses.email', 'user_addresses.phone_number', 'user_addresses.country_id', 'user_addresses.address', 'user_addresses.postal_code', 'user_addresses.city_id', 'user_addresses.state_id', 'user_addresses.landmark')
            ->get();
        $delivered_orders_count = $delivered_orders->count();
        $delivered_orders = $delivered_orders->toArray();

        if (!empty($delivered_orders)) {
            foreach ($delivered_orders as &$delivered_order) {
                $delivered_order['product_image'] = ProductVariantCombinationImage::where('product_variant_combination_images.product_variant_combination_id', $delivered_order['product_id'])->leftJoin('product_images', 'product_images.id', 'product_variant_combination_images.product_image_id')->select('product_images.image')->first();
                if (!empty($delivered_order['product_image'])) {
                    $productImage = (!empty($delivered_order['product_image'])) ? Config('constant.PRODUCT_IMAGE_URL') . $delivered_order['product_image']['image'] : Config('constant.IMAGE_URL') . "noimage.png";
                    $delivered_order['product_image'] = $productImage;
                }
            }
        }

        $cancelled_orders = OrderItem::where('status', 'cancelled')
            ->leftjoin('orders', 'orders.id', 'order_items.order_id')
            ->leftjoin('product_variant_combinations', 'product_variant_combinations.id', 'order_items.product_id')
            ->leftjoin('products', 'product_variant_combinations.product_id', 'products.id')
            ->leftjoin('user_addresses', 'user_addresses.id', 'orders.address_id')
            ->where('orders.user_id', Auth::guard('customer')->user()->id)
            ->select('order_items.*', 'orders.order_number', 'orders.currency_code', 'products.name', 'user_addresses.name', 'user_addresses.email', 'user_addresses.phone_number', 'user_addresses.country_id', 'user_addresses.address', 'user_addresses.postal_code', 'user_addresses.city_id', 'user_addresses.state_id', 'user_addresses.landmark')
            ->get();
        $cancelled_orders_count = $cancelled_orders->count();
        $cancelled_orders = $cancelled_orders->toArray();

        if (!empty($cancelled_orders)) {
            foreach ($cancelled_orders as &$cancelled_order) {
                $cancelled_order['product_image'] = ProductVariantCombinationImage::where('product_variant_combination_images.product_variant_combination_id', $cancelled_order['product_id'])->leftJoin('product_images', 'product_images.id', 'product_variant_combination_images.product_image_id')->select('product_images.image')->first();
                if (!empty($cancelled_order['product_image'])) {
                    $productImage = (!empty($cancelled_order['product_image'])) ? Config('constant.PRODUCT_IMAGE_URL') . $cancelled_order['product_image']['image'] : Config('constant.IMAGE_URL') . "noimage.png";
                    $cancelled_order['product_image'] = $productImage;
                }
            }
        }
        // echo "<pre>"; print_r($active_orders); die;
        return view('front.modules.dashboard.orders', compact('active_orders', 'delivered_orders', 'cancelled_orders', 'cancelled_orders_count', 'active_orders_count', 'delivered_orders_count'));
        // } catch (Exception $e) {
        //     Log::error($e);
        //     return redirect()->back()->with(['error' => 'Somethig went wrong', 'error_msg' => $e->getMessage()]);
        // }
    }

    public function updateProfile(Request $request)
    {
        $formData = $request->all();
        if (!empty($formData)) {
            $validator = Validator::make(
                $request->all(),
                array(
                   // 'image'      => 'nullable|mimes:jpg,jpeg,png,webp',
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->guard('customer')->user()->id)->where('user_role_id', config('constant.ROLE_ID.CUSTOMER_ROLE_ID'))],
                    'phone_number' =>  ['required', 'numeric', Rule::unique('users')->ignore(auth()->guard('customer')->user()->id)->where('user_role_id', config('constant.ROLE_ID.CUSTOMER_ROLE_ID')), 'digits:10'],
                    'new_password' => ['required', Password::min(8)],
                    'confirm_password' => 'required|required_with:new_password|min:8|same:new_password'
                ),
                array(
                    //"image.mimes" => trans("The image field must be a file of type jpg jpeg png"),
                    "phone_number.required" => trans("The phone number field is required"),
                    "new_password.required" => trans("The new password field is required"),
                    "new_password.min" => trans("The new password must be atleast 8 characters"),
                    "confirm_password.required" => trans("The confirm password field is required"),
                    "confirm_password.same" => trans("The confirm password should be same as new password")
                )
            );
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                DB::beginTransaction();
                $obj   = User::find(Auth::guard('customer')->user()->id);
                $obj->name = $request->name;
                $obj->phone_number = $request->phone_number;
                $obj->email = $request->email;
                $obj->gender = $request->gender;
               // $obj->date_of_birth = $request->date_of_birth;

                // if ($request->hasFile('image')) {
                //     $extension = $request->file('image')->getClientOriginalExtension();
                //     $originalName = $request->file('image')->getClientOriginalName();
                //     $fileName = time() . '-image.' . $extension;

                //     $folderName = strtoupper(date('M') . date('Y')) . "/";
                //     $folderPath = Config('constant.USER_IMAGE_ROOT_PATH') . $folderName;
                //     if (!File::exists($folderPath)) {
                //         File::makeDirectory($folderPath, $mode = 0777, true);
                //     }
                //     if ($request->file('image')->move($folderPath, $fileName)) {
                //         $obj->image = $folderName . $fileName;
                //     }
                // }
                $obj->password = Hash::make($request->new_password);
                $obj->save();
                $lastId = $obj->id;
                if (empty($lastId)) {
                    DB::rollback();
                    return Redirect::back()->with('error', 'Something went wrong');
                }
                DB::commit();

                return Redirect::back()->with('success', 'Your Profile has been updated successfully');
            }
        } else {
           return Redirect::back()->with('error', 'Invalid Request');
        }
    }

    public function changePassword(Request $request)
    {
        $user = User::where('id', auth()->guard('customer')->user()->id)->first();
        $formData = $request->all();
        if (!empty($formData)) {
            Validator::extend('current_password_check', function ($attribute, $value, $parameters, $validator) use ($user) {
                return Hash::check($value, $user->password);
            });
            $validator = Validator::make(
                $request->all(),
                array(
                    'current_password' => (!empty($user->password)) ? 'required|current_password_check' : '',
                    'new_password' => ['required', Password::min(8)],
                    'confirm_password' => 'required|required_with:new_password|min:8|same:new_password'

                ),
                array(

                    "current_password.current_password_check" => trans("The password entered is invalid"),
                    "current_password.required" => trans("The current password field is required"),
                    "new_password.required" => trans("The new password field is required"),
                    "new_password.min" => trans("The new password must be atleast 8 characters"),
                    "confirm_password.required" => trans("The confirm password field is required"),
                    "confirm_password.same" => trans("The confirm password should be same as new password")

                )
            );
            if ($validator->fails()) {
                $response = $this->change_error_msg_layout($validator->errors()->getMessages());
                return Response::json($response, 200);
            } else {
                DB::beginTransaction();
                $obj   = User::find(Auth::guard('customer')->user()->id);
                $obj->password = Hash::make($request->new_password);
                $obj->save();
                $lastId = $obj->id;
                if (empty($lastId)) {
                    DB::rollback();
                    $response = array();
                    $response["status"] = "error";
                    $response["msg"] = trans("Something_went_wrong");
                    $response["data"] = (object) array();
                    $response["http_code"] = 500;
                    return Response::json($response, 500);
                }
                DB::commit();



                $response = array();
                $response["status"] = "success";
                $response["msg"] = trans("Password Changed Successfully");
                $response["data"] = (object) array();
                $response["http_code"] = 200;
                return Response::json($response, 200);
            }
        } else {
            $response = array();
            $response["status"] = "error";
            $response["msg"] = trans("Invalid request");
            $response["data"] = (object) array();
            $response["http_code"] = 500;
            return Response::json($response, 500);
        }
    }

    public function addAddress(Request $request)
    {
        $formData = $request->all();
        if (!empty($formData)) {
            $validator = Validator::make(
                $request->all(),
                array(
                    'name'      => 'required',
                    'email' => ['required', 'email'],
                    'phone_number' =>  ['required', 'numeric', 'digits:10'],
                    'country'      => 'required',
                    'address_line_1'      => 'required',
                    'postal_code' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                )
            );
            if ($validator->fails()) {
                $response = $this->change_error_msg_layout($validator->errors()->getMessages());
                return Response::json($response, 200);
            } else {
                DB::beginTransaction();
                $obj   = new UserAddress;
                $obj->user_id = Auth::guard('customer')->user()->id;
                $obj->name = !empty($request->name) ? $request->name : NULL;
                $obj->email = !empty($request->email) ? $request->email : NULL;
                $obj->phone_number = !empty($request->phone_number) ? $request->phone_number : NULL;
                $obj->country = !empty($request->country) ? $request->country : NULL;
                $obj->address_line_1 = !empty($request->address_line_1) ? $request->address_line_1 : NULL;
                $obj->address_line_2 = !empty($request->address_line_2) ? $request->address_line_2 : NULL;
                $obj->postal_code = !empty($request->postal_code) ? $request->postal_code : NULL;
                $obj->city = !empty($request->city) ? $request->city : NULL;
                $obj->state = !empty($request->state) ? $request->state : NULL;
                $obj->landmark = !empty($request->landmark) ? $request->landmark : NULL;
                $checkUserAddresses = UserAddress::where('user_id', Auth::guard('customer')->user()->id)->where('is_primary', 1)->count();
                if ($checkUserAddresses == 0) {
                    $obj->is_primary = 1;
                }
                $obj->save();

                $lastId = $obj->id;
                if (empty($lastId)) {
                    DB::rollback();
                    $response = array();
                    $response["status"] = "error";
                    $response["msg"] = trans("Something went wrong");
                    $response["data"] = (object) array();
                    $response["http_code"] = 500;
                    return Response::json($response, 500);
                }
                DB::commit();

                Session()->flash('success', trans("Your profile has been updated successfully."));
                return Redirect::route('front-user.accountSetting')->with('status', 'profile-updated');
            }
        } else {
            $response = array();
            $response["status"] = "error";
            $response["msg"] = trans("Invalid request");
            $response["data"] = (object) array();
            $response["http_code"] = 500;
            return Response::json($response, 500);
        }
    }

    public function editAddress(Request $request, $addressId)
    {
        $formData = $request->all();
        if (!empty($formData)) {
            $validator = Validator::make(
                $request->all(),
                array(
                    'name'      => 'required',
                    'email' => ['required', 'email'],
                    'phone_number' =>  ['required', 'numeric', 'digits:10'],
                    'country'      => 'required',
                    'address_line_1'      => 'required',
                    'postal_code' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                )
            );
            if ($validator->fails()) {
                $response = $this->change_error_msg_layout($validator->errors()->getMessages());
                return Response::json($response, 200);
            } else {
                DB::beginTransaction();
                $obj   = UserAddress::find($addressId);
                $obj->name = !empty($request->name) ? $request->name : NULL;
                $obj->email = !empty($request->email) ? $request->email : NULL;
                $obj->phone_number = !empty($request->phone_number) ? $request->phone_number : NULL;
                $obj->country = !empty($request->country) ? $request->country : NULL;
                $obj->address_line_1 = !empty($request->address_line_1) ? $request->address_line_1 : NULL;
                $obj->address_line_2 = !empty($request->address_line_2) ? $request->address_line_2 : NULL;
                $obj->postal_code = !empty($request->postal_code) ? $request->postal_code : NULL;
                $obj->city = !empty($request->city) ? $request->city : NULL;
                $obj->state = !empty($request->state) ? $request->state : NULL;
                $obj->landmark = !empty($request->landmark) ? $request->landmark : NULL;
                $obj->save();

                $lastId = $obj->id;
                if (empty($lastId)) {
                    DB::rollback();
                    $response = array();
                    $response["status"] = "error";
                    $response["msg"] = trans("Something went wrong");
                    $response["data"] = (object) array();
                    $response["http_code"] = 500;
                    return Response::json($response, 500);
                }
                DB::commit();

                $response = array();
                $response["status"] = "success";
                $response["msg"] = trans("Address updated Successfully");
                $response["data"] = (object) array();
                $response["http_code"] = 200;
                return Response::json($response, 200);
            }
        } else {
            $response = array();
            $response["status"] = "error";
            $response["msg"] = trans("Invalid request");
            $response["data"] = (object) array();
            $response["http_code"] = 500;
            return Response::json($response, 500);
        }
    }

    public function deleteAddress(Request $request, $addressId)
    {
        $checkIfAddressExists = UserAddress::where('id', $addressId)->first();
        if (!empty($checkIfAddressExists)) {

            UserAddress::where('id', $addressId)->delete();
            if ($checkIfAddressExists->is_primary == 1) {
                $addressCount = UserAddress::where('user_id', $checkIfAddressExists->user_id)->count();
                if ($addressCount > 0) {

                    UserAddress::where('user_id', $checkIfAddressExists->user_id)->first()->update(['is_primary' => 1]);
                }
            }
            Session()->flash('flash_notice', 'Address deleted successfully');
            return Redirect::route('front-user.addresses');
        } else {
            Session()->flash('error', 'Invalid Request');
            return Redirect::route('front-user.addresses');
        }
    }
    public function makeAddressPrimary(Request $request, $addressId)
    {
        $checkIfAddressExists = UserAddress::where('id', $addressId)->first();
        if (!empty($checkIfAddressExists)) {

            UserAddress::where('user_id', $checkIfAddressExists->user_id)->update(['is_primary' => 0]);
            UserAddress::where('id', $addressId)->update(['is_primary' => 1]);

            Session()->flash('flash_notice', 'Address status changed successfully');
            return Redirect::route('front-user.addresses');
        } else {
            Session()->flash('error', 'Invalid Request');
            return Redirect::route('front-user.addresses');
        }
    }
    public function addReview(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'title' => 'required|max:255',
            'review' => 'required|max:5000',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        $user = Auth::guard('customer')->user();

        $uploadedImages = [];

        if ($request->hasFile('image')) {

            $uploadedImages = [];

            foreach ($request->file('image') as $image) {
                $extension = $image->getClientOriginalExtension();
                $originalName = $image->getClientOriginalName();
                $fileName = time() . '-' . uniqid() . '.' . $extension;

                $folderName = strtoupper(date('M') . date('Y')) . "/";
                $folderPath = Config('constant.REVIEW_IMAGE_ROOT_PATH') . $folderName;

                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, 0777, true);
                }

                if ($image->move($folderPath, $fileName)) {
                    $uploadedImages[] = $folderName . $fileName;
                }
            }
        }
        UserReview::create([
            'product_id' => $request->product_id,
            'user_id' => $user->id, // optional
            'title' => $request->title,
            'review' => $request->review,
            'rating' => $request->rating,
            'image' => json_encode($uploadedImages),
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }



    public function toggle(Request $request)
    {
        $user = Auth::guard('customer')->user();
        $productId = $request->product_id;
        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            //return response()->json(['status' => 'removed', 'message' => 'Removed from wishlist']);
            $wishlistCount = $user ? Wishlist::where('user_id', $user->id)->count() : 0;
            return response()->json(['status' => 'removed', 'message' => 'Removed from wishlist', 'wishlistCount' => $wishlistCount]);
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
            //return response()->json(['status' => 'added','message' => 'Added to wishlist']);
            $wishlistCount = $user ? Wishlist::where('user_id', $user->id)->count() : 0;
            return response()->json(['status' => 'added', 'message' => 'Added to wishlist', 'wishlistCount' => $wishlistCount]);
        }
    }

    /* New function accoring to new design od dashboard */
    public function myPurchase(Request $request)
    {
        $active_orders = OrderItem::whereNotIn('order_items.status', ['delivered', 'cancelled', 'returned'])
                                ->leftJoin('orders', 'orders.id', '=', 'order_items.order_id')
                                ->leftJoin('product_variant_combinations', 'product_variant_combinations.id', '=', 'order_items.product_id')
                                ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
                                ->leftJoin('user_addresses as ua', 'ua.id', '=', 'orders.shipping_address_id')
                                ->leftJoin('countries', 'countries.id', '=', 'ua.country_id')
                                ->leftJoin('states', 'states.id', '=', 'ua.state_id')
                                ->leftJoin('cities', 'cities.id', '=', 'ua.city_id')
                                ->where('orders.user_id', Auth::guard('customer')->user()->id)
                                ->select(
                                    'order_items.*',
                                    'orders.delivery',
                                    'orders.coupon_discount',
                                    'orders.order_number',
                                    'orders.id as order_id',
                                    'orders.shippingcharge',
                                    'orders.total as order_total',
                                    'orders.currency_code',
                                    'products.name as product_name',
                                    'ua.name as address_name',
                                    'ua.address as address',
                                    'ua.email',
                                    'ua.phone_number',
                                    'ua.postal_code',
                                    'ua.landmark',
                                    'countries.name as country_name',
                                    'states.name as state_name',
                                    'cities.name as city_name'
                                )->get();

            $active_orders_count = $active_orders->count();
            $active_orders = $active_orders->toArray();

            if(!empty($active_orders)){
                foreach ($active_orders as &$active_order) {
                    $productId = $active_order['product_id'];
                
                    $frontGraphic = \DB::table('product_graphics')
                        ->where('product_id', $productId)
                        ->where('is_front', 1)
                        ->value('graphic');
                
                    $backGraphic = \DB::table('product_graphics')
                        ->where('product_id', $productId)
                        ->where('is_back', 1)
                        ->value('graphic');
                
                    $graphics = [
                        !empty($frontGraphic)
                            ? config('constant.PRODUCT_IMAGE_URL') . $frontGraphic
                            : config('constant.IMAGE_URL') . "noimage.png",
                
                        !empty($backGraphic)
                            ? config('constant.PRODUCT_IMAGE_URL') . $backGraphic
                            : config('constant.IMAGE_URL') . "noimage.png",
                    ];
                
                    // Assign to the view-accessible parameter
                    $active_order['product_image'] = $graphics;
                }
            }

            $delivered_orders = OrderItem::where('order_items.status', 'delivered')
                                    ->leftJoin('orders', 'orders.id', '=', 'order_items.order_id')
                                    ->leftJoin('product_variant_combinations', 'product_variant_combinations.id', '=', 'order_items.product_id')
                                    ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
                                    ->leftJoin('user_addresses as ua', 'ua.id', '=', 'orders.shipping_address_id')
                                    ->leftJoin('countries', 'countries.id', '=', 'ua.country_id')
                                    ->leftJoin('states', 'states.id', '=', 'ua.state_id')
                                    ->leftJoin('cities', 'cities.id', '=', 'ua.city_id')
                                    ->where('orders.user_id', Auth::guard('customer')->user()->id)
                                    ->select(
                                        'order_items.*',
                                        'orders.delivery',
                                        'orders.coupon_discount',
                                        'orders.order_number',
                                        'orders.id as order_id',
                                        'orders.shippingcharge',
                                        'orders.total as order_total',
                                        'orders.currency_code',
                                        'products.name as product_name',
                                        'ua.name as address_name',
                                        'ua.address as address',
                                        'ua.email',
                                        'ua.phone_number',
                                        'ua.postal_code',
                                        'ua.landmark',
                                        'countries.name as country_name',
                                        'states.name as state_name',
                                        'cities.name as city_name'
                                        )
                                    ->get();
            $delivered_orders_count = $delivered_orders->count();
            $delivered_orders = $delivered_orders->toArray();

            if (!empty($delivered_orders)) {
                foreach ($delivered_orders as &$delivered_order) {
                    $productId = $delivered_order['product_id'];

                    $frontGraphic = \DB::table('product_graphics')
                        ->where('product_id', $productId)
                        ->where('is_front', 1)
                        ->value('graphic');

                    $backGraphic = \DB::table('product_graphics')
                        ->where('product_id', $productId)
                        ->where('is_back', 1)
                        ->value('graphic');

                    $graphics = [
                        !empty($frontGraphic)
                            ? config('constant.PRODUCT_IMAGE_URL') . $frontGraphic
                            : config('constant.IMAGE_URL') . "noimage.png",

                        !empty($backGraphic)
                            ? config('constant.PRODUCT_IMAGE_URL') . $backGraphic
                            : config('constant.IMAGE_URL') . "noimage.png",
                    ];

                    $delivered_order['product_image'] = $graphics;
                }
            }

            $cancelled_orders = OrderItem::where('order_items.status', 'cancelled')
                                    ->leftJoin('orders', 'orders.id', '=', 'order_items.order_id')
                                    ->leftJoin('product_variant_combinations', 'product_variant_combinations.id', '=', 'order_items.product_id')
                                    ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
                                    ->leftJoin('user_addresses as ua', 'ua.id', '=', 'orders.shipping_address_id')
                                    ->leftJoin('countries', 'countries.id', '=', 'ua.country_id')
                                    ->leftJoin('states', 'states.id', '=', 'ua.state_id')
                                    ->leftJoin('cities', 'cities.id', '=', 'ua.city_id')
                                    ->where('orders.user_id', Auth::guard('customer')->user()->id)
                                    ->select(
                                        'order_items.*',
                                        'orders.delivery',
                                        'orders.coupon_discount',
                                        'orders.order_number',
                                        'orders.id as order_id',
                                        'orders.shippingcharge',
                                        'orders.total as order_total',
                                        'orders.currency_code',
                                        'products.name as product_name',
                                        'ua.name as address_name',
                                        'ua.address as address',
                                        'ua.email',
                                        'ua.phone_number',
                                        'ua.postal_code',
                                        'ua.landmark',
                                        'countries.name as country_name',
                                        'states.name as state_name',
                                        'cities.name as city_name'
                                    )
                                    ->get();

            $cancelled_orders_count = $cancelled_orders->count();
            $cancelled_orders = $cancelled_orders->toArray();

            if (!empty($cancelled_orders)) {
                foreach ($cancelled_orders as &$cancelled_order) {
                    $productId = $cancelled_order['product_id'];

                    $frontGraphic = \DB::table('product_graphics')
                        ->where('product_id', $productId)
                        ->where('is_front', 1)
                        ->value('graphic');

                    $backGraphic = \DB::table('product_graphics')
                        ->where('product_id', $productId)
                        ->where('is_back', 1)
                        ->value('graphic');

                    $graphics = [
                        !empty($frontGraphic)
                            ? config('constant.PRODUCT_IMAGE_URL') . $frontGraphic
                            : config('constant.IMAGE_URL') . "noimage.png",

                        !empty($backGraphic)
                            ? config('constant.PRODUCT_IMAGE_URL') . $backGraphic
                            : config('constant.IMAGE_URL') . "noimage.png",
                    ];

                    $cancelled_order['product_image'] = $graphics;
                }
            }
        return view('front.modules.dashboard.mypurchase', compact('active_orders', 'delivered_orders', 'cancelled_orders', 'cancelled_orders_count', 'active_orders_count', 'delivered_orders_count'));
    }

    public function myPurchaseDetail(Request $request , $orderId)
    {
        $orderDetails = Order::with('items', 'items.productGraphics')->where('id', $orderId)->where('user_id', Auth::guard('customer')->user()->id)->first();
        return view('front.modules.dashboard.mypurchasedetail', compact('orderDetails'));
    }

    public function accountSetting(Request $request)
    {
        $user = User::where('id', Auth::guard('customer')->user()->id)->first();
        $userBillingAddress = UserAddress::with(['country', 'state', 'city'])->where(['user_id' => Auth::guard('customer')->user()->id, 'type' => 'billing'])->orderBy('id', 'desc')->get();
        $usershippingAddress = UserAddress::with(['country', 'state', 'city'])->where(['user_id' => Auth::guard('customer')->user()->id, 'type' => 'shipping'])->orderBy('id', 'desc')->get();
        $countries = Country::where('is_active', 1)->pluck('name', 'id');
        $states = State::where('country_id', 101)->where('is_active', 1)->pluck('name', 'id');
        return view('front.modules.dashboard.accountsetting', compact('user', 'userBillingAddress', 'usershippingAddress', 'countries', 'states'));
    }

    public function walletPayment(Request $request)
    {
        $user = User::where('id', Auth::guard('customer')->user()->id)->first();
        $refundRequest = RefundRequest::where('user_id', Auth::guard('customer')->user()->id)->where('status', 1)->where('refund_mode', 'account')->get();
        return view('front.modules.dashboard.walletpayment', compact('user', 'refundRequest'));
    }

    public function wishlist(Request $request)
    {
        try {
            if ($request->ajax()) {
                $count = Wishlist::where('user_id', auth()->guard('customer')->user()->id)->count();
                $wishlistData = Wishlist::where('user_id', auth()->guard('customer')->user()->id)
                    ->get();
                $html = view('front.modules.dashboard.wishlist', compact('wishlistData'))->render();
                return response()->json([
                    'status' => true,
                    'wishlistData' => $html,
                    'data' => $count
                ]);
            } else {
                  $wishlistData = Wishlist::where('user_id', auth()->guard('customer')->user()->id)
                    ->get();
                 return view('front.modules.dashboard.wishlist', compact('wishlistData'));
            }
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with(['error' => 'Somethig went wrong', 'error_msg' => $e->getMessage()]);
        }
    }

    public function suggestion(Request $request)
    {
        return view('front.modules.dashboard.suggestion');
    }

    public function contactwithus(Request $request)
    {
        return view('front.modules.dashboard.contactwithus');
    }

    public function contactSuggestionSave(Request $request)
    {
        $request->replace($this->arrayStripTags($request->all()));

        $fields = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
            ],
            'phone_number' => [
                'required',
                'numeric',
                'digits:10',
            ],
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:255',
        ];
        if($request->type == 'suggestion') { 
            $fields['title'] = 'required|string|max:255';
        }

        $validator = Validator::make($request->all(), $fields);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        
        if($request->type == 'suggestion') {
            $data['name'] = Auth::guard('customer')->user()->name;
            $data['email'] = Auth::guard('customer')->user()->email;
            $data['phone_number'] = Auth::guard('customer')->user()->phone_number;
        } 
        Contact::create($data);
        return Redirect::route('user.dashboard')->with('success', trans('Your Message has been submitted successfully'));
    }

    public function rateingReview(Request $request)
    {
        return view('front.modules.dashboard.rateingReview');
    }

    public function inviteFriends(Request $request)
    {
        return view('front.modules.dashboard.inviteFriends');
    }

    /* New function accoring to new design od dashboard */
}
