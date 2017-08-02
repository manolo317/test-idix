<?php


namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Character;
use AppBundle\Entity\Film;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadFilm implements FixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface $container */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $films = $this->container->get('app.swapi')->getFilms();

        foreach ($films as $film) {
            // if the movie is not exist
            if (!$manager->getRepository('AppBundle:Film')->findBy(['title' => $film['title']])) {
                // create a new movie
                $newFilm = new Film();
                $newFilm
                    ->setTitle($film['title'])
                    ->setAbstract($film['abstract'])
                    ->setDate($film['date']);

                foreach ($film['characters'] as $character) {
                    // if the character is not exist
                    if (!$manager->getRepository('AppBundle:Character')->findBy(['name' => $character['name']])) {
                        // create and attach character to the movie
                        $newCharacter = new Character();
                        $newCharacter->setName($character['name']);
                        $newFilm->addCharacter($newCharacter);
                    }

                }

                $manager->persist($newFilm);
                $manager->flush();
            }

        }

    }
}