<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Sale extends Model
{
    use HasUuid, Sortable;

    // PRIMARY KEY uuid 設定
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'quantity',
        'total_price',
        'sale_date',
    ];

    protected $casts = [
        'sale_date' => 'datetime:Y-m-d H:i',
    ];

    public $sortable = ['id', 'product_id', 'sale_date', 'quantity', 'total_price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function categoryNameSortable($query, $direction)
    {
        return $query->join('products', 'sales.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->orderBy('categories.name', $direction)
            ->select('sales.*');
    }
}
