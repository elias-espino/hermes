<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model {
    use SoftDeletes;

    protected $fillable = ['manager_id', 'max_companies'];

    public function manager() {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
