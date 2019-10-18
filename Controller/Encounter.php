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
     * Render users that current user liked
     * 
     * @return string
     */
    public function myLikesAction()
    {
        $encSrv = $this->getModuleService('encounterService');
        $id = $this->getAuthService()->getId();

        $output = $this->view->render('profile/likes', array(
            'users' => $encSrv->findMyLikes($id)
        ));

        // Mark all as read
        $encSrv->markAllAsRead($id);

        return $output;
    }

    /**
     * Render users that liked current user
     * 
     * @return string
     */
    public function theirLikesAction()
    {
        return $this->view->render('profile/likes', array(
            'users' => $this->getModuleService('encounterService')->findTheirLikes($this->getAuthService()->getId())
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
