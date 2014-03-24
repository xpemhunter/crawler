<?php
set_time_limit (0);
ini_set('display_errors', 1);
define('ISCLI', PHP_SAPI === 'cli'); 

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
if(ISCLI)
{
    //get args count
    if($argc != 2)
        die('Site url didnt set!');

    //set params keys
    $params = array(
        'help'      => 'help',
        'u:'      => 'url:'
    );

    //get params
    $_POST = getopt(implode('', array_keys($params)), $params);
    if(isset($_POST['help']))
    {
        $help = "
                Options:
                            --help      Show this message
                        -u  --url       url
                Example:
                        php index.php --url=http://test.com.ua
                ";
        die($help);
    }
}
//CLI/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//form sent
if(isset($_POST['url']))
{
    if($_POST['url'] == '')
        echo "URL isn't correct!";
    else
    {
        //main timer
        $timer = new Timer();
        $timer->start('total');

        $crawler = new Crawler($_POST['url']);

        //execute crawling
        $crawler->crawling();

        //show result
        $result = $crawler->show();

        if(!$result)
            die("Data not found!");
        
        //main timer
        $timer->end('total');
        echo $timer->results();

        die("Done");
    }
}

?>

<? if(!ISCLI): ?>
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
<? endif; ?>