<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Product extends Model
{
    use HasUuid, Sortable;

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

    public $sortable = ['id', 'category_id', 'name', 'stock_quantity', 'unit_cost', 'unit_price', 'updated_at'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
