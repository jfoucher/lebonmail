<?php
namespace Parsers;

use ParserInterface;

class SegundaManoParser implements ParserInterface
{
    public function parse($html)
    {
        $annonces = array();
        foreach ($html->find('ul.list_ads_row') as $annonce) {

            $ann = array();
            //var_dump($annonce->find ('div.image', 0));
            $image_cell = $annonce->find('li.image', 0);
            $text_cell = $annonce->find('li.subject', 0)->find('a.subjectTitle', 0);

            $text = utf8_encode(trim($text_cell->plaintext));
            $lnk = trim($text_cell->attr['href']);
            if ($price_cell = $annonce->find('li.subject', 0)->find('a.subjectPrice', 0)) {
                $price = trim($price_cell->plaintext);
            } else {
                $price = '';
            }
            if ($location_cell = $annonce->find('li.zone', 0)->find('a', 0)) {
                $location = trim($location_cell->plaintext);
            } else {
                $location = '';
            }

            //var_dump($text);
            $ann['text'] = $text;
            $ann['link'] = $lnk;
            $ann['price'] = $price;
            $ann['location'] = $location;
            //echo '<a href="'.$lnk.'">'.$text.'</a><br/>';
            $ann['imgsrc'] = '';
            if ($image_cell->children()) {
                $image = $image_cell->find('div.thumbnail_container', 0)->find('img', 0)->attr['title'];
//                echo $image;
                $ann['imgsrc'] = str_replace('thumbs', 'images', $image);
            }
            $annonces[] = $ann;
        }
        return $annonces;
    }
}