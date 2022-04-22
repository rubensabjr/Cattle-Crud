<?php

namespace App\Controller;

use App\Entity\Cattle;
use App\Form\CattleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CattleController extends AbstractController{

    /**
     * @Route("/cattle/new", name="create")
     */
    public function create(ManagerRegistry $doctrine, Request $request) : Response {
        $entityManager = $doctrine->getManager();
        
        $cattle = new Cattle();
        $form = $this->createForm(CattleType::class, $cattle);
        $form->handleRequest($request);
        
        ##Temporário
        $cattle->setSlaughter(false);
        $cattle->setSlaughtered(false);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($cattle);
            $entityManager->flush();
            return $this->redirectToRoute('getAll');
        }

        return $this->renderForm('cattle/form.html.twig', [
            'tittle'=> 'Novo Registro',
            'form'=> $form
        ]); 
    }

    /**
     * @Route("/cattle", name="getAll")
     */
    public function getAll(ManagerRegistry $doctrine) : Response {
        $cattles = $doctrine->getRepository(Cattle::class)->findAll();
        
        if(!$cattles)
            throw $this->createNotFoundException('Not found!');

        return $this->render('cattle/index.html.twig', [
            'tittle'=> 'Gerenciamento do Gado',
            'cattles'=> $cattles
        ]);
    }

    /**
     * @Route("/cattle/{id}", name="getById")
     */
    public function getById(ManagerRegistry $doctrine, int $id) : Response {
        $cattle = $doctrine->getRepository(Cattle::class)->find($id);

        if(!$cattle)
            throw $this->createNotFoundException('No cattle found for id '.$id);

        return $this->render('cattle/cattle.html.twig', [
            'tittle'=> 'Gado',
            'cattle'=> $cattle
        ]);
    }

    /**
     * @Route("/cattle/edit/{id}", name="update")
     */
    public function update(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        
        $cattle = $entityManager->getRepository(Cattle::class)->find($id);

        if(!$cattle)
            throw $this->createNotFoundException('No cattle found for id '.$id);

        $form = $this->createForm(CattleType::class, $cattle);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('getAll');
        }

        return $this->renderForm('cattle/form.html.twig', [
            'tittle'=> 'Editar Registro',
            'form'=> $form
        ]); 
    }
}