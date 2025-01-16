<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class PriceChangeHistory extends Model
{
    use HasUuid;

    // PRIMARY KEY uuid 設定
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'price_change',
        'status',
        'change_date',
    ];

    protected $casts = [
        'change_date' => 'datetime:Y-m-d H:i',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
