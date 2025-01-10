<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasUuid;

    // PRIMARY KEY uuid 設定
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'quantity',
        'total_cost',
        'purchase_date',
    ];

    protected $casts = [
        'purchase_date' => 'datetime:Y-m-d H:i',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
