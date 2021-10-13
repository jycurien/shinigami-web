<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 20/12/2018
 * Time: 11:14
 */

namespace App\DataFixtures;


use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class ArticleFixtures extends Fixture /* implements DependentFixtureInterface */
{
    public function load(ObjectManager $manager)
    {
        // Get input data for articles from yaml file
        $data = Yaml::parseFile(__DIR__.'/articlesData.yaml');

        foreach ($data as $key => $articleData) {
            $article = new Article();
            $article->setTitle($articleData['title']);
            $article->setSlug($articleData['title']);
            $article->setContent($articleData['content']);
            $createdAt = (new \DateTimeImmutable())->setTimestamp(rand(time() - (60 * 24 * 3600), time()));
            $article->setCreatedAt($createdAt);
            $article->setPicture($key.'.jpg');
            $article->setSlider($key != 1 );
            // TODO
//            $article->setAuthor($this->getReference('admin'));

            $manager->persist($article);
        }

        $manager->flush();
    }

//    /**
//     * @return array
//     */
//    public function getDependencies()
//    {
//        return [
//            UserFixtures::class,
//        ];
//    }
}

