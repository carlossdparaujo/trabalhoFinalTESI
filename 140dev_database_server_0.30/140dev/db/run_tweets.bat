START /B php get_tweets.php
START /B php parse_tweets.php
START /B php write_tweets.php  > logWriteTweets.txt
START /B python TweetProcessor > logTweetProcessor.txt
START /B php read_entities.php > logReadEntities.txt

