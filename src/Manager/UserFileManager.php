<?php
/**
 * Created by PhpStorm.
 * User: abdellah
 * Date: 02/11/18
 * Time: 12:01
 */

namespace App\Manager;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserFileManager
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

    public function create($path)
    {
        /** @var User[] $users */
        $users = $this->entityManager
            ->getRepository(User::class)
            ->findAll();

        $tabUser =[];
        foreach ($users as $key => $user) {

            $tabUser[$key] = [
                "id" => $user->getId(),
                "firstname" => $user->getFirstname(),
                "lastname" => $user->getLastname(),
                "birthday" => $user->getBirthday()->format('d-m-y'),
            ];
        }

        $delimiter = ';';
        $file_csv = fopen($path, 'w+');

        foreach ($tabUser as $field) {
            if (is_object($field))
                $field = (array)$field;
            fputcsv($file_csv, $field, $delimiter);
        }

        fclose($file_csv);

        return true;
    }
}
