<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;
use AppBundle\Entity\NewsItem;

class GetHackerNewsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('get:hacker-news')
            ->setDescription('Get Hacker News from API')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client();
        $em = $this->getContainer()->get('doctrine')->getManager();

        $newsSource = $em->getRepository('AppBundle:NewsSource')->find(1);

        // Read all new stories
        $newStoriesRaw = $client->request('GET', 'https://hacker-news.firebaseio.com/v0/newstories.json');

        $newStories = json_decode($newStoriesRaw->getBody()->getContents());

        foreach ($newStories as $key => $storyId) {
            // Read story
            $storyRaw = $client->request('GET', 'https://hacker-news.firebaseio.com/v0/item/' . $storyId . '.json?print=pretty');
            $story = json_decode($storyRaw->getBody()->getContents(), false);
            $output->writeln('Processing ' . $key . ' of ' . count($newStories) . ' story id: ' . $story->id);

            $storyTime = new \DateTime();

            if (isset($story->time)) {
                $storyTime->setTimestamp($story->time);
            }

            $storyTitle = isset($story->title) ? $story->title : null;
            $storyUrl   = isset($story->url) ? $story->url : null;

            // Save story to DB
            $newsItem = new NewsItem();
            $newsItem
                ->setSource($newsSource)
                ->setTitle($storyTitle)
                ->setUrl($storyUrl)
                ->setPublishedAt($storyTime)
            ;
            $em->persist($newsItem);
            $em->flush();
        }
    }
}
