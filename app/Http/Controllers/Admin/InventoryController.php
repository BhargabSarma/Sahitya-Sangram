<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Book;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::with('book')->get();
        return view('admin.inventory.index', compact('inventories'));
    }

    public function create()
    {
        $books = Book::all();
        return view('admin.inventory.create', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'stock' => 'required|integer|min:0',
            'location' => 'nullable|string',
        ]);

        Inventory::create($request->all());

        return redirect()->route('admin.inventory.index')->with('success', 'Inventory added!');
    }

    public function edit(Inventory $inventory)
    {
        $books = Book::all();
        return view('admin.inventory.edit', compact('inventory', 'books'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'stock' => 'required|integer|min:0',
            'location' => 'nullable|string',
        ]);

        $inventory->update($request->all());

        return redirect()->route('admin.inventory.index')->with('success', 'Inventory updated!');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('admin.inventory.index')->with('success', 'Inventory deleted!');
    }
}
