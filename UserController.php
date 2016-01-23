<?php

use SocialNorm\Exceptions\ApplicationRejectedException;
use SocialNorm\Exceptions\InvalidAuthorizationCodeException;

use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */

    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        } else {
            return View::make('login');
        }

    }

    public function register()
    {
        return View::make('register');

    }

    public function storeAccount()
    {
        static $rules = array(
            'firstname' => 'required|min:2',
            'lastname' => 'required|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|alpha_num|between:6,20|confirmed',
            'password_confirmation' => 'required|alpha_num|between:6,20'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->passes()) {
            $user = new User;


            //Send Confirmation Mail
            $confirm_key = md5(str_random(64) . time() * 64);
            try {
                Mail::raw("Hi " . Input::get('firstname') . "!\nThank you for signing up for SurelyRent.
             \nPlease verify your email address by clicking on the link below:\n" . url('/' . 'confirm_email' . '?Ckey=' . $confirm_key), function ($message) {
                        $message->from('noreply@surelyrent.com', 'SurelyRent');
                        $message->subject("Confirm your SurelyRent email address");
                        $message->to(Input::get('email'));

                    });

            } catch (Exception $e) {
                echo "Oops! Something went wrong";
            }

            $user->firstname = trim(Input::get('firstname'));
            $user->lastname = trim(Input::get('lastname'));
            $user->email = Input::get('email');
            $user->password = Hash::make(Input::get('password'));
            $user->status = 'U';
            $user->confirm_key = $confirm_key;
            $user->save();

            //Session::flash('message', 'Thank you for registering!');

            return Redirect::to('registrationThanks');
        } else {
            return Redirect::to('register')->withErrors($validator)->withInput();
        }
    }

    public function confirmEmail()
    {
        $ckey = Input::get('Ckey');

        $db_ckey_value = User::where('confirm_key', '=', $ckey)->first();
        if ($db_ckey_value) {
            $db_ckey_value->status = 'A';
            $db_ckey_value->save();
            return Redirect::to('login');
        }

    }


    public function loginUser()
    {
        //Get if user selects remember me
        if (Input::get('remember-me'))
            $remember = true;
        else {
            $remember = false;
        }

        $email = Input::get('email');
        $password = Input::get('password');
        if (Auth::attempt(array('email' => $email, 'password' => $password, 'status' => 'A'), $remember)) {
            //$logged_user = Auth::user()->user_id; //Get now logged in user
            //Session::put('logged_in_user', $logged_user); //set session for logged in user
            return Redirect::intended('/');
        } else {

            return redirect('login')->with('status', 'The email and password you entered don\'t match.')->withInput();

        }
    }

    public function logoutUser()
    {
        Auth::logout();

        return redirect('/');
    }

    public function showResetPassword()
    {


        return View::make('resetpassword');
    }



    public function editAccount()
    {

        $account = DB::table('users')->where('user_id', '=', Auth::user()->user_id)->first();
        //$account = Property::where('user_id', '=', $logged_user)->count();
        return View::make('account_edit')->with('account', $account);

    }

    public function updateAccount()
    {

        $user = User::find(Auth::user()->user_id);

        if (Input::get('email') == $user->email) {

            static $rules = array(
                'firstname' => 'required|alpha|min:2',
                'lastname' => 'required|alpha|min:2',
                'email' => 'required'
            );
        } else {

            static $rules = array(
                'firstname' => 'required|alpha|min:2',
                'lastname' => 'required|alpha|min:2',
                'email' => 'required|email'
            );
        }

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->passes()) {
            $user->firstname = Input::get('firstname');
            $user->lastname = Input::get('lastname');
            $user->email = Input::get('email');
            $user->save();

            Session::flash('message', 'Your account was updated successfully.');

            return Redirect::to('manageuser');
        } else {
            return Redirect::to('editaccount')->withErrors($validator)->withInput();
        }
    }

    public function changePassword()
    {

        $users = User::find(Auth::user()->user_id);
        return View::make('password_edit')->with('user', $users);

    }

    public function registrationThanks()
    {


        return View::make('register_thanks');

    }

    public function updatePassword($id)
    {

        $old_password = Input::get('oldpassword');
        $new_password = Input::get('newpassword');
        $confirm_password = Input::get('password_confirmation');
        $hash = Auth::user()->password;


        if (Hash::check($old_password, $hash) == $hash) {

            if ($new_password === $confirm_password) {
                $user = User::find($id);
                $user->password = Hash::make($new_password);
                $user->save();
                return Redirect::to('manageuser');
            } else {
                return Redirect::to('changepassword')->with('message', 'Sorry, passwords did not match.')->withInput();
            }
        } else {

            return Redirect::to('changepassword')->with('message', 'Sorry, passwords did not match.')->withInput();
        }
    }

    public function userMgmt()
    {
        //Method gets the details for user account. i.e. Amount of property posted and user details


        $users = DB::table('users')->where('user_id', '=', Auth::user()->user_id)->get();
        $count = Property::where('user_id', '=', Auth::user()->user_id)->count();
        return View::make('manageuser')->with('users', $users)->with('count', $count);
    }

    /**
     * Redirect the user to the Provider authentication page.
     *
     * @return Response
     */

    public function redirectToProvider($provider)
    {

        return SocialAuth::authorize($provider);

    }
    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */

    public function handleProviderCallback($provider)
    {
        try {
            SocialAuth::login($provider, function($user, $details) use($provider) {
                $existingUser = User::where('email', $details->email)->first();

                if ($existingUser) {
                    return $existingUser;
                }

                if ($provider == 'facebook') {
                    $user->firstname = $details->raw['first_name'];
                    $user->lastname = $details->raw['last_name'];
                    $user->email = $details->raw['email'];
                    $user->status = 'A';
                    $user->save();
                } else
                    if ($provider == 'google') {
                        $user->firstname = $details->raw['given_name'];
                        $user->lastname = $details->raw['family_name'];
                        $user->email = $details->raw['email'];
                        $user->status = 'A';
                        $user->save();
                    }

            });
        } catch (ApplicationRejectedException $e) {
            // User rejected application
        } catch (InvalidAuthorizationCodeException $e) {
            // Authorization was attempted with invalid
            // code,likely forgery attempt
        }

        // Current user is now available via Auth facade
        $user = Auth::user();

        return Redirect::intended();
    }

}
