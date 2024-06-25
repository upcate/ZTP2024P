<?php

/**
 * This test file is a part of project made as a part of the ZTP course completion.
 *
 * (c) Miłosz Świątek <milosz.swiatek@student.uj.edu.pl>
 */

namespace App\Repository;

use App\Entity\Admin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Admin>
 */
class AdminRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager Registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Admin::class);
    }// end __construct()

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     *
     * @param PasswordAuthenticatedUserInterface $user              Password Authenticated User Interface
     * @param string                             $newHashedPassword New hashed password
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Admin) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }// end upgradePassword()

    // **
    // * @return Admin[] Returns an array of Admin objects
    // */
    // public function findByExampleField($value): array
    // {
    // return $this->createQueryBuilder('a')
    // ->andWhere('a.exampleField = :val')
    // ->setParameter('val', $value)
    // ->orderBy('a.id', 'ASC')
    // ->setMaxResults(10)
    // ->getQuery()
    // ->getResult()
    // ;
    // }
    // public function findOneBySomeField($value): ?Admin
    // {
    // return $this->createQueryBuilder('a')
    // ->andWhere('a.exampleField = :val')
    // ->setParameter('val', $value)
    // ->getQuery()
    // ->getOneOrNullResult()
    // ;
    // }
}// end class
