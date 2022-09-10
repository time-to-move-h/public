<?php
declare(strict_types=1);
require_once 'bootstrap.php';
use Moviao\Http\Route\Request; 
use Moviao\Http\Route\Response;
//exit();
// GeoLocation -------------------------------------
//    include("class/GeoIP/geoiploc.php"); // Must include this
//    $geoloc = new GeoIP\GeoLocation();
//    $geoloc->initDB();
//    return $app->redirect("http://www.moviao.es");   
//---------------------------------------------------
$array_lang = array('en-GB'); //arimray("es-ES", "fr-BE", "en-GB");
$userLanguage = new \Moviao\Http\UserLanguage('en-GB', $array_lang);

// Template Engine
// Create new Plates instance
$templates = new League\Plates\Engine();
$templates->addFolder('tpl', 'app/view/templates');// Add folders
$templates->addFolder('partials', 'app/view/templates/partials');
$templates->addFolder('inc', 'app/view/templates/inc');
$templates->addFolder('modules', 'app/view/templates/modules');
$templates->addFolder('view', 'app/view');
$templates->addFolder('util', 'lib/Moviao/Util');

//$templates->addFolder('test', 'private/test');
//---------------------------------------------------
// Language Selection
//$lang_iso = null; // Default Language
//$lang = (isset($_GET['lang'])) ? filter_var($_GET['lang'], FILTER_SANITIZE_STRING) : 'EN';
//if ($lang === 'ES') {
//    $lang_iso = 'es-ES'; 
//} else if ($lang === 'FR') {
//    $lang_iso = 'fr-BE'; 
//} else {
//    $lang_iso = 'en-EN'; 
//}
//---------------------------------------------------
// Session
$sessionUser = new \Moviao\Session\SessionUser();
//$sessionUser->startSession();
//---------------------------------------------------
$router = new \Moviao\Http\Route\Router();
// Router
$router->route('/', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $server = new \Moviao\Http\ServerInfo();
    $suffix = $server->getServerSuffix();
    $haystack = []; // Domaines Autorise "BE"
    $lang = $userLanguage->parseLang();
    if (in_array($suffix, $haystack)) {
        $params = $request->getParameters();
        $response->renderData($templates->render('view::home', ['lang' => $lang, 'sessionUser' => $sessionUser, 'params' => $params]));
    } else {
        $response->renderData($templates->render('view::index', ['lang' => $lang,'sessionUser' => $sessionUser]));
    }
});




//$router->route('/api/messenger/v1', function(Request $request, Response $response) use ($sessionUser) {
//    $params = $request->getParameters();
//    $response->render("app/api/messenger/v1/index.php", ['sessionUser' => $sessionUser, 'params' => $params ]);
//});



// open id
$router->route('/app/auth/php-openid/examples/server/server.php/?.*', function(Request $request, Response $response){
    $response->render("app/auth/php-openid/examples/server/server.php");
});



// open id 2 
$router->route('/access_token', function(Request $request, Response $response){
 
    exit("accesssssssssssss token mec");

    //$response->render("app/auth/phpoidc/phpOp/index.php");
});








// $router->route('/testpayment', function(Request $request, Response $response){
//     $response->render("private/test/index.php");
// });






$router->route('/data/suggest', function(Request $request, Response $response) use ($sessionUser) {
    $params = $request->getParameters();
    $response->render("data/suggest.php", ['sessionUser' => $sessionUser, 'params' => $params ]);
});

// $router->route('/auth/sso', function(Request $request, Response $response) use ($sessionUser) {
//     $params = $request->getParameters();
//     $response->render("app/auth/sso.php", ['sessionUser' => $sessionUser, 'params' => $params ]);
// });


