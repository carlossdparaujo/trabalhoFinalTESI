<?php
/**
 * Created by JetBrains PhpStorm.
 * User: windows
 * Date: 26/11/14
 * Time: 21:47
 * To change this template use File | Settings | File Templates.
 */

require_once('140dev_config.php');
require_once('db_lib.php');
$oDB = new db;
$filename = 'tweets.txt';
// This should run continuously as a background process
sleep(20);
// Process all new tweets
$query = 'SELECT tweet_text ' .
    'FROM tweets '.
    'ORDER BY tweet_id '.
    'LIMIT 0 , 500';
while (true) {
    $tweetsString = '';
    $writeTweet = false;

    $result = $oDB->select($query);
    while($row = mysqli_fetch_assoc($result)) {

        $writeTweet = true;
        $tweet_text = $row['tweet_text'];
        if(!empty($tweet_text)){
            $tweet_text = json_encode($tweet_text);
            $tweetsString .= '{"tweetText":'.$tweet_text.'},';
        }
    }

    if($writeTweet)
    {
        $tweetsString = substr($tweetsString,0,strlen($tweetsString)-1);
        $tweetsString = '{"tweet":['.$tweetsString."]}";


        $fileWriteResult = file_put_contents( $filename, $tweetsString , LOCK_EX | FILE_TEXT );
        if($fileWriteResult)
        {
            $oDB->select("DELETE FROM tweets ORDER BY tweet_id LIMIT 500");
            $oDB->select("DELETE FROM tweet_mentions");
            $oDB->select("DELETE FROM tweet_tags");
            $oDB->select("DELETE FROM tweet_urls");
            $oDB->select("DELETE FROM users");
        }
    }
    sleep(20);
}
