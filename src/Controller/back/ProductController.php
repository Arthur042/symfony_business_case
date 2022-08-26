<?php

namespace App\Controller\back;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CommentRepository;
use App\Repository\ContainRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product')]
class ProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager,
        private CommentRepository $commentRepository,
        private ContainRepository $containRepository,
    ){}
    #[Route('/', name: 'app_admin_product', methods: ['GET'])]
    public function index(
        Request $request,
        PaginatorInterface $paginator,
    ): Response
    {
        $products = $paginator->paginate(
            $this->productRepository->getQbAll(),
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('back/product/index.html.twig', [
            'products' => $products,
        ]);
    }
//    todo AJOUTER L UPLOADER DE FICHIER POUR METTRE LES IMAGES ET MODIFIER LES CATEGORIES
    #[Route('/new', name: 'app_admin_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedAt(new \DateTime('now'));
            $tva = $product->getTva() / 100;
            $product->setTva($tva);
            $productRepository->add($product, true);

            return $this->redirectToRoute('app_admin_product', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/detail/{id}', name: 'app_admin_product_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $detailProduct = $this->productRepository->getDetailProduct($id);
        return $this->render('back/product/show.html.twig', [
            'product' => $detailProduct[0][0],
            'note' => $detailProduct[0]['average'],
            'totalSell' => $detailProduct[0]['totalSell'],
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tva = $product->getTva() / 100;
            $product->setTva($tva);
            $productRepository->add($product, true);

            return $this->redirectToRoute('app_admin_product', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_product_delete')]
    public function delete(Product $product): Response
    {

        if($product = $this->productRepository->find($product->getId())) {
            $contains = $this->containRepository->findBy(['products' => $product]);
            foreach($contains as $contain){
                $contain->setProducts(null);
            }
            $comments = $this->commentRepository->findBy(['product' => $product]);
            foreach($comments as $comment){
                $product->removeComment($comment);
            }
            $this->entityManager->remove($product);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_product');
    }
}