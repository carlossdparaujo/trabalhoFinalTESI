<?php
/**
* 140dev_config.php
* Constants for the entire 140dev Twitter framework
* You MUST modify these to match your server setup when installing the framework
* 
* Latest copy of this code: http://140dev.com/free-twitter-api-source-code-library/
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.30
*/

// OAuth settings for connecting to the Twitter streaming API
// Fill in the values for a valid Twitter app
define('TWITTER_CONSUMER_KEY','3tRxHKM64mAF9PCKVaMWMcIQ4');
define('TWITTER_CONSUMER_SECRET','OfHgGGGevVLTocdEjVbFIw39N9nnccgmsDjtb7hRhAyNfDTNGJ');
define('OAUTH_TOKEN','2466393715-a29fuoroYxqsOy7oDGERYDZWCQ3SqmbXTHEm4ro');
define('OAUTH_SECRET','puAB4x1dPgh02MvFlhHKZAIKWESODdEkAn86cmMplbshg');

// Settings for monitor_tweets.php
define('TWEET_ERROR_INTERVAL',10);
// Fill in the email address for error messages
define('TWEET_ERROR_ADDRESS','gabriellsf@gmail.com');
?>