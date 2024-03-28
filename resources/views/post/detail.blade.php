@extends("layout/layout")

@section("title", "Post Detail")


@section("content")
    <div class="my-4 gap-2">
        <div class="flex-1">
            <div>
                <h1>{{$post['title']}}</h1>
                <p>{{$post['content']}}</p>
            </div>
        </div>
    </div>
@endsection