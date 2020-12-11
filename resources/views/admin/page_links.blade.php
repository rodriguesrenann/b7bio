@extends('admin.page')

@section('body')
    <a class="bigButton" href="{{url('/admin/'.$page->slug.'/newlink')}}">Novo link</a>
    <ul id="links">
        @foreach ($links as $link)
            <li class="linkItem" data-id="{{$link->id}}">
                <div class="linkItemOrder">
                    <img src="{{url('/assets/images/sort.png')}}" alt="Ordernar" width="18px">
                </div>
                <div class="linkItemInfo">
                    <div class="linkItemTitle">{{$link->title}}</div>
                    <div class="linkItemHref">{{$link->href}}</div>
                </div>
                <div class="linkItemButtons">
                    <a href="{{url('/admin/'.$page->slug.'/editlink/'.$link->id)}}">Editar</a>
                    <a href="{{url('/admin/'.$page->slug.'/dellink/'.$link->id)}}">Excluir</a>
                </div>
            </li>
        @endforeach
    </ul>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        new Sortable(document.querySelector('#links'), {
            animation: 150,
            onEnd: async(e) => {
                let id = e.item.getAttribute('data-id');
                let link = `{{url('/admin/linkorder/${id}/${e.newIndex}')}}`;
                await fetch(link);
                window.location.href = window.location.href;
            }
        });
    </script>
@endsection