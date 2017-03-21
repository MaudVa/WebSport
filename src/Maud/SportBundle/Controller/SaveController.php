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
        
     //On récupère le mot de passe client
        //$password=getenv(NEONESS_PASSWORD);
        $password=$this->container->getParameter('neoness_password');
	
    //On affiche la liste des cours favoris
    return $this->render('MaudSportBundle:Save:reserver.html.twig', array('listSaves'=>$listSaves,'password'=>$password));
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

  public function nextSaveAction($password) {

    // Step 1: get course list from Neoness
    // We are going to use cURL to requests urls
    $curlHandleCourseList = curl_init();
    curl_setopt_array($curlHandleCourseList, array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => 'http://www.neoness-forme.com/cours_planning_json.php',
      CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.90 Safari/537.36'
    ));
    $rawResult = curl_exec($curlHandleCourseList);
    curl_close($curlHandleCourseList);

    // $rawResults contains the response as raw text, we process it to get usefull informations as JSON
    $result = json_decode($rawResult, true);
    $courses = $result['json_cours'];
    $clubs = $result['json_clubs'];

    // Step 2: get target course id from these informations
    // First we get the Club Id based on club Name
    $targetClubName = "Saint-Lazare";

    // Array filter will filter our clubs array to leave only the club whose name match
    $targetClubs = array_filter(
      $clubs,
      function ($club) use ($targetClubName) {
        if ($club["nom"] == $targetClubName) {
          return true;
        } else {
          return false;
        }
      }
    );

    $targetClub = array_values($targetClubs)[0]; // Weird syntax: we take the first element of $targetClubs which is our target club
    $targetClubId = $targetClub["id"];

    // Now that we have the Id, we want to find target courses
    $targetCourseName = "STEP";

    $targetCourses = array_filter(
      $courses,
      function ($course) use ($targetClubId, $targetCourseName) {
        if ($course["nom"] == $targetCourseName && $course["club_id"] == $targetClubId) {
            return true;
        } else {
            return false;
        }
      }
    );

    $targetCourses = array_values($targetCourses);

    // $target courses contains our target courses but we want to get the next one so we need to order them by date using usort function
    usort($targetCourses, function($dateA, $dateB) {
      return $dateA['debutTimeStamp'] - $dateB['debutTimeStamp'];
    });
    $targetCourse = $targetCourses[0];

    // $targetCourse contains the next target course!

    // Step 3: We need to log in using your credentials
    // We create a new cURL request (POST)
    $curlHandleLogIn = curl_init("http://www.neoness-forme.com/scr/mon-espace-connexion-scr.php");
    curl_setopt($curlHandleLogIn, CURLOPT_POST,1);
    curl_setopt($curlHandleLogIn, CURLOPT_POSTFIELDS,  http_build_query(array('num_client' => '197949', 'mdp_client' => $password)));
    curl_setopt($curlHandleLogIn, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlHandleLogIn, CURLOPT_HEADER, 1);
    $resultLogIn = curl_exec($curlHandleLogIn);
    curl_close($curlHandleLogIn);

    // We should check if LogIn is successful (based on location header) but it's up to you ;) I'm not doing it as of now
    // We get the PHPSESSID cookie: this cookie identifies your session and each time Neoness servers receive a request containing this cookie, it identifies you
    preg_match("/.*PHPSESSID=([^;]*);.*/", $resultLogIn, $phpSessidCookie);

    $curlHandleBook = curl_init("http://www.neoness-forme.com/cours-reservation.php?resa=".$targetCourse["id"]);
    curl_setopt($curlHandleBook, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlHandleBook, CURLOPT_HTTPHEADER, array("Cookie: PHPSESSID=".$phpSessidCookie[1])); // We add PHPSESSID cookie to the request
    $resultBook = curl_exec($curlHandleBook);
    curl_close($curlHandleBook);
    // ET voilà!

    return $this->render('MaudSportBundle:Save:next.html.twig', array('result' => $resultBook));
  }
}  