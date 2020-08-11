<?php

namespace App\Controller;

use App\Doctrine\UuidEncoder;
use App\Entity\User;
use App\Form\UserEmailType;
use App\Form\UserPasswordType;
use App\Form\UserRoleType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController{
	/**
	 * @Route("/users", name="users")
	 */
	public function users(UserRepository $userRepository){
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		$users = $userRepository->findAll();
		return $this->render('dashboard/users/list.html.twig', ['bodyClass'=>'users_list', 'users'=>$users]);
	}

	/**
	 * @Route("/users/add", name="add_user")
	 */
	public function addUser(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder){
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		$user = new User();
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$user = $form->getData();
			$user->setRoles([$form->get('roles')->getData()]);
			$user->setPassword($passwordEncoder->encodePassword($user, $form->get('password')->getData()));
			$manager = $this->getDoctrine()->getManager();
			$manager->persist($user);
			$manager->flush();
			return $this->redirectToRoute('users');
		}
		return $this->render('dashboard/users/add.html.twig', ['bodyClass'=>'add_user', 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/user/{id}/edit/{field}", name="edit_user")
	 */
	public function editUser(int $id, string $field, UuidEncoder $uuidEncoder, Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder){
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		$user = $userRepository->find($id);
		$formTypes = [
			'email'=>UserEmailType::class,
			'password'=>UserPasswordType::class,
			'role'=>UserRoleType::class
		];
		if(!$user || !in_array($field, array_keys($formTypes))){
			return $this->redirectToRoute('users');
		}

		$form = $this->createForm($formTypes[$field], $user);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$user = $form->getData();
			if('role'==$field)	$user->setRoles([$form->get('roles')->getData()]);
			if('password'==$field) $user->setPassword($passwordEncoder->encodePassword($user, $form->get('password')->getData()));
			$manager = $this->getDoctrine()->getManager();
			$manager->persist($user);
			$manager->flush();
			return $this->redirectToRoute('users');
		}
		return $this->render('dashboard/users/edit.html.twig', ['bodyClass'=>'edit_user', 'user'=>$user, 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/user/{id}/delete", name="delete_user")
	 */
	public function deleteUser(int $id, UuidEncoder $uuidEncoder, Request $request, UserRepository $userRepository){
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		$user = $userRepository->find($id);
		if($user && $user!=$this->getUser() && !in_array('ROLE_DEVELOPER', $user->getRoles())){
			$manager = $this->getDoctrine()->getManager();
			$manager->remove($user);
			$manager->flush();
		}
		return $this->redirectToRoute('users');
	}

	/**
	 * @Route("/user/profile", name="user_profile")
	 */
	public function userProfile(){
		if(!$this->getUser()){
			return $this->redirectToRoute('dashboard');
		}
		return $this->render('dashboard/users/profile.html.twig', ['bodyClass'=>'profile']);
	}

	/**
	 * @Route("/user/profile/email", name="change_email")
	 */
	public function changeEmail(Request $request){
		$user = $this->getUser();
		if(!$user){
			return $this->redirectToRoute('dashboard');
		}
		$form = $this->createForm(UserEmailType::class, $user);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$user = $form->getData();
			$manager = $this->getDoctrine()->getManager();
			$manager->persist($user);
			$manager->flush();
			return $this->redirectToRoute('user_profile');
		}
		return $this->render('dashboard/users/change_email.html.twig', ['bodyClass'=>'profile_email', 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/user/profile/password", name="change_password")
	 */
	public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder){
		$user = $this->getUser();
		if(!$user){
			return $this->redirectToRoute('dashboard');
		}
		$form = $this->createForm(UserPasswordType::class, $user);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$user->setPassword($passwordEncoder->encodePassword($user, $form->get('password')->getData()));
			$manager = $this->getDoctrine()->getManager();
			$manager->persist($user);
			$manager->flush();
			return $this->redirectToRoute('user_profile');
		}
		return $this->render('dashboard/users/change_password.html.twig', ['bodyClass'=>'profile_password', 'form'=>$form->createView()]);
	}
}