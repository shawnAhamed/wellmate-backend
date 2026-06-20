<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }

    public static function findOrCreateByName(string $name): self
    {
        return static::firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => trim($name)]
        );
    }
}
