<?php
namespace App\Service;

use App\Entity\MediaFile;
use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUpload{
	private $directory;
	private $slugger;
	private $kernel;

	public function __construct($directory, SluggerInterface $slugger, Kernel $kernel){
		$this->directory = $directory;
		$this->slugger = $slugger;
		$this->kernel = $kernel;
	}

	public function upload(UploadedFile $file){
		$originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		$safeName = $this->slugger->slug($originalName);
		$fileName = $safeName . '-' . uniqid() . '.' . $file->guessExtension();

		try{
			$movedFile = $file->move($this->directory . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m'), $fileName);
		}catch(FileException $e){
			return $e;
		}

		return $movedFile;
	}

	public function uploadToMediaFile(UploadedFile $file, EntityManagerInterface $entityManager){
		/**
		 * @var File|FileException
		 */
		$movedFile = $this->upload($file);
		if($movedFile instanceof FileException){
			return $movedFile->getMessage();
		}else{
			$mediaFile = new MediaFile();
			$mediaFile->setName($movedFile->getFilename());
			$mediaFile->setSize($movedFile->getSize());
			$mediaFile->setMimeType($movedFile->getMimeType());
			$mediaFile->setPath(substr($movedFile->getPathname(), strlen($this->kernel->getProjectDir())));
			$entityManager->persist($mediaFile);
			$entityManager->flush();
			return $mediaFile;
		}
	}
}