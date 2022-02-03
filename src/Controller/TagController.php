<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class TagController extends AbstractController
{
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route ("/admin/tags/{id}/edit", name="admin.tags.edit")
     * @param Tag $tag
     * @param Request $request
     * @return Response
     */
    public function editTag(Tag $tag, Request $request): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($tag);
            $this->em->flush();

            return $this->redirectToRoute('admin.tags');
        }

        return $this->render('Security/adminTagEdit.html.twig', [
            'tag'=>$tag,
            'form' => $form->createView()
        ]);
    }


}