// Upload
$router->route('/upload', function(Request $request, Response $response) use ($sessionUser) {
    $response->render("app/service/upload/upl.php", ['sessionUser' => $sessionUser ]);
});
// Images
$router->route('/img/(?:(?P<prefix>[c|e|p|u|f|m|i])/)?(?P<prefix2>\d+/)?(?P<img>\w+.(?:jpg|png|gif|svg))', function(Request $request, Response $response) {                
    $prefix = $request->getAttribute("prefix");
    $prefix2 = $request->getAttribute("prefix2");
    $img = $request->getAttribute("img");

    $folder = '';   
    if ($prefix === 'c') $folder = 'channels';
    if ($prefix === 'e') $folder = 'events';
    if ($prefix === 'u') $folder = 'users';
    if ($prefix === 'p') $folder = 'placeholder';
    if ($prefix === 'f') $folder = 'feeds';
    if ($prefix === 'm') $folder = 'chat';
    if ($prefix === 'i') $folder = 'icons';
    if (mb_strlen($prefix2)>0) $folder = $folder . '/' . $prefix2;
    if (mb_strlen($folder)>0) $img = $folder . '/' .$img;

    $file = "img/$img";
    $ext = explode('.', $file); // Extension Extract
    $content_type = $response->getImageContentType($ext[1]);
    $response->renderFile($file,$content_type);
});
// Javascript
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?dist/js/(?:(?P<prefix>calendar|chat|parsley)/)?(?P<file>[\w+-\.]*\.js)', function(Request $request, Response $response) {   
    $prefix = $request->getAttribute("prefix");
    $file = $request->getAttribute("file");     
    if (! empty($prefix)) {
        $file = $prefix . '/' .$file;
    }
    $response->renderFile("dist/js/$file", $response::APPLICATION_JS);
});
// CSS
$router->route('/dist/css/(?P<prefix>fullcalendar|chat)?/?(?P<file>[\w+-\.]*.css)', function(Request $request, Response $response) {    
    $prefix = $request->getAttribute("prefix");
    $file = $request->getAttribute("file");
    if (! empty($prefix)) {
        $file = $prefix . '/' .$file;
    }
    $response->renderFile("dist/css/$file", $response::TEXT_CSS);
});
// Fonts
$router->route('/dist/fonts/(?P<file>[\w+-]*\.woff2)(?:\?v=[\d+\.]*)?', function(Request $request, Response $response) {  
    $file = $request->getAttribute("file"); //fontawesome-webfont.woff2?v=4.7.0     
    $response->renderFile("dist/css/fonts/$file", $response::FONT_WOFF2);
});
// Controllers
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?ctrl/(?P<prefix>app|modules|lib|app/modules)?/?(?P<file>[\w+-\.]*.js)', function(Request $request, Response $response) {  
    $prefix = $request->getAttribute("prefix");
    $file = $request->getAttribute("file");    
    if (mb_strlen($prefix)>0) $file = $prefix . '/' .$file;
    $response->renderFile("dist/ctrl/$file", $response::APPLICATION_JS);
});
// Controller Auth
$router->route('/ctrl/auth', function(Request $request, Response $response) use ($sessionUser) {      
    $response->render("app/controller/auth.php", ['sessionUser' => $sessionUser ], $response::APPLICATION_JS);
});
// Sign In
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?login', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $params = $request->getParameters();
    $response->renderData($templates->render('view::signin', ['lang' => $lang, 'sessionUser' => $sessionUser, 'params' => $params ]));
});
// Sign In Recover
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?login/recover', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $params = $request->getParameters();   
    $response->renderData($templates->render('view::signin_recover', ['lang' => $lang, 'sessionUser' => $sessionUser, 'params' => $params ]));
});
// Sign Out
$router->route('/logout', function(Request $request, Response $response){    
    $response->render("app/view/logout.php");
});
// Sign up
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?signup', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $params = $request->getParameters();
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $response->renderData($templates->render('view::signup', ['lang' => $lang, 'sessionUser' => $sessionUser, 'params' => $params ]));
});
// Signup Validation
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?signup/validation', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $params = $request->getParameters();
    $response->renderData($templates->render('view::signup_validation', [ 'lang' => $lang, 'sessionUser' => $sessionUser, 'params' => $params]));
});
// Channel
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?channel/(?P<name>\w+)', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $name = $request->getAttribute("name");   
    $response->renderData($templates->render('view::channel', ['lang' => $lang, 'sessionUser' => $sessionUser , 'uid' => $name]));
});
// Channels
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?channels/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $response->renderData($templates->render('view::channels', ['lang' => $lang, 'sessionUser' => $sessionUser]));
});
// Create Channel
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?create_channel/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $params = $request->getParameters();
    $response->renderData($templates->render('view::create_channel', ['lang' => $lang, 'sessionUser' => $sessionUser, 'params' => $params]));
});
// Event
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?event/(?P<name>[\w+-]*)', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $name = $request->getAttribute("name");
    $response->renderData($templates->render('view::event', ['lang' => $lang,'sessionUser' => $sessionUser ,'urllink' => $name]));
});




