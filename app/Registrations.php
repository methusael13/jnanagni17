<?php

namespace jnanagni;

use Illuminate\Database\Eloquent\Model;

class Registrations extends Model {
    protected $table = 'registrations';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'event_id', 'event_name'
    ];
}
