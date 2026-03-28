<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitDay extends Model
{
    protected $table = 'habit_days';

    protected $primaryKey = 'had_id';

    protected $fillable = [
        'had_hab_id',
        'had_day',
        'had_description',
        'had_schedule'
    ];

    // ------------------------
    // Getters
    // ------------------------

    public function getHadId()
    {
        return $this->had_id;
    }

    public function getHadHabId()
    {
        return $this->had_hab_id;
    }

    public function getHadDay()
    {
        return $this->had_day;
    }

    public function getHadDescription()
    {
        return $this->had_description;
    }

    public function getHadSchedule()
    {
        return $this->had_schedule;
    }

    // ------------------------
    // Setters
    // ------------------------

    public function setHadHabId($value)
    {
        $this->had_hab_id = $value;
        return $this;
    }

    public function setHadDay($value)
    {
        $this->had_day = $value;
        return $this;
    }

    public function setHadDescription($value)
    {
        $this->had_description = $value;
        return $this;
    }

    public function setHadSchedule($value)
    {
        $this->had_schedule = $value;
        return $this;
    }

}
