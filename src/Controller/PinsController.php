<?php

namespace App\Controller;
use App\Entity\Pin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use\App\Repository\PinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class PinsController extends AbstractController
{
      
    
    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function index(PinRepository $repo): Response
    {

        // $repo= $em->getRepository(Pin::class);
        // $pins = $repo->findAll();
    
         return $this->render('pins/index.html.twig', ['pins' =>$repo->findAll()]);
        
    }
    /**
     * @Route("/pins/create", name="app_pins_create", priority=10, methods={"GET", "POST"})
     */

 public function create(Request $request, EntityManagerInterface $em): Response
 {
    $form = $this->createFormBuilder()
    ->add( 'title', TextType::class, ['attr'=>['autofocus'=>'true']] )
    ->add( 'image', TextType::class)
    ->add( 'auteur', TextType::class)
    ->add( 'genre', TextType::class)
    ->add( 'editeur', TextType::class)
    ->add( 'annee', TextType::class)
    ->add( 'collection', TextType::class)
    ->add( 'nombre_de_pages', TextType::class)
    ->add( 'description',TextareaType::class, ['attr'=>['rows'=>10, 'cols'=>60]] )
    ->getForm()
    ;

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $data = $form->getData();
        $pin= new pin; 
        $pin->setTitle($data['title']);
        $pin->setImage($data['image']);
        $pin->setAuteur($data['auteur']);
        $pin->setGenre($data['genre']);
        $pin->setEditeur($data['editeur']);
        $pin->setAnnee($data['annee']);
        $pin->setCollection($data['collection']);
        $pin->setNombre_de_pages($data['nombre_de_pages']);
        $pin->setDescription($data['description']);
        $em->persist($pin);
        $em->flush();

        return $this->redirectToRoute('app_pins_show', ['id' => $pin->getId()]);
    }
    return $this->render('pins/create.html.twig', [
        'monFormulaire' => $form->createView()
    ]);
   
 }
 
    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show")
     */

 public function show(Pin $pin): Response
 {
     return $this->render('pins/show.html.twig', compact('pin'));
 }

 
    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods={"GET", "POST"})
     */

    public function edit(Request $request,Pin $pin, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder($pin)
    ->add( 'title', TextType::class, ['attr'=>['autofocus'=>'true']] )
    ->add( 'image', TextType::class)
    ->add( 'auteur', TextType::class)
    ->add( 'genre', TextType::class)
    ->add( 'editeur', TextType::class)
    ->add( 'annee', TextType::class)
    ->add( 'collection', TextType::class)
    //->add( 'nombre_de_pages', TextType::class)
    ->add( 'description',TextareaType::class, ['attr'=>['rows'=>10, 'cols'=>60]] )
    ->getForm()
    ;

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        // $data = $form->getData();

        // $pin= new pin;
        // $pin->setTitle($data['title']);
        // $pin->setAuteur($data['auteur']);
        // $pin->setGenre($data['genre']);
        // $pin->setEditeur($data['editeur']);
        // $pin->setAnnee($data['annee']);
        // $pin->setCollection($data['collection']);
        // $pin->setNombre_de_pages($data['nombre_de_pages']);
        // $pin->setDescription($data['description']);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }

        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'form' => $form->createView()
        ]);
    }

        /**
     * @Route("/pins/{id<[0-9]+>}/delete", name="app_pins_delete", methods={"DELETE"})
     */

 public function delete(Request $request,Pin $pin, EntityManagerInterface $em): Response
 {
    $em->remove($pin);
    $em->flush();
    
      return $this->redirectToRoute('app_home');
 }
}

