<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rolpage extends Model
{
    use HasFactory;

    protected $table="rol_page";

    protected $fillable = [
       
        'name',    
        'enlaced_to',
        'page_id',
        'rol_id',
        'order',    
        'create_by',      
        'update_by' 
    ];

  



    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function create_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by');
    }

    public function update_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'update_by');
    }

    public function enlaced(): BelongsTo
    {
        return $this->belongsTo(Rolpage::class, 'enlaced_to');
    }

    public function linkeds(): HasMany
    {
        return $this->HasMany(Rolpage::class, 'enlaced_to');
    }

    
}
