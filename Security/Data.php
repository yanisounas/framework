<?php

namespace Framework\Security;

class Data
{

    /**
     * Basic function to secure data passed to the database
     *
     * @param string $data Sequence to secure
     * @return string Secure sequence as a string
     */
    public static function cleanString(string $data): string
    {
        // TODO: More advanced function
        return htmlspecialchars(trim($data));
    }
}