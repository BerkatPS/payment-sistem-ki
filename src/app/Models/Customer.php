<?php

namespace App\Models;

use App\Traits\EncryptsAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory, EncryptsAttributes;
    
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];
    
    /**
     * The attributes that should be encrypted.
     *
     * @var array
     */
    protected $encryptable = [
        'email',
        'phone',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalPaymentsAttribute()
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }
}
