<?php
/**
 * Created by JetBrains PhpStorm.
 * User: windows
 * Date: 27/11/14
 * Time: 00:35
 * To change this template use File | Settings | File Templates.
 */

require_once('140dev_config.php');
require_once('db_lib.php');
$oDB = new db;
$filename = 'entities.txt';
// This should run continuously as a background process

while (true) {

    $entities = file_get_contents ( $filename , FILE_TEXT);

    if($entities)
    {
        $entities = $entities;
        $entitiesObj = json_decode($entities);
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
            $fileWriteResult = file_put_contents( $filename, $tweetsString , FILE_APPEND | LOCK_EX | FILE_TEXT );
            if($fileWriteResult)
            {
                $oDB->select("DELETE FROM tweets ORDER BY tweet_id LIMIT 500");
                $oDB->select("DELETE FROM tweets_mentions");
                $oDB->select("DELETE FROM tweets_tags");
                $oDB->select("DELETE FROM tweets_urls");
                $oDB->select("DELETE FROM users");
            }
        }
        $writeTweet = false;
        $tweetsString = '';
    }
    sleep(25);
}