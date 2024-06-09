<?php

/**
 * AdCategoryRepository.
 */

namespace App\Repository;

use App\Entity\AdCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AdCategoryRepository.
 *
 * @extends ServiceEntityRepository<AdCategory>
 *
 * @method AdCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdCategory[]    findAll()
 * @method AdCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdCategoryRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager Registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdCategory::class);
    }

    /**
     * Query all.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('adCategory.updatedAt', 'DESC');
    }

    /**
     * Add.
     *
     * @param AdCategory $entity AdCategory Entity
     * @param bool       $flush  Flush flag
     */
    public function add(AdCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove.
     *
     * @param AdCategory $entity AdCategory Entity
     * @param bool       $flush  Flush flag
     */
    public function remove(AdCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Save.
     *
     * @param AdCategory $adCategory AcCategory Entity
     */
    public function save(AdCategory $adCategory): void
    {
        $this->_em->persist($adCategory);
        $this->_em->flush();
    }

    /**
     * Delete.
     *
     * @param AdCategory $adCategory AdCategory Entity
     */
    public function delete(AdCategory $adCategory): void
    {
        $this->_em->remove($adCategory);
        $this->_em->flush();
    }

    /**
     * Get or create query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('adCategory');
    }

//    /**
//     * @return AdCategory[] Returns an array of AdCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AdCategory
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
