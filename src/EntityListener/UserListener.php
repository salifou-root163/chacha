<?php

namespace App\EntityListener;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener{

    private UserPasswordHasherInterface $hasher;
    public function __construct( UserPasswordHasherInterface $hasher ){
        $this->hasher = $hasher;
    }

    public function prePersist(User $user){
        $this->hashPassword($user);
    }

    public function preUpdate(User $user){
        $this->hashPassword($user);
    }

    public function hashPassword(User $user){

        if ($user->getPlainPassword() === null ){
            return;
        }

        $user->setPassword(
            $this->hasher->hashPassword(  
                $user,
                $user->getPlainPassword()
            )
        );
        

    }
}