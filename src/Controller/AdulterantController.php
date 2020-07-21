<?php
namespace App\Controller;

use App\Doctrine\UuidEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AdulterantType;
use App\Repository\AdulterantRepository;

class AdulterantController extends AbstractController{
	/**
	 * @Route("/adulterants/{letter}", name="adulterants")
	 */
	public function adulterants(string $letter = "A"){
		$letter = substr($letter, 0, 1);
		$letters = "abcdefghijklmnopqrstuvwxyz";
		$allowedLetters = [];
		for ($i=0; $i < strlen($letters); $i++) {
			$allowedLetters[] = strtoupper($letters[$i]);
		}
		if(!in_array($letter, $allowedLetters)){
			$letter = 'A';
		}
		return $this->render("dashboard/adulterants/list.html.twig", ['bodyClass'=>'adulterants', 'activeLetter'=>strtoupper($letter)]);
	}

	/**
	 * @Route("/adulterants/edit/{encodedUuid}", name="edit_adulterant")
	 */
	public function edit(string $encodedUuid = null, UuidEncoder $encoder, AdulterantRepository $adulterantRepository, Request $request){
		if($encodedUuid && $adulterant = $adulterantRepository->findOneByEncodedUuid($encodedUuid)){
			//edit existing
		}else{
			if(!$encodedUuid){
				//create new
			}else{
				//not found
			}
		}
	}
}