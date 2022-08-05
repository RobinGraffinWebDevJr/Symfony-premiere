<?php
namespace App\Controller;

use App\Entity\Propertys;
use App\Repository\PropertysRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(): Response
    {
        return $this->render('property/index.html.twig',  [
            'current_menu' => 'properties'
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show(Propertys $propertys, string $slug): Response
    {
        if ($propertys->getSlug() === $slug) {
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