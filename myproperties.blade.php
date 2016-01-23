@extends('layouts.master')

@section('title')
<title>SurelyRent| My Properties</title>
@stop

@section('content')
<!-- start subheader -->
<section class="subHeader page">
    <div class="container">
        <h1>Property Management</h1>

    </div><!-- end subheader container -->
</section><!-- end subheader section -->

<!-- start my properties list -->
<section class="genericSection">
    <div class="container">
        <a style="float:right; margin-top:-7px;" href="{!! URL::to('postproperty')!!}" class="buttonGrey">+ Submit New Property</a>
        <h3>My Properties</h3>
        <div class="divider"></div>
        <table class="myProperties">
            <tr class="myPropertiesHeader">
                <td class="myPropertyImg"><!--Image--></td>
                <td class="myPropertyAddress">Address</td>
                <td class="myPropertyType">Type</td>
                <td class="myPropertyStatus">Price</td>
                <td class="myPropertyDate">Date Created</td>
                <td class="myPropertyActions">Actions</td>
            </tr>
            @foreach ($properties as $property)
            <tr>
                <td class="myPropertyImg">
                    <a href="{!! URL::to('property', array($property->property_id)) !!}"><img class="smallThumb" src="images/home_default.png" alt="" /></a>
                </td>
                <td class="myPropertyAddress"><a href="{!! URL::to('property', array($property->property_id)) !!}"><h4>{!! $property->property_address !!}</h4></a></td>
                <td class="myPropertyType">{!! $property->property_type !!}</td>
                <td class="myPropertyStatus">${!! $property->price_per_month !!}</td>
                <td class="myPropertyDate">{!! $property->created_at !!}</td>
                <td class="myPropertyActions">
                    <span>
                        <a href="{!! URL::to('editproperty', array($property->property_id)) !!}">
                            <img class="icon" src="images/icon-pencil.png" alt="" />EDIT
                        </a>
                    </span>
                    <span><a id="delete" data-property="{!!$property->property_id!!}"href="#"><img class="icon" src="images/icon-cross.png" alt="" />REMOVE</a></span>
                    <span><a href="{!! URL::to('property', array($property->property_id)) !!}"><img class="icon" src="images/icon-view.png" alt="" />VIEW</a></span>
                </td>
            </tr>
            @endforeach
        </table>
    <div class="text-center">
        <ul class="pagination">
            <li>{!! $properties->render() !!}</li>
        </ul>
    </div>

    </div><!-- end container -->
</section>
@stop
@section('scripts')
<script>

    $("a#delete").click(function () {
    propertyId = $(this).data('property');
    var selected = this;
    swal({
    title: "Are you sure?",
    text: "You will not be able to undo this action once it is completed!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Yes, delete it!",
    closeOnConfirm: false
    },
    function (isConfirm) {
    if (isConfirm) {
    $.post("{!!URL::to('/')!!}" + "/deleteproperty/" + propertyId).then(function (data) {
    if (data == "success") {
    swal(
        "Done!",
        "Your property was successfully deleted.",
        "success");
        $(selected).closest("tr").hide();

    } else {
    swal(
        "Oops! Something went wrong.",
        "Your property was not deleted.",
        "error");

    }
    });
    }
    });
    });
</script>
@stop


