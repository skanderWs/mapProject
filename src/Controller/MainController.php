<?php

namespace App\Controller;
use App\Service\Params;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MainController extends AbstractController
{

    protected $entityManager;
    protected $passwordEncoder;
    protected $validator;
    protected $message;

    protected $serializer;
    protected $tokenGenerator;
    protected $mailer;
    protected $security;

    const HEADER_CONTENT = "Content-Type";
    const CONTENT_TYPE = "application/json";


    /**
     * OrganizerController constructor.
     * @param Security $security
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $encoder
     * @param TokenGeneratorInterface $tokenGenerator
     */
    public function __construct(Security $security,
                                SerializerInterface $serializer,
                                EntityManagerInterface $entityManager,
                                ValidatorInterface $validator,
                                UserPasswordEncoderInterface $encoder,
                                TokenGeneratorInterface $tokenGenerator
    )
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $encoder;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->tokenGenerator = $tokenGenerator;
        $this->security = $security;
    }

    public function createResponse($entity)
    {
        $response = new Response($this->jmsGroupNormalize($entity));
        $response->headers->set(self::HEADER_CONTENT, self::CONTENT_TYPE);
        //$response->setStatusCode(200);
        return $response;
    }


    public function createErrorResponse($errors)
    {
        $response = new Response($this->jmsNormalize($this->getErrors($errors)));
        $response->headers->set(self::HEADER_CONTENT, self::CONTENT_TYPE);
        //$response->setStatusCode($code);
        return $response;
    }


    private function jmsNormalize($entity)
    {
        $data = $this->serializer->serialize($entity, 'json');
        return $data;
    }

    private function jmsGroupNormalize($entity)
    {
        /* @var SerializationContext $serializerContext */
        $serializerContext = SerializationContext::create();
        //$serializerContext->setGroups($groups);
        // if list limit depth else show all
       /* if (in_array(Params::LIST, $groups)){
            $serializerContext->enableMaxDepthChecks();
        }*/
        $data = $this->serializer->serialize($entity, 'json', $serializerContext);
        return $data;
    }


}
