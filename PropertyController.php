<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use SurelyRent\Services\Validation\PropertyValidator as Validator;
use SurelyRent\Utilities\FormUtility;
use SurelyRent\Events\StoreProperty;
use Validator as Validator_c;
//Laracasts\Utilities\JavaScript\JavascriptServiceProvider
use Illuminate\Routing\Controller as BaseController;

class PropertyController extends BaseController
{

    protected $validator;
    private $form_utility;

    function __construct(Validator $validator, FormUtility $form_utility)
    {
        $this->validator = $validator;
        $this->form_utility = $form_utility;
    }

    /**
     * Searches for a related property.
     *
     * @return Response
     */
    public function searchProperty()
    {


        if (Input::get('location')) {
            $location = Input::get('location');
        }

        if ( Input::get('occupancy')=='privateRoom') {
            $occupancy_type = 'Private Room';
        }elseif(Input::get('occupancy')=='sharedRoom'){
            $occupancy_type = 'Shared Room';
        }elseif(Input::get('occupancy')=='entireProperty'){
            $occupancy_type = 'Entire Property';
        }else{
            $occupancy_type = 'Any';
        }


        if($occupancy_type!='Any'){
            $properties=Property::whereRaw(
                "MATCH(property_address) AGAINST(? IN BOOLEAN MODE)",
                array($location))->where('occupancy_type','=',$occupancy_type)->get();
        }elseif($occupancy_type='Any'){
            $properties=Property::whereRaw(
                "MATCH(property_address) AGAINST(? IN BOOLEAN MODE)",
                array($location))->get();
        }

        $listing_results=array();
        $count=0;
        $image_names=[];
        foreach ($properties as $property) {
            $image_names = PropertyPhoto::where('property_id', '=', $property->property_id)->orderBy('default','DESC')->get()->all();
            if(!empty($image_names)){
                $listing_results = array_add($listing_results,$count++, array($property->property_type,$property->price_per_month,$property->contact_number,$image_names[0]->photo_source,$property->property_address,$property->property_id,$property->user_id,$property->created_at));
            }else {
                $listing_results = array_add($listing_results, $count++, array($property->property_type,$property->price_per_month, $property->contact_number,null,$property->property_address,$property->property_id,null,$property->created_at));
            }
        }

        $perPage = 9;
        $collection = new Collection($listing_results);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageResults = $collection->forPage($currentPage, $perPage);

        $paginatedSearchResults = new LengthAwarePaginator($currentPageResults,
            $collection->count(), $perPage, $currentPage, [
                'path' =>  \Request::url(),
                'query' => \Request::query()
            ]);


        return View::make('listing')->with('paginatedSearchResults', $paginatedSearchResults,'property_photo_names',$image_names);

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $properties=Property::whereRaw(
            "MATCH(property_address) AGAINST(? IN BOOLEAN MODE)",
            array('kingston'))->orderBy('created_at','desc')
            ->take(4)->get()->shuffle();
        //dd($properties);
        $featured_listing =array();
        $count=0;
        $image_names=[];
        foreach ($properties as $property) {
            $image_names = PropertyPhoto::where('property_id', '=', $property->property_id)->orderBy('default','DESC')->get()->all();
            if(!empty($image_names)){
                $featured_listing = array_add($featured_listing ,$count++, array($property->property_type,$property->price_per_month,$property->contact_number,$image_names[0]->photo_source,$property->property_address,$property->property_id,$property->user_id,$property->created_at));
            }else {
                $featured_listing  = array_add($featured_listing , $count++, array($property->property_type,$property->price_per_month, $property->contact_number,null,$property->property_address,$property->property_id,null,$property->created_at));
            }
        }

        return View::make('index')->with('featured_listing', $featured_listing,'property_photo_names',$image_names);
    }

