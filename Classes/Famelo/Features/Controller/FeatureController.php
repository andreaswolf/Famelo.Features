<?php
namespace Famelo\Features\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.Features".       *
 *                                                                        *
 *                                                                        */

use Famelo\Features\Domain\Model\Feature;
use TYPO3\Flow\Annotations as Flow;

class FeatureController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * @var \Famelo\Features\Domain\Repository\FeatureRepository
	 * @Flow\Inject
	 */
	protected $featureRepository;

	/**
	 * @var \Famelo\Features\FeatureService
	 * @Flow\Inject
	 */
	protected $featureService;

	/**
	 * @return void
	 */
	public function indexAction() {
		$definedFeatures = $this->configurationManager->getConfiguration('Features');
		$configuredFeatures = $this->featureRepository->findAll();
		/** @var $feature Feature */
		foreach ($configuredFeatures as $feature) {
			if (array_key_exists($feature->getName(), $definedFeatures)) {
				$definedFeatures[$feature->getName()]['configuration'] = $feature;
			}
		}

		$this->view->assign('definedFeatures', $definedFeatures);
	}

	/**
	 * @param string $name
	 * @param \Famelo\Features\Domain\Model\Feature $feature
	 */
	public function newAction($name, \Famelo\Features\Domain\Model\Feature $feature = NULL) {
		$this->view->assign('name', $name);
		// TODO improve this -> we should get rid of this branching and supply a default Feature object if none has been defined
		if ($feature !== NULL) {
			$this->view->assign('feature', $feature);
		} else {
			$this->view->assign('feature', array('configuration' => $this->featureService->getFeatureDefinition($name)));
		}
	}

	/**
	 * @param \Famelo\Features\Domain\Model\Feature $feature
	 */
	public function createAction(\Famelo\Features\Domain\Model\Feature $feature) {
		$this->featureRepository->add($feature);

		$this->redirect('index');
	}

	/**
	 * @param \Famelo\Features\Domain\Model\Feature $feature
	 */
	public function showAction(\Famelo\Features\Domain\Model\Feature $feature) {
		$this->view->assign('feature', $feature);
	}

}

?>