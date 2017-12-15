<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\NewsItem;

class GetBBCTechNewsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('get:bbc-tech-news')
            ->setDescription('Read BBC Technology RRS')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $newsSource = $em->getRepository('AppBundle:NewsSource')->findOneByType('bbc-tech');

        $feedIo   = $this->getContainer()->get('feedio');
        $fromDate = new \DateTime(date("Y-m-d H:i:s", strtotime('-24 hours')));
        $url      = 'http://feeds.bbci.co.uk/news/technology/rss.xml';

        $feeds = $feedIo->readSince($url, $fromDate)->getFeed();

        $i = 1;
        foreach ($feeds as $key => $feed) {
            $output->writeln('Processing ' . $i . ' of ' . count($feeds));

            $newsItem = new NewsItem();
            $newsItem
                ->setSource($newsSource)
                ->setTitle($feed->getTitle())
                ->setSummary($feed->getDescription())
                ->setUrl($feed->getLink())
                ->setPublishedAt($feed->getLastModified())
            ;
            $em->persist($newsItem);
            $em->flush();

            $i++;
        }
    }
}
