<?php
namespace Famelo\Features\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.Features".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Feature {

	/**
	 * The package this feature is defined in.
	 *
	 * @var string
	 */
	protected $package = '';

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * If this feature is defined (i.e. if it is [still] in the Features.yaml file of its package).
	 *
	 * @var boolean
	 * @Flow\Transient
	 */
	protected $defined;

	/**
	 * @var string
	 */
	protected $customCondition = '';

	/**
	 * The status of this feature; one of the STATUS_* constants
	 *
	 * @var integer
	 */
	protected $status = self::STATUS_HARDCODED_CONDITION;

	const STATUS_HARDCODED_CONDITION = 0;
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 2;
	const STATUS_CUSTOM_CONDITION = 3;

	/**
	 * @var \Famelo\Features\FeatureService
	 * @Flow\Inject
	 */
	protected $featureService;


	public function __construct($name) {
		//$this->package = $package;
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getPackage() {
		return $this->package;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return boolean
	 */
	public function isDefined() {
		if ($this->defined === NULL) {
			$this->defined = $this->featureService->isFeatureDefined($this->name);
		}
		return $this->defined;
	}

	/**
	 * @param int $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @return int
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param string $customCondition
	 */
	public function setCustomCondition($customCondition) {
		$this->customCondition = $customCondition;
	}

	/**
	 * @return string
	 */
	public function getCustomCondition() {
		return $this->customCondition;
	}

	/**
	 * @return string
	 */
	public function getDefaultCondition() {
		$featureDefinition = $this->featureService->getFeatureDefinition($this->name);

		return isset($featureDefinition['condition']) ? $featureDefinition['condition'] : '';
	}

}
?>