<?php

namespace App\Http\Controllers\Front\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\Setting;
use App\Helpers\EmailHelper;
use App\Models\ReferralSettingUpdateHistory;
use Illuminate\Http\Request;
use App\Mail\orderSuccessEmail;
use App\Mail\emailVerify;
use App\Models\ReferralHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;
use App\Http\Requests\Auth\AuthRequest;
use Session, Config, DB, Response, Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\{User, Cart, VariantValue, ProductVariantCombination};


class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            return view('front.auth.login');
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with(['error' => 'Somethig went wrong', 'error_msg' => $e->getMessage()]);
        }
    }

    public function signup(Request $request)
    {
        try {
            return view('front.auth.signup');
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with(['error' => 'Somethig went wrong', 'error_msg' => $e->getMessage()]);
        }
    }

    /*public function postLogin(Request $request)
    {
        $request->replace($this->arrayStripTags($request->all()));

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)
            ->where('user_role_id', config('constant.ROLE_ID.CUSTOMER_ROLE_ID'))
            ->where('is_deleted', 0)
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email is not registered with Vasvi'
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email or password is incorrect'
            ], 401);
        }

        if ($user->is_active == 0) {
            return response()->json([
                'message' => 'Your account is blocked. Please contact admin.'
            ], 403);
        }

        if ($user->id == 1) {
            return response()->json([
                'message' => 'This account is not allowed to login here.'
            ], 403);
        }

    
        Auth::guard('customer')->login($user);
        $cartItems = json_decode($request->cartItems, true);
        $this->cartItems($cartItems);
    
        return response()->json([
            'message' => 'Login successful',
        ]);
    }*/

    // public function postLogin(Request $request)
    // {
    //     $request->replace($this->arrayStripTags($request->all()));

    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ], [
    //         'email.required' => 'Email is required',
    //         'password.required' => 'Password is required',
    //     ]);
    //     if ($validator->fails()) {
    //         // return response()->json([
    //         //     'status' => false,
    //         //     'errors' => $validator->errors()
    //         // ], 422);
    //         return Redirect::back()->withErrors($validator)->withInput();
    //     }

    //     $user = User::where('email', $request->email)
    //         ->where('user_role_id', config('constant.ROLE_ID.CUSTOMER_ROLE_ID'))
    //         ->where('is_deleted', 0)
    //         ->first();

    //     if (!$user) {
    //         // return response()->json([
    //         //     'status' => false,
    //         //     'errors' => 'Email is not registered with Us'
    //         // ], 404);
    //         return Redirect::back()->withErrors('Email is not registered with Us')->withInput();
    //     }

    //     if (!Hash::check($request->password, $user->password)) {
    //         // return response()->json([
    //         //     'status' => false,
    //         //     'errors' => 'Email or password is incorrect'
    //         // ], 401);
    //         return Redirect::back()->withErrors('Email or password is incorrect')->withInput();
    //     }

    //     if ($user->is_active == 0) {
    //         // return response()->json([
    //         //     'status' => false,
    //         //     'errors' => 'Your account is blocked. Please contact admin.'
    //         // ], 403);
    //         return Redirect::back()->withErrors('Your account is blocked. Please contact admin.')->withInput();
    //     }

    //     if ($user->id == 1) {
    //         // return response()->json([
    //         //     'status' => false,
    //         //     'errors' => 'This account is not allowed to login here.'
    //         // ], 403);
    //         return Redirect::back()->withErrors('This account is not allowed to login here.')->withInput();
    //     }

    //     if (is_null($user->email_verified_at) || $user->email_verified_at == '0000-00-00 00:00:00')
    //     {
    //         // return response()->json([
    //         //     'status' => false,
    //         //     'errors' => 'Your email is not verified'
    //         // ], 403);
    //         return Redirect::back()->withErrors('Your email is not verified')->withInput();
    //     }

    //     $remember = $request->has('remember');
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials, $remember)) {
    //         // ✅ Store email in a cookie for 7 days (optional)
    //         Cookie::queue('user_email', $request->email, 60 * 24 * 15); // 15 days
    //         Cookie::queue('user_password', $request->password, 60 * 24 * 15); // 15 days
    //         if ($remember) {
    //             Cookie::queue('remember', true, 60 * 24 * 15); // Store for 15 days
    //         }
    //         Cookie::queue('auto_login', encrypt($user->id), 60 * 24 * 30); // 15 days

    //         // Optional: If using a custom guard like 'customer'
    //         $user = Auth::user();
    //         Auth::guard('customer')->login($user);

    //         // Handle cart items if present
    //         if ($request->has('cartItems')) {
    //             $cartItems = json_decode($request->cartItems, true);
    //             $this->cartItems($cartItems); // Assuming this is a valid method
    //         }

    //         return Redirect::route('user.dashboard')->with('success', trans('Login successfully'));
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Login successful',
    //         ]);
    //     }
    //     return response()->json([
    //         'status' => false,
    //         'errors' => 'Invalid credentials',
    //     ], 401);
    // }

    public function postLogin(Request $request)
    {
        // $request->replace($this->arrayStripTags($request->all()));

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }
        $user = User::where('email', $request->email)
            ->where('user_role_id', config('constant.ROLE_ID.CUSTOMER_ROLE_ID'))
            ->where('is_deleted', 0)
            ->first();

        if (!$user) {
            return Redirect::back()
                ->withErrors([
                    'email' => 'Email is not registered with Us'
                ])
                ->withInput($request->except('password'));
        }
        if (!Hash::check($request->password, $user->password)) {
            return Redirect::back()
                ->withErrors([
                    'email' => 'Email or password is incorrect'
                ])
                ->withInput($request->except('password'));
        }

        if ($user->is_active == 0) {
            return Redirect::back()
                ->withErrors([
                    'email' => 'Your account is blocked. Please contact admin.'
                ])
                ->withInput($request->except('password'));
        }
        if ($user->id == 1) {
            return Redirect::back()
                ->withErrors([
                    'email' => 'This account is not allowed to login here.'
                ])
                ->withInput($request->except('password'));
        }
        if (is_null($user->email_verified_at) || $user->email_verified_at == '0000-00-00 00:00:00') {
            return Redirect::back()
                ->withErrors([
                    'email' => 'Your email is not verified'
                ])
                ->withInput($request->except('password'));
        }
        $remember = $request->boolean('remember');

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (!Auth::guard('customer')->attempt($credentials, $remember)) {
            return Redirect::back()
                ->withErrors([
                    'email' => 'Invalid credentials'
                ])
                ->withInput($request->except('password'));
        }

        $request->session()->regenerate();
        $loggedInUser = Auth::guard('customer')->user();

        Cookie::queue('user_email',$request->email,60 * 24 * 15);

        if ($remember) {
            Cookie::queue('remember',true,60 * 24 * 15);
            Cookie::queue('auto_login',encrypt($loggedInUser->id),60 * 24 * 30);
        }

        if ($request->filled('cartItems')) {

            $cartItems = json_decode($request->cartItems, true);
            info("--------cartitems--------",[$cartItems]); 
            if (is_array($cartItems)) {
                $this->cartItems($cartItems);
            }
        }
        return Redirect::route('user.dashboard')
            ->with('success', trans('Login successfully'));
    }

    private function cartItems($cartItems)
    {
        $insertData = [];

        foreach ($cartItems as $item) {
            $variantValueNames = array_values($item["selectedVariants"]);
            $nameToId = VariantValue::whereIn('name', $variantValueNames)
                ->get()
                ->pluck('id', 'name');

            $variantValueIds = collect($variantValueNames)
                ->map(fn($name) => (int) ($nameToId[$name] ?? null))
                ->toArray();
            $valueIds = array_map('intval', $variantValueIds);
            $jsonCombo = json_encode($valueIds);

            $combination = ProductVariantCombination::where('product_id', $item['productId'])
                ->where('combination_id', $jsonCombo)
                ->first();

            if ($combination) {
                $alreadyExists = Cart::where('user_id', Auth::guard('customer')->id())
                    ->where('product_id', $item['productId'])
                    ->where('product_variant_combination_id', $combination->id)
                    ->exists();

                if (!$alreadyExists) {
                    $insertData[] = [
                        'user_id'                        => Auth::guard('customer')->id(),
                        'product_id'                     => $item['productId'],
                        'product_variant_combination_id' => $combination->id,
                        'quantity'                       => $item['quantity'],
                    ];
                }
            }
        }


        if (!empty($insertData)) {
            Cart::insert($insertData);
        }
    }


    public function postSignup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:users,email', 
            ],
            'phone_number' => [
                'required',
                'numeric',
                'digits:10',
                'unique:users,phone_number', 
            ],
            'referral' => [
                'nullable',
                'exists:users,user_referral_code',
            ],
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'confirm_password' => 'required|same:password',
        ]);


        if ($validator->fails()) {
            // return response()->json([
            //     'errors' => $validator->errors()
            // ], 422);
            return Redirect::back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $fullName = ucwords($request->name);

            $user = new User();
            $user->user_role_id = config('constant.ROLE_ID.CUSTOMER_ROLE_ID');
            $user->name = $fullName;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->password = Hash::make($request->password);
            $user->referral_code = $request->referral;
            $user->is_verified = 0;
            $user->is_active = 1;
            $user->is_approved = 1;
            $user->email_verified_at = now();
            $user->save();

            $lastId = $user->id;
            

			// Referral code generate logic
            // $referralCode = 'vas' .str_pad(substr($lastId, 0, 2), 2, '0', STR_PAD_LEFT) .substr($request->first_name, 0, 3) .substr($request->phone_number, -2); // OLD
			
			// PREFIX furnish_ FIRST 4 LETTERS OF NAME_LAST 2 DIGIT MOBILE NUMBER_01
			$referralCode = 'FURNISH_'. substr($request->name, 0, 4) .'_'. substr($request->phone_number, -2).'_'.$lastId;
			
            // $contactDetails = Setting::whereIn('key', ['Referral.receiver'])->first();
            // User::where('id', $lastId)->update([
            //     'user_referral_code' => $referralCode,
            //     'referral_wallet' => $contactDetails->value,
            //     'wallet_avl_balance' => $contactDetails->value,
            // ]);
            // $ref_sender = Setting::whereIn('key', ['Referral.sender'])->first();
            // $refuser = User::where('user_referral_code', $request->referral)->first();
            // $refHistory = new ReferralHistory();
            // $refHistory->referral_by = $refuser->id;
            // $refHistory->referral_to = $lastId;
            // $refHistory->referral_by_amount = $ref_sender->value;
            // $refHistory->referral_to_amount = $contactDetails->value;
            // $refHistory->save();
            // $refuser->wallet_avl_balance = $refuser->wallet_avl_balance + $ref_sender->value;
            // $refuser->referral_wallet = $refuser->referral_wallet + $ref_sender->value;
            // $refuser->save();

            $receiver_amount = 0;
            $sender_amount = 0;
            $referalDetails = ReferralSettingUpdateHistory::where('status',1)->first();
            if(!empty($referalDetails)){
                $receiver_amount = $referalDetails->receiver_amount;
                $sender_amount = $referalDetails->sender_amount;
            }
            $otp= random_int(100000, 999999);
            User::where('id', $lastId)->update([
                'user_referral_code' => $referralCode,
                'referral_wallet' => $receiver_amount,
                'wallet_avl_balance' => $receiver_amount,
                'email_otp' => $otp
            ]);
            
           
            if(!empty($request->referral)){
                $refuser = User::where('user_referral_code', $request->referral)->first();
                if(!empty($refuser)){
                    $refuser->wallet_avl_balance = $refuser->wallet_avl_balance + $sender_amount;
                    $refuser->referral_wallet = $refuser->referral_wallet + $sender_amount;
                    $refuser->save();

                    $refHistory = new ReferralHistory();
                    $refHistory->referral_by = $refuser->id;
                    $refHistory->referral_to = $lastId;
                    $refHistory->referral_by_amount = $sender_amount;
                    $refHistory->referral_to_amount = $receiver_amount;
                    $refHistory->save();
                }
            }
            
           // DB::commit();
            // $data = [
            //     'CUSTOMER_NAME' => $fullName,
            //     'OTP' => $otp,
            // ];
            // $template = EmailHelper::getProcessedTemplate('verify-your-account', $data);
            // Mail::to($request->email)->send(new emailVerify($template['subject'], $template['body']));
            
            // Auth::guard('customer')->login(User::findOrFail($lastId));
            //$cartItems = json_decode($request->cartItems, true);
            // $this->cartItems($cartItems);

            // return response()->json([
            //     'message' => 'Signup successfully, We have sent a OTP on your email.',
            //     'user_id' => $lastId,
            // ]);
            return Redirect::back()->with('success', 'You have successfully registered with us, Now you can login');
            // return Redirect::route('user.dashboard')->with('success', trans('Login successfully'));

        } catch (\Exception $e) {
           // DB::rollBack();
            return response()->json([
                'message' => $e
            ], 500);
        }
    }

    public function postSignupVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_otp' => [
                'required',
                'numeric',
                'digits:6',
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userByOtp = User::where('id', $request->user_id)->first();
            if($userByOtp){
                User::where('id', $userByOtp->id)->update(['email_verified_at' => date("Y-m-d H:i:s")]);
                
                Auth::guard('customer')->login(User::findOrFail($userByOtp->id));
                $cartItems = json_decode($request->cartItems, true);
                $this->cartItems($cartItems);

                return response()->json([
                    'status' => true,
                    'message' => 'Signup successfully.',

                ]);
            } else {
                return response()->json([
                    'message' => 'Something went wrong. Please try again.'
                 ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    public function resentotp(Request $request)
    {
        $user_id = $request->user_id;
        if(!empty($user_id)){
            $otp= random_int(100000, 999999);
            User::where('id', $user_id)->update(['email_otp' => $otp]);
            $user = User::where('id', $user_id)->first();
            $data = [
                'CUSTOMER_NAME' => $user->name,
                'OTP' => $otp,
            ];
            $template = EmailHelper::getProcessedTemplate('email_verify', $data);
            Mail::to($user->email)->send(new emailVerify($template['subject'], $template['body']));
            return response()->json([
                'status' => true,
                'message' => 'OTP resent successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again.'
            ]);
        }
    }


    public function forgetPassword()
    {

        return view('front.modules.auth.forget_password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        $token = \Illuminate\Support\Facades\Password::createToken($user);
        User::where('email', $user->email)->update(array('forgot_password_validate_string' => $token));

        $resetLink = url('/reset-password/' . $token . '?email=' . urlencode($user->email));

        // Get dynamic template

        $data = [
            'CUSTOMER_NAME' => $user->name,
            'RESET_LINK'      => $resetLink,
        ];

        $template = EmailHelper::getProcessedTemplate('forgot-password', $data);

        Mail::to($user->email)
            ->send(new orderSuccessEmail($template['subject'], $template['body']));

        return back()->with('success', 'We have successfully sent reset password link to your email');
    }

    public function resetPassword($validate_string = null, Request $request)
    {
        if ($validate_string != "" && $validate_string != null) {

            $userDetail    =    User::where('is_active', '1')->where('forgot_password_validate_string', $validate_string)->first();
            if (!empty($userDetail)) {
                return View::make('front.modules.auth.reset_password', compact('validate_string'));
            } else {
                return Redirect::route('front-user.login')
                    ->with('error', trans('Sorry, you are using wrong link.'));
            }
        } else {
            return Redirect::route('front-user.login')->with('error', trans('Sorry, you are using wrong link.'));
        }
    } // end resetPassword()

    public function sendPassword(Request $request)
    {
        $thisData                =    $request->all();
        $messages = array(
            'email.required'         => trans('The email field is required.'),
            'email.email'             => trans('The email must be a valid email address.'),
        );
        $validator = Validator::make(
            $request->all(),
            array(
                'email'             => 'required|email',
            ),
            $messages
        );
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $email        =    $request->input('email');
            $userDetail    =    User::where('email', $email)->where('user_role_id', config('constant.ROLE_ID.CUSTOMER_ROLE_ID'))->first();
            if (!empty($userDetail)) {
                if ($userDetail->is_active == 1) {
                    $forgot_password_validate_string    =     md5($userDetail->email . time() . time());
                    User::where('email', $email)->update(array('forgot_password_validate_string' => $forgot_password_validate_string));

                    $settingsEmail         =  Config::get('Site.email');
                    $email                 =  $userDetail->email;
                    $full_name            =  $userDetail->name;
                    $route_url          = route('front-user.resetPassword', $forgot_password_validate_string);

                    // $emailActions		=	EmailAction::where('action','=','forgot_password')->get()->toArray();
                    // $language_id			=	1;
                    // $emailTemplates			= 	EmailTemplate::where('action','=','forgot_password')->select("name","action",DB::raw("(select subject from email_template_descriptions where parent_id=email_templates.id AND language_id=$language_id) as subject"),DB::raw("(select body from email_template_descriptions where parent_id=email_templates.id AND language_id=$language_id) as body"))->get()->toArray();

                    // $cons = explode(',',$emailActions[0]['options']);
                    // $constants = array();

                    // foreach($cons as $key=>$val){
                    //     $constants[] = '{'.$val.'}';
                    // }
                    // $subject 			=  $emailTemplates[0]['subject'];
                    // $rep_Array 			= array($email,$route_url); 
                    // $messageBody		=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);

                    // $this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
                    Session::flash('flash_notice', trans('An email has been sent to your inbox. To reset your password please follow the steps mentioned in the email.'));
                    return Redirect::route('front-user.forgetPassword');
                } else {
                    return Redirect::route('front-user.forgetPassword')->with('error', trans('Your account has been temporarily disabled. Please contact administrator to unlock.'));
                }
            } else {
                return Redirect::route('front-user.forgetPassword')->with('error', trans('Your email is not registered with ' . config::get("Site.title") . "."));
            }
        }
    } // sendPassword()	

    public function resetPasswordSave($validate_string = null, Request $request)
    {
        $thisData                =    $request->all();
        $newPassword        =    $request->input('new_password');

        $messages = array(
            'new_password.required'                 => trans('The new password field is required.'),
            'new_password_confirmation.required'     => trans('The confirm password field is required.'),
            'new_password.confirmed'                 => trans('The confirm password must be match to new password.'),
            'new_password.min'                         => trans('The password must be at least 8 characters.'),
            'new_password_confirmation.min'         => trans('The confirm password must be at least 8 characters.'),
            "new_password.custom_password"            =>    "Password must have combination of numeric, alphabet and special characters.",
        );

        Validator::extend('custom_password', function ($attribute, $value, $parameters) {
            if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
                return true;
            } else {
                return false;
            }
        });
        $validator = Validator::make(
            $request->all(),
            array(
                'new_password'            => 'required|min:8|custom_password',
                'new_password_confirmation' => 'required|same:new_password',

            ),
            $messages
        );
        if ($validator->fails()) {
            return Redirect::route('front-user.resetPassword', $validate_string)
                ->withErrors($validator)->withInput();
        } else {

            $userInfo = User::where('forgot_password_validate_string', $validate_string)->first();
            if (empty($userInfo)) {
                Session::flash('error', trans('Invalid Validate String.'));
                return Redirect::back();
            }
            User::where('forgot_password_validate_string', $validate_string)
                ->update(array(
                    'password'                            =>    Hash::make($newPassword),
                    'forgot_password_validate_string'    =>    ''
                ));
            $settingsEmail         = Config::get('Site.email');

            Session::flash('flash_notice', trans('Thank you for resetting your password. Please login to access your account.'));

            return Redirect::route('front-user.login');
        }
    } // end resetPasswordSave()

    public function logout()
    {   
        try {
            Cookie::queue(Cookie::forget('auto_login'));
            $user = auth()->user();
            session()->flush();
            cache()->flush();
            auth()->logout();

            return redirect(url('/'))->with('success', "You're logged out successfully");
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with(['error' => 'Somethig went wrong', 'error_msg' => $e->getMessage()]);
        }
    }
}