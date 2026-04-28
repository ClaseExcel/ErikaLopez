<?php

namespace App\Services;

use Parsedown;

class MarkdownService
{
    protected $parsedown;

    public function __construct(Parsedown $parsedown)
    {
        $this->parsedown = $parsedown;
    }

    public function parse($content)
    {
        // Enable table support
        $this->parsedown->setMarkupEscaped(true);
        // Enable code block support
        $this->parsedown->setSafeMode(true);
        // Handle new lines in content
        $parsedContent = $this->parsedown->text($content);
        // Add a newline after code blocks
        $parsedContent = preg_replace('/(<\/pre>)/', '$1<br>', $parsedContent);
        return $parsedContent;
    }
    
}