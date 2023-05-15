<?php

namespace projeto;

class SourceURLs
{
    private $urls;

    /**
     * Constructor method for the SourceURLs class.
     * 
     * @param array $urls The array of URLs to be used as data sources.
     */
    public function __construct(array $urls)
    {
        $this->urls = $urls;
    }

    /**
     * Getter method for the $urls property.
     * 
     * @return array Returns an array of URLs.
     */
    public function getURLs(): array
    {
        return $this->urls;
    }
}
