<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void  
    {
        $categorie1 = new Categorie();
        $categorie1->setNom("Science fiction");
        $manager->persist($categorie1);

        $categorie2 = new Categorie();
        $categorie2->setNom("Policier");
        $manager->persist($categorie2);

        $categorie3 = new Categorie();
        $categorie3->setNom("Jeunesse");
        $manager->persist($categorie3);

        $categorie4 = new Categorie();
        $categorie4->setNom("Fantastique");
        $manager->persist($categorie4);

        $categorie5 = new Categorie();
        $categorie5->setNom("Thriller");
        $manager->persist($categorie5);

        $categorie6 = new Categorie();
        $categorie6->setNom("Poésie");
        $manager->persist($categorie6);

        $categorie7 = new Categorie();
        $categorie7->setNom("Biopic");
        $manager->persist($categorie7);

        $categorie8 = new Categorie();
        $categorie8->setNom("Aventure");
        $manager->persist($categorie8);

        $categorie9 = new Categorie();
        $categorie9->setNom("Horreur");
        $manager->persist($categorie9);

        $manager->flush();
    }
}
