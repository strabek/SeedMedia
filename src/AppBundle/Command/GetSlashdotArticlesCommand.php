<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use AppBundle\Entity\NewsItem;

class GetSlashdotArticlesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('get:slashdot-articles')
            ->setDescription('Read Slashdot.org articles')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $newsSource = $em->getRepository('AppBundle:NewsSource')->findOneByType('slashdot');

        $client = new Client();
        $crawler = $client->request('GET', 'https://slashdot.org/');

        $articles = $crawler->filter('article.article');
        $this->results = array();

        try {
            $articles->each(function($node, $i) {
                $a = array();

                $d = str_replace(array('on ','@'), '', $node->filter('header')->filter('div')->last()->filter('time')->attr('datetime'));
                $t = $node->filter('header')->filter('h2')->filter('span')->children()->filter('a')->extract(array('_text', 'href'));
                $a['title']       = $t[0][0];
                $a['url']         = $t[1][1];
                $a['publishedAt'] = \DateTime::createFromFormat("l F d, Y h:iA", $d);
                $a['summary']     = $node->filter('div')->filter('div')->filter('i')->text();

                array_push($this->results, $a);
            });
        } catch (Exception $e) {
            
        }

        foreach ($this->results as $article) {
            $newsItem = new NewsItem();
            $newsItem
                ->setSource($newsSource)
                ->setTitle($article['title'])
                ->setUrl($article['url'])
                ->setSummary($article['summary'])
                ->setPublishedAt($article['publishedAt'])
            ;
            $em->persist($newsItem);
        }

        $em->flush();

        $output->writeln('Finished');
    }

}
