<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordReset{
	private $passwordEncoder;
	private $entityManager;

	public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
	{
		$this->passwordEncoder = $passwordEncoder;
		$this->entityManager = $entityManager;
	}

	public function resetUserPassword(User $user){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $password = '';
        for ($i = 0; $i < 6; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
		}
		$user->setPassword($this->passwordEncoder->encodePassword($user, $password));
		$this->entityManager->persist($user);
		$this->entityManager->flush();
		return $password;
	}
}