<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Platforms\Keywords;

use function array_merge;

/**
 * MariaDB 12.3 reserved keywords list.
 *
 * @deprecated
 */
class MariaDB123Keywords extends MariaDB117Keywords
{
    /**
     * {@inheritDoc}
     *
     * @link https://jira.mariadb.org/browse/MDEV-19683
     */
    protected function getKeywords(): array
    {
        $keywords = parent::getKeywords();

        // TO_DATE() was added as an Oracle-compatible function in MariaDB 12.3
        // and is implemented as a parser-level keyword, so it can no longer be
        // used as an unquoted identifier.
        $keywords = array_merge($keywords, ['TO_DATE']);

        return $keywords;
    }
}
