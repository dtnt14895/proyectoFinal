<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    use HasFactory;
    protected  $table="persons";

    protected $fillable = [
       
        'name',
        'lastname',
        'phone', 
        'born' ,     
        'create_by',      
        'update_by' 
    ];

   


    public function users(): HasMany
    {
        return $this->HasMany(User::class, 'person_id');
    }


    public function create_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by');
    }

    public function update_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'update_by');
    }
   
}
