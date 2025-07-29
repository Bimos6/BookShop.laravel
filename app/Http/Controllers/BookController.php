<?

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Actions\Books\CreateBookAction;
use App\Actions\Books\UpdateBookAction;
use App\Actions\Books\DeleteBookAction;
use App\Http\Requests\BookRequest;
use App\Services\BookService;

class BookController extends Controller
{
    public function __construct(
        private BookService $bookService,
        private CreateBookAction $createBook,
        private UpdateBookAction $updateBook,
        private DeleteBookAction $deleteBook
    ) {}

    public function index()
    {
        return view('books.index', $this->bookService->getBooksData());
    }

    public function show(Book $book)
    {
        $book->load('author'); 
        return view('books.show', compact('book'));
    }

    public function store(BookRequest $request)
    {
        $this->createBook->handle($request->validated());
        return redirect()->route('books.index')->with('success', 'Книга добавлена');
    }

    public function edit(Book $book)
    {
        $authors = Author::orderBy('name')->get();
        return view('books.edit', compact('book', 'authors'));
    }

    public function update(BookRequest $request, Book $book)
    {
        $this->updateBook->handle($book, $request->validated());
        return redirect()->route('books.index')
               ->with('success', 'Книга успешно обновлена');
    }

    public function destroy(Book $book)
    {
        $this->deleteBook->handle($book);
        return redirect()->route('books.index')->with('success', 'Книга удалена');
    }
}