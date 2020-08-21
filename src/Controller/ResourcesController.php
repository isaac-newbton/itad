<?php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Presentation;
use App\Entity\Publication;
use App\Form\ArticleType;
use App\Form\PresentationType;
use App\Form\PublicationType;
use App\Repository\ArticleRepository;
use App\Repository\PresentationRepository;
use App\Repository\PublicationRepository;
use App\Service\FileUpload;
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
	 * @Route("/resources/articles", name="articles")
	 */
	public function articles(ArticleRepository $articleRepository){
		$articles = $articleRepository->findAll();
		return $this->render("dashboard/articles/list.html.twig", ['bodyClass'=>'articles', 'articles'=>$articles]);
	}

	/**
	 * @Route("/resources/articles/add", name="add_article")
	 */
	public function addArticle(Request $request){
		$article = new Article();
		$form = $this->createForm(ArticleType::class, $article);

		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$article = $form->getData();
			$manager = $this->getDoctrine()->getManager();
			$manager->persist($article);
			$manager->flush();

			return $this->redirectToRoute('articles');
		}

		return $this->render("dashboard/articles/add.html.twig", ['bodyClass'=>'add_article', 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/resources/article/{uuid}", name="article")
	 */
	public function article(string $uuid, ArticleRepository $articleRepository){
		$article = $articleRepository->findOneByEncodedUuid($uuid);
		if(!$article){
			return $this->redirectToRoute('articles');
		}
		return $this->render("dashboard/articles/view.html.twig", ['bodyClass'=>'article', 'article'=>$article]);
	}

	/**
	 * @Route("/resources/article/{uuid}/edit", name="edit_article")
	 */
	public function editArticle(string $uuid, Request $request, ArticleRepository $articleRepository){
		$article = $articleRepository->findOneByEncodedUuid($uuid);
		if(!$article){
			return $this->redirectToRoute('articles');
		}
		$form = $this->createForm(ArticleType::class, $article);

		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$article = $form->getData();
			$manager = $this->getDoctrine()->getManager();
			$manager->persist($article);
			$manager->flush();

			return $this->redirectToRoute(($article->getExternalUrl() ? 'articles' : 'article'), ['uuid'=>$uuid] );
		}

		return $this->render("dashboard/articles/add.html.twig", ['bodyClass'=>'add_article', 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/resources/article/{uuid}/delete", name="delete_article")
	 */
	public function deleteArticle(string $uuid, ArticleRepository $articleRepository){
		$article = $articleRepository->findOneByEncodedUuid($uuid);
		if($article){
			$manager = $this->getDoctrine()->getManager();
			$manager->remove($article);
			$manager->flush();
		}
		return $this->redirectToRoute('articles');
	}

	/**
	 * @Route("/resources/publications", name="publications")
	 */
	public function publications(PublicationRepository $publicationRepository){
		$publications = $publicationRepository->findAll();
		return $this->render("dashboard/publications/list.html.twig", ['bodyClass'=>'publications', 'publications'=>$publications]);
	}

	/**
	 * @Route("/resources/publications/add", name="add_publication")
	 */
	public function addPublication(Request $request, FileUpload $fileUploadService){
		$publication = new Publication();
		$form = $this->createForm(PublicationType::class, $publication);

		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$publication = $form->getData();
			$manager = $this->getDoctrine()->getManager();

			$file = $form->get('file')->getData();
			if($file){
				$result = $fileUploadService->uploadToMediaFile($file, $manager);
				$publication->setFile($result);
			}

			$manager->persist($publication);
			$manager->flush();

			return $this->redirectToRoute('publications');
		}

		return $this->render("dashboard/publications/add.html.twig", ['bodyClass'=>'add_publication', 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/resources/publication/{uuid}/edit", name="edit_publication")
	 */
	public function editPublication(string $uuid, Request $request, PublicationRepository $publicationRepository, FileUpload $fileUploadService){
		$publication = $publicationRepository->findOneByEncodedUuid($uuid);
		if(!$publication){
			return $this->redirectToRoute('publications');
		}
		$form = $this->createForm(PublicationType::class, $publication);

		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$publication = $form->getData();
			$manager = $this->getDoctrine()->getManager();

			$file = $form->get('file')->getData();
			if($file){
				$oldFile = $publication->getFile();
				if($oldFile) $manager->remove($oldFile);
				$result = $fileUploadService->uploadToMediaFile($file, $manager);
				$publication->setFile($result);
			}

			$manager->persist($publication);
			$manager->flush();

			return $this->redirectToRoute('publications');
		}

		return $this->render("dashboard/publications/edit.html.twig", ['bodyClass'=>'edit_publication', 'publication'=>$publication, 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/resources/publication/{uuid}/delete", name="delete_publication")
	 */
	public function deletePublication(string $uuid, PublicationRepository $publicationRepository){
		$publication = $publicationRepository->findOneByEncodedUuid($uuid);
		if($publication){
			$manager = $this->getDoctrine()->getManager();
			$manager->remove($publication);
			$manager->flush();

		}
		return $this->redirectToRoute('publications');
	}

	/**
	 * @Route("/resources/presentations", name="presentations")
	 */
	public function presentations(PresentationRepository $presentationRepository){
		$presentations = $presentationRepository->findAll();
		return $this->render("dashboard/presentations/list.html.twig", ['bodyClass'=>'presentations', 'presentations'=>$presentations]);
	}

	/**
	 * @Route("/resources/presentations/add", name="add_presentation")
	 */
	public function addPresentation(Request $request, FileUpload $fileUploadService){
		$presentation = new Presentation();
		$form = $this->createForm(PresentationType::class, $presentation);

		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$presentation = $form->getData();

			$manager = $this->getDoctrine()->getManager();

			$file = $form->get('file')->getData();
			if($file){
				$result = $fileUploadService->uploadToMediaFile($file, $manager);
				$presentation->setFile($result);
			}

			$manager->persist($presentation);
			$manager->flush();

			return $this->redirectToRoute('presentations');
		}

		return $this->render("dashboard/presentations/add.html.twig", ['bodyClass'=>'add_publication', 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/resources/presentation/{uuid}/edit", name="edit_presentation")
	 */
	public function editPresentation(string $uuid, Request $request, PresentationRepository $presentationRepository, FileUpload $fileUploadService){
		$presentation = $presentationRepository->findOneByEncodedUuid($uuid);

		if(!$presentation){
			return $this->redirectToRoute('presentations');
		}

		$form = $this->createForm(PresentationType::class, $presentation);

		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$presentation = $form->getData();

			$manager = $this->getDoctrine()->getManager();

			$file = $form->get('file')->getData();
			if($file){
				$oldFile = $presentation->getFile();
				if($oldFile) $manager->remove($oldFile);
				$result = $fileUploadService->uploadToMediaFile($file, $manager);
				$presentation->setFile($result);
			}

			$manager->persist($presentation);
			$manager->flush();

			return $this->redirectToRoute('presentations');
		}

		return $this->render("dashboard/presentations/edit.html.twig", ['bodyClass'=>'add_publication', 'presentation'=>$presentation, 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/resources/presentation/{uuid}/delete", name="delete_presentation")
	 */
	public function deletePresentation(string $uuid, PresentationRepository $presentationRepository){
		$presentation = $presentationRepository->findOneByEncodedUuid($uuid);
		if($presentation){
			$manager = $this->getDoctrine()->getManager();
			$manager->remove($presentation);
			$manager->flush();

		}
		return $this->redirectToRoute('presentations');
	}
}