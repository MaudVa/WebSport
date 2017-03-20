<?php

namespace myAppFilmothequeBundle\Controller;

//use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use myAppFilmothequeBundle\Entity\Acteur;
//use myAppFilmothequeBundle\Form\ActeurForm;

class ActeurController extends Controller
{
    public function listerAction()
    {
	return $this->container->get('templating')->renderResponse(
'myAppFilmothequeBundle:Acteur:lister.html.twig');
    }
    
    public function ajouterAction()
    {
	return $this->container->get('templating')->renderResponse(
'myAppFilmothequeBundle:Acteur:ajouter.html.twig');
    }

    public function modifierAction($id)
    {
	return $this->container->get('templating')->renderResponse(
'myAppFilmothequeBundle:Acteur:modifier.html.twig');
    }

    public function supprimerAction($id)
    {
	return $this->container->get('templating')->renderResponse(
'myAppFilmothequeBundle:Acteur:supprimer.html.twig');
    }
}