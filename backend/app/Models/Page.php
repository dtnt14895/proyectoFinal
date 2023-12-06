<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'url',
        'name',
        'description', 
        'icon' ,
        'type',      
        'create_by',      
        'update_by' 
    ];



    public function create_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by');
    }

    public function update_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'update_by');
    }
}
