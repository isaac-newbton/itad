<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResourcesController extends AbstractController{
	/**
	 * @Route("/resources", name="resources")
	 */
	public function resources(){
		return $this->render("dashboard/resources.html.twig", ['bodyClass'=>'resources']);
	}

	/**
	 * @Route("/resources/articles", name="resources_articles")
	 */
	public function articles(){
		return $this->render("dashboard/articles/list.html.twig", ['bodyClass'=>'articles']);
	}

	/**
	 * @Route("/resources/articles/view", name="view_article")
	 */
	public function article(){
		return $this->render("dashboard/articles/view.html.twig", ['bodyClass'=>'article']);
	}

	/**
	 * @Route("/resources/publications", name="resources_publications")
	 */
	public function publications(){
		return $this->render("dashboard/publications/list.html.twig", ['bodyClass'=>'publications']);
	}

	/**
	 * @Route("/resources/presentations", name="resources_presentations")
	 */
	public function presentations(){
		return $this->render("dashboard/presentations/list.html.twig", ['bodyClass'=>'presentations']);
	}
}