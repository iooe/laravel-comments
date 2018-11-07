@php
    $count = $model->comments()->parentless()->count();
    $comments = $model->comments()->parentless()->get();
@endphp
@if($count < 1)
    <p class="lead">There are no comments yet.</p>
@endif
<div class="ui threaded comments">
    @foreach($comments as $comment)
        @include('comments::components.comment.comment')
    @endforeach
</div>
@auth
    @include('comments::form')
@else
    @include('comments::login-message')
@endauth