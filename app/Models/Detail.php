<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\DetailStatus;
use App\Models\Location;
use App\Models\Project;

class Detail extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['status'];

    public function status(): BelongsTo
    {
        return $this->belongsTo(DetailStatus::class, 'detail_status_id');
    }

    public function histories()
    {
        return $this->hasMany(DetailHistory::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
