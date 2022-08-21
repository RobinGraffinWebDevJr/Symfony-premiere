<?php
namespace App\Controller;

use App\Entity\Propertys;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertysRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PropertyController extends AbstractController
{

    /**
     * @var PropertysRepository
     */
    private $repository;
    private $em;

    public function __construct(PropertysRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->em = $entityManager;
    }

    /**
     * @Route("/biens", name="property.index")
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class , $search);
        $form->handleRequest($request);

        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('property/index.html.twig',  [
            'current_menu' => 'properties',
            'properties'   => $properties,
            'form'         => $form->createView()
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show(Propertys $propertys, string $slug): Response
    {
        if ($propertys->getSlug() !== $slug) {
            return $this->redirectToRoute('property.show', [
                'id' => $propertys->getId(),
                'slug' => $propertys-getSlug()
            ], 301);
        }
        return $this->render('property/show.html.twig', [
            'propertys' => $propertys,
            'current_menu' =>'properties'
        ]);
    }
}