// Event Tickets (Mobile use - Webview)
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?event_tickets/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    //$name = $request->getAttribute("name");
    $params = $request->getParameters();
    $response->renderData($templates->render('view::event_tickets', ['lang' => $lang,'sessionUser' => $sessionUser ,'params' => $params]));
});


// Pick a city
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?pickacity/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $params = $request->getParameters();
    $response->renderData($templates->render('view::pickacity', ['lang' => $lang, 'sessionUser' => $sessionUser, 'params' => $params]));
});


// Dashboard
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?dashboard/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $params = $request->getParameters();
    $response->renderData($templates->render('view::dashboard', ['lang' => $lang, 'sessionUser' => $sessionUser, 'params' => $params]));
});


// Events
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?events/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $params = $request->getParameters();
    $response->renderData($templates->render('view::events', ['lang' => $lang, 'sessionUser' => $sessionUser, 'params' => $params]));
});
// Home
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?home', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $params = $request->getParameters(); 
    $response->renderData($templates->render('view::home', ['lang' => $lang, 'sessionUser' => $sessionUser, 'params' => $params]));
});
// Create Event
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?create_event/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $params = $request->getParameters();
    $response->renderData($templates->render('view::create_event_t1', ['lang' => $lang, 'sessionUser' => $sessionUser,'params' => $params]));
});
// Profile
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?profile(?:/(?P<name>[\w+-]*))?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $name = $request->getAttribute("name");
    $params = $request->getParameters();
    $response->renderData($templates->render('view::profile', ['lang' => $lang, 'sessionUser' => $sessionUser, 'uid' => $name,'params' => $params]));
});


// Profile Public
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?profile_public(?:/(?P<name>[\w+-]*))?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $name = $request->getAttribute("name");
    $params = $request->getParameters();
    $response->renderData($templates->render('view::profile_public', ['lang' => $lang, 'sessionUser' => $sessionUser, 'uid' => $name,'params' => $params]));
});





// Profiles
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?profiles/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $response->renderData($templates->render('view::profiles', ['lang' => $lang, 'sessionUser' => $sessionUser]));
});
// Calendar
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?calendar/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $response->renderData($templates->render('view::calendar', ['lang' => $lang, 'sessionUser' => $sessionUser]));
});
// Contacts
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?contacts/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $response->renderData($templates->render('view::contacts', ['lang' => $lang, 'sessionUser' => $sessionUser]));
});

// Messenger
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?messenger/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $response->renderData($templates->render('view::messenger', ['lang' => $lang, 'sessionUser' => $sessionUser]));
});


// Checkout
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?checkout/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $params = $request->getParameters();
    $response->renderData($templates->render('view::checkout', ['lang' => $lang, 'sessionUser' => $sessionUser,'params' => $params]));
});

// Payment
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?payment/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $params = $request->getParameters();
    $response->renderData($templates->render('view::payment', ['lang' => $lang, 'sessionUser' => $sessionUser,'params' => $params]));
});

// Payment Order
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?payment/order/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $params = $request->getParameters();
    $response->renderData($templates->render('view::payment_order', ['lang' => $lang, 'sessionUser' => $sessionUser,'params' => $params]));
});

// Webhook Payment
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?payment/webhook/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $params = $request->getParameters();
    $response->renderData($templates->render('view::webhook', ['lang' => $lang, 'sessionUser' => $sessionUser,'params' => $params]));
});

// Ticket Order details
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?order/details/(?P<orderid>[0-9]*)', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $orderid = $request->getAttribute("orderid");
    $response->renderData($templates->render('view::ticket_details', ['lang' => $lang, 'sessionUser' => $sessionUser,'orderid' => $orderid]));
});


// Ticket Order View Ticket
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?order/ticket/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $params = $request->getParameters();
    $response->renderData($templates->render('view::ticket_view', ['lang' => $lang, 'sessionUser' => $sessionUser,'params' => $params]));
});


