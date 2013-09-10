<?php
namespace Parsers;

use ParserInterface;

class LeBonCoinParser implements ParserInterface
{
    public function parse($html)
    {
        $annonces = array();
        foreach ($html->find('div.list-lbc > a') as $annonce) {

            $ann = array();
            //var_dump($annonce->find ('div.image', 0));
            $image_cell = $annonce->find('div.image', 0);
            $text_cell = $annonce->find('div.detail', 0)->find('div.title', 0);

            $text = trim($text_cell->plaintext);
            $lnk = trim($annonce->attr['href']);
            if ($price_cell = $annonce->find('div.detail', 0)->find('div.price', 0)) {
                $price = trim($price_cell->plaintext);
            } else {
                $price = '';
            }
            if ($location_cell = $annonce->find('div.detail', 0)->find('div.placement', 0)) {
                $location = trim($location_cell->plaintext);
            } else {
                $location = '';
            }

            //var_dump($text);
            $ann['text'] = $text;
            $ann['link'] = $lnk;
            $ann['price'] = $price;
            $ann['location'] = utf8_encode($location);
            //echo '<a href="'.$lnk.'">'.$text.'</a><br/>';
            $ann['imgsrc'] = '';
            if (is_object($image_cell) && $image_cell->children()) {
                $image = $image_cell->find('div.image-and-nb', 0)->find('img', 0)->attr['src'];
                $ann['imgsrc'] = str_replace('thumbs', 'images', $image);
            }
            $annonces[] = $ann;
        }
        return $annonces;
    }
}