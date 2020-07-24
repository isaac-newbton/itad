<?php
namespace App\Controller;

use App\Repository\AdulterantRepository;
use App\Repository\ArticleRepository;
use App\Repository\CountryRepository;
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
	public function dashboard(){
		return $this->redirectToRoute("countries");
		return $this->render("dashboard/dashboard.html.twig", ['bodyClass'=>'dashboard']);
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
			'articles'=>$articleRepository->search($term),
			'publications'=>$publicationRepository->search($term),
			'presentations'=>$presentationRepository->search($term)
		]);
	}
}