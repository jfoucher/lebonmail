<?php

interface ParserInterface
{
    /**
     * Parse HTML to ads
     * @return array
     **/
    public function parse($html);

}