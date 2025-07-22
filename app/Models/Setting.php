<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['admin_mode'];
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        // Создаем запись по умолчанию при инициализации
        static::created(function ($setting) {
            if (Setting::count() === 1) {
                $setting->update(['admin_mode' => false]);
            }
        });
    }
}