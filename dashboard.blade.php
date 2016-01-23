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
<title>SurelyRent| My Properties</title>
@include('main_menu')
<!-- start subheader -->
<section class="subHeader page">
    <div class="container">
        <h1>Dashboard</h1>
        
    </div><!-- end subheader container -->
</section><!-- end subheader section -->

<!-- start my properties list -->
<section class="genericSection">
    <div class="container">
        <h3>DASHBOARD</h3>
        <div class="divider"></div>
        <table class="myProperties">
           
            <tr>
                <td class="myPropertyImg"><a href="{!!URL::to('manageproperty')!!}"><img class="smallThumb" src="images/property-dash.png" alt="" /></a></td>
                <td class=""><a href="{!!URL::to('manageproperty')!!}"><button type="button" class="btn btn-success btn-lg">Property Management</button></a></td>
                <td class="hidden-xs"><b>Manage your properties</b></td>
            </tr>
           <tr>
                <td class="myPropertyImg"><a href="{!!URL::to('manageuser')!!}"><img class="smallThumb" src="images/user-dash.png" alt="" /></a></td>
                <td class=""><a href="{!!URL::to('manageuser')!!}"><button type="button" class="btn btn-success btn-lg">User Management&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</button></a></td>
                <td class="hidden-xs"><b>Manage your user account</b></td>
            </tr>

        </table>
       
    </div><!-- end container -->
</section>


<!-- end call to action -->
@include('main_footer')
<!-- JavaScript file links -->
<script src="js/jquery.js"></script>            <!-- Jquery -->
<script src="js/bootstrap.min.js"></script>  <!-- bootstrap 3.0 -->
<script src="js/respond.js"></script>
<script src="js/tabs.js"></script>       <!-- tabs -->

</body>
</html>
