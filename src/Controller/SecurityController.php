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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $em;

    private $paginator;


    public function __construct(EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->em = $em;

        $this->paginator = $paginator;
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('home');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route ("/admin/users", name="admin.user")
     * @param UserRepository $userRepository
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