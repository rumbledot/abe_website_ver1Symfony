<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Form\Type\ProfileType\profileNewType;
use App\Form\Type\ProfileType\profileUpdateType;

class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="_user_profile")
     * @Method({"GET"})
     * @Security("is_granted('ROLE_USER')")
     * @Template()
     */
    public function indexAction()
    {
        $em         = $this->get('doctrine')->getManager();
        $curr_user  = $this->get('security.token_storage')->getToken()->getUser();
        
        $user       = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                'id' => $curr_user->getId(),
                ]);

        $profile    = $user->getProfile();
        
        if (!$profile) {
            return $this->redirectToRoute('_user_profile_new');
        }

        return array(
            'profile'  => $profile,
        );
    }

    /**
     * @Route("/new", name="_user_profile_new")
     * @Method({"GET"})
     * @Security("is_granted('ROLE_USER')")
     * @Template()
     */
    public function newAction(Request $req)
    {
        $em         = $this->get('doctrine')->getManager();
        $curr_user  = $this->get('security.token_storage')->getToken()->getUser();
        
        $user       = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                'id' => $curr_user->getId(),
                ]);

        $profile    = new UserProfile();

        $form = $this->createForm(profileNewType::class, $profile, array(
        ));
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid())
        {            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($profile);
            $entityManager->flush();

            $user->setProfile($profile);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('_blogs');
        }
        
        return array (
            'form'      => $form->createView(),
        );
    }

    /**
     * @Route("/update", name="_user_profile_update")
     * @Method({"GET"})
     * @Security("is_granted('ROLE_USER')")
     * @Template()
     */
    public function updateAction(Request $req)
    {
        $em         = $this->get('doctrine')->getManager();
        $curr_user  = $this->get('security.token_storage')->getToken()->getUser();

        $user       = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                'id'        => $curr_user->getId(),
                ]);

        $profile    = $user->getProfile();

        $form = $this->createForm(profileUpdateType::class, null, array(
            'profile'   => $profile,
        ));
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid())
        {
            if ($profile->getFirstName() != $form->get('first_name')->getData())
                $profile->setFirstName($form->get('first_name')->getData());
            if ($profile->getLastName() != $form->get('last_name')->getData())
                $profile->setLastName($form->get('last_name')->getData());
            if ($profile->getPhone() != $form->get('phone')->getData())
                $profile->setPhone($form->get('phone')->getData());
            if ($profile->getMobile() != $form->get('mobile')->getData())
                $profile->setMobile($form->get('mobile')->getData());
            if ($profile->getAddress() != $form->get('address')->getData())
                $profile->setAddress($form->get('address')->getData());
            if ($profile->getPostcode() != $form->get('postcode')->getData())
                $profile->setPostcode($form->get('postcode')->getData());
            if ($profile->getBio() != $form->get('bio')->getData())
                $profile->setBio($form->get('bio')->getData());
            if ($profile->getBirthday() != $form->get('birthday')->getData())
                $profile->setBirthday($form->get('birthday')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($profile);
            $entityManager->flush();

            return $this->redirectToRoute('_user_profile');
        }
        
        return array (
            'form'      => $form->createView(),
        );
    }
    
}