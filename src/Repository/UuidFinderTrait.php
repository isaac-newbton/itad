<?php
namespace App\Repository;

use App\Doctrine\UuidEncoder;

trait UuidFinderTrait{
	/**
	 * @var UuidEncoder
	 */
	protected $uuidEncoder;

	public function findOneByEncodedUuid(string $encoded){
		return $this->findOneBy(['uuid'=>$this->uuidEncoder->decode($encoded)]);
	}
}