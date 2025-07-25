<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function ancestors()
    {
        return $this->belongsToMany(Category::class, 'category_closure',
            'descendant_id', 'ancestor_id')
            ->withPivot('depth')
            ->wherePivot('depth', '>', 0)
            ->orderBy('depth', 'desc');
    }

    public function descendants()
    {
        return $this->belongsToMany(Category::class, 'category_closure',
            'ancestor_id', 'descendant_id')
            ->withPivot('depth')
            ->wherePivot('depth', '>', 0);
    }

    public function children()
    {
        return $this->descendants()
            ->wherePivot('depth', 1);
    }

    public function addChild($child)
    {
        $child = is_int($child) ? Category::find($child) : Category::create(['name' => $child]);

        $this->insertClosureRelations($child);

        return $child;
    }

    public function moveTo($newParent)
    {
        $newParent = is_int($newParent) ? Category::find($newParent) : $newParent;

        DB::table('category_closure')
            ->where('descendant_id', $this->id)
            ->delete();

        $newParent->insertClosureRelations($this);
    }

    protected function insertClosureRelations(Category $child)
    {
        $ancestors = DB::table('category_closure')
            ->where('descendant_id', $this->id)
            ->select('ancestor_id', 'depth')
            ->get();

        $ancestors->push((object)[
            'ancestor_id' => $this->id,
            'depth' => 0
        ]);

        $relations = [];
        foreach ($ancestors as $ancestor) {
            $relations[] = [
                'ancestor_id' => $ancestor->ancestor_id,
                'descendant_id' => $child->id,
                'depth' => $ancestor->depth + 1
            ];
        }

        DB::table('category_closure')->insert($relations);
    }



/*    // دریافت تمام نوادگان با CTE
    public function getAllDescendants()
    {
        return DB::select("
        WITH RECURSIVE descendants AS (
            SELECT descendant_id, depth
            FROM category_closure
            WHERE ancestor_id = ? AND depth > 0

            UNION ALL

            SELECT c.descendant_id, c.depth
            FROM category_closure c
            INNER JOIN descendants d ON d.descendant_id = c.ancestor_id
        )
        SELECT categories.*, descendants.depth
        FROM descendants
        JOIN categories ON categories.id = descendants.descendant_id
    ", [$this->id]);
    }

// دریافت تمام اجداد با CTE
    public function getAllAncestors()
    {
        return DB::select("
        WITH RECURSIVE ancestors AS (
            SELECT ancestor_id, depth
            FROM category_closure
            WHERE descendant_id = ? AND depth > 0

            UNION ALL

            SELECT c.ancestor_id, c.depth
            FROM category_closure c
            INNER JOIN ancestors a ON a.ancestor_id = c.descendant_id
        )
        SELECT categories.*, ancestors.depth
        FROM ancestors
        JOIN categories ON categories.id = ancestors.ancestor_id
        ORDER BY depth DESC
    ", [$this->id]);
    }*/
}
