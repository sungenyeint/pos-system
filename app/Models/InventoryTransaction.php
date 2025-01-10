<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasUuid;

    // PRIMARY KEY uuid 設定
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'quantity_change',
        'reason',
        'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'datetime:Y-m-d H:i',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