    /**
     * Store a newly created property in storage.
     *
     * @return Response
     */
    public function storeProperty()
    {


        if (!$this->validator->validate(Input::all())) {
            return Redirect::back()->withErrors($this->validator->errors())->withInput();
        }

        $occupancy = array(
            'Private Room' => 'Private Room',
            'Shared Room' => 'Shared Room',
            'Entire Property' => 'Entire Property'
        );

        $property_types = array(
            'House' => 'House',
            'Apartment' => 'Apartment',
            'Flat' => 'Flat',
            'Studio' => 'Studio',
            'Townhouse' => 'Townhouse',
            'Commercial' => 'Commercial',
            'Condo' => 'Condo',
            'Cottage' => 'Cottage',
            'Residential' => 'Residential',
            'Hotel' => 'Hotel',
            'Villa' => 'Villa',
            'Other' => 'Other'
        );


        $property = new Property;
        $property->user_id = Auth::user()->user_id;
        $property->property_type = Input::get('property_types');
        $property->occupancy_type = Input::get('occupancy');
        $property->furnished = Input::get('furnished');
        $property->contact_number = Input::get('contact_number');
        $property->price_per_month = str_replace(',','',Input::get('price_per_month'));
        $property->property_address = str_replace(', Jamaica','',Input::get('property_address'));


        DB::transaction(function () use ($property) {

            $property->save();
        });
        Session::flash('initialPost', 'FirstPost');
        $property_photo_names = []; //TODO  - To be removed
        return View::make('finishpostproperty', compact('property', 'property_types', 'occupancy', 'property_photo_names'));
    }

    /**
     * Display the specified property.
     *
     * @param  int $id
     * @return Response
     */
    public function show($property_id)
    {

        $property=Property::where('property_id', '=', $property_id)->get();

        $amenities = PropertyAmenity::where('property_id','=', $property_id)->get(['amenity_name']);
        $property_photos = PropertyPhoto::where('property_id', '=', $property[0]->property_id)->orderBy('default','DESC')->get()->all();

        $list_of_related_properties=Property::whereRaw("MATCH(property_address) AGAINST(? IN BOOLEAN MODE)",
            array($property[0]->property_address))
            ->where('property_id','!=', $property[0]->property_id)
            ->orderByRaw("RAND()")
            ->take(3)->get();

        $related_properties=array();
        $count=0;
        $related_property_photos=[];
        foreach ($list_of_related_properties as $related_property) {
            $related_property_photos = PropertyPhoto::where('property_id', '=', $related_property->property_id)->orderBy('default','DESC')->get()->all();
            if(!empty($related_property_photos)){
                $related_properties = array_add($related_properties,$count++, array($related_property->property_type,$related_property->price_per_month,$related_property->contact_number,$related_property_photos[0]->photo_source,$related_property->property_address,$related_property->property_id,$related_property->user_id,$related_property->created_at));
            }else {
                $related_properties = array_add($related_properties, $count++, array($related_property->property_type,$related_property->price_per_month, $related_property->contact_number,null,$related_property->property_address,$related_property->property_id,$related_property->user_id,$related_property->created_at));
            }
        }

        return View::make('property_single', array(
            'property' => $property,
            'amenities' => $amenities,
            'related_properties' => $related_properties,
            'property_photos'=>$property_photos,
            'related_property_photos'=>$related_property_photos
        ));

    }

    public function dashboard()
    {
        return View::make('dashboard');

    }

    public function propertyMgmt()
    {

        //$properties = Property::paginate(15);
        $properties=DB::table('info_properties')->where('user_id', '=', Auth::user()->user_id)
            ->whereNull('deleted_at')->orderBy('created_at','DESC')->paginate(15);

        return view('myproperties', compact('properties'));
    }


