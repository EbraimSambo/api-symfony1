<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
//meu metodo

use App\Repository\BookRepository;

class BookController extends AbstractController
{
    #[Route('/books', name: 'book_list', methods:['GET'])]
    public function index(BookRepository $bookRepository): JsonResponse
    {
        return $this->json([
            'data' => $bookRepository->findAll(),
        ]);
    }

    #[Route('/books/{book}', name: 'single-book', methods:['GET'])]
    public function singleBook(int $book, BookRepository $bookRepository): JsonResponse
    {
        $book= $bookRepository->find($book);
        if (!$book) throw $this->createNotFoundException();

        return $this->json([
            'data' => $book,
        ]);
    }

    #[Route('/bookcreate', name: 'app_create', methods:['POST'])]
    public function create(Request $request, BookRepository $bookRepository): JsonResponse
    {
        $data = $request->request->all();

        $book = new Book();
        $book->setTitle($data["title"]);
        $book->setIsbn($data["isbn"]);
        $book->setCreatedAt(new \DateTimeImmutable('now'));
        $book->setUpdateAt(new \DateTimeImmutable());

        $bookRepository->save($book, true);
        return $this->json([
            'message' => 'Sucesso',
            'data'=> $book
        ], 201);
    }

    #[Route('/book-update/{book}', name: 'update', methods:['PUT', 'PATCH'])]
    public function update(int $book, Request $request, ManagerRegistry $doctrine,BookRepository $bookRepository): JsonResponse
    {

        $book = $bookRepository->find($book);
        if (!$book) throw $this->createNotFoundException();
        $data = $request->request->all();
        $book->setTitle($data["title"]);
        $book->setIsbn($data["isbn"]);
        $book->setUpdateAt(new \DateTimeImmutable("now",new \DateTimeZone("American/Sao_Paulo")));

        $doctrine->getManager()->flush();
        return $this->json([
            'message' => 'Sucesso',
            'data'=> $book
        ], 201);
    }

    #[Route('/book-update/{book}', name: 'update', methods:['PUT', 'PATCH'])]
    public function delete(int $book, Request $request, BookRepository $bookRepository): JsonResponse
    {

        if($request->headers->get('Content-Type')=='application/json'){

        }else{
            return $this->json([
                'header-type' => $request->headers->get('Content-Type'),

            ]);
        }

    }

}
