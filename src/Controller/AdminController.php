<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\User;
use App\Form\MenuCreateType;
use App\Form\UserType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{

    /**
     * @Route ("/admin", name="adminPanel")
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminPanel()
    {
        return $this->render("admin/admin.html.twig");
    }

    /**
     * @Route ("/modify", name="modify")
     * @IsGranted ("ROLE_ADMIN")
     */
    public function modify(EntityManagerInterface $em, Request $request)
    {
        $menu = new Menu();
        $form = $this->createForm(MenuCreateType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($menu);
            $em->flush();
            return $this->redirectToRoute("home");
        }
        return $this->render("admin/mod.html.twig", ['form' => $form->createView()]);
    }

}