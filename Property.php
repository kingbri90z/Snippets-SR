<?php
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination;
use Sofa\Eloquence\Eloquence;

class Property extends Eloquent
{
    use SoftDeletes;

    protected $table = 'info_properties';
    protected $primaryKey = 'property_id';
    protected $dates = ['deleted_at'];
    public function propertyPhotos()
    {
        return $this->hasMany('PropertyPhoto', 'property_id');
    }

    public function propertyAmenity()
    {
        return $this->hasMany('PropertyAmenity', 'property_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

    public function getPricePerMonthAttribute($value)
    {
        return number_format($value);
    }
    public function getCreatedAtAttribute($value)
    {
        return date("D M j, Y",strtotime($value));
    }

}
