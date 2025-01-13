<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasUuid;

    // PRIMARY KEY uuid 設定
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'file_name',
        'status',
        'messages',
        'foreign_id',
    ];

    protected $casts = [
        'messages' => 'array'
    ];

    protected $attributes = [
        'messages' => '[]',
    ];

    public function import_details()
    {
        return $this->hasMany(ImportDetail::class);
    }
}
