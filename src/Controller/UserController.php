<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
}
