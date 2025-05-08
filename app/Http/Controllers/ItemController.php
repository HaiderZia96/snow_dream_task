<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\ItemCreated;
use App\Events\ItemUpdated;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        if ($request->has('status')) {
            $query->when($request->status === 'active', fn($q) => $q->active())
                ->when($request->status === 'inactive', fn($q) => $q->inactive());
        }

        if ($request->has(['min_price', 'max_price'])) {
            $query->priceRange($request->min_price, $request->max_price);
        }

        return $query->with('owner')->paginate(10);
    }

    public function show(Item $item)
    {
        return $item->load('owner');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $item = Item::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
            'owner_id' => Auth::id(),
        ]);

        broadcast(new ItemCreated($item))->toOthers();

        return response()->json($item->load('owner'), 201);
    }

    public function update(Request $request, $itemId)
    {
        $item = Item::find($itemId);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $item->update($request->only(['title', 'description', 'price', 'status']));

        return response()->json($item->load('owner'), 200);
    }



    public function destroy(Request $request, $itemId)
    {

        $item = Item::find($itemId);


        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }


        $this->authorize('delete', $item);


        $item->delete();

        return response()->json(['message' => 'Item deleted successfully'], 200);
    }
}
