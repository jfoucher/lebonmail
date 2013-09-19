<?php

/**
 * Change the below to your database connection data
 */

define('DBPASSWD', 'z0bilam0uche');
define('DBUSER', 'root');
define('DBHOST', 'localhost');
define('DBDATABASE', 'lebonmail');

/**
 * Change the below to your SMTP connection data
 */
define('SMTPHOST', 'smtp.lebonmail.com');
define('SMTPUSER', 'lebonmail');
define('SMTPPASSWORD', 'lebonmail');
define('SMTPPORT', 587);

setlocale(LC_ALL, 'fr_FR.utf8');

require 'Slim/Slim.php';
require 'Views/TwigView.php';
require 'libraries/simple_html_dom.php';
require 'helpers/helpers.php';
require 'helpers/browser.php';

require 'libraries/Swift-4.3.1/lib/swift_required.php';

/**
 * Step 2: Instantiate the Slim application
 *
 * Here we instantiate the Slim application with its default settings.
 * However, we could also pass a key-value array of settings.
 * Refer to the online documentation for available settings.
 */
$app = new Slim_Return(array(
    'mode' => 'development',
    'view' => 'TwigView',
    'log.enable' => true,
    'debug' => true,
    'log.level' => 4,
    'cookies.secret_key' => 'gfdg456fghFGHFG549DFH3đŋħŧ↓←↓',
    'cookies.lifetime' => 0,
    'cookies.path' => '/',
    'cookies.secure' => true,
    'cookies.httponly' => true,

    'cookies.encrypt' => true,
    'cookies.user_id' => 'jfoucher'
));
$app->config('database', 'mysql:host='.DBHOST.';dbname='.DBDATABASE);
$app->config('dbuser', DBUSER);
$app->config('dbpassword', DBPASSWD);

$app->config('cache_duration', '1');

function getLang()
{
    if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        return 'en';
    }

    $languages = array_unique(array_map(function ($el) {
        return substr($el, 0, 2);
    }, explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])));

    return reset($languages);
}

function getLocale()
{

    if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        return array('en_US', 'en_US.utf8');
    }

    $languages = array_unique(array_map(function ($el) {
        return substr($el, 0, 2);
    }, explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])));

    foreach ($languages as $lang) {
        switch ($lang) {
            case 'fr':
                return array('fr_FR', 'fr_FR.utf8');
            case 'en':
                return array('en_US', 'en_US.utf8');
            case 'es':
                return array('es_ES', 'es_ES.utf8');
        }
    }

    return array('en_US', 'en_US.utf8');
}

function getSearchLocale($lang)
{
    if(!$lang) {
        return array('en_US', 'en_US.utf8');
    }
    switch ($lang) {
        case 'fr':
            return array('fr_FR', 'fr_FR.utf8');
        case 'en':
            return array('en_US', 'en_US.utf8');
        case 'es':
            return array('es_ES', 'es_ES.utf8');
        default:
            return array('en_US', 'en_US.utf8');
    }
}

function setLeBonMailLocale($locales)
{
    foreach ($locales as $locale) {
        if (setlocale(LC_ALL, $locale) !== false) {
            putenv('LC_ALL=' . $locale);
            break;
        }
    }

    bindtextdomain('messages', './locale');
    bind_textdomain_codeset('messages', 'UTF-8');
    textdomain('messages');
}

setLeBonMailLocale(getLocale());

//GET route
$app->get('/', 'register_form');
$app->get('/index', 'register_form');
$app->get('/remove/:hash', 'remove');
$app->post('/register', 'register');
$app->get('/email', 'email_test');
$app->get('/paid', 'paid');
$app->post('/ipn', 'ipn');


