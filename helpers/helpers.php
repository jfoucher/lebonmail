<?php
function ago($time)
{
    $periods = array("segundo", "minuto", "hora", "día", "semana", "mes", "año", "decada");
    $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

    $now = time();

    $difference = $now - $time;
    $tense = "ago";

    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if ($difference != 1) {
        if ($periods[$j] == "mes")
            $periods[$j] .= "es";
        else
            $periods[$j] .= "s";
    }

    return "hace $difference $periods[$j] ";
}

function authuser($role = 'member')
{

    $user = User::fetchFromDatabaseSomehow();
    if ($user->belongsToRole($role) === false) {
        Slim::flash('error', 'Login required');
        Slim::redirect('/login');
    }

}

function title($str)
{

    $replace = '-';
    $trans = array(
        '&\#\d+?;' => '',
        '&\S+?;' => '',
        '\s+' => $replace,
        '[^a-z0-9\-\._]' => '',
        $replace . '+' => $replace,
        $replace . '$' => $replace,
        '^' . $replace => $replace,
        '\.+$' => ''
    );

    $str = strip_tags($str);
    foreach ($trans as $key => $val) {
        $str = preg_replace("#" . $key . "#i", $val, $str);
    }

    return trim(stripslashes(strtolower($str)));
}

spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    if (is_file('libraries/' . $class . '.php')) {
        include 'libraries/' . $class . '.php';
    }
});

function getParser($link)
{

    $host = preg_replace('/^www./', '', parse_url($link, PHP_URL_HOST));

    switch ($host) {
        case 'leboncoin.fr':
            return new Parsers\LeBonCoinParser();
        case 'dinkos.com.au':
            return new Parsers\DinkosParser();
        case 'segundamano.es':
            return new Parsers\SegundaManoParser();
    }


}


function scrape($link = 'http://google.com', $logger = null)
{


    $browsers = array(
        'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10.4; en; rv:1.9.0.19) Gecko/2011091218 Camino/2.0.9 (like Firefox/3.0.19)',
        'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.97 Safari/537.22',
        'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.12 (KHTML, like Gecko) Chrome/24.0.1273.0 Safari/537.12',
        'Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/420+ (KHTML, like Gecko)',
        'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10.5; en-US; rv:1.9.0.3) Gecko/2008092414 Firefox/3.0.3',
        'Opera/10.00 (X11; Linux i686 ; U; en) Presto/2.2.0',
        'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 8.00',
        'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_2; en-us) AppleWebKit/531.21.8 (KHTML, like Gecko) Version/4.0.4 Safari/531.21.10',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_3) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.65 Safari/537.31',
    );

    $browser = $browsers[array_rand($browsers)];

    if ($logger) {
        $logger->info('scraping '.$link.' with '.$browser);
    }
    // create HTML DOM
    try {
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => //"Accept-language: es\r\n" .
                "User-Agent: " . $browser . "\r\n"
            )
        );
        $context = stream_context_create($opts);
        $urlContents = file_get_contents($link, false, $context);
        $html = str_get_html($urlContents);
        $logger->info('html content '.$html);
    } catch (Exception $e) {
        $logger->info('error '.$e->getMessage());
        return array();
    }

    $parser = getParser($link);

    $annonces = $parser->parse($html);

    $html->clear();

    unset($html);

    return $annonces;
}
