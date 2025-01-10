<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasUuid;

    // PRIMARY KEY uuid 設定
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'category_id',
        'name',
        'unit_cost',
        'unit_price',
        'stock_quantity',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
