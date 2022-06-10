@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-uppercase mb-0">{{ $category->name }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="post">
                            <p class="mb-4"><strong>Slug: </strong>{{ $category->slug }}</p>
                            @if (count($category->posts) > 0)
                                <div class="posts">
                                    <h6 class="my-4">Post list:</h6>
                                    <ul>
                                        @foreach ($category->posts as $post)
                                            <li>{{ $post->title }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="buttons mt-4">
                            <button type="button" class="btn btn-danger btnP" data-toggle="modal"
                                data-target="#exampleModal">Delete</button>
                            <button type="button" class="btn btn-primary"><a class="text-white"
                                    href="{{ route('categories.index') }}">Back to the list</a></button>
                        </div>
                        {{-- Modal --}}
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-uppercase" id="exampleModalLabel">Attention! ❌</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this category?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary btnP"
                                            data-dismiss="modal">Close</button>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger toastClicker btnP">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
