@inject('markdown', 'Parsedown')

@if(isset($reply) && $reply === true)
  <div id="comment-{{ $comment->id }}" class="media">
@else
  <li id="comment-{{ $comment->id }}" class="media">
@endif
    <img class="mr-3" src="https://www.gravatar.com/avatar/{{ md5($comment->commenter->email) }}.jpg?s=64" alt="{{ $comment->commenter->name }} Avatar">
    <div class="media-body">
        <h5 class="mt-0 mb-1">{{ $comment->commenter->name }} <small class="text-muted">- {{ $comment->created_at->diffForHumans() }}</small></h5>
        <div style="white-space: pre-wrap;">
            {!! $markdown->line($comment->comment) !!}
        </div>

        <p>
            @can('comments.reply', $comment)
                <button data-toggle="modal" data-target="#reply-modal-{{ $comment->id }}" class="btn btn-sm btn-link text-uppercase">Reply</button>
            @endcan
            @can('comments.edit', $comment)
                <button data-toggle="modal" data-target="#comment-modal-{{ $comment->id }}" class="btn btn-sm btn-link text-uppercase">Edit</button>
            @endcan
            @can('comments.delete', $comment)
                <a href="{{ url('comments/' . $comment->id) }}" onclick="event.preventDefault();document.getElementById('comment-delete-form-{{ $comment->id }}').submit();" class="btn btn-sm btn-link text-danger text-uppercase">Delete</a>
                <form id="comment-delete-form-{{ $comment->id }}" action="{{ url('comments/' . $comment->id) }}" method="POST" style="display: none;">
                    @method('DELETE')
                    @csrf
                </form>
            @endcan
        </p>

        @include('comments::components.comment.forms')
        <br />

        @foreach($comment->children as $child)
            @include('comments::components.comment.comment', [
                    'comment' => $child,
                    'reply' => true
                ])
        @endforeach
    </div>

      {!! isset($reply) && $reply === true ? '</div>' : '</li>' !!}
