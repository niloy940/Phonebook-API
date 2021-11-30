<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'label'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function abilities()
    {
        return $this->belongsToMany(Ability::class)->withTimestamps();
    }

    public function allowTo($ability)
    {
        $this->abilities()->sync($ability, false);
    }
}
