<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\AdRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController // Sans EasyAdmin pour le skill
{

    private $em;

    private $paginator;


    public function __construct(EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->em = $em;

        $this->paginator = $paginator;
    }


    /**
     * @Route ("/admin/users", name="admin.user")
     * @param UserRepository $userRepository
     * @param Request $request
     * @return Response
     */
    public function adminUser(UserRepository $userRepository, Request $request):Response
    {
        $query = $userRepository->findAll();
        $users = $this->paginator->paginate(
            $query,
            $request->get('page', 1),
            6
        );


        return $this->render('security/adminUser.html.twig', ['users' => $users]);
    }

    /**
     * @Route ("/admin/ads", name="admin.ads")
     * @param AdRepository $adRepository
     * @param Request $request
     * @return Response
     */
    public function adminAd(AdRepository $adRepository, Request $request):Response
    {
        $query = $adRepository->findAll();
        $ads = $this->paginator->paginate(
            $query,
            $request->get('page', 1),
            6
        );

        return $this->render('security/adminAd.html.twig', ['ads' => $ads]);
    }

    /**
     * @Route ("/admin/tags", name="admin.tags")
     * @param TagRepository $tagRepository
     * @param Request $request
     * @return Response
     */
    public function adminTag(TagRepository $tagRepository, Request $request):Response
    {
        $tags = $tagRepository->findAll();
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($tag);
            $this->em->flush();

            return $this->redirectToRoute('admin.tags');
        }

        return $this->render('security/adminTag.html.twig', [
            'tags' => $tags,
            'form' => $form->createView()
        ]);
    }
}