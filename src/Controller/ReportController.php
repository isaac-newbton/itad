<?php
namespace App\Controller;

use App\Doctrine\UuidEncoder;
use App\Entity\Country;
use App\Entity\ExcelDataFile;
use App\Entity\FileDownload;
use App\Entity\ReportLineItem;
use App\Entity\YearlyReport;
use App\Form\CountryType;
use App\Form\ExcelDataFileEditType;
use App\Form\ExcelDataFileType;
use App\Form\ReportLineItemType;
use App\Form\YearlyReportDownloadType;
use App\Form\YearlyReportLaboratoriesType;
use App\Form\YearlyReportType;
use App\Repository\AdulterantRepository;
use App\Repository\CountryRepository;
use App\Repository\ExcelDataFileRepository;
use App\Repository\FileDownloadRepository;
use App\Repository\LaboratoryRepository;
use App\Repository\ReportLineItemRepository;
use App\Repository\YearlyReportRepository;
use App\Service\FileUpload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ReportController extends AbstractController{
	/**
	 * @Route("/country/{code}/add", name="add_report")
	 */
	public function add(Request $request, string $code, CountryRepository $countryRepository, YearlyReportRepository $reportRepository){
		$report = new YearlyReport();

		$country = $countryRepository->findOneByCode($code);
		if(!$country){
			return $this->redirect('countries');
		}

		$form = $this->createForm(YearlyReportType::class, $report);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$existingReport = $reportRepository->findOneBy([
				'country'=>$country,
				'year'=>$form->get('year')->getData()
			]);

			$report = $form->getData();

			if(!$existingReport){
				$country->addYearlyReport($report);
				$manager = $this->getDoctrine()->getManager();
				$manager->persist($report);
				$manager->flush();
			}

			return $this->redirectToRoute('report', ['code'=>$code, 'year'=>$report->getYear()]);
		}

		return $this->render("dashboard/reports/add.html.twig", ['bodyClass'=>'add_report', 'country'=>$country, 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/country/{code}/{year}", name="report")
	 */
	public function report(string $code, int $year, CountryRepository $countryRepository, YearlyReportRepository $reportRepository){
		$country = $countryRepository->findOneByCode($code);
		if(!$country){
			return $this->redirect('countries');
		}
		$report = $reportRepository->findOneBy([
			'country'=>$country,
			'year'=>(string)$year
		]);
		if(!$report){
			return $this->redirectToRoute('add_report', ['code'=>$country->getCode()]);
		}
		return $this->render("dashboard/reports/view.html.twig", ['bodyClass'=>'report', 'report'=>$report, 'country'=>$country, 'reports'=>$country->getYearlyReports()]);
	}

	/**
	 * @Route("/report/{uuid}/add-laboratory", name="add_laboratory_to_report")
	 */
	public function addLaboratory(string $uuid, Request $request, YearlyReportRepository $reportRepository, LaboratoryRepository $laboratoryRepository){
		$report = $reportRepository->findOneByEncodedUuid($uuid);
		if(!$report){
			return $this->redirectToRoute('countries');
		}

		$form = $this->createForm(YearlyReportLaboratoriesType::class, $report);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			$report = $form->getData();

			$manager = $this->getDoctrine()->getManager();
			$manager->persist($report);
			$manager->flush();

			return $this->redirectToRoute("report", ['code'=>$report->getCountry()->getCode(), 'year'=>$report->getYear()]);
		}

		return $this->render("dashboard/reports/add_laboratory.html.twig", ['bodyClass'=>'add_laboratory_to_report', 'report'=>$report, 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/report/{uuid}/add-adulterant", name="add_adulterant_to_report")
	 */
	public function addAdulterant(string $uuid, Request $request, YearlyReportRepository $reportRepository, AdulterantRepository $adulterantRepository){
		$report = $reportRepository->findOneByEncodedUuid($uuid);
		if(!$report){
			return $this->redirectToRoute('countries');
		}

		$item = new ReportLineItem();

		$form = $this->createForm(ReportLineItemType::class, $item);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			$item = $form->getData();

			$report->addReportLineItem($item);

			$manager = $this->getDoctrine()->getManager();
			$manager->persist($item);
			$manager->flush();

			return $this->redirectToRoute("report", ['code'=>$report->getCountry()->getCode(), 'year'=>$report->getYear()]);
		}

		return $this->render("dashboard/reports/add_adulterant.html.twig", ['bodyClass'=>'add_adulterant_to_report', 'report'=>$report, 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/report/item/{uuid}/edit", name="edit_adulterant_in_report")
	 */
	public function editAdulterant(string $uuid, Request $request, ReportLineItemRepository $reportLineItemRepository){
		$item = $reportLineItemRepository->findOneByEncodedUuid($uuid);
		if(!$item){
			return $this->redirectToRoute('countries');
		}
		$report = $item->getReport();

		$form = $this->createForm(ReportLineItemType::class, $item);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			$item = $form->getData();
			$manager = $this->getDoctrine()->getManager();
			$manager->persist($item);
			$manager->flush();

			return $this->redirectToRoute("report", ['code'=>$report->getCountry()->getCode(), 'year'=>$report->getYear()]);
		}

		return $this->render("dashboard/reports/edit_adulterant.html.twig", ['bodyClass'=>'add_adulterant_to_report', 'report'=>$report, 'item'=>$item, 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/report/item/{uuid}/delete", name="delete_adulterant_in_report")
	 */
	public function deleteAdulterant(string $uuid, Request $request, ReportLineItemRepository $reportLineItemRepository){
		$item = $reportLineItemRepository->findOneByEncodedUuid($uuid);
		if($item){
			$report = $item->getReport();
			$manager = $this->getDoctrine()->getManager();
			$manager->remove($item);
			$manager->flush();
			return $this->redirectToRoute("report", ['code'=>$report->getCountry()->getCode(), 'year'=>$report->getYear()]);
		}

		return $this->redirectToRoute('countries');
	}

	/**
	 * @Route("/report/download/{uuid}/delete", name="delete_download")
	 */
	public function deleteDownload(string $uuid, Request $request, FileDownloadRepository $fileDownloadRepository){
		$item = $fileDownloadRepository->findOneByEncodedUuid($uuid);
		if($item){
			$report = $item->getYearlyReport();
			$manager = $this->getDoctrine()->getManager();
			$manager->remove($item);
			$manager->flush();
			return $this->redirectToRoute("report", ['code'=>$report->getCountry()->getCode(), 'year'=>$report->getYear()]);
		}

		return $this->redirectToRoute('countries');
	}

	/**
	 * @Route("/report/{uuid}/add-download", name="add_download_to_report")
	 */
	public function addDownload(string $uuid, Request $request, YearlyReportRepository $reportRepository, FileUpload $fileUploadService){
		$report = $reportRepository->findOneByEncodedUuid($uuid);
		if(!$report){
			return $this->redirectToRoute('countries');
		}

		$download = new FileDownload();
		$form = $this->createForm(YearlyReportDownloadType::class, $download);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			/**
			 * @var UploadedFile
			 */
			$file = $form->get('file')->getData();
			if($file){
				$manager = $this->getDoctrine()->getManager();
				$result = $fileUploadService->uploadToMediaFile($file, $manager);
				$download->setFile($result);

				$thumbnail = $form->get('thumbnail')->getData();
				if($thumbnail){
					$thumbnailResult = $fileUploadService->uploadToMediaFile($thumbnail, $manager);
					$download->setThumbnail($thumbnailResult);
				}

				$report->addFileDownload($download);

				$manager->persist($download);
				$manager->persist($report);
				$manager->flush();
			}

			return $this->redirectToRoute('report', ['code'=>$report->getCountry()->getCode(), 'year'=>$report->getYear()]);
		}

		return $this->render("dashboard/reports/add_download.html.twig", ['bodyClass'=>'add_download_to_report', 'report'=>$report, 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/report/{uuid}/add-excel-data", name="add_excel_to_report")
	 */
	public function addExcel(string $uuid, Request $request, YearlyReportRepository $reportRepository, FileUpload $fileUploadService){
		$report = $reportRepository->findOneByEncodedUuid($uuid);
		if(!$report){
			return $this->redirectToRoute('countries');
		}

		$excelFile = new ExcelDataFile();
		$form = $this->createForm(ExcelDataFileType::class, $excelFile);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			/**
			 * @var UploadedFile
			 */
			$file = $form->get('file')->getData();
			if($file){
				$manager = $this->getDoctrine()->getManager();
				$result = $fileUploadService->uploadToMediaFile($file, $manager);
				$excelFile = $form->getData();
				$excelFile->setUser($this->getUser());
				$excelFile->setFile($result);
				$report->addExcelDataFile($excelFile);
				$manager->persist($excelFile);
				$manager->persist($report);
				$manager->flush();
			}

			return $this->redirectToRoute('report', ['code'=>$report->getCountry()->getCode(), 'year'=>$report->getYear()]);
		}

		return $this->render("dashboard/reports/add_excel.html.twig", ['bodyClass'=>'add_excel_to_report', 'report'=>$report, 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/report/download/{uuid}/edit", name="edit_download_in_report")
	 */
	public function editDownload(string $uuid, Request $request, FileDownloadRepository $fileDownloadRepository, FileUpload $fileUploadService){
		$download = $fileDownloadRepository->findOneByEncodedUuid($uuid);
		if(!$download){
			return $this->redirectToRoute('countries');
		}

		$report = $download->getYearlyReport();

		$form = $this->createForm(YearlyReportDownloadType::class, $download);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			/**
			 * @var UploadedFile
			 */
			$file = $form->get('file')->getData();
			if($file){
				$manager = $this->getDoctrine()->getManager();
				$result = $fileUploadService->uploadToMediaFile($file, $manager);
				$download->setFile($result);

				$thumbnail = $form->get('thumbnail')->getData();
				if($thumbnail){
					$thumbnailResult = $fileUploadService->uploadToMediaFile($thumbnail, $manager);
					$download->setThumbnail($thumbnailResult);
				}


				$manager->persist($download);
				$manager->flush();
			}

			return $this->redirectToRoute('report', ['code'=>$report->getCountry()->getCode(), 'year'=>$report->getYear()]);
		}

		return $this->render("dashboard/reports/edit_download.html.twig", ['bodyClass'=>'edit_download_in_report', 'report'=>$report, 'download'=>$download, 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/report/excel/{uuid}/edit", name="edit_excel_in_report")
	 */
	public function editExcel(string $uuid, Request $request, ExcelDataFileRepository $excelRepository, FileUpload $fileUploadService){
		/**
		 * @var ExcelDataFile|null
		 */
		$excel = $excelRepository->findOneByEncodedUuid($uuid);
		if(!$excel){
			return $this->redirectToRoute('countries');
		}

		$report = $excel->getYearlyReport();

		$form = $this->createForm(ExcelDataFileEditType::class, $excel);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$excel = $form->getData();
			$manager = $this->getDoctrine()->getManager();
			/**
			 * @var UploadedFile
			 */
			$file = $form->get('file')->getData();
			if($file){
				$result = $fileUploadService->uploadToMediaFile($file, $manager);
				$excel->setFile($result);
			}
			$manager->persist($excel);
			$manager->flush();

			return $this->redirectToRoute('report', ['code'=>$report->getCountry()->getCode(), 'year'=>$report->getYear()]);
		}

		return $this->render("dashboard/reports/edit_excel.html.twig", ['bodyClass'=>'edit_excel_in_report', 'report'=>$report, 'excel'=>$excel, 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/report/excel/{uuid}/delete", name="delete_excel")
	 */
	public function deleteExcel(string $uuid, ExcelDataFileRepository $excelRepository){
		/**
		 * @var ExcelDataFile|null
		 */
		$excel = $excelRepository->findOneByEncodedUuid($uuid);
		if(!$excel){
			return $this->redirectToRoute('countries');
		}

		$report = $excel->getYearlyReport();
		$report->removeExcelDataFile($excel);

		$manager = $this->getDoctrine()->getManager();
		$manager->remove($excel);
		$manager->remove($excel->getFile());
		$manager->flush();

		return $this->redirectToRoute('report', ['code'=>$report->getCountry()->getCode(), 'year'=>$report->getYear()]);
	}

	/**
	 * @Route("/report/{uuid}/edit", name="edit_report")
	 */
	function edit(string $uuid, Request $request, YearlyReportRepository $reportRepository){
		$report = $reportRepository->findOneByEncodedUuid($uuid);
		if(!$report){
			return $this->redirectToRoute("countries");
		}

		$country = $report->getCountry();
		$form = $this->createForm(YearlyReportType::class, $report);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){

			$report = $form->getData();

			$country->addYearlyReport($report);
			$manager = $this->getDoctrine()->getManager();
			$manager->persist($report);
			$manager->flush();

			return $this->redirectToRoute('report', ['code'=>$country->getCode(), 'year'=>$report->getYear()]);
		}

		return $this->render("dashboard/reports/edit.html.twig", ['bodyClass'=>'add_report', 'report'=>$report, 'form'=>$form->createView()]);
	}

	/**
	 * @Route("/report/{uuid}/delete", name="delete_report")
	 */
	function delete(string $uuid, YearlyReportRepository $reportRepository){
		$report = $reportRepository->findOneByEncodedUuid($uuid);
		if($report){
			$country = $report->getCountry();
			$manager = $this->getDoctrine()->getManager();
			$downloads = $report->getFileDownloads();
			if($downloads){
				foreach($downloads as $download){
					$manager->remove($download);
				}
			}
			$manager->remove($report);
			$manager->flush();
			return $this->redirectToRoute("country", ['code'=>$country->getCode()]);
		}

		return $this->redirectToRoute("countries");
	}

	/**
	 * @Route("/report/{uuid}/download", name="download_report")
	 */
	function download(string $uuid, YearlyReportRepository $reportRepository){
		/**
		 * @var YearlyReport|null
		 */
		$report = $reportRepository->findOneByEncodedUuid($uuid);
		if($report){
			$country = $report->getCountry();
			$items = $report->getReportLineItems();
			$laboratories = $report->getParticipatingLaboratories();
			if(!empty($items)){
				$rows = [["{$report->getYear()} report for {$country->getName()}"]];
				if(!empty($laboratories)){
					foreach($laboratories as $laboratory){
						$rows[] = ["{$laboratory->getName()}"];
					}
				}
				$rows[] = ["Adulterant", "Value"];
				foreach($items as $item){
					$rows[] = ["{$item->getAdulterant()->getName()}", "{$item->getValue()}%"];
				}
				if(!empty($rows)){
					$filename = "{$country->getCode()}-{$report->getYear()}.csv";
					$content = '';
					foreach($rows as $row){
						$content .= implode(',', $row) . PHP_EOL;
					}
					$response = new Response($content);
					$disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
					$response->headers->set('Content-Disposition', $disposition);
					return $response;
				}
			}
			return $this->redirectToRoute("report", ['code'=>$country->getCode(), 'year'=>$report->getYear()]);
		}

		return $this->redirectToRoute("countries");
	}
}