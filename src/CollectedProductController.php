<?php

namespace Schrattenholz\Order;
 
 use SilverStripe\Control\HTTPRequest;
use PageController;

class CollectedProductController extends PageController
{
public function index(HTTPRequest $request)
    {
        /** @var RedirectorPage $page */
        $page = $this->data();
       // if (!$this->getResponse()->isFinished() && $link = $page->redirectionLink()) {
            $this->redirect($this->owner->Children()->First()->Link(),301);
       // }
        return parent::handleAction($request, 'handleIndex');
    }
}