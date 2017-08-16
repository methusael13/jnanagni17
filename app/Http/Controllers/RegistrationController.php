<?php

namespace jnanagni\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use jnanagni\User;
use jnanagni\Http\Requests;
use jnanagni\Mail\VerifyEmail;
use jnanagni\Library\Utility\StringUtility;

class RegistrationController extends Controller {

    const RANDOM_STR_LENGTH = 20;
    private static $appAPIKey = 'e80f491806701ca2c737b01e7ba5a37d';

    const API_ROUTE_LOGIN = 'login';
    const API_ROUTE_LOGOUT = 'logout';
    const API_ROUTE_REGISTER = 'register';

    protected function rules() {
        return [
            'first-name' => 'required',
            'email' => 'bail|required|email|unique:users',
            'phone' => 'bail|required|digits:10|unique:users',
            'password' => 'required|min:6|max:100',
            'conf-password' => 'bail|required|same:password',
            'college' => 'required', 'gender' => 'required|regex:/^[mf]$/'
        ];
    }

    protected function messages() {
        return [
            'email.unique' => 'This email is already registered!',
            'phone.unique' => 'This phone number is already registered!',
            'password.min' => 'Password should be at least 6 characters in length',
            'password.max' => 'Password should not be more than 100 characters in length',
            'conf-password.same' => 'Passwords do not match',
            'gender.regex' => 'That\'s weird, did you hack the scripts?'
        ];
    }

    private static $success_msg =
        'Thank you for your interest. ' .
        'Please check your inbox for the verification link ' .
        'to finalize your registration. It usually takes 1-2 minutes.';

    public function __construct() {
        $this->middleware('verify.csrf')->only(
            [ 'webRouteRegister', 'webRouteLogin', 'webRouteLogout', 'resend' ]
        );
    }

    public static function buildJSONResponse($success, $msg, $custom_arr = null, $code = 200) {
        $def_ret_arr = array('status' => $success ? 0 : 1, 'msg' => $msg);
        $ret_arr = $def_ret_arr;
        if ($custom_arr)
            $ret_arr = array_merge($def_ret_arr, $custom_arr);

        return response()->json($ret_arr, $code);
    }

    public static function authenticateAPIRoute(Request $request) {
        return $request->hasHeader('Api-Key') &&
               $request->header('Api-Key') === self::$appAPIKey;
    }
    
    protected function parseWebRouteData(Request $request) {
        $data = null;
        if (!$request->ajax()) {
            Log::info('Caught Non-AJAX request');
            $data = $request->all();
        } else { $data = $request->input('regd'); }

        return $data;
    }

    public function webRouteRegister(Request $request) {
        $data = $this->parseWebRouteData($request);
        return $this->register($request, $data);
    }

    public function webRouteLogin(Request $request) {
        $data = $this->parseWebRouteData($request);
        return $this->login($request, $data);
    }

    public function webRouteLogout(Request $request) {
        return $this->logout($request);
    }

    public function apiRoute(Request $request) {
        if (!self::authenticateAPIRoute($request))
            return self::buildJSONResponse(false, 'Unauthorized access');

        switch ($request->input('tag')) {
            case RegistrationController::API_ROUTE_REGISTER:
                return $this->register($request, $request->all());
                break;

            case RegistrationController::API_ROUTE_LOGIN:
                return $this->login($request, $request->all());
                break;

            default:
                return self::buildJSONResponse(false, 'Invalid Tag');
                break;
        }
    }

    // Register
    protected function register(Request $request, $data) {
        Log::info('Data:');
        foreach ($data as $key => $val)
            Log::info($key . ' = ' . $val);

        // Because, Pranjul
        $data['gender'] = strtolower($data['gender']);
        $validator = Validator::make($data, $this->rules(), $this->messages());

        if ($validator->fails()) {
            $msg = null; $errors = $validator->errors();

            $keys = [ 'email', 'phone', 'password', 'conf-password' ];
            foreach ($keys as $key) {
                if ($errors->has($key)) {
                    $msg = $errors->first($key);
                    break;
                }
            }
            
            if (!$msg)
                $msg = 'Unable to validate your data, please re-check and try again.';

            return self::buildJSONResponse(false, $msg);
        }

        $email = strtolower($data['email']);
        $user = User::create([
            'first_name' => StringUtility::capitalize($data['first-name']),
            'last_name' => StringUtility::capitalize($data['last-name']),
            'email' => $email,
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'college' => StringUtility::capitalize($data['college']),
            'gender' => $data['gender'] == 'm',
            'active' => false,
            'email_hash' => sha1(md5($email)),
            'token' => str_random(RegistrationController::RANDOM_STR_LENGTH)
        ]);

        Mail::to($email)->queue(new VerifyEmail($user));
        return self::buildJSONResponse(true, self::$success_msg);
    }

    // Resend verification mail
    public function resend(Request $request) {
        $email = strtolower($request->input('email'));
        Log::info('Resend email request for: ' . $email);

        $validator = Validator::make($request->all(), ['email' => 'required']);
        if ($validator->fails())
            return self::buildJSONResponse(false, 'Invalid email!');

        $user = null;
        try {
            $user = User::where('email', $email)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return self::buildJSONResponse(false,
                'You\'re unregistered. Please register first.');
        }

        if ($user->active)
            return self::buildJSONResponse(false,
                'Nothing to do, email\'s already verified.');

        // Generate new token
        $user->token = str_random(RegistrationController::RANDOM_STR_LENGTH);
        $user->save();

        Mail::to($email)->queue(new VerifyEmail($user));
        return self::buildJSONResponse(true,
            'Verification email sent, it might take about 1-2 minutes. ' .
            'Please recheck your inbox.');
    }

    protected function login(Request $request, $data) {
        $validator = Validator::make($data,
            ['fritolay' => 'required', 'kingfisher' => 'required']
        );
        if ($validator->fails())
            return self::buildJSONResponse(false, 'Missing data!');

        $user = null; $valid = true;
        $email = strtolower($data['fritolay']);
        $password = $data['kingfisher'];

        // Get valid user from email
        try {
            $user = User::where('email', $email)->firstOrFail();
        } catch (ModelNotFoundException $ex) { $valid = false; }

        // Validate User
        if ($valid) {
            $valid = Auth::attempt(
                ['email' => $email, 'password' => $password, 'active' => true], true
            );
        }

        if (!$valid)
            return self::buildJSONResponse(false, 'Invalid email or password');

        return self::buildJSONResponse(true, 'Welcome back, ' . $user->first_name,
            [
                'fname' => explode(' ', $user->first_name)[0],
                'lname' => $user->last_name,
                'email' => $user->email
            ]
        );
    }

    protected function logout(Request $request) {
        $success = false;
        if (Auth::check()) {
            Auth::logout(); $success = true;
        }

        return self::buildJSONResponse($success, "");
    }
}
