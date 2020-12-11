<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Models\Page;
use App\Models\Link;

class LinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function pageLinks($slug)
    {
        $user = Auth::user();
        $page = Page::where('id_user', $user->id)->where('slug', $slug)->first();

        if ($page) {
            $links = Link::where('id_page', $page->id)->orderBy('order', 'ASC')->get();
            return view('admin.page_links', [
                'links' => $links,
                'page' => $page,
                'menu' => 'links'
            ]);
        }

        return redirect('/admin');
    }

    public function pageDesign($slug)
    {
        return view('admin.page_design', [
            'menu' => 'design',
        ]);
    }

    public function pageStats($slug)
    {
        return view('admin.page', [
            'menu' => 'stats',
        ]);
    }

    public function linkOrderUpdate($linkId, $pos)
    {
        $user = Auth::user(); 

        $link = Link::find($linkId);
        $myPages = [];
        $myPagesQuery = Page::where('id_user', $user->id)->get();

        foreach($myPagesQuery as $item) {
            $myPages[] = $item->id;
        }
        if(in_array($link->id_page, $myPages)) {
            //verifiar se o link subiu
            if($link->order > $pos) {
                //pegar links restantes e subir um numero na ordem para a ordem que eu quero ficar vazia
                $afterLinks = Link::where('id_page', $user->id)->where('order', '>=', $pos)->get();
                foreach($afterLinks as $afterLink) {
                    $afterLink->order++;
                    $afterLink->save();
                }
            }elseif($link->order < $pos) {
                //diminuir um numero da ordem
                $beforeLinks = Link::where('id_page', $user->id)->where('order', '<=', $pos)->get();
                foreach($beforeLinks as $beforeLink) {
                    $beforeLink->order--;
                    $beforeLink->save();
                }
            }
            $link->order = $pos;
            $link->save();

            $allLinks = Link::where('id_page', $user->id)->orderBy('order', 'ASC')->get();
            foreach($allLinks as $linkIndex => $linkItem) {
                $linkItem->order = $linkIndex;
                $linkItem->save();
            }
        }

        return [];
    }

    public function newLink($slug) {
        $user = Auth::user();

        $page = Page::where('id_user', $user->id)->where('slug', $slug)->first();

        if($page) {
            return view('admin.page_editlink', [
                'menu' => 'links',
                'page' => $page
            ]);
        }

        return redirect('/admin');
    }

    public function newLinkAction(Request $request, $slug) {
        $user = Auth::user();
        
        $page = Page::where('id_user', $user->id)->where('slug', $slug)->first();

        if($page) {
            $data = $request->validate([
                'status' => 'required|boolean',
                'title' => 'required|min:3',
                'href' => 'required|url',
                'op_bg_color' => 'required|regex:/^[#][0-9A-F]{3,6}$/i',
                'op_text_color' => 'required|regex:/^[#][0-9A-F]{3,6}$/i',
                'op_border_type' => ['required', Rule::in(['square', 'rounded'])]
            ]);
            
            $totalLinks = Link::where('id_page', $user->id)->count();

            $newLink = new Link();
            $newLink->id_page = $page->id;
            $newLink->status = $data['status'];
            $newLink->order = $totalLinks;
            $newLink->title = $data['title'];
            $newLink->href = $data['href'];
            $newLink->op_text_color = $data['op_text_color'];
            $newLink->op_bg_color = $data['op_bg_color'];
            $newLink->op_border_type = $data['op_border_type'];
            $newLink->save();

            return redirect('/admin/'.$page->slug.'/links');
        }

        return redirect('/admin');
    }

    public function editLink($slug, $linkId) {
        $user = Auth::user();
        $page = Page::where('id_user', $user->id)->where('slug', $slug)->first();

        if($page) {
            $link = Link::where('id_page', $page->id)
                ->where('id', $linkId)
                ->first();
            if($link) {
                return view('admin.page_editlink', [
                    'menu' => 'links',
                    'page' => $page,
                    'link' => $link
                ]);
            }
        }

        return redirect('/admin');
    }

    public function editLinkAction($slug, $linkId, Request $request) {
        $user = Auth::user();
        $page = Page::where('id_user', $user->id)->where('slug', $slug)->first();

        if($page) {
            $link = Link::where('id_page', $page->id)->where('id', $linkId)->first();

            if($link) {
                $data = $request->validate([
                    'status' => 'required|boolean',
                    'title' => 'required|min:3',
                    'href' => 'required|url',
                    'op_bg_color' => 'required|regex:/^[#][0-9A-F]{3,6}$/i',
                    'op_text_color' => 'required|regex:/^[#][0-9A-F]{3,6}$/i',
                    'op_border_type' => ['required', Rule::in(['square', 'rounded'])]
                ]);

                $link->status = $data['status'];
                $link->title = $data['title'];
                $link->href = $data['href'];
                $link->op_bg_color = $data['op_bg_color'];
                $link->op_text_color = $data['op_text_color'];
                $link->op_border_type = $data['op_border_type'];
                $link->save();

                return redirect('/admin/'.$page->slug.'/links');
            }
        }

        return redirect('/admin');
    }
}
