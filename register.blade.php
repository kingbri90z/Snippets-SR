<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="SurelyRent is an online platform that is designed to help you find your next home.">
<meta name="keywords" content="Jamaica, Kingston Jamaica,place for rent,homes for rent,apartments for rent, college student rentals,
student accommodation, student apartments, for rent in Jamaica, for rent in Kingston, University of the West Indies, University of Technology">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="/manifest.json">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">
<title>SurelyRent| Register </title>
@include('main_menu')
<!-- start subheader -->
<section class="subHeader page">
    <div class="container">
    	<h1>User Registration</h1>
    </div><!-- end subheader container -->
</section><!-- end subheader section -->

<!-- start main content -->
<section class="properties">
    <div class="container">
        <div class="row">

            <div class="col-lg-4 col-lg-offset-4">
                <h3>REGISTER</h3>
                <div class="divider"></div>
                <p style="font-size:13px;">Already have an account? <a href="{!!URL::to('login')!!}">Login here!</a></p>
                 <h4 class="text-center">OR</h4>

                  <a href="{!!URL::to('facebook/authorize')!!}" class="btn btn-block btn-lg btn-social btn-facebook">
                                         <span class="fa fa-facebook"></span> Sign in with Facebook
                                      </a></br>
                                     <a href="{!!URL::to('google/authorize')!!}" class="btn btn-block btn-lg btn-social btn-google">
                                        <span class="fa fa-google"></span> Sign in with Google Plus
                                      </a></br>
                <!-- start login form -->
                <div class="filterContent sidebarWidget">
                  <ul>
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">
                            <li>{!! $error !!}</li>
                            </div>
                        @endforeach
                    </ul>
                            {!! Form::open(array('url' => 'storeaccount')) !!}
                        <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-6">
                                <div class="formBlock">
                                <label for="firstname">Firstname</label><br/>
                                <input type="text" name="firstname" value="{{ old('firstname') }}" id="firstname" required/>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-6">
                                <div class="formBlock">
                                <label for="email">Lastname</label><br/>
                                <input type="text" name="lastname" value="{{ old('lastname') }}" id="lastname" required/>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-6">
                                <div class="formBlock">
                                <label for="email">Email</label><br/>
                                <input type="text" name="email" value="{{ old('email') }}" id="email" required/>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-6">
                                <div class="formBlock">
                                <label for="pass">Password</label><br/>
                                <input type="password" name="password" id="password" required/>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-6">
                                <div class="formBlock">
                                <label for="confirmPass">Confirm Password</label><br/>
                                <input type="password" name="password_confirmation" id="password_confirmation" required/>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-6">
                                <div class="formBlock">
                                    <input class="buttonColor" type="submit" value="REGISTER" style="margin-top:24px;">
                                </div>                            
                            </div>
                            <div style="clear:both;"></div>
                        </div><!-- end row -->
                    {!! Form::close() !!}
                </div><!-- end login form -->
            </div><!-- end col -->
            
        </div>
    </div><!-- end container -->
</section>
<!-- end main content -->



@include('main_footer')

<!-- JavaScript file links -->
<script src="js/jquery.js"></script>			<!-- Jquery -->
<script src="js/bootstrap.min.js"></script>  <!-- bootstrap 3.0 -->
<script src="js/respond.js"></script>

</body>
</html>