<?php
namespace App\Controller;

use App\Entity\Laboratory;
use App\Form\LaboratoryType;
use App\Repository\LaboratoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LaboratoryController extends AbstractController{

	/**
	 * @Route("/laboratories", name="laboratories")
	 */
	function laboratories(LaboratoryRepository $laboratoryRepository){
		$laboratories = $laboratoryRepository->findAll();
		return $this->render("dashboard/laboratories/list.html.twig", ['bodyClass'=>'laboratories', 'laboratories'=>$laboratories]);
	}

	/**
	 * @Route("/laboratory/{uuid}", name="laboratory")
	 */
	function laboratory(string $uuid, LaboratoryRepository $laboratoryRepository){
		$laboratory = $laboratoryRepository->findOneByEncodedUuid($uuid);
		if(!$laboratory){
			return $this->redirectToRoute("laboratories");
		}

		return $this->render("dashboard/laboratories/view.html.twig", ['bodyClass'=>'laboratory', 'laboratory'=>$laboratory]);
	}

	/**
	 * @Route("/laboratories/add", name="add_laboratory")
	 */
	function add(Request $request){
		$laboratory = new Laboratory();
		$form = $this->createForm(LaboratoryType::class, $laboratory);

		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$laboratory = $form->getData();

			$manager = $this->getDoctrine()->getManager();
			$manager->persist($laboratory);
			$manager->flush();

			return $this->redirectToRoute('laboratories');
		}

		return $this->render("dashboard/laboratories/add.html.twig", ['bodyClass'=>'add_laboratory', 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/laboratory/{uuid}/delete", name="delete_laboratory")
	 */
	function delete(string $uuid, LaboratoryRepository $laboratoryRepository){
		$laboratory = $laboratoryRepository->findOneByEncodedUuid($uuid);
		if($laboratory){
			$manager = $this->getDoctrine()->getManager();
			$manager->remove($laboratory);
			$manager->flush();
		}

		return $this->redirectToRoute("laboratories");
	}
}