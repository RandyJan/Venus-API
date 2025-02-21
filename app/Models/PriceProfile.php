<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceProfile extends Model
{
    use HasFactory;

    protected $table = 'Price_Profile';
    protected $primaryKey = 'Price_Profile_ID';
    //protected $connection = 'enablerDb';

    protected $fillable = [
        'Price_Profile_ID', 'Price_Profile_Name', 'Scheduled_ST', 'Parent_Grade_ID', 'Deleted',
    ];

    /**
     * Scopes
     */
    public function scopeCheckIfPriceProfileExist($query, $grade_id, $date){
        return $query->where('Parent_Grade_ID', $grade_id)
            ->whereDate('Scheduled_ST', $date);
    }
}
