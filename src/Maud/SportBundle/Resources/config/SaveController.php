<?php

// src/Maud/SportBundle/Controller/SaveController.php

namespace Maud\SportBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class SaveController
{
    public function indexAction()
    {
        return new Response("Notre propre Hello World !");
    }
}