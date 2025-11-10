<?php

declare(strict_types=1);

namespace Tests\Fixtures;

enum Role: string
{
    case Admin = 'admin';
    case Member = 'member';
}
