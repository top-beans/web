<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManager,
        Application\API\Canonicals\Entity\User,
        Application\API\Repositories\Interfaces\IUsersRepository;

    class UsersRepository extends BaseRepository implements IUsersRepository {
        
        public function __construct(EntityManager $em) {
            parent::__construct($em);
        }

        public function find($username) {
            return $this->usersRepo->fetch($username);
        }
        
        public function findAll() {
            return $this->usersRepo->fetchAll();
        }

        public function addUser(User $user) {
            $this->usersRepo->add($user);
        }

        public function addOrUpdateUser(User $user) {
            $this->usersRepo->addOrUpdate($user);
        }

        public function updateUser(User $user, $oldPassword) {
            $oneRecord = $this->usersRepo->repository->findOneBy(
                array("username" => $user->getUsername(), "password" => $oldPassword)
            );

            if ($oneRecord != null) {
                $this->usersRepo->update($user);
            } else {
                throw new \Exception("Could not find matching record to update");
            }
        }
        
        public function deleteUser($username, $oldPassword) {
            $oneRecord = $this->usersRepo->repository->findOneBy(
                array("username" => $username, "password" => $oldPassword)
            );

            if ($oneRecord != null) {
                $total = $this->usersRepo->total();

                if ($total > 1) {
                    $this->usersRepo->delete($oneRecord);
                } else {
                    throw new \Exception("Delete was aborted because only one user is left");
                }
            } else {
                throw new \Exception("Could not find matching record to delete");
            }
        }

        public function incrementTries($username) {
            $entity = $this->usersRepo->fetch($username);
            
            if ($entity == null) {
                return;
            } else {
                $entity->setTries($entity->getTries() + 1);
                $this->usersRepo->update($entity);
            }
        }

        public function resetTriesAndLogin($username) {
            $entity = $this->usersRepo->fetch($username);
            
            if ($entity == null) {
                return;
            } else {
                $entity->setTries(0);
                $entity->setLastlogin(new \DateTime());
                $this->usersRepo->update($entity);
            }
        }
   }
}