<?php
set_time_limit (0);
ini_set('display_errors', 1);

function __autoload($classname) 
{
    $filename = "library/". strtolower($classname) .".php";
    require_once($filename);
}

function _iscurl()
{
    if(function_exists('curl_version'))
      return true;
    else 
      return false;
}

if(!_iscurl())
    die("Curl extension doesn't enable on this server!");

//CLI/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//get args count
if($argc != 2)
    die('Site url didnt set!');

//set params keys
$params = array(
    'help'      => 'help',
    'u:'      => 'url:'
);

$options = getopt(implode('', array_keys($params)), $params);
if(isset($options['url']) || isset($options['u']))
{
    print_r($options['url']);die();
}
if(isset($options['help']))
{
    $help = "
            Options:
                        --help      Show this message
                    -u  --url       url
            Example:
                    php index.php http:://test.com.ua
            ";
    die($help);
}

die;
//CLI/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//main timer
$timer = new Timer();
$timer->start('total');

//form sent
if(isset($_POST['hidden']) == 'form_sent')
{
    if($_POST['url'] == '')
        echo "URL isn't correct!";
    else
    {
        $crawler = new Crawler($_POST['url']);

        //execute crawling
        $crawler->crawling();
        
        //show result
        $crawler->show();
    }
}

//main timer
$timer->end('total');

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Crawler Bot</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <div class="content">
            <form action="" method="POST">
                <input type="hidden" name="hidden" value="form_sent" />
                <input type="text" name="url" value="<?= (isset($_POST['url']) ? $_POST['url'] : '') ?>" />
                <input type="submit" name="submit" value="Start" />
            </form>
        </div>
    </body>
</html>

<?= $timer->results(); ?>