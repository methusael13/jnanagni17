<?php

namespace jnanagni;

use Illuminate\Database\Eloquent\Model;

class PreUser extends Model {
    protected $table = 'pre_users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'first_name', 'last_name', 'email',
        'phone', 'college', 'active', 'email_hash',
        'token'
    ];
}
