<?php

namespace App\Controller;

use App\Entity\Cattle;
use App\Form\CattleType;
use App\Repository\CattleRepository;
use App\Service\CattleService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CattleController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index() : Response 
    {
        return $this->render('base.html.twig', ['title'=> 'Bem Vindo!']);
    }

    /**
     * @Route("/cattle/new", name="create")
     */
    public function create(ManagerRegistry $doctrine, Request $request, CattleService $cs) : Response 
    {
        $entityManager = $doctrine->getManager();
        
        $cattle = new Cattle();
        $cattle->setSlaughtered(false);

        if($cs->Slaughter($cattle))
            $cattle->setSlaughter(true);
        else
            $cattle->setSlaughter(false);

        $form = $this->createForm(CattleType::class, $cattle);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($cattle);
            $entityManager->flush();
            return $this->redirectToRoute('getAll');
        }

        return $this->renderForm('cattle/form.html.twig', [
            'title'=> 'Novo Registro',
            'form'=> $form,
            'new'=> true
        ]); 
    }

    /**
     * @Route("/cattle", name="getAll")
     */
    public function getAll(ManagerRegistry $doctrine) : Response 
    {
        $cattles = $doctrine->getRepository(Cattle::class)->findBy(
            ['slaughtered'=> false]
        );
        
        if(!$cattles)
            throw $this->createNotFoundException('Not found!');

        return $this->render('cattle/index.html.twig', [
            'title'=> 'Gerenciamento do Gado',
            'cattles'=> $cattles
        ]);
    }

    /**
     * @Route("/cattle/{id}", name="getById")
     */
    public function getById(ManagerRegistry $doctrine, int $id) : Response 
    {
        $cattle = $doctrine->getRepository(Cattle::class)->find($id);

        if(!$cattle)
            throw $this->createNotFoundException('No cattle found for id '.$id);

        return $this->render('cattle/cattle.html.twig', [
            'title'=> 'Gado',
            'cattle'=> $cattle
        ]);
    }

    /**
     * @Route("/cattle/edit/{id}", name="update")
     */
    public function update(ManagerRegistry $doctrine, int $id, Request $request, CattleService $cs): Response
    {
        $entityManager = $doctrine->getManager();
        
        $cattle = $entityManager->getRepository(Cattle::class)->find($id);

        if(!$cattle)
            throw $this->createNotFoundException('No cattle found for id '.$id);

        if($cs->Slaughter($cattle))
            $cattle->setSlaughter(true);
        else
            $cattle->setSlaughter(false);

        $form = $this->createForm(CattleType::class, $cattle);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('getAll');
        }

        return $this->renderForm('cattle/form.html.twig', [
            'title'=> 'Editar Registro',
            'form'=> $form,
            'new'=> false,
            'id'=> $id
        ]); 
    }

    /**
     * @Route("/slaughter/{id}", name="slaughter")
     */
    public function slaughter(ManagerRegistry $doctrine, CattleRepository $repository, int $id = null): Response
    {
        $entityManager = $doctrine->getManager();

        if($id){
            $cattle = $doctrine->getRepository(Cattle::class)->find($id);
            $cattle->setSlaughtered(true);
            $entityManager->flush();
            return $this->redirectToRoute('slaughter');
        }

        $cattles = $repository->getForSlaughter();

        if(!$cattles)
            throw $this->createNotFoundException('Not found!');
        
        return $this->render('cattle/slaughter.html.twig', [
            'title'=> 'Abate do Gado',
            'cattles'=> $cattles
        ]);
    }

    /**
     * @Route("/slaughtered", name="slaughtered")
     */
    public function slaughtered(CattleRepository $repository): Response 
    {
        $cattles = $repository->getSlaughtered();

        if(!$cattles)
            throw $this->createNotFoundException('Not found!');

        return $this->render('cattle/slaughtered.html.twig', [
            'title'=> 'Animais Abatidos',
            'cattles'=> $cattles
        ]);
    }

    /**
     * @Route("/milk", name="milkWeek")
     */
    public function milk(CattleRepository $repository): Response 
    {
        $milk = $repository->getMilk();

        if(!$milk)
            throw $this->createNotFoundException('Not found!');

        return $this->render('cattle/milk.html.twig', [
            'title'=> 'Leite produzido por semana',
            'milk'=> $milk
        ]);
    }

    /**
     * @Route("/ration", name="rationWeek")
     */
    public function ration(CattleRepository $repository): Response 
    {
        $ration = $repository->getRation();

        if(!$ration)
            throw $this->createNotFoundException('Not found!');

        return $this->render('cattle/ration.html.twig', [
            'title'=> 'Ração necessária por semana',
            'ration'=> $ration
        ]);
    }
}