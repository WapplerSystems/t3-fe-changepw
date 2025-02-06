<?php

namespace WapplerSystems\FrontendChangePassword\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 *
 */
class UserController extends ActionController
{


    public function passwordAction(): ResponseInterface
    {

        $this->view->assignMultiple([
            'settings' => $this->settings,
        ]);
        return $this->htmlResponse();
    }

}
