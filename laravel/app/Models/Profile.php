<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\File;
use Illuminate\Support\Facades\DB;

class Profile extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    protected $fillable = [
		'name',
		'author_id',
		'file_id',
    ];

    public function file()
    {
       return $this->belongsTo(File::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    public function author()
    {
        return $this->belongsTo(User::class);
    }
    protected $perPage = 20;

}
