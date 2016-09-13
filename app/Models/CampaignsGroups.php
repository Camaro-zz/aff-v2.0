<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignsGroups extends Model
{
    protected $table = 'mt_groups';

    protected $guarded = ['group_id'];

    public    $timestamps = false;
}
