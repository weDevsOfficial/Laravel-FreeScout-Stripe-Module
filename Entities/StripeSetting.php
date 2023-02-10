<?php

namespace Modules\Stripe\Entities;

use App\Conversation;
use Illuminate\Database\Eloquent\Model;

class StripeSetting extends Model
{
    protected $fillable = ['mailbox_id', 'stripe_secret_key'];

}
