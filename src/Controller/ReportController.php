<?php
namespace App\Controller;

use App\Entity\Country;
use App\Entity\YearlyReport;
use App\Form\CountryType;
use App\Form\YearlyReportType;
use App\Repository\CountryRepository;
use App\Repository\YearlyReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController{
	/**
	 * @Route("/country/{code}/add", name="add_report")
	 */
	public function add(Request $request, string $code, CountryRepository $countryRepository){
		$report = new YearlyReport();

		$country = $countryRepository->findOneByCode($code);
		if(!$country){
			return $this->redirect('countries');
		}

		$form = $this->createForm(YearlyReportType::class, $report);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$report = $form->getData();

			$country->addYearlyReport($report);
			$manager = $this->getDoctrine()->getManager();
			$manager->persist($report);
			$manager->flush();

			return $this->redirectToRoute('country', ['code'=>$code]);
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
		return $this->render("dashboard/reports/view.html.twig", ['bodyClass'=>'report', 'report'=>$report]);
	}
}