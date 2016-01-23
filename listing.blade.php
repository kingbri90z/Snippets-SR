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
<title>SurelyRent| Search Results</title>

@include('main_menu')
<section class="subHeader page">
    <div class="container">

    </div><!-- end subheader container -->
</section><!-- end subheader section -->

<!-- start properties -->

<section class="properties">

    <div class="container">
  <!-- start horizontal filter -->
                <section class="filter">
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

        </section>
        <!-- end horizontal filter -->
        <div class="">
    	<ul class="propertyCat_list option-set" id="searchResults">
    		<li><a data-filter="*" class="current triangle">SEARCH RESULTS:</a></li>
    	</ul>
    	</div>
        <div class="divider"></div>
        <div class="row">
            <div class="col-lg-9 col-md-9">
                @if(!count($paginatedSearchResults))
                    <h2 class="text-center">Oops! Your search did not match any locations.</h2>
               @endif
             <?php $count=3;?>
             @foreach ($paginatedSearchResults as $property)

                @if(($count%3)==0)
                <div class="row">
                @endif
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="propertyItem">
                        <div class="propertyContent">
                            <a class="propertyType" href="#"> {!!$property[0]!!}</a>
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
                 <?php $count++;?>
                @if(($count%3)==0)
                </div><!-- end row -->
                @endif
                @if($property==end($paginatedSearchResults) AND ($count%3)<>0)
                  </div><!-- end row -->
                @endif
                @endforeach
            </div><!-- end col -->

        </div><!-- end row -->


 </div><!-- end container -->
 <div class="text-center">
     <ul class="pagination">
         <li>{!!$paginatedSearchResults->render()!!}</li>
     </ul>
 </div>
</section>

<!-- end properties -->
@include('main_footer')

<!-- JavaScript file links -->
<script src="js/jquery.js"></script>			<!-- Jquery -->
<script src="js/bootstrap.min.js"></script>  <!-- bootstrap 3.0 -->
<script src="js/respond.js"></script>
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

<script>
$.fn.scrollTo = function( target, options, callback ){
  if(typeof options == 'function' && arguments.length == 2){ callback = options; options = target; }
  var settings = $.extend({
    scrollTarget  : target,
    offsetTop     : 50,
    duration      : 500,
    easing        : 'swing'
  }, options);
  return this.each(function(){
    var scrollPane = $(this);
    var scrollTarget = (typeof settings.scrollTarget == "number") ? settings.scrollTarget : $(settings.scrollTarget);
    var scrollY = (typeof scrollTarget == "number") ? scrollTarget : scrollTarget.offset().top + scrollPane.scrollTop() - parseInt(settings.offsetTop);
    scrollPane.animate({scrollTop : scrollY }, parseInt(settings.duration), settings.easing, function(){
      if (typeof callback == 'function') { callback.call(this); }
    });
  });
}
//scroll to search results
$('body').scrollTo('#searchResults');
</script>
</body>
</html>