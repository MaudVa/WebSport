<?php

namespace myAppFilmothequeBundle\Controller;

/*use Symfony\Component\DependencyInjection\ContainerAware,
       Symfony\Component\HttpFoundation\RedirectResponse;*/

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use myAppFilmothequeBundle\Entity\Categorie;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
         $em = $this->container->get('doctrine')->getEntityManager();
	$categories = $em->getRepository('myAppFilmothequeBundle:Categorie')->findAll();

	return $this->container->get('templating')->renderResponse('myAppFilmothequeBundle:Default:index.html.twig',array(
		 'categories' => $categories)
	);
    }
}
