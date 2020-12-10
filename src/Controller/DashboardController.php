<?php
namespace App\Controller;

use App\Entity\MediaFile;
use App\Form\MediaFileType;
use App\Repository\AdulterantRepository;
use App\Repository\ArticleRepository;
use App\Repository\CountryRepository;
use App\Repository\MediaFileRepository;
use App\Repository\PresentationRepository;
use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController{
	/**
	 * @Route("/dashboard", name="dashboard")
	 */
	public function dashboard(CountryRepository $countryRepository, Request $request){
		$user = $this->getUser();
        $user->setLastLoginDt(new \DateTime());
		$user->setLastLoginIP($request->getClientIp());
		$manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();
		return $this->render("dashboard/dashboard.html.twig", ['bodyClass'=>'dashboard']);
	}

	/**
	 * @Route("/map", name="map")
	 */
	public function map(CountryRepository $countryRepository){
		$googleApiKey = $_ENV['GOOGLE_API_KEY'];
		if(empty($googleApiKey) || ''==trim($googleApiKey)){
			return $this->redirectToRoute("countries");
		}

		$countries = $countryRepository->findAll();
		$countryData = [];

		if(!empty($countries)){
			foreach($countries as $country){
				$data = [
					'code'=>$country->getCode(),
					'name'=>$country->getName(),
					'profileUrl'=>$this->generateUrl('country', ['code'=>$country->getCode()]),
					'reports'=>$country->getYearlyReports()
				];
				$countryData["{$country->getUuid()}"] = $data;
			}
		}

		return $this->render("dashboard/map.html.twig", ['bodyClass'=>'world_map', 'googleApiKey'=>$googleApiKey, 'countries'=>$countries, 'countryData'=>$countryData]);
	}

	/**
	 * @Route("/search", name="search")
	 */
	public function search(Request $request, ArticleRepository $articleRepository, AdulterantRepository $adulterantRepository, CountryRepository $countryRepository, PublicationRepository $publicationRepository, PresentationRepository $presentationRepository){
		$term = $request->request->get('term');
		if(''==trim($term)){
			return $this->redirectToRoute('dashboard');
		}
		return $this->render("dashboard/search.html.twig", [
			'bodyClass'=>'search',
			'searchTerm'=>$term,
			'adulterants'=>$adulterantRepository->search($term),
			'countries'=>$countryRepository->search($term),
			'publications'=>$publicationRepository->search($term),
			'presentations'=>$presentationRepository->search($term)
		]);
	}

	/**
	 * @Route("/file/{uuid}/edit-name", name="edit_file_name")
	 */
	public function editFilename(string $uuid, MediaFileRepository $mediaFileRepository, Request $request){
		/**
		 * @var MediaFile|null
		 */
		$mediaFile = $mediaFileRepository->findOneByEncodedUuid($uuid);
		if(!$mediaFile){
			return $this->redirectToRoute('dashboard');
		}

		$form = $this->createForm(MediaFileType::class, $mediaFile);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$mediaFile = $form->getData();
			$manager = $this->getDoctrine()->getManager();
			$manager->persist($mediaFile);
			$manager->flush();
			return $this->redirectToRoute('dashboard');
		}

		return $this->render("dashboard/edit_file_name.html.twig", ['bodyClass'=>'edit_file_name', 'file'=>$mediaFile, 'form'=>$form->createView()]);
	}
}