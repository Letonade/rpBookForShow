<?php

declare(strict_types=1);

namespace App\Controller\FrontTwig\Home;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Document\Shop;
use App\Entity\Role;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Exception;

class FrontTwigHomeAction extends AbstractController
{

    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    public function __construct(
        DocumentManager $documentManager,
        ManagerRegistry $managerRegistry,
        ValidatorInterface $validator
    ) {
        $this->documentManager = $documentManager;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @throws Exception
     */
    public function __invoke()
    {
        return $this->render('Home/home.html.twig', [
        //    Args come here to be set on the front
        ]);
    }

}
