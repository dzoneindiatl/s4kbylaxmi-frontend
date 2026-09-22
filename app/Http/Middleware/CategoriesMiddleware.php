<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Category;
use Illuminate\Support\Facades\View;

class CategoriesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // $categories = new Category;
        // $all_categories = $categories->getAllCategories();
        // View::share('all_categories', $all_categories);

        // $all_menu_categories = $categories->getAllMenuCategories();
        // View::share('all_menu_categories', $all_menu_categories);

        // $all_home_categories = $categories->getAllHomeCategories();
        // View::share('all_home_categories', $all_home_categories);

        // $all_menu_colletions = $categories->getAllMenuCollections();
        // View::share('all_menu_colletions', $all_menu_colletions);

        // $all_home_collections = $categories->getAllHomeCollections();
        // View::share('all_home_collections', $all_home_collections);

        return $next($request);
    }
}
