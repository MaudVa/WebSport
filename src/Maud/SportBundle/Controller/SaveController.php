<?php

// src/Maud/SportBundle/Controller/SaveController.php

namespace Maud\SportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Maud\SportBundle\Entity\Save;
use Maud\SportBundle\Form\SaveType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class SaveController extends Controller
{
  public function indexAction()
  {
    //On récupère la liste des cours réservés dans la BDD
	$repository = $this->getDoctrine()->getManager()->getRepository('MaudSportBundle:Save');
	$listSaves = $repository->findAll();

    return $this->render('MaudSportBundle:Save:index.html.twig', array(

      'listSaves' => $listSaves

    ));

  }
  
  public function reserverAction()
  {
    
	//On récupère la liste des cours réservés dans la BDD
	$repository = $this->getDoctrine()->getManager()->getRepository('MaudSportBundle:Save');
	$listSaves = $repository->findAll();
	
    //On affiche la liste des cours favoris
    return $this->render('MaudSportBundle:Save:reserver.html.twig', array('listSaves'=>$listSaves));
  }
  
    public function viewAction($id)
  {
    // Notre liste de cours en dur

   $listSaves = array(
      array('id' => 1, 'cours' => 'Step', 'club'=> 'Saint-Lazare'),
      array('id' => 2, 'cours' => 'Body Combat', 'club'=> 'Saint-Lazare')
    );

    return $this->render('MaudSportBundle:Save:view.html.twig', array('id'=>1));
  }
  
  
   public function menuAction($limit)
  {
    // On fixe en dur une liste ici, bien entendu par la suite
    // on la récupérera depuis la BDD !
    $listSaves = array(
      array('id' => 1, 'cours' => 'Step', 'club'=> 'Saint-Lazare'),
      array('id' => 5, 'cours' => 'Body Combat', 'club'=> 'Saint-Lazare'),
      array('id' => 9, 'cours' => 'Zumba', 'club'=> 'Saint-Lazare')
    );

    return $this->render('MaudSportBundle:Save:menu.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe
      // les variables nécessaires au template !
      'listSaves' => $listSaves
    ));
  }
  
  public function addAction(Request $request)

  {
    // Création de l'entité
    $Save = new Save();
	
	// Création du FormBuilder
	$form=$this->get('form.factory')->create(SaveType::class,$Save);

    // Si la requête est en post
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // On enregistre notre objet $Save dans la base de données, par exemple
        $em = $this->getDoctrine()->getManager();
        $em->persist($Save);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'cours bien enregistré.');

        // On redirige vers la page d'accueil pour l'instant

        return $this->redirectToRoute('cours_sport');

	}	

    return $this->render('MaudSportBundle:Save:add.html.twig', array(
	'form' => $form->createView(),
	));

  }
  
  public function deleteAction($id,Request $request)

  {
    // Ici, on récupère l'annonce correspondant à $id
	$repository = $this->getDoctrine()->getManager()->getRepository('MaudSportBundle:Save');
	$save = $repository->find($id);
    // Ici, on gérera la suppression de l'annonce en question
	$em = $this->getDoctrine()->getManager();
	$em->remove($save);
	$em->flush();
	
	//On affiche la liste des cours favoris
	$listSaves = $repository->findAll();
    return $this->render('MaudSportBundle:Save:reserver.html.twig', array('listSaves'=>$listSaves));
  }
}  