// Ticket Order View Output html for pdf conversion
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?order/ticket/output/?', function(Request $request, Response $response) use ($templates) {
    //$lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $params = $request->getParameters();
    $response->renderData($templates->render('view::ticket_output', ['params' => $params]));
});





$router->route('/util/qrcode', function(Request $request, Response $response) use ($templates) {
    $params = $request->getParameters();
    $response->renderData($templates->render('util::qrcode', ['params' => $params]));
});


//------------------------------------------------------------------------------
// Backend
$router->route('/backend/(?P<prefix>profile|event|channel|customer|generic|pm|comingsoon|ticket|messenger)/?', function(Request $request, Response $response) use ($sessionUser) {
    $prefix = $request->getAttribute("prefix");
    $response->render("app/model/$prefix.php",['sessionUser' => $sessionUser]);
});

// Footer 

// About
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?about/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $response->renderData($templates->render('view::about', ['lang' => $lang, 'sessionUser' => $sessionUser]));    
});

// Press
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?press/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $response->renderData($templates->render('view::press', ['lang' => $lang, 'sessionUser' => $sessionUser]));    
});

// Careers
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?careers/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $response->renderData($templates->render('view::careers', ['lang' => $lang, 'sessionUser' => $sessionUser]));    
});

// Terms of use
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?termsofuse/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $response->renderData($templates->render('view::termsofuse', ['lang' => $lang, 'sessionUser' => $sessionUser]));    
});
// Contact Us
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?contactus/?', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $response->renderData($templates->render('view::contactus', ['lang' => $lang, 'sessionUser' => $sessionUser]));
});








$router->route('/tpl/social_template', function(Request $request, Response $response) {    
    $response->render('app/view/templates/social_template.html'); 
});
// Data Json
$router->route('/data/tags', function(Request $request, Response $response){        
    $response->renderFile('data/tags.json.php', $response::APPLICATION_JSON);
});
// Calendar Events Feed
$router->route('/data/eventscalfeed', function(Request $request, Response $response) use ($sessionUser) {     
    $params = $request->getParameters();
    $response->render('data/eventscalfeed.json.php',['sessionUser' => $sessionUser, 'params' => $params], $response::APPLICATION_JSON);
});
// User Feeds
$router->route('/data/feeds/v1/', function(Request $request, Response $response) use ($sessionUser) {     
    $params = $request->getParameters();
    $response->render('data/user_feeds.json.php',['sessionUser' => $sessionUser, 'params' => $params], $response::APPLICATION_JSON);
});
//##################################################################################################################
// Mobile Events API
$router->route('/data/e/v1/', function(Request $request, Response $response) use ($sessionUser) {     
    $params = $request->getParameters();
    $response->render('data/m_events.json.php',['sessionUser' => $sessionUser, 'params' => $params],$response::APPLICATION_JSON);
});

//##################################################################################################################
// ICS Calendar
$router->route('/(?:(?P<lang>[a-z]{2}-[A-Z]{2})/)?calendar/ics/(?P<name>[\w+-]*)', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    //$lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $name = $request->getAttribute("name");
    $response->renderData($templates->render('view::ics', ['sessionUser' => $sessionUser ,'urllink' => $name]));
});
//##################################################################################################################
//##################################################################################################################
// Errors
$router->setErrorPage('404', function(Request $request, Response $response) use ($templates,$sessionUser,$userLanguage) {
    $lang = $userLanguage->parseLang($request->getAttribute("lang"));
    $response->renderData($templates->render('view::404',['lang' => $lang, 'sessionUser' => $sessionUser]));    
});
//##################################################################################################################
// test/tinode/
//$router->route('/test/tinode', function(Request $request, Response $response) {
//    $response->render("test/tinode/index.html");
//});
//$router->route('/nic/update', function(Request $request, Response $response) {
//    $params = $request->getParameters();
//    $response->render("test/nic.php", ['params' => $params]);
//});

//.well-known/acme-challenge/wBFPtjBEFlx8nd1knMF-aNSFknHd9Wpw4G-9hAWg6pE
//$router->route('/\.well-known/acme-challenge/(?P<challenge>.*)', function(Request $request, Response $response) {
//    $challenge = $request->getAttribute("challenge");
//    exit(var_dump($challenge));
//    $response->render(".well-known/acme-challenge/$challenge");
//});
$url_sanitized = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
$router->execute($url_sanitized);
exit(0);