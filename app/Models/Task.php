<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends BaseModel
{
    use HasFactory;
    const SELECT_FIELD = ['id', 'user_id', 'name', 'code', 'schedule', 'created_at', 'updated_at'];

    const UPDATED_FIELD = ['id', 'name', 'code', 'schedule'];

    const ORDERABLE_COLUMNS = ['name', 'created_at', 'updated_at'];

    protected $fillable = ['user_id', 'name', 'code', 'schedule', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
