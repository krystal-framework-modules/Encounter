<?php

namespace Encounter\Controller;

use Site\Controller\AbstractSiteController;

final class Encounter extends AbstractSiteController
{
    /**
     * Starts encounter game
     * 
     * @return string
     */
    public function indexAction()
    {
        // Find a single suggestion
        $suggestion = $this->getModuleService('encounterService')->findEncounter($this->getAuthService()->getId());

        return $this->view->render('profile/encounter', array(
            'suggestion' => $suggestion
        ));
    }

    /**
     * Likes a user
     * 
     * @param int $id Id of user to be liked
     * @return string
     */
    public function likeAction($id)
    {
        $this->getModuleService('encounterService')->like($this->getAuthService()->getId(), $id);
        $this->response->back();
    }

    /**
     * Dislikes a user
     * 
     * @param int $id Id of user to be disliked
     * @return string
     */
    public function dislikeAction($id)
    {
        $this->getModuleService('encounterService')->dislike($this->getAuthService()->getId(), $id);
        $this->response->back();
    }
}
