<?php

declare(strict_types=1);

namespace WapplerSystems\FrontendChangePassword\Form\Factory;

use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Validation\Exception\InvalidValidationOptionsException;
use TYPO3\CMS\Extbase\Validation\ValidatorResolver;
use TYPO3\CMS\Form\Domain\Configuration\ConfigurationService;
use TYPO3\CMS\Form\Domain\Configuration\Exception\PrototypeNotFoundException;
use TYPO3\CMS\Form\Domain\Exception\TypeDefinitionNotFoundException;
use TYPO3\CMS\Form\Domain\Exception\TypeDefinitionNotValidException;
use TYPO3\CMS\Form\Domain\Factory\AbstractFormFactory;
use TYPO3\CMS\Form\Domain\Model\Exception\FinisherPresetNotFoundException;
use TYPO3\CMS\Form\Domain\Model\FormDefinition;
use TYPO3\CMS\Form\Domain\Model\FormElements\AbstractFormElement;
use TYPO3\CMS\Form\Domain\Model\FormElements\Page;
use TYPO3\CMS\Form\Domain\Renderer\FluidFormRenderer;

class PasswordFormFactory extends AbstractFormFactory
{
    private function addFormElement(
        Page    $page,
        string  $type,
        string  $id,
        ?string $label = null,
        mixed   $defaultValue = null,
        ?array  $properties = null,
        ?array  $renderingOptions = null,
        ?array  $validators = null
    ): AbstractFormElement
    {
        /** @var AbstractFormElement $element */
        $element = $page->createElement($id, $type);

        if (isset($label)) $element->setLabel($label);
        if (isset($defaultValue)) $element->setDefaultValue($defaultValue);
        if (isset($properties)) {
            foreach ($properties as $key => $value) {
                $element->setProperty($key, $value);
            }
        }
        if (isset($renderingOptions)) {
            foreach ($renderingOptions as $key => $value) {
                $element->setRenderingOption($key, $value);
            }
        }
        if (isset($validators)) {
            foreach ($validators as $validator) {
                $element->addValidator($validator);
            }
        }

        return $element;
    }

    /**
     * @param array $configuration
     * @param string|null $prototypeName
     * @return FormDefinition
     * @throws InvalidValidationOptionsException
     * @throws PrototypeNotFoundException
     * @throws TypeDefinitionNotFoundException
     * @throws TypeDefinitionNotValidException
     * @throws FinisherPresetNotFoundException
     */
    public function build(array $configuration, ?string $prototypeName = null): FormDefinition
    {

        /** @var array */
        $feUser = $GLOBALS['TSFE']->fe_user->user;

        /** @var ConfigurationService $configurationService */
        $configurationService = GeneralUtility::makeInstance(ConfigurationService::class);
        $prototypeConfiguration = $configurationService->getPrototypeConfiguration('standard');

        /** @var FormDefinition $formDefinition */
        $formDefinition = GeneralUtility::makeInstance(FormDefinition::class, 'profileForm', $prototypeConfiguration);
        $formDefinition->setRendererClassName(FluidFormRenderer::class);
        $formDefinition->setRenderingOption('controllerAction', 'profile');
        $formDefinition->setRenderingOption('submitButtonLabel', 'save');


        $page = $formDefinition->createPage('page1');
        $resolver = GeneralUtility::makeInstance(ValidatorResolver::class);

        $this->addFormElement(
            $page,
            type: 'AdvancedPassword',
            id: 'password',
            label: 'password',
            properties: [
            ]
        );


        $formDefinition->createFinisher('FlashMessage', [
            'messageBody' => LocalizationUtility::translate('LLL:EXT:bga_site/Resources/Private/Language/Frontend.xlf:msg.passwordSavedSuccessfully'),
            'severity' => ContextualFeedbackSeverity::OK,
            'messageCode' => 1644873735,
        ]);


        $saveToUserTableFinisher = $formDefinition->createFinisher('FeUser');
        $saveToUserTableFinisher->setOptions([
            'table' => 'fe_users',
            'mode' => 'update',
            'pid' => $user->getPid(),
            'whereClause' => [
                'uid' => $user->getUid(),
            ],
            'elements' => [
                'password' => [
                    'mapOnDatabaseColumn' => 'password',
                    'hashPassword' => true,
                ],
            ],
        ]);


        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $uri = $uriBuilder->setTargetPageUid($GLOBALS['TSFE']->id)
            ->setSection('content')
            ->setCreateAbsoluteUri(true)
            ->build();

        $formDefinition->createFinisher('RedirectToUri', [
            'uri' => $uri,
        ]);

        return $formDefinition;
    }
}
