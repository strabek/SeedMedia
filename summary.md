I've chosen Symfony 3.4 as my main PHP framework for this project.

Hacker News - API (https://github.com/HackerNews/API)
I've created GetHackerNewsCommand to read new stories from HN API. Command can be run in CLI (get:hacker-news). It will read all new stories and will process each story as a separate API call and will add it to database.

BBC Technology News - RSS (http://feeds.bbci.co.uk/news/technology/rss.xml)
I used https://packagist.org/packages/debril/rss-atom-bundle to read and parse RSS feed.

Slashdot.org - DOM (https://slashdot.org/)
I will use Symfony Dom Crawler (http://symfony.com/doc/3.4/components/dom_crawler.html)

I've used Symfony framework as this is my main PHP framework, but if I would have to decide which technology to use I would use AWS LAmbda with Python, save all items to DynamoDB or SQS, this depends on business needs, and then process it and finally save it in AWS RDS.
Advantages of AWS solution:
1. Fast and scalable - AWS Lambda scales a lot faster than AWS Elastic Beanstalk
2. Cost effective - AWS Lambda runs only when I need it - in PHP solution I need a server to run the code. I could use a container as well.

All three news sources have different data structure and this should be taken into account while designing real life solution.