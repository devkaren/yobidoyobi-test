<?php

namespace Infrastructure\Auth;

final class CheckMessage
{
    public static function getMessageAsCode(AbstractCheck $check, bool $negated = true): string
    {
        return str($check::class)
            ->classBasename()
            ->snake()
            ->upper()
            ->beforeLast('_CHECK')
            ->prepend($negated ? '!' : '')
            ->toString();
    }
}