    public function myProperties()
    {
        return View::make('myproperties');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $property
     * @return Response
     */
    public function edit($id)
    {

        $amenity = Amenity::all();
        $occupancy = array(
            'Private Room' => 'Private Room',
            'Shared Room' => 'Shared Room',
            'Entire Property' => 'Entire Property'
        );

        $property_types = array(
            'House' => 'House',
            'Apartment' => 'Apartment',
            'Flat' => 'Flat',
            'Studio' => 'Studio',
            'Townhouse' => 'Townhouse',
            'Commercial' => 'Commercial',
            'Condo' => 'Condo',
            'Cottage' => 'Cottage',
            'Residential' => 'Residential',
            'Hotel' => 'Hotel',
            'Villa' => 'Villa',
            'Other' => 'Other'
        );
        $property= Property::where('property_id' , '=',$id)->first();
        $property_amenities = DB::table('property_amenities')->where('property_id', '=', $id)->get();
        $amenity_boolean_array = [];
        foreach ($property_amenities as $pa) {
            if (!is_null($pa->amenity_name)) {
                $amenity_boolean_array = array_add($amenity_boolean_array, $pa->amenity_name, TRUE);
            } else {
                $amenity_boolean_array = array_add($amenity_boolean_array, $pa->amenity_name, FALSE);
            }

        }
        //retrieve image
        $property_photos = PropertyPhoto::where('property_id', '=', $id)->get();
        $property_photo_names = [];
        foreach ($property_photos as $file) { //get an array which has the names of all the files and loop through it

            $property_photo_names[] = $file['photo_source']; // copy it to another array

        }


        return View::make('finishpostproperty', compact('property', 'property_types', 'occupancy', 'amenity', 'amenity_boolean_array', 'property_photo_names'));
    }

    /**
     * Update the specified property in storage.
     *
     * @param  int $property
     * @return Response
     */
    public function updateProperty($id)
    {

        $property = Property::find($id);
        $amenity = Amenity::all();
        $go_to_id =null;
        $occupancy = array(
            'Private Room' => 'Private Room',
            'Shared Room' => 'Shared Room',
            'Entire Property' => 'Entire Property'
        );

        $property_types = array(
            'House' => 'House',
            'Apartment' => 'Apartment',
            'Flat' => 'Flat',
            'Studio' => 'Studio',
            'Townhouse' => 'Townhouse',
            'Commercial' => 'Commercial',
            'Condo' => 'Condo',
            'Cottage' => 'Cottage',
            'Residential' => 'Residential',
            'Hotel' => 'Hotel',
            'Villa' => 'Villa',
            'Other' => 'Other'
        );

        if (Input::get('basic')) {

            $go_to_id = '#property';
            if (!$this->validator->validate(Input::all())) {
                return Redirect::back()->withErrors($this->validator->errors())->withInput();
            } else {

                $property->property_type = Input::get('property_types');
                $property->occupancy_type = Input::get('occupancy');
                if (Input::get('furnished') == "1") {
                    $property->furnished = true;
                } elseif (Input::get('furnished') == "0") {
                    $property->furnished = false;
                }
                $property->price_per_month = str_replace(',','',Input::get('price_per_month'));
                $property->property_address = Input::get('property_address');
                $property->contact_number = Input::get('contact_number');
                $property->save();

                //	Session::flash('message', 'Your account was updated successfully.');
            }

        } elseif (Input::get('property')) {
            $go_to_id = '#photos';
            if (Input::get('kitchen') == "1") {
                $property->kitchen_type = 'Private';
            } elseif (Input::get('kitchen') == "2") {
                $property->kitchen_type = 'Shared';
            } elseif (Input::get('kitchen') == "3") {
                $property->kitchen_type = 'None';
            }


            if (Input::get('bathroom') == "1") {
                $property->bathroom_type = 'Private';
            } elseif (Input::get('bathroom') == "2") {
                $property->bathroom_type = 'Shared';
            } elseif (Input::get('bathroom') == "3") {
                $property->bathroom_type = 'None';
            }
            $property->save();
        } elseif (Input::get('amenities')) {
            $go_to_id = '#tenant';

            DB::table('property_amenities')->where('property_id', '=', $id)->delete();

            $amenityList = Input::get('amenity');

            if ($amenityList) {

                foreach ($amenityList as $am) {
                    $property_amenity = new PropertyAmenity;
                    $property_amenity->property_id = $id;
                    $property_amenity->amenity_name = $am;
                    $property_amenity->save();
                }
            }
        }elseif (Input::get('tenant')) {
            $go_to_id = '#additional';
            if (Input::get('pref_tenant') == "1") {
                $property->preferred_tenant = 'Student';
            } elseif (Input::get('pref_tenant') == "2") {
                $property->preferred_tenant = 'Professional';
            } elseif (Input::get('pref_tenant') == "3") {
                $property->preferred_tenant = 'Any';
            }


            if (Input::get('pref_gender') == "1") {
                $property->preferred_gender = 'Female';
            } elseif (Input::get('pref_gender') == "2") {
                $property->preferred_gender = 'Male';
            } elseif (Input::get('pref_gender') == "3") {
                $property->preferred_gender = 'Any';
            }

            if (Input::get('interview') == "1") {
                $property->interview_required = TRUE;
            } elseif (Input::get('interview') == "2") {
                $property->interview_required = FALSE;
            }
            $property->save();


        }elseif (Input::get('additional')) {
            $go_to_id = '#additional';
            $property->property_additional_info= Input::get('additional_info');
            $property->save();
        }
        $property_amenities = DB::table('property_amenities')->where('property_id', '=', $id)->get();
        $amenity_boolean_array = [];
        foreach ($property_amenities as $pa) {
            if (!is_null($pa->amenity_name)) {
                $amenity_boolean_array = array_add($amenity_boolean_array, $pa->amenity_name, TRUE);
            } else {
                $amenity_boolean_array = array_add($amenity_boolean_array, $pa->amenity_name, FALSE);
            }

        }

        //retrieve image
        $property_photos = PropertyPhoto::where('property_id', '=', $id)->get();
        $property_photo_names = [];
        foreach ($property_photos as $file) { //get an array which has the names of all the files and loop through it

            $property_photo_names[] = $file['photo_source']; // copy it to another array

        }
        return Redirect::to('editproperty/'.$id.$go_to_id)
            ->with('property', 'property_types',
                'occupancy', 'amenity',
                'amenity_boolean_array','property_photo_names');
    }



    public function destroy($id)
    {


        DB::beginTransaction();
        try {
            Property::where('property_id', '=', $id)->delete();
            DB::commit();
            $result="success";
        } catch (\Exception $e) {
            DB::rollback();
            $result="error";
        }
        return response()->json([$result]);
    }


    public function showBlog()
    {
        return View::make('blog');

    }

    public function showAbout()
    {
        return View::make('about');

    }

    public function showContact()
    {
        return View::make('contact');

    }


    public function SendContact()
    {
        $emailSent = false;
        $hasError = true;

        $data = [
            'contactName' => Input::get('contactName'),
            'email' => Input::get('email'),
            'comments' => Input::get('comments'),
        ];

        $validator = Validator_c::make($data, [
            'contactName' => 'required',
            'email' => 'required|email',
            'comments' => 'required',
        ]);

        if (!$validator->fails()) {

            $email = Mail::send('contact-form-mail', $data, function ($message) {
                $message->from('webapp@surelyrent.com', 'Surelyrent Contact Form');
                $message->to('hello@surelyrent.com');
            });
            $emailSent = true;
            $hasError = false;

        }

        return View::make('contact')->with(['emailSent' => $emailSent, 'hasError' => $hasError]);

    }


    public function postProperty()
    {

        $num = array(1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5',
            6 => '6', 7 => '7', 8 => '8', 9 => '9', 10 => '10');

        $occupancy = array(
            'Private Room' => 'Private Room',
            'Shared Room' => 'Shared Room',
            'Entire Property' => 'Entire Property'
        );

        $property_types = array(
            'House' => 'House',
            'Apartment' => 'Apartment',
            'Flat' => 'Flat',
            'Studio' => 'Studio',
            'Townhouse' => 'Townhouse',
            'Commercial' => 'Commercial',
            'Condo' => 'Condo',
            'Cottage' => 'Cottage',
            'Residential' => 'Residential',
            'Hotel' => 'Hotel',
            'Villa' => 'Villa',
            'Other' => 'Other'
        );

        return View::make('postproperty', compact('property_types', 'num', 'occupancy'));

    }


    public function uploader($id)
    {

        /*stores photos in property_photos table */

        $uploadPath = public_path() . '/uploads';
        $destinationPath = $uploadPath . '/' . Auth::user()->user_id;
        if (!file_exists($destinationPath)) File::makeDirectory($destinationPath);

        $file = Input::file('file');

        $ext = $file->getClientOriginalExtension();
        $file_name = rand(11111111111, 99999999999) . '.' . $ext;
        $original_upload_success = $file->move($destinationPath, $file_name);

        if ($original_upload_success) {
            $thumbnail_stamp = "206x206_";
            Image::make($destinationPath . "/" . $file_name)->fit(206)->save($destinationPath . "/" . $thumbnail_stamp . $file_name);
            /*Save name to Database*/
            $property_photo = new  PropertyPhoto;
            $property_photo->photo_source = $file_name;//Input::get($filename.'.'.$ext);
            $property_photo->user_id = Auth::user()->user_id;

            $property_photo->property_id = $id;
            $property_photo->save();
            return Response::json(array('success' => 200, 'filename' => $file_name));
        } else {
            return Response::json('error', 400);
        }

    }


    public function deletePhoto($id)
    {
        DB::beginTransaction();
        try {
            PropertyPhoto::where('photo_source', '=', $id)->delete();
            DB::commit();
            $result="success";
        } catch (\Exception $e) {
            DB::rollback();
            $result="error";
        }
        return response()->json([$result]);

    }

    public function setDefaultPhoto($id){

        DB::beginTransaction();
        try {
            // $photo_name=PropertyPhoto::where('photo_source', '=', $id)->pluck('photo_source');
            //dd($photo_name);
            PropertyPhoto::where('photo_source', $id)
                ->update(['default' => 1]);
            PropertyPhoto::where('photo_source','!=',$id)
                ->update(['default' => 0]);
            DB::commit();
            $result="success";
        } catch (\Exception $e) {
            DB::rollback();
            $result="error";
        }
        return response()->json([$result]);

    }

}
