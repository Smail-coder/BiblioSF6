<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function searchLivres(?string $query)
{
    // Si la recherche est vide, retourne un tableau vide
    if ($query === null || $query === '') {
        return [];
    }

    // Création d'une requête personnalisée avec QueryBuilder
    return $this->createQueryBuilder('l') // 'l' est l'alias pour 'livre'
        ->where('LOWER(l.titre) LIKE LOWER(:query)') // Recherche dans le titre
        ->orWhere('LOWER(l.auteur) LIKE LOWER(:query)') // Recherche dans l'auteur
        ->setParameter('query', '%'.$query.'%') // Les % permettent une recherche partielle
        ->getQuery() // Transforme en requête
        ->getResult(); // Exécute la requête et retourne les résultats
}
    //    /**
    //     * @return Livre[] Returns an array of Livre objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Livre
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
