<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Form\Type\UserType\userNewType;
use Symfony\Component\Form\FormError;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_signin")
     */
    public function signin(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/check_user/{username}", 
     *      name    ="app_check_user",
     *      options = {"expose" = true})
     * @Method({"POST"})
     */
    public function checkuser($username)
    {
        $res = 'false';
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);

        if ($user) {
            $res = 'true';
        }
        return new JsonResponse ($res);
    }

    /**
     * @Route("/signup/{username}",
     *      defaults= {"username"="username"},
     *      name    = "app_signup",
     *      options = {"expose" = true})
     */
    public function signup($username, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();

        $keywords = array('__halt_compiler', 'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch', 'class', 'clone', 'const', 'continue', 'declare', 'default', 'die', 'do', 'echo', 'else', 'elseif', 'empty', 'enddeclare', 'endfor', 'endforeach', 'endif', 'endswitch', 'endwhile', 'eval', 'exit', 'extends', 'final', 'for', 'foreach', 'function', 'global', 'goto', 'if', 'implements', 'include', 'include_once', 'instanceof', 'insteadof', 'interface', 'isset', 'list', 'namespace', 'new', 'or', 'print', 'private', 'protected', 'public', 'require', 'require_once', 'return', 'static', 'switch', 'throw', 'trait', 'try', 'unset', 'use', 'var', 'while', 'xor', 'admin', 'username');
        $predefined_constants = array('__CLASS__', '__DIR__', '__FILE__', '__FUNCTION__', '__LINE__', '__METHOD__', '__NAMESPACE__', '__TRAIT__');

        // create user object
        $user = new User();
        
        // form
        $form = $this->createForm(userNewType::class, null, array(
            'username' => htmlspecialchars($username),
        ));
        $form->handleRequest($request);

        // form submission
        if ($form->isSubmitted() && $form->isValid()) {
            
            // get data from form values
            $username   = $form->get('username')->getData();
            $password   = $form->get('password1')->getData();
            $email      = $form->get('email')->getData();

            if (in_array($username, $keywords)) {
                return $this->render('security/signup.html.twig', array(
                    'form'      => $form->createView(),
                    'username'  => $username,
                    'error'     => 'Please check your username'
                ));
            } else {
                $user->setUsername(htmlspecialchars($username));

                $password   = htmlspecialchars($password);
                $password   = $encoder->encodePassword($user, $password);
                $user->setPassword(htmlspecialchars($password));

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $user->setEmail(htmlspecialchars($email));
                } else {
                    return $this->render('security/signup.html.twig', array(
                        'form'      => $form->createView(),
                        'username'  => $username,
                        'error'     => 'Please check your email'
                    ));
                }

                $user->setRoles(array('ROLE_USER'));

                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('app_signin');
            }

        }
        
        return $this->render('security/signup.html.twig', array(
            'form'      => $form->createView(),
            'username'  => $username,
            'error'     => null
        ));
    }

    /**
     * @Route("/logout", name="app_signout")
     */
    public function signout()
    {
        return new RedirectResponse($this->urlGenerator->generate('_blogs'));
    }
}