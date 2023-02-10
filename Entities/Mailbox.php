<?php

namespace Modules\Stripe\Entities;

class Mailbox extends \App\Mailbox
{
    public function stripeSetting()
    {
        return $this->hasOne(StripeSetting::class, 'mailbox_id', 'id');
    }
}
