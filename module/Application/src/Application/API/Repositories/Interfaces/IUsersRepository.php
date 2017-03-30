<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\User;
    
    interface IUsersRepository {
        public function findAll();
        public function find($username);
        
        public function incrementTries($username);
        public function resetTriesAndLogin($username);
        
        public function addUser(User $user);
        public function updateUser(User $user, $oldPassword);
        public function addOrUpdateUser(User $user);
        public function deleteUser($username, $oldPassword);
    }
}

