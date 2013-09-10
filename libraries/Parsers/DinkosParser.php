<?php

namespace Parsers;
use ParserInterface;

class DinkosParser implements ParserInterface
{
    public function parse($html)
    {
        $annonces = array();
        foreach ($html->find('table.listing_thumbs', 0)->find('tr') as $annonce) {
            $image_cell = $annonce->find('a.thumb_image', 0);

            $ann['imgsrc'] = '';
            if (is_object($image_cell)) {

                if ($image_cell->children()) {
                    $image = $image_cell->find('img', 0)->attr['src'];
                    $ann['imgsrc'] = str_replace('thumbs', 'images', $image);
                }
                $text_cell = $annonce->find('a', 1);
            } else {
                $text_cell = $annonce->find('a', 0);
            }

            $text = '';
            $lnk = '';
            if (is_object($text_cell)) {
                $text = trim(preg_replace('/\s+/', ' ', $text_cell->plaintext));
                $lnk = trim($text_cell->attr['href']);
                preg_match('/\$([0-9\.,]+)/', $annonce->plaintext, $matches);
                $price = 0;
                if (isset($matches[1])) {
                    $price = $matches[1];
                }

                $location = '';

                if($location_cell = $annonce->find('div.list_area', 0)) {
                    $location = $location_cell->plaintext;
                }


                //var_dump($text);
                $ann['text'] = $text;
                $ann['link'] = $lnk;
                $ann['price'] = $price;
                $ann['location'] = $location;
                //echo '<a href="'.$lnk.'">'.$text.'</a><br/>';

                $annonces[] = $ann;
            }


        }
        return $annonces;
    }
}