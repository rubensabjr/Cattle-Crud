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
use Knp\Component\Pager\PaginatorInterface;

class CattleController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(CattleRepository $repository) : Response 
    {
        $milk = $repository->getMilk();
        $ration = $repository->getRation();

        return $this->render('base.html.twig', [
            'title'=> 'Bem Vindo!',
            'titleMilk'=> 'Leite produzido por semana',
            'titleRation'=> 'Ração necessária por semana',
            'milk'=> $milk,
            'ration'=> $ration
        ]);
    }

    /**
     * @Route("/cattle/new", name="create")
     */
    public function create(ManagerRegistry $doctrine, Request $request, CattleService $cs) : Response 
    {
        $entityManager = $doctrine->getManager();
        
        $cattle = new Cattle();
        $cattle->setSlaughtered(false);

        $form = $this->createForm(CattleType::class, $cattle);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($cs->Slaughter($cattle))
                $cattle->setSlaughter(true);
            else
                $cattle->setSlaughter(false);

            $entityManager->persist($cattle);
            $entityManager->flush();

            $this->addFlash('success', 'Animal registrado com sucesso!');

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
    public function getAll(ManagerRegistry $doctrine, PaginatorInterface $paginator, Request $request) : Response 
    {
        $cattles = $doctrine->getRepository(Cattle::class)->findBy(
            ['slaughtered'=> false]
        );

        $cattlesPage = $paginator->paginate(
            $cattles,
            $request->query->getInt('page', 1),
            7
        );

        return $this->render('cattle/index.html.twig', [
            'title'=> 'Gerenciamento do Gado',
            'cattles'=> $cattlesPage
        ]);
    }

    /**
     * @Route("/cattle/{id}", name="getById")
     */
    public function getById(ManagerRegistry $doctrine, int $id, CattleService $cs) : Response 
    {
        $entityManager = $doctrine->getManager();

        $cattle = $entityManager->getRepository(Cattle::class)->find($id);
        
        if(!$cattle || $cattle->getSlaughtered()){
            $this->addFlash('error', 'Animal não existe!');

            return $this->redirectToRoute('getAll');
        }

        if($cs->Slaughter($cattle)){
            $cattle->setSlaughter(true);
            $entityManager->flush();
        }
        
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

        if(!$cattle || $cattle->getSlaughtered()){
            $this->addFlash('error', 'Animal não existe!');

            return $this->redirectToRoute('getAll');
        }

        $form = $this->createForm(CattleType::class, $cattle);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($cs->Slaughter($cattle))
                $cattle->setSlaughter(true);
            else
                $cattle->setSlaughter(false);

            $entityManager->flush();

            $this->addFlash('success', 'Animal atualizado com sucesso!');

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
    public function slaughter(ManagerRegistry $doctrine, CattleRepository $repository, int $id = null, PaginatorInterface $paginator, Request $request, CattleService $cs): Response
    {
        $entityManager = $doctrine->getManager();

        if($id){
            $cattle = $doctrine->getRepository(Cattle::class)->find($id);

            if($cs->Slaughter($cattle) && !$cattle->getSlaughtered()){
                $cattle->setSlaughtered(true);
                $entityManager->flush();

                $this->addFlash('success', 'Animal abatido com sucesso!');
            }else
                $this->addFlash('error', 'Animal inválido para abate!');

            return $this->redirectToRoute('slaughter');
        }

        $cattles = $repository->getForSlaughter();

        $cattlesPage = $paginator->paginate(
            $cattles,
            $request->query->getInt('page', 1),
            7
        );
        
        return $this->render('cattle/slaughter.html.twig', [
            'title'=> 'Abate do Gado',
            'cattles'=> $cattlesPage
        ]);
    }

    /**
     * @Route("/slaughtered", name="slaughtered")
     */
    public function slaughtered(CattleRepository $repository, PaginatorInterface $paginator, Request $request): Response 
    {
        $cattles = $repository->getSlaughtered();

        $cattlesPage = $paginator->paginate(
            $cattles,
            $request->query->getInt('page', 1),
            7
        );

        return $this->render('cattle/slaughtered.html.twig', [
            'title'=> 'Animais Abatidos',
            'cattles'=> $cattlesPage
        ]);
    }
}