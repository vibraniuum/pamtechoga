<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Models\Model as LegoModel;
use Helix\Lego\Models\User;

class OrganizationUser extends LegoModel
{

    protected $table = 'pamtechoga_organization_users';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
