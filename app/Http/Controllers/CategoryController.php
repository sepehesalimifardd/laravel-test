<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $category = new Category(['name' => $request->name]);

        if ($request->parent_id) {
            $parent = Category::find($request->parent_id);
            $parent->addChild($category);
        } else {
            $category->save();
            DB::table('category_closure')->insert([
                'ancestor_id' => $category->id,
                'descendant_id' => $category->id,
                'depth' => 0
            ]);
        }

        return response()->json($category, 201);
    }

    public function children($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category->children);
    }

    public function descendants($id)
    {
        $descendants = DB::table('category_closure as c1')
            ->join('categories', 'categories.id', '=', 'c1.descendant_id')
            ->where('c1.ancestor_id', $id)
            ->where('c1.depth', '>', 0)
            ->orderBy('c1.depth')
            ->select('categories.*', 'c1.depth')
            ->get();

        return response()->json($descendants);
    }

    public function ancestors($id)
    {
        $ancestors = DB::table('category_closure as c1')
            ->join('categories', 'categories.id', '=', 'c1.ancestor_id')
            ->where('c1.descendant_id', $id)
            ->where('c1.depth', '>', 0)
            ->orderBy('c1.depth', 'desc')
            ->select('categories.*', 'c1.depth')
            ->get();

        return response()->json($ancestors);
    }

    public function move(Request $request, $id)
    {
        $request->validate([
            'new_parent_id' => 'required|exists:categories,id'
        ]);

        $category = Category::findOrFail($id);
        $newParent = Category::find($request->new_parent_id);

        if ($id == $request->new_parent_id ||
            $newParent->descendants->contains('id', $id)) {
            return response()->json([
                'message' => 'Invalid parent selection'
            ], 422);
        }

        $category->moveTo($newParent);

        return response()->json([
            'message' => 'Category moved successfully'
        ]);
    }
}
