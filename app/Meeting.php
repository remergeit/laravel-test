<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'start',
        'end',
        'status',
        'facilitator_id',
        'secretary_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start' => 'date:d-m-Y H:i',
        'end' => 'date:d-m-Y H:i',
    ];

    public function attendees()
    {
        return $this->belongsToMany(User::class)
                    ->withTimestamps();
    }
}
