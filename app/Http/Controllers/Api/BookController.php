<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\{JsonResponse, Request, Response};
use App\Services\BookService;


/**
 * @OA\Schema(
 *     schema="Book",
 *     required={"title", "author", "published_at", "genre", "amount_words", "price"},
 *     @OA\Property(property="title", type="string", example="Book title"),
 *     @OA\Property(property="author", type="string", example="Author name"),
 *     @OA\Property(property="published_at", type="string", example="2021-01-01"),
 *     @OA\Property(property="genre", type="string", example="Genre"),
 *     @OA\Property(property="amount_words", type="integer", example=1000),
 *     @OA\Property(property="price", type="number", example=10.99),
 *     @OA\Property(property="cyrency", type="string", example="USD"),
 * )
 * 
 * @OA\Info(
 *     title="Book API",
 *     version="1.0.0",
 *     description="Book API"
 * )
 */
class BookController extends Controller
{
    private BookService $bookService;

    /**
     * BookController constructor
     *
     * @param BookService $bookService
     */
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
    * Select all books
    *
    * @return JsonResponse

    * @OA\Get(
    *   path="/api/books",
    *   summary="Select all books",
    *   tags={"Books"},
    *   @OA\Response(
    *     response=200,
    *     description="Successful operation",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string", example="success"),
    *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Book")),
    *     ),
    *   ),
    *   @OA\Response(
    *     response=404,
    *     description="No books found",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string", example="error"),
    *       @OA\Property(property="message", type="string", example="No books found"),
    *     ),
    *   ),
    * )
    */
    public function index(): JsonResponse
    {
        $collection = $this->bookService->getCollection();

        if (! $collection) {
            return response()->json([
                'status' => 'error',
                'message' => 'No books found'
            ])->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data' => $collection
        ]);
    }

    /**
    * Select a book by id
    *
    * @param int $id
    * @return JsonResponse

    * @OA\Get(
    *   path="/api/books/{id}",
    *   summary="Select a book by id",
    *   tags={"Books"},
    *   @OA\Parameter(
    *     name="id",
    *     in="path",
    *     required=true,
    *     description="Book ID",
    *     @OA\Schema(
    *       type="integer",
    *       format="int64"
    *     )
    *   ),
    *   @OA\Response(
    *     response=200,
    *     description="Successful operation",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string", example="success"),
    *       @OA\Property(property="data", ref="#/components/schemas/Book"),
    *     ),
    *   ),
    *   @OA\Response(
    *     response=404,
    *     description="Book not found",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string", example="error"),
    *       @OA\Property(property="message", type="string", example="Book not found"),
    *     ),
    *   ),
    * )
    */
    public function getById(int $id): JsonResponse
    {
        $book = $this->bookService->getByID((int) $id);

        if (! $book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ])->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data' => $book
        ]);
    }

    /**
    * Create a book
    * 
    * @return JsonResponse
    *
    * @OA\Post(
    *     path="/api/books",
    *     summary="Create a book",
    *     tags={"Books"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"title", "author", "published_at", "genre", "amount_words", "price"},
    *             @OA\Property(property="title", type="string", example="Book title"),
    *             @OA\Property(property="publisher", type="string", example="Publisher name"),
    *             @OA\Property(property="author", type="string", example="Author name"),
    *             @OA\Property(property="published_at", type="string", example="2021-01-01"),
    *             @OA\Property(property="genre", type="string", example="Genre"),
    *             @OA\Property(property="amount_words", type="integer", example=1000),
    *             @OA\Property(property="price", type="number", example=10.99),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Successful operation",
    *         @OA\JsonContent(
    *             @OA\Property(property="status", type="string", example="success"),
    *             @OA\Property(property="data", ref="#/components/schemas/Book"),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=422,
    *         description="Validation error",
    *         @OA\JsonContent(
    *             @OA\Property(property="status", type="string", example="error"),
    *             @OA\Property(property="message", type="string", example="The given data was invalid."),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Internal server error",
    *         @OA\JsonContent(
    *             @OA\Property(property="status", type="string", example="error"),
    *             @OA\Property(property="message", type="string", example="Internal server error"),
    *         ),
    *     ),
    * )
    */
    public function create(): JsonResponse
    {
        try {
            request()->validate([
                'title' => 'required',
                'publisher' => 'required',
                'author' => 'required',
                'published_at' => 'required|date',
                'genre' => 'required',
                'amount_words' => 'required',
                'price' => 'required'
            ]);
        } catch (\Illuminate\Validation\ValidationException $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ])->setStatusCode($th->status);
        }

        try {
            $book = $this->bookService->create(request());
        } catch (\Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => 'success',
            'data' => $book
        ]);
    }

    /**
    * Update a book
    * 
    * @param int $id
    * @return JsonResponse
    * 
    * @OA\Patch(
    *   path="/api/books/{id}",
    *   summary="Update a book",
    *   tags={"Books"},
    *   @OA\Parameter(
    *     name="id",
    *     in="path",
    *     required=true,
    *     description="Book ID",
    *     @OA\Schema(
    *       type="integer",
    *       format="int64"
    *     )
    *   ),
    *   @OA\RequestBody(
    *     required=true,
    *     @OA\JsonContent(
    *       required={"title", "author", "published_at", "genre", "amount_words", "price"},
    *       @OA\Property(property="title", type="string", example="Book title"),
    *       @OA\Property(property="publisher", type="string", example="Publisher name"),
    *       @OA\Property(property="author", type="string", example="Author name"),
    *       @OA\Property(property="published_at", type="string", example="2021-01-01"),
    *       @OA\Property(property="genre", type="string", example="Genre"),
    *       @OA\Property(property="amount_words", type="integer", example=1000),
    *       @OA\Property(property="price", type="number", example=10.99),
    *     ),
    *   ),
    *   @OA\Response(
    *     response=200,
    *     description="Successful operation",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string", example="success"),
    *       @OA\Property(property="data", ref="#/components/schemas/Book"),
    *     ),
    *   ),
    *   @OA\Response(
    *     response=422,
    *     description="Validation error",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string", example="error"),
    *       @OA\Property(property="message", type="string", example="The given data was invalid."),
    *     ),
    *   ),
    *   @OA\Response(
    *     response=404,
    *     description="Book not found",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string", example="error"),
    *       @OA\Property(property="message", type="string", example="Book not found"),
    *     ),
    *   ),
    *   @OA\Response(
    *     response=500,
    *     description="Internal server error",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string", example="error"),
    *       @OA\Property(property="message", type="string", example="Internal server error"),
    *     ),
    *   ),
    * )
    */
    public function update(int $id): JsonResponse
    {
        try {
            request()->validate([
                'title' => 'nullable',
                'publisher' => 'nullable',
                'author' => 'nullable',
                'published_at' => 'nullable|date',
                'genre' => 'nullable',
                'amount_words' => 'nullable',
                'price' => 'nullable'
            ]);
        } catch (\Illuminate\Validation\ValidationException $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ])->setStatusCode($th->status);
        }

        try {
            $book = $this->bookService->update(request(), $id);

            if (! $book) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Book not found'
                ])->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => 'success',
            'data' => $book
        ]);
    }

    /**
    * Delete a book
    *
    * @param int $id
    * @return JsonResponse

    * @OA\Delete(
    *   path="/api/books/{id}",
    *   summary="Delete a book",
    *   tags={"Books"},
    *   @OA\Parameter(
    *     name="id",
    *     in="path",
    *     required=true,
    *     description="Book ID",
    *     @OA\Schema(
    *       type="integer",
    *       format="int64"
    *     )
    *   ),
    *   @OA\Response(
    *     response=200,
    *     description="Successful operation",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string", example="success"),
    *       @OA\Property(property="message", type="string", example="Book deleted successfully"),
    *     ),
    *   ),
    *   @OA\Response(
    *     response=404,
    *     description="Book not found",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string", example="error"),
    *       @OA\Property(property="message", type="string", example="Book not found"),
    *     ),
    *   ),
    * )
    */
    public function delete(int $id): JsonResponse
    {
        if (! $this->bookService->delete($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ])->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Book deleted successfully'
        ]);
    }
}
