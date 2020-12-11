<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Page;
use App\Models\Link;
use App\Models\View;

class PageController extends Controller
{
    public function index($slug)
    {
        $page = Page::where('slug', $slug)->first();

        if ($page) {
            //Background
            $bg = '';
            switch($page->op_bg_type) {
                case 'color':
                    $colors = explode(',', $page->op_bg_value);
                    $bg = 'linear-gradient(90deg,'.$colors[0].',';
                    $bg .= $colors[1] ?? $colors[0];
                    $bg .= ')';
                break;
                case 'image':
                    $bg = "url('".url('/media/uploads').'/'.$page->op_bg_value."')";
                break;
            }

            //Links
            $links = Link::where('id_page', $page->id)
                ->where('status', 1)
                ->orderBy('order')
                ->get();

            //Registar visualização
            $view = View::firstOrNew(
                ['id_page' => $page->id, 'view_date' => date('Y-m-d')]
            );
            $view->total++;
            $view->save();

            return view('page', [
                'font_color' => $page->op_font_color,
                'profile_image' => url('media/uploads/'.$page->op_profile_image),
                'title' => $page->op_title,
                'description' => $page->description,
                'fb_pixel' => $page->fb_pixel,
                'bg' => $bg,
                'links' => $links

            ]);
        }
        return view('notfound');
    }
}
