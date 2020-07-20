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
	 * @Route("/dashboard/adulterant/edit/{encodedUuid}", name="edit_adulterant")
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