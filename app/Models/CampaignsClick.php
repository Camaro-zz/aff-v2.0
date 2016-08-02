<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignsClick extends Model
{
    protected $table = 'mt_click';

    protected $guarded = ['click_id'];

    public    $timestamps = false;
}
