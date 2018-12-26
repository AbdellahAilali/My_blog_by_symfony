<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UserManager
 * @package App\Manager
 */
class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string             $id
     * @param string             $firstName
     * @param string             $lastName
     * @param \DateTimeInterface $birthday
     */
    public function createUser(string $id, string $firstName, string $lastName, \DateTimeInterface $birthday)
    {
        $user = new User($id, $firstName, $lastName, $birthday);

        $this->entityManager->persist($user);

        $this->entityManager->flush();
    }

    /**entityManager
     * @param string             $id
     * @param string             $firstName
     * @param string             $lastName
     * @param \DateTimeInterface $birthDay
     */
    public function modifyUser(string $id, string $firstName, string $lastName, \DateTimeInterface $birthDay)
    {
        /** @var User $user */
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['id' => $id]);

        $user->update($firstName, $lastName, $birthDay);

        $this->entityManager->persist($user);

        $this->entityManager->flush();
    }

    /**
     * @param string $id
     */
    public function deleteUser(string $id)
    {
        /**@var User $user */

        $user = $this->entityManager
            ->getRepository(User::class)
            ->find($id);

        if (empty($user)) {
            throw new NotFoundHttpException("error while deleting the user, the user is empty ");
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    /**
     * @param string $id
     * @return array
     */
    public function loadUser(string $id)
    {
        /**@var User $user */
        $user = $this->entityManager
            ->getRepository(User::class)
            ->find($id);

        if (empty($user)) {
            throw new NotFoundHttpException('User not found');
        }

        $result = [];
        $result["firstname"] = $user->getFirstname();
        $result["lastname"] = $user->getLastname();

        $tabComments = [];
        foreach ($user->getComments() as $comment) {
            $tabComments = [
                "title" => $comment->getTitle(),
                "description" => $comment->getDescription()
            ];
        }
        $result["comments"] = $tabComments;

        return $result;
    }

    public function loadAllUser()
    {
        /** @var User[] $users */
        $users = $this->entityManager
            ->getRepository(User::class)
            ->findAll();

        if (empty($users)) {
            throw new NotFoundHttpException('Users not found');
        }
        $tabUser=[];
        foreach ($users as $key => $user) {

            $tabUser[$key] = [
                "id" => $user->getId(),
                "firstName" => $user->getFirstname(),
                "lastName" => $user->getLastname(),
                "birthday" => $user->getBirthday()->format('Y-m-d'),
            ];

            foreach ($user->getComments() as $comment) {
                $tabUser[$key]['comments'][] = [
                    "title" => $comment->getTitle(),
                    "comment" => $comment->getDescription()
                ];
            }
        }

        return $tabUser;
    }


}

























































