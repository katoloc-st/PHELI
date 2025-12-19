<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'business_license',
        'tax_code',
        'company_description',
        'company_logo',
        'phone',
        'address',
        'description',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function collectionPoints()
    {
        return $this->hasMany(CollectionPoint::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function buyerTransactions()
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function sellerTransactions()
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    public function isWasteCompany()
    {
        return $this->role === 'waste_company';
    }

    public function isScrapDealer()
    {
        return $this->role === 'scrap_dealer';
    }

    public function isRecyclingPlant()
    {
        return $this->role === 'recycling_plant';
    }
}
