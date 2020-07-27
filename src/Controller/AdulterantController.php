<?php
namespace App\Controller;

use App\Doctrine\UuidEncoder;
use App\Entity\Adulterant;
use App\Form\AdulterantThumbnailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AdulterantType;
use App\Repository\AdulterantRepository;
use App\Service\FileUpload;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdulterantController extends AbstractController{
	/**
	 * @Route("/adulterants/{letter}", name="adulterants", requirements={"letter"="\w"})
	 */
	public function adulterants(string $letter = "A", AdulterantRepository $adulterantRepository){
		$letter = substr($letter, 0, 1);
		$letters = "abcdefghijklmnopqrstuvwxyz";
		$allowedLetters = [];
		for ($i=0; $i < strlen($letters); $i++) {
			$allowedLetters[] = strtoupper($letters[$i]);
		}
		if(!in_array($letter, $allowedLetters)){
			$letter = 'A';
		}
		$adulterants = $adulterantRepository->findByFirstLetter($letter);
		return $this->render("dashboard/adulterants/list.html.twig", ['bodyClass'=>'adulterants', 'adulterants'=>$adulterants, 'activeLetter'=>strtoupper($letter)]);
	}

	/**
	 * @Route("/adulterant/{uuid}", name="adulterant")
	 */
	public function adulterant(string $uuid, AdulterantRepository $adulterantRepository){
		$adulterant = $adulterantRepository->findOneByEncodedUuid($uuid);
		if(!$adulterant){
			return $this->redirectToRoute("adulterants");
		}

		return $this->render("dashboard/adulterants/view.html.twig", ['bodyClass'=>'adulterant', 'adulterant'=>$adulterant]);
	}

	/**
	 * @Route("/adulterants/add", name="add_adulterant")
	 */
	public function add(Request $request){
		$adulterant = new Adulterant();
		$form = $this->createForm(AdulterantType::class, $adulterant);

		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$adulterant = $form->getData();
			$adulterant->setOccurrenceUsage(nl2br($adulterant->getOccurrenceUsage()));
			$adulterant->setPhysiologicalEffect(nl2br($adulterant->getPhysiologicalEffect()));

			$manager = $this->getDoctrine()->getManager();
			$manager->persist($adulterant);
			$manager->flush();

			return $this->redirectToRoute('adulterants', ['letter'=>substr($adulterant->getName(), 0, 1)]);
		}

		return $this->render("dashboard/adulterants/add.html.twig", ['bodyClass'=>'add_adulterant', 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/adulterant/{uuid}/delete", name="delete_adulterant")
	 */
	public function delete(string $uuid, AdulterantRepository $adulterantRepository){
		$adulterant = $adulterantRepository->findOneByEncodedUuid($uuid);
		$letter = 'A';
		if($adulterant){
			$letter = strtoupper(substr($adulterant->getName(), 0, 1));
			$manager = $this->getDoctrine()->getManager();
			$manager->remove($adulterant);
			$manager->flush();
		}

		return $this->redirectToRoute("adulterants", ['letter'=>$letter]);
	}

	/**
	 * @Route("/adulterant/{uuid}/thumbnail", name="add_adulterant_thumbnail")
	 */
	public function addThumbnail(string $uuid, Request $request, AdulterantRepository $adulterantRepository, UuidEncoder $uuidEncoder, FileUpload $fileUploadService){
		$adulterant = $adulterantRepository->findOneByEncodedUuid($uuid);
		if(!$adulterant){
			return $this->redirectToRoute('adulterants');
		}

		$form = $this->createForm(AdulterantThumbnailType::class, $adulterant);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			/**
			 * @var UploadedFile
			 */
			$file = $form->get('file')->getData();
			if($file){
				$manager = $this->getDoctrine()->getManager();
				$result = $fileUploadService->uploadToMediaFile($file, $manager);
				$adulterant->setThumbnail($result);
				$manager->persist($adulterant);
				$manager->flush();
			}

			return $this->redirectToRoute('adulterant', ['uuid'=>$uuidEncoder->encode($adulterant->getUuid())]);
		}

		return $this->render("dashboard/adulterants/add_thumbnail.html.twig", ['bodyClass'=>'add_adulterant_thumbnail', 'form'=>$form->createView(), 'adulterant'=>$adulterant]);
	}
}