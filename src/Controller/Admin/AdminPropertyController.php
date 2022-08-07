<?php
namespace App\Controller\Admin;

use App\Entity\Propertys;
use App\Form\PropertyType;
use App\Repository\PropertysRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPropertyController extends AbstractController {

    /**
     * @var PropertysRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(PropertysRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin.property.index")
     * @return Response
     */
    public function index()
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig', compact('properties'));
    }

    /**
     * @Route("/admin/property/create", name="admin.property.new")
     */
    public function new(Request $request) 
    {
        $propertys = new Propertys();
        $form = $this->createForm(PropertyType::class, $propertys);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($propertys);
            $this->em->flush();
            $this->addFlash('success', 'Bien créer avec succès');
            return $this->redirectToRoute('admin.property.index');
        }
            return $this->render('admin/property/new.html.twig', [
            'propertys' => $propertys,
            'form'      => $form->createView()
        ]);
}

    /**
     * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
     * @param Propertys $propertys
     * @param Request $request
     * @return \Symfony\Component\HtppFoundation\Response
     */
    public function edit(Propertys $propertys, Request $request)
    {
        $form = $this->createForm(PropertyType::class, $propertys);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig', [
            'propertys' => $propertys,
            'form'      => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
     * @param Propertys $propertys
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Propertys $propertys, Request $request) {
        if ($this->isCsrfTokenValid('delete' . $propertys->getId(), $request->get('_token'))) {
            $this->em->remove($propertys);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès');
        }
        return $this->redirectToRoute('admin.property.index');
    }

}