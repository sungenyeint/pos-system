<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class ImportDetail extends Model
{
    use HasUuid;

    // PRIMARY KEY uuid 設定
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'import_id',
        'line_number',
        'result',
        'messages'
    ];

    protected $casts = [
        'messages' => 'array',
    ];

    protected $attributes = [
        'messages' => '[]',
    ];

    public function import()
    {
        return $this->belongsTo(Import::class);
    }
}
