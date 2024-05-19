<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Increment extends Model
{
    use HasFactory;

    protected $table = 'increments';
    public $timestamps = false;
    protected $fillable = ['source', 'code', 'year', 'month', 'date', 'increment', 'updated_at'];

    public static function getOrCreateIncrement($source, $code, $year, $month, $date)
    {
        $incrementData = self::where('source', $source)
            ->where('code', $code)
            ->where('year', $year)
            ->where('month', $month)
            ->where('date', $date)
            ->first();

        if ($incrementData) {
            // Update increment
            $incrementData->increment++;
            $incrementData->updated_at = now();
            $incrementData->save();
        } else {
            // Insert new record
            $incrementData = self::create([
                'source' => $source,
                'code' => $code,
                'year' => $year,
                'month' => $month,
                'date' => $date,
                'increment' => 1,
                'updated_at' => now(),
            ]);
        }

        return $incrementData;
    }

    public function getFormattedCode()
    {
        return $this->code . $this->year . $this->month . str_pad($this->increment, 3, '0', STR_PAD_LEFT);
    }
}
