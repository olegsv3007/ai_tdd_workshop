<?php

namespace App\DDD\User\Domain\Collection;

use App\DDD\Shared\Domain\Collection\EntityCollection;
use App\DDD\User\Domain\Entity\Token;

class TokenCollection extends EntityCollection
{
    protected static function getItemType(): string
    {
        return Token::class;
    }

    public function first(): ?Token
    {
        return $this->getFirstItem();
    }
}
