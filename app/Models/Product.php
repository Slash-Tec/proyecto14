<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    const BORRADOR = 1;
    const PUBLICADO = 2;

    protected $fillable = ['name', 'slug', 'description', 'price', 'subcategory_id', 'brand_id', 'quantity', 'sold', 'reserved', 'status'];
    //protected $guarded = ['id', 'created_at', 'updated_at'];

    public function sizes(){
        return $this->hasMany(Size::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function subcategory(){
        return $this->belongsTo(Subcategory::class);
    }

    public function colors(){
        return $this->belongsToMany(Color::class)->withPivot('quantity', 'id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($product) {
            $oldQuantity = $product->getOriginal('quantity');
            $newQuantity = $product->quantity;

            if ($newQuantity < $oldQuantity) {
                $soldIncrement = $oldQuantity - $newQuantity;
                $product->increment('sold', $soldIncrement);
            }
        });
    }

    public function getStockAttribute(){
        if ($this->subcategory->size) {
            return  ColorSize::whereHas('size.product', function(Builder $query){
                $query->where('id', $this->id);
            })->sum('quantity');
        } elseif ($this->subcategory->color) {
            return  ColorProduct::whereHas('product', function(Builder $query){
                $query->where('id', $this->id);
            })->sum('quantity');
        } else {
            return $this->quantity;
        }
    }

    public function getSoldAttribute()
    {
        return $this->attributes['sold'] ?? 0;
    }

    public function increaseReserved($reservedQuantity)
    {
        $this->increment('reserved', $reservedQuantity);
    }
}
