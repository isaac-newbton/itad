<?php

namespace App\Controller;

use App\Form\FileUploadType;
use App\Service\FileUpload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultController extends AbstractController{

	/**
	 * @Route("/dev/fileupload", name="dev_fileupload")
	 */
	public function fileUpload(Request $request, FileUpload $fileUploadService){
		$this->denyAccessUnlessGranted('ROLE_DEVELOPER');
		$form = $this->createForm(FileUploadType::class);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			/**
			 * @var UploadedFile
			 */
			$file = $form->get('file')->getData();
			if($file){
				$result = $fileUploadService->uploadToMediaFile($file, $this->getDoctrine()->getManager());
				return new Response("<html><body><pre>" . var_export($result->getUuid()->toString(), true) . "</pre></body></html>");
			}

		}

		return $this->render('dev/file_upload.html.twig', ['bodyClass'=>'login_page', 'form'=>$form->createView()]);
	}

}