$app->get('/all_searches', function () use ($app) {

    try {
        $db = new PDO($app->config('database'), $app->config('dbuser'), $app->config('dbpassword'));
        $q = "SELECT searches.* FROM searches HAVING searches.updated_at IS NULL  OR searches.updated_at < unix_timestamp(DATE_SUB(NOW(), INTERVAL 1 HOUR))";
        $stmt = $db->prepare($q);
        $p = array(date('Y-m-d H:i:s'));
        $stmt->execute($p);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $res = array();

        //TODO reduce scraping frequency when there are rarely any new ads

        foreach ($data as $search) {

            if(isset($search['lang']) && $search['lang']) {
                setLeBonMailLocale(getSearchLocale($search['lang']));
            } else {
                setLeBonMailLocale(array('fr_FR', 'fr_FR.utf8'));
            }

            if ($search['annonces'] == null || $search['updated_at'] == null || $search['updated_at'] < time() - 3600 * $app->config('cache_duration')) {
                $app->getLog()->info('scraping for '.$search['url']);
                $annonces = scrape($search['url'], $app->getLog());
                $app->getLog()->info('Got '.count($annonces).' adds');
                if (is_array($annonces) && !empty($annonces)) {
                    $p = array(serialize($annonces), time(), serialize($annonces[0]), $search['hash']);
                    $q = "UPDATE searches SET annonces=?, updated_at=?, last=? WHERE hash=?";
                    $stmt = $db->prepare($q);
                    $stmt->execute($p);
                } else {
                    $p = array(time(), $search['hash']);
                    $q = "UPDATE searches SET updated_at=? WHERE hash=?";
                    $stmt = $db->prepare($q);
                    $stmt->execute($p);
                }
            } else {
                $annonces = unserialize($search['annonces']);

            }
            $res[] = email($search, $annonces);
        }
        $app->halt(200, json_encode(array('date' => date_create('now')->format(DATE_W3C), 'result' => $res)) . ', ' . "\r\n");

    } catch (PDOException $e) {
        $app->halt(500, json_encode(array('status' => false, 'errors' => 'Exception : ' . $e->getMessage())));
    }
});


function email($search, $annonces)
{
    $app = Slim::getInstance();

    $ann = array();
    foreach ($annonces as $a) {
        $annonce = $a;
        if (!isset($annonce['imgsrc']) || !$annonce['imgsrc'] || trim($annonce['imgsrc']) == '') {
            $annonce['imgsrc'] = 'http://placehold.it/450x300/aaaaaa/eeeeee.jpg&text=Pas+de+photo';
        }
        $last = unserialize($search['last']);
        if ($annonce['link'] == $last['link']) {
            break;
        }
        $ann[] = $annonce;
    }

    if (empty($ann)) {
        return array('sent' => null, 'email' => $search['email'], 'hash' => $search['hash']);
    }

    $unsub_link = 'http://' . $_SERVER['SERVER_NAME'] . '/remove/' . $search['hash'];

    $data = array(
        'timestamp' => (isset($search['updated_at']) ? $search['updated_at'] : time()),
        'link' => $search['url'],
        'annonces' => $ann,
        'remove_link' => $unsub_link,

    );


    $message = $app->render('email.twig', $data, null, false);
    $text = $app->render('email.txt.twig', $data, null, false);

    list(, , , $category) = explode('/', $search['url']);

    $category = ucfirst(str_replace(array('_', '-'), ' ', $category));

    parse_str(parse_url($search['url'], PHP_URL_QUERY), $qs);
    $recherche = $category;
    if(isset($qs['q'])) {

        $recherche = $category.' - '.ucfirst($qs['q']);

        if ($category == 'Annonces') {
            $recherche = ucfirst($qs['q']);
        }
    }



    $sub = 'New classifieds';

    if (strpos($data['link'], 'leboncoin.fr') !== false) {
        $sub = 'Nouvelle annonce - '.$recherche;
    } elseif (strpos($data['link'], 'segundamano.es') !== false) {
        $sub = 'Nuevo anuncio - '.$recherche;
    }

    $r = send_message($search['email'], $sub, $message, $text, $unsub_link);

    return array('sent' => $r['request'], 'email' => $search['email'], 'hash' => $search['hash']);
}


function send_message($to, $sub, $mess, $text, $unsub_link)
{
    $app = Slim::getInstance();


    $transport = Swift_SmtpTransport::newInstance(SMTPHOST, SMTPPORT)
        ->setUsername(SMTPUSER)
        ->setPassword(SMTPPASSWORD)
    ;

    // Create the Mailer using your created Transport
    $mailer = Swift_Mailer::newInstance($transport);

    // Create a message
    $message = Swift_Message::newInstance($sub);
    $message
        ->setFrom(array('info@lebonmail.com' => 'Le Bon Mail'))
        ->setTo(array($to))
        ->setBody($text)
        ->addPart($mess, 'text/html')
    ;

    $headers = $message->getHeaders();
    $headers->addTextHeader('List-Unsubscribe', '<'.$unsub_link.'>');

    $log = $app->getLog();
    $db = new PDO($app->config('database'), $app->config('dbuser'), $app->config('dbpassword'));
    $hash = sha1($mess.$to);
    $q = "SELECT count(*) as cnt FROM sent WHERE hash='".$hash."'";
    $stmt = $db->prepare($q);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result = reset($data);
    $request = 0;
    if($result['cnt'] == 0) {
        $q = "INSERT INTO sent(hash) VALUES(?)";
        $stmt = $db->prepare($q);
        $stmt->execute(array($hash));
        $request = $mailer->send($message);
    } else {
        $log->info('email not sent, already exists : '.$to.' - subject : '.$sub. ' - message hash : '.$hash);
    }


    return array('request' => $request);

}


