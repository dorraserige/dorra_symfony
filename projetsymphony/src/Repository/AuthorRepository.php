<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * Retourne tous les auteurs en utilisant le QueryBuilder.
     *
     * @return Author[]
     */
    public function showAllAuthorQB(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /*
    // Exemple : chercher par un champ spécifique
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findOneBySomeField($value): ?Author
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
    */







    /**
     * Récupère tous les auteurs triés par email (ordre alphabétique)
     */
    public function listAuthorByEmail(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.email', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche les auteurs par nombre de livres dans une plage
     */
    public function findByBookRange(int $min, int $max): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.nbBooks >= :min')
            ->andWhere('a.nbBooks <= :max')
            ->setParameter('min', $min)
            ->setParameter('max', $max)
            ->getQuery()
            ->getResult();
    }


    public function ShowAllAuthorDQL(): mixed{
        $query=$this->getEntityManager()
        ->createQuery(dql: 'SELECT a FROM App\Entity\Author a WHERE a.username LIKE ?1 ORDER By a.username ASC')
        ->setParameter(key: 'condition', value: '%a%')
        ->getResult();
        return $query;

    }
    





}







