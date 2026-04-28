<?php

namespace App\Exceptions;

use Exception;

class MissingHeadingsException extends Exception
{
    protected $missingHeadings;

    public function __construct(array $missingHeadings)
    {
        $this->missingHeadings = $missingHeadings;
        parent::__construct('Missing headings: ' . implode(', ', $missingHeadings));
    }

    public function getMissingHeadings()
    {
        return $this->missingHeadings;
    }
}
