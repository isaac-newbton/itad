<?php
namespace App\Twig;

use App\Doctrine\UuidEncoder;
use App\Repository\CountryRepository;
use Ramsey\Uuid\UuidInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension{
	private $encoder;
	private $countryRepository;

	public function __construct(UuidEncoder $encoder, CountryRepository $countryRepository){
		$this->encoder = $encoder;
		$this->countryRepository = $countryRepository;
	}

	public function getFunctions(): array{
		return [
			new TwigFunction('uuid_encode', [$this, 'encodeUuid'], ['is_safe'=>['html']]),
			new TwigFunction('all_countries', [$this, 'allCountries'], ['is_safe'=>['html']])
		];
	}

	public function encodeUuid(UuidInterface $uuid): string{
		return $this->encoder->encode($uuid);
	}

	public function allCountries(): array{
		return $this->countryRepository->findAll();
	}
}