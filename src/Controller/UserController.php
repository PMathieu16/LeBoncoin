<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\User;
use App\Form\AdType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/user/{id}/{slug}", name="user.show")
     * @param User $user
     * @return Response
     */

    public function show(User $user, string $slug): Response
    {
        if($user->getSlug() !== $slug) {
            return $this->redirectToRoute('user.show', [
                'id' => $user->getId(),
                'slug' => $user->getSlug(),
            ], 301);
        }

        return $this->render('Frontend/user.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route ("/user/{id}/{slug}/vote", name="user.vote", methods="post")
     * @return Response
     */

    public function userVote(User $user, Request $request):Response
    {
        $vote = $request->request->get('vote');
        if ($vote === 'up') {
            $user->setUpVote(($user->getUpVote() + 1));
        } elseif ($vote === 'down') {
            $user->setDownVote(($user->getDownVote() + 1));
        }

        $this->em->flush();

        return $this->redirectToRoute('user.show', [
            'id' => $user->getId(),
            'slug' => $user->getSlug()
        ]);
    }

    /**
     * @Route("/user/edit", name="user.edit")
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @return Response
     */
    public function userEdit(Request $request, UserPasswordHasherInterface $userPasswordHasher)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('user.show', [
                'id' => $user->getId(),
                'slug' => $user->getSlug(),
            ]);
        }

        return $this->render('Security/userEdit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/user/{id}/{slug}/delete", name="user.delete", requirements={"slug": "[a-z0-9\-]*"})
     * @param User $user
     * @param string $slug
     * @return Response
     */
    public function removeUser(User $user, string $slug):Response
    {
        if($user->getSlug() !== $slug) {
            return $this->redirectToRoute('user.delete', [
                'id' => $user->getId(),
                'slug' => $user->getSlug(),
            ], 301);
        }

        $this->em->remove($user);
        $this->em->flush();

        return $this->redirectToRoute('admin.user');
    }
}
