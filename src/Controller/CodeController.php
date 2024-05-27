<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\ElasticsearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CodeController extends AbstractController
{
    private $elasticsearchService;

    public function __construct(ElasticsearchService $elasticsearchService)
    {
        $this->elasticsearchService = $elasticsearchService;
    }

    #[Route('/code', name: 'app_code')]
        public function index(): Response
        {
            return $this->render('code/index.html.twig', [
                'controller_name' => 'CodeController',
            ]);
        }

    #[Route('/code/getCode/{searchWord}', name: 'app_get_code')]
    public function getCode(string $searchWord)
    {
        $results = $this->elasticsearchService->search($searchWord, 1);
        // dd($results);
        if (!empty($results)) {
            return new JsonResponse(['data' => $results]);
        }
        return new JsonResponse(['error' => 'Code not found']);
    }
    
    #[Route('/code/getGroupOfCode/{searchWord}/{quantityCodes}', name: 'app_get_group_of_code')]
    public function getGroupOfCode(string $searchWord, int $quantityCodes)
    {
        $results = $this->elasticsearchService->search($searchWord, $quantityCodes);
        dd($results);
        return new JsonResponse(['data' => $results]);
    }
}
