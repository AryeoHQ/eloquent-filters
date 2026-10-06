<?php

declare(strict_types=1);

namespace Tests\Fixtures\Support\Database;

enum Role: string
{
    case Admin = 'admin';
    case Member = 'member';
}
