<?php
namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryFlagType;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use App\Service\FileUpload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends AbstractController{

	/**
	 * @Route("/countries", name="countries")
	 */
	function countries(CountryRepository $countryRepository){
		$countries = $countryRepository->findAll();
		return $this->render("dashboard/countries/list.html.twig", ['bodyClass'=>'countries', 'countries'=>$countries]);
	}

	/**
	 * @Route("/country/{code}", name="country")
	 */
	function country(string $code = '', CountryRepository $countryRepository){
		$country = $countryRepository->findOneByCode($code);
		if(!$country){
			return $this->redirectToRoute("countries");
		}

		if(0==count($country->getYearlyReports())){
			return $this->render("dashboard/countries/country.html.twig", ['bodyClass'=>'country', 'country'=>$country]);
		}

		return $this->redirectToRoute('report', ['code'=>$country->getCode(), 'year'=>$country->getYearlyReports()[0]->getYear()]);
	}

	/**
	 * @Route("/countries/add", name="add_country")
	 */
	function add(Request $request){
		$country = new Country();
		$form = $this->createForm(CountryType::class, $country);

		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$country = $form->getData();

			$manager = $this->getDoctrine()->getManager();
			$manager->persist($country);
			$manager->flush();

			return $this->redirectToRoute('countries');
		}

		return $this->render("dashboard/countries/add.html.twig", ['bodyClass'=>'add_country', 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/country/{code}/delete", name="delete_country")
	 */
	function delete(string $code = '', CountryRepository $countryRepository){
		$country = $countryRepository->findOneByCode($code);
		if($country){
			$manager = $this->getDoctrine()->getManager();
			$manager->remove($country);
			$manager->flush();
		}

		return $this->redirectToRoute("countries");
	}

	/**
	 * @Route("/country/{code}/add-flag", name="add_flag")
	 */
	function addFlag(string $code, Request $request, CountryRepository $countryRepository, FileUpload $fileUploadService){
		$country = $countryRepository->findOneByCode($code);
		if(!$country){
			return $this->redirectToRoute("countries");
		}

		$form = $this->createForm(CountryFlagType::class, $country);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			/**
			 * @var UploadedFile
			 */
			$file = $form->get('file')->getData();
			if($file){
				$manager = $this->getDoctrine()->getManager();
				$result = $fileUploadService->uploadToMediaFile($file, $manager);
				$country->setFlag($result);
				$manager->persist($country);
				$manager->flush();
			}

			return $this->redirectToRoute('country', ['code'=>$country->getCode()]);
		}

		return $this->render("dashboard/countries/add_flag.html.twig", ['bodyClass'=>'country_add_flag', 'country'=>$country, 'form'=>$form->createView()]);
	}
}