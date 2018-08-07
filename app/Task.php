<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'user_id','updated_at'
    ];

    /**
     * One to many (one user many tasks) relationship
     *
     */
    public function users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
