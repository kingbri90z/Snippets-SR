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
    <title>SurelyRent | Home</title>
    @include('main_menu')

    <section class="subHeader home bxslider">
        <div id="slide1">
            <div class="container">
                <div class="col-lg-6">
                    <h1>Search. Find.<span> Rent</span>.</h1>
                    <div class="sliderTextBox">
                        <p>SurelyRent affords you the ability to rent your next home with ease. Whether you are a college student or a young professional,
                            SurelyRent brings to you your home away from home.</p>
                    </div>
                    </div>
                </div>
            </div>

                </section>

                <!-- start horizontal filter -->
                <section class="filter">
                    <div class="container">
                        <div class="filterHeader">
                            <ul class="filterNav tabs">
                                <li><a class="current triangle">SEARCH FOR PROPERTY:</a></li>
                            </ul>
                        </div>
                        <div class="filterContent" id="tab1">
                            {{--{!! Form::open(array('url' => 'searchproperty')) !!}--}}
                            {!! Form::open(array('method' => 'get','url' => 'searchproperty')) !!}
                               <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="formBlock">
                                        <label for="occupancy">Occupancy</label><br/>
                                        <select name="occupancy" id="occupancy" class="formDropdown">
                                            <option value="any">Any</option>
                                            <option value="privateRoom">Private Room</option>
                                            <option value="sharedRoom">Shared Room</option>
                                            <option value="entireProperty">Entire Property</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="formBlock">
                                        <label for="location">Location</label><br/>
                                        <div id="locationField">
                                            <input name="location" id="autocomplete" placeholder="Search by Address, City or Region"   onFocus="geolocate()" type="text" required>
                                        </div>
                                </div>
                            </div>
                              <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="formBlock">
                                                                <input class="buttonColor" type="submit" value="FIND PROPERTIES" style="margin-top:24px;">
                                                            </div>
                                                        </div>

                            <div style="clear:both;"></div>
                        </div>
                    {!! Form::close() !!}
                </div><!-- END TAB1 -->

            </div><!-- END CONTAINER -->
        </section>
        <!-- end horizontal filter -->

        <!-- start big message -->
        <section class="bigMessage">
            <div class="container">
                <h1><span>Easy</span> and <span>Convenient</span>  way to find home rentals.</h1><br/>
                <p>Finding somewhere to rent can be one of the most frustrating encounters.
                    It doesn't have to be. SurelyRent is dedicated to connecting you to your next home.
                </p>
                 </div>
            </section>
            <!-- end big message -->

            <!-- start recent properties -->
            <section class="properties">
                <div class="container">
                    <h3>RECENT PROPERTIES</h3>
                    <div class="divider"></div>
                    <div class="row">
                         @foreach ($featured_listing as $property)
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                            <div class="propertyItem">
                                                <div class="propertyContent">
                                                    <a class="propertyType" href="{!!URL::to('property/'.$property[5])!!}"> {!!$property[0]!!}</a>
                                                    @if (isset($property[3]))
                                                       <a href="{!!URL::to('property/'.$property[5])!!}" class="propertyImgLink"><img class="propertyImg" src="{!!URL::to('/'.'uploads/'.$property[6].'/'.$property[3])!!}"  alt="Property-Image" /></a>
                                                     @else
                                                      <a href="{!!URL::to('property/'.$property[5])!!}" class="propertyImgLink"><img class="propertyImg" src="images/home_default.png"  alt="Property-Image" /></a>
                                                    @endif
                                                     <h4><a href="{!!URL::to('property/'.$property[5])!!}"> {!!$property[4]!!}</a></h4>
                                                    @if (Auth::check())
                                                       <h4><span><img src="{!!URL::to('/'.'images/'.'/phone.png')!!}" height="18" width="18"/>{!!$property[2] or 'NA' !!}</span></h4>
                                                   @endif
                                                     <h4><a href="{!!URL::to('property/'.$property[5])!!}"> {!!$property[7]!!}</a></h4>

                                                     <div class="divider thin"></div>
                                                     <p class="forSale">FOR RENT</p>
                                                      <p class="price"> ${!!$property[1]!!}</p>
                                                </div>
                                                <table border="1" class="propertyDetails">
                                                    <tr>
                                                    <td><img src="images/icon-area.png" alt="" style="margin-right:7px;" /> </td>
                                                    <td><img src="images/icon-bed.png" alt="" style="margin-right:7px;" /> </td>
                                                    <td><img src="images/icon-drop.png" alt="" style="margin-right:7px;" /> </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                         @endforeach
                             </div><!-- end row -->

                    </div><!-- end row -->
                </div><!-- end container -->
            </section>
            <!-- end recent properties -->

            @include('main_footer')

            <!-- JavaScript file links -->
            <!--<script src="js/jquery.js"></script>-->         <!-- Jquery -->
            <script src="js/bootstrap.min.js"></script>  <!-- bootstrap 3.0 -->
            <script src="js/respond.js"></script>
            <script src="js/jquery.bxslider.min.js"></script>           <!-- bxslider -->
            <script src="js/tabs.js"></script>       <!-- tabs -->
            <script src="js/jquery.nouislider.min.js"></script>  <!-- price slider -->
            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
            <script>

// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.
var  autocomplete;
function initialize() {
  // Create the autocomplete object, restricting the search
  // to geographical location types.
  autocomplete = new google.maps.places.Autocomplete(
      /** @type {HTMLInputElement} */(document.getElementById('autocomplete')),
      { types: ['geocode'] });
  // When the user selects an address from the dropdown,
  // populate the address fields in the form.
}
$(window).load(initialize);
</script>
<script>
 $("#contact").click(function () {
   swal(
         "You are not signed in.",
         "Please sign in to retrieve contact number.",
         "info");
  });
</script>
</body>
</html>
