<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{

    /**
     * @Route ("/", name="home")
     */
    public function home(MenuRepository $menuRepository)
    {
        $menus = $menuRepository->findAll();
        $images = array();
        foreach ($menus as $key => $menu) {
            $images[$key] = base64_encode(stream_get_contents($menu->getFile()));
        }
        return $this->render("base.html.twig", ['menus' => $menus, 'images' => $images]);
    }

    /**
     * @Route ("/login",name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login.html.twig', ['lastUsername' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route ("/logout", name="logout")
     */
    public function logout()
    {

    }

    // TODO : SUPPRIMER APRES CREATION DE L'ADMIN

    /**
     * @Route ("/create_admin", name="CreateAdmin")
     */
    public function createAdmin(UserPasswordEncoderInterface $encoder, Request $request, EntityManagerInterface $em)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $user->setRole((array)"ROLE_ADMIN");
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("login");
        }
        return $this->render("regsitration.html.twig", ['form' => $form->createView()]);
    }

    // TODO : COMPRENDRE CETTE MERDE

    /**
     * @Route ("/disp", name="disp")
     */
    public function disp(MenuRepository $menuRepository)
    {
        $menus = $menuRepository->findAll();
        $images = array();
        foreach ($menus as $key => $menu) {
            $images[$key] = base64_encode(stream_get_contents($menu->getFile()));
        }
        return $this->render("disp.html.twig", ['menus' => $menus, 'images' => $images]);
    }

    /**
     * @Route ("display/{id}, name="display")
     */
    public function display($id, MenuRepository $menuRepository)
    {
        $picture = $menuRepository->find($id);
        return $this->render("display.html.twig", ["picture" => $picture]);
    }
}