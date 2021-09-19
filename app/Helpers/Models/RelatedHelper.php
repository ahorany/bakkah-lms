<?php
namespace App\Helpers\Models;

use App\Helpers\Models\Training\SessionHelper;
use App\Models\Admin\Post;
use App\Models\Training\Course;
use App\Models\Admin\RelatedItem;

class RelatedHelper {

    public function Articles($post_id, $item_type_id, $parent_type_id, $col = 'parent_id', $pluck = 'item_id'){

        $relatedArticles = $this->getRelatedItems($post_id, $item_type_id, $parent_type_id, $col, $pluck);
        // if($parent_type_id == 471) {
        //     $relatedArticles = RelatedItem::where($col, $post_id)->pluck($pluck)->toArray();
        // }else {
        //     $relatedArticles = $this->getRelatedItems($post_id, $item_type_id, $parent_type_id, $col, $pluck);
        // }

        $relatedArticles = Post::with(['upload:uploadable_id,uploadable_type,file', 'postMorph.constant'])
        ->whereIn('id', $relatedArticles)
        ->lang()
        ->get();

        return $relatedArticles;
    }

    public function Courses($post_id, $item_type_id, $parent_type_id){

        // $RelatedCourses = RelatedItem::where('parent_id', $post_id)->get()->pluck('item_id')->toArray();
        $RelatedCourses = $this->getRelatedItems($post_id, $item_type_id, $parent_type_id);

        $SessionHelper = new SessionHelper();
        $RelatedCourses = $SessionHelper->Sessions(null, null, $RelatedCourses);

        return $RelatedCourses;
    }

    public function create($parent_id, $name = 'course_ids', $type = 472, $parent_type = 472){
        if(request()->has($name)) {
            foreach (request()->$name as $id) {
                $this->createItem($parent_id, $id, $type, $parent_type);
            }
        }
    }

    public function update($parent_id, $name = 'course_ids', $type = 472, $parent_type = 472){

        if(request()->has($name)) {

            foreach(request()->$name as $id) {

                $RelatedItem = RelatedItem::where('parent_id', $parent_id)
                ->where('item_type_id', $type)
                ->where('parent_type_id', $parent_type)
                ->where('item_id', $id)
                ->count();
                if($RelatedItem==0){
                    $this->createItem($parent_id, $id, $type, $parent_type);
                }
            }
            RelatedItem::where('parent_id', $parent_id)
            ->where('item_type_id', $type)
            ->where('parent_type_id', $parent_type)
            ->whereNotIn('item_id', request()->$name)
            ->delete();
        }
    }

    private function createItem($parent_id, $id, $item_type_id, $parent_type_id){
        RelatedItem::create([
            'parent_id' => $parent_id,
            'item_id' => $id,
            'item_type_id' => $item_type_id,
            'parent_type_id' => $parent_type_id,
        ]);
    }

    public function getRelatedItems($parent_id, $item_type_id, $parent_type_id, $col = 'parent_id', $pluck = 'item_id') {
        //$relatedArticles = RelatedItem::where($col, $post_id)->pluck($pluck)->toArray();

        if($parent_type_id == 471) {
            return RelatedItem::where($col, $parent_id)
            ->where('item_type_id', $parent_type_id)
            ->where('parent_type_id', $item_type_id)
            ->pluck($pluck)
            ->toArray();
        }else {
            return RelatedItem::where($col, $parent_id)
            ->where('item_type_id', $item_type_id)
            ->where('parent_type_id', $parent_type_id)
            ->pluck($pluck)
            ->toArray();
        }

    }
}
