<?

namespace App\Services;

use App\Models\Book;
use Illuminate\Http\Request;

class BookService
{
    public function __construct(private Request $request) {}

    public function getBooksData(): array
    {
        return [
            'books' => $this->getFilteredBooks(),
            'genres' => Book::distinctGenres()
        ];
    }

    protected function getFilteredBooks()
    {
        return Book::with('author')
            ->when($this->request->genre, fn($q, $genre) => $q->whereGenre($genre))
            ->when($this->request->pages, $this->applyPagesFilter())
            ->orderByField($this->request->sort, $this->request->direction)
            ->paginate(10)
            ->withQueryString();
    }

    protected function applyPagesFilter()
    {
        return match($this->request->pages) {
            '0-100' => fn($q) => $q->where('pages', '<=', 100),
            '100-300' => fn($q) => $q->whereBetween('pages', [100, 300]),
            '300+' => fn($q) => $q->where('pages', '>', 300),
            default => fn($q) => $q
        };
    }
}