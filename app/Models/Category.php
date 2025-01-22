<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Category extends Model
{
    use HasUuid, Sortable;

    // PRIMARY KEY uuid 設定
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
    ];

    public $sortable = ['id', 'name', 'updated_at'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
