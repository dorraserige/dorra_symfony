<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }




   


    /**
     * Recherche un livre par sa référence
     */
    public function searchBookByRef(string $ref): ?Book
    {
        return $this->createQueryBuilder('b')
            ->where('b.ref = :ref')
            ->setParameter('ref', $ref)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // Si vous voulez aussi une méthode qui retourne tous les livres correspondants (au cas où)
    public function findAllByRef(string $ref): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.ref LIKE :ref')
            ->setParameter('ref', '%' . $ref . '%')
            ->getQuery()
            ->getResult();
    }

    ///////////////


      // Question 1: Afficher le nombre des livres dont la catégorie est « Romance »
    public function countBooksByCategory(string $category): int
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->where('b.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Question 2: Afficher la liste des livres publiés entre deux dates
    public function findBooksBetweenDates(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.publicationYear BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('b.publicationYear', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Méthode alternative en DQL pure pour la question 1
    public function countRomanceBooksDQL(): int
    {
        $dql = 'SELECT COUNT(b.id) FROM App\Entity\Book b WHERE b.category = :category';
        
        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('category', 'Romance');
            
        return $query->getSingleScalarResult();
    }

    // Méthode alternative en DQL pure pour la question 2
    public function findBooksBetweenDatesDQL(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        $dql = 'SELECT b FROM App\Entity\Book b 
                WHERE b.publicationYear BETWEEN :startDate AND :endDate 
                ORDER BY b.publicationYear ASC';
        
        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);
            
        return $query->getResult();
    }
}


