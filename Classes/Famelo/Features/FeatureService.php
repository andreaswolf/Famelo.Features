<?php
namespace Famelo\Features;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.Features".       *
 *                                                                        *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use Famelo\Features\Core\ConditionMatcher;
use TYPO3\Eel\Context;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Exception;

/**
 * @Flow\Scope("singleton")
 */
class FeatureService {
	/**
	 * The securityContext
	 *
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 */
	protected $configurationManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Eel\CompilingEvaluator
	 */
	protected $eelEvaluator;

	/**
	 * @var array
	 */
	protected $runtimeCache = array();

	public function isFeatureActive($requestedFeature) {
		if (!isset($this->runtimeCache[$requestedFeature])) {
			$this->runtimeCache[$requestedFeature] = NULL;

			$settings = $this->configurationManager->getConfiguration('Settings', 'Famelo.Features');

			$conditionMatcher = new $settings['conditionMatcher']($requestedFeature);
			$context = new Context($conditionMatcher);

			$feature = $this->getFeatureDefinition($requestedFeature);
			if (isset($feature['condition'])) {
				$this->runtimeCache[$requestedFeature] =  $this->eelEvaluator->evaluate($feature['condition'], $context);
			}

			if ($this->runtimeCache[$requestedFeature] === NULL) {
				switch ($settings['noMatchBehavior']) {
					case 'active':
						$this->runtimeCache[$requestedFeature] = TRUE;
						break;

					case 'inactive':
						$this->runtimeCache[$requestedFeature] = FALSE;
						break;

					case 'exception':
					default:
						throw new Exception('The Feature you\'re trying to use does not exist: ' . $requestedFeature);
						break;
				}
			}
		}

		return $this->runtimeCache[$requestedFeature];
	}

	public function getFeatureDefinition($featureName) {
		$features = $this->configurationManager->getConfiguration('Features');

		if (array_key_exists($featureName, $features)) {
			return $features[$featureName];
		}
		return NULL;
	}
}
?>