function register()
{
    $app = Slim::getInstance();
    try {
        $db = new PDO($app->config('database'), $app->config('dbuser'), $app->config('dbpassword'));
        $email = filter_var($app->request()->post('email'), FILTER_VALIDATE_EMAIL);
        $url = filter_var($app->request()->post('url'), FILTER_VALIDATE_URL);


        //TODO check number of active searches for this email

        $q = "SELECT count(*) as cnt FROM searches WHERE url='".$url."' AND email='".$email."'";
        $stmt = $db->prepare($q);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = reset($data);
        $errors = array();

        if (isset($result['cnt']) && $result['cnt'] >= 1) {
            $errors[] = array('element' => 'url', 'message' => 'Cette recherche existe déja');
        } else {

            $q = "SELECT count(*) as cnt FROM searches WHERE email = ?";
            $stmt = $db->prepare($q);
            $stmt->execute(array($email));
            $data = $stmt->fetch();

            if (!preg_match('~^https?://[w]+\.?(leboncoin.fr|dinkos.com.au|segundamano.es)/(.+)~', $url)) {
                $url = false;
            } else {
                try {
                    $opts = array(
                        'http' => array(
                            'method' => "GET",
                            'header' => "Accept-language: es\r\n" .
                                "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.13 (KHTML, like Gecko) Chrome/9.0.597.107 Safari/534.13\r\n"
                        )
                    );
                    $context = stream_context_create($opts);
                    $urlContents = file_get_contents($url, false, $context);
    //                file_get_contents($url);
                } catch (Exception $e) {
                    $url = false;
                }
            }



            if (!$email) {
                $errors[] = array('element' => 'email', 'message' => 'Cet adresse email est incorrecte');
            } elseif (!$url) {
                $errors[] = array('element' => 'url', 'message' => 'Cet URL est incorrecte');
            }
        }
        if (!empty($errors)) {
            if ($app->request()->isAjax()) {
                $app->response()->header('Content-Type', 'application/json');
                $app->halt(400, json_encode(array('status' => false, 'errors' => $errors)));
            } else {
                $app->redirect('/');
            }
        }

        $p = array($email, $url, time(), sha1($email . $url . time() . microtime() . md5(rand(0, 9999))), getLang());
        $q = "INSERT INTO searches (email, url, created_at, hash, lang) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($q);
        $stmt->execute($p);
        $db = null;

        if ($app->request()->isAjax()) {
            $app->response()->header('Content-Type', 'application/json');
            $app->halt(200, json_encode(array('status' => true)));
        } else {
            success('Inscription réussie', 'Vous allez bientôt commencer à recevoir des emails pour les annonces correspondant à votre recherche');
        }

    } catch (PDOException $e) {
        $app->halt(500, json_encode(array('status' => false, 'errors' => 'Exception : ' . $e->getMessage())));
    }
}

function register_form()
{
    $app = Slim::getInstance();

    $app->view()->setData(array(
        'server' => $_SERVER['SERVER_NAME'],
    ));
    $app->render('header.twig');
    $app->render('register.twig');
    $app->render('footer.twig');
}

function success($title, $message, $type = "success")
{
    $app = Slim::getInstance();

    $app->view()->setData(array(
        'title' => $title,
        'message' => $message,
        'type' => $type,
    ));
    $app->render('header.twig');
    $app->render('success.twig');
    $app->render('footer.twig');
}

function remove($hash)
{
    $app = Slim::getInstance();
    try {

        if (!$hash) $app->redirect('/');
        $db = new PDO($app->config('database'), $app->config('dbuser'), $app->config('dbpassword'));
        $p = array($hash);
        $q = "DELETE FROM searches WHERE hash = ?";
        $stmt = $db->prepare($q);
        $stmt->execute($p);
        if ($stmt->rowCount() > 0) {
            $app->render('header.twig');
            $app->render('remove.twig');
            $app->render('footer.twig');
        } else {
            $app->redirect('/');
        }
        $db = null;

    } catch (PDOException $e) {
        $app->halt(500, json_encode(array('status' => false, 'errors' => 'Exception : ' . $e->getMessage())));
    }
}

$app->run();