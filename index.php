<?php
require_once __DIR__ . '/vendor/autoload.php';

use Medoo\Medoo;
use Core\BannedIP;

ini_set('session.gc_maxlifetime', 10*60);

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader, []);

$sklonovatRokFilter = new \Twig\TwigFilter('sklonovat_rok', function ($value) {
    if ($value == 1) {
        return "$value rok";
    } elseif ($value >= 2 && $value <= 4) {
        return "$value roky";
    } else {
        return "$value let";
    }
});
$sklonovatMesicFilter = new \Twig\TwigFilter('sklonovat_mesic', function ($value) {
    if ($value == 1) {
        return "$value měsíc";
    } elseif ($value >= 2 && $value <= 4) {
        return "$value měsíce";
    } else {
        return "$value měsíců";
    }
});

$twig->addFilter($sklonovatRokFilter);
$twig->addFilter($sklonovatMesicFilter);

$isAdministratorFunction = new \Twig\TwigFunction('is_admin', function ($user) {
    if($user == null) return false;
    foreach ($user["role"] as $key => $value) {
        if($value["role_id"] == 1) return true;
    }
    return false;
});
$isPecovatelFunction = new \Twig\TwigFunction('is_pecovatel', function ($user) {
    if($user == null) return false;
    foreach ($user["role"] as $key => $value) {
        if($value["role_id"] == 1) return true;
        if($value["role_id"] == 2) return true;
    }
    return false;
});
$isVeterinarFunction = new \Twig\TwigFunction('is_vet', function ($user) {
    if($user == null) return false;
    foreach ($user["role"] as $key => $value) {
        if($value["role_id"] == 1) return true;
        if($value["role_id"] == 3) return true;
    }
    return false;
});
$isDobrovolnikFunction = new \Twig\TwigFunction('is_dobrovolnik', function ($user) {
    if($user == null) return false;
    foreach ($user["role"] as $key => $value) {
        if($value["role_id"] == 1) return true;
        if($value["role_id"] == 4) return true;
    }
    return false;
});
$twig->addFunction($isAdministratorFunction);
$twig->addFunction($isPecovatelFunction);
$twig->addFunction($isVeterinarFunction);
$twig->addFunction($isDobrovolnikFunction);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//Connect to DB
$db = new Medoo([
    'type' => 'mysql',
    'host' => 'localhost',
    'database' => 'iis_project',
    'username' => 'root',
    'password' => ''
]);

$router = new AltoRouter();

//Load every single .php file in "controllers" folder
foreach (rglob("controllers/*.php") as $filename)
{
    include $filename;
}

//Match request
$match = $router->match();

if( is_array($match) && is_callable( $match['target'] ) ) {
    //Calling callback from correct route
    call_user_func_array( $match['target'], $match['params'] );
} else {
	//No route was found
    display_404();
}


function httpPost($url, $data)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}


//Recursive glob
function rglob($pattern, $flags = 0) {
    $files = glob($pattern, $flags); 
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge(
            [],
            ...[$files, rglob($dir . "/" . basename($pattern), $flags)]
        );
    }
    return $files;
}

//Displays 404 page and exits
function display_404(){
    global $twig;
    echo $twig->render('404.twig',[]);
    exit();
}

function admin_kick(){
    if(!isset($_SESSION["user_email"])){
        header("Location: /");
    }
}

?>