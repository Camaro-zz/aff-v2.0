<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignsUsers extends Model{
    protected $table = 'camp_users';

    protected $guarded = ['id'];

    public    $timestamps = true;

}
