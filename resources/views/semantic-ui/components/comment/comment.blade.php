@inject('markdown', 'Parsedown')

@if(isset($reply) && $reply === true)
  <div class="comments threaded" id="comment-{{ $comment->id }}">
@else
  <div class="comment" id="comment-{{ $comment->id }}">
@endif


  <a class="avatar">
      <img src="https://semantic-ui.com/images/avatar/small/jenny.jpg">
  </a>


      <div class="content">
          <a class="author">{{ $comment->commenter->name  }}</a>
          <div class="metadata">
              <span class="date">{{ $comment->created_at->diffForHumans() }}</span>
          </div>
          <div class="text">
              {!! $markdown->line($comment->comment) !!}
          </div>
          <div class="actions">
              <p>
                  @can('comments.reply', $comment)
                      <a class="reply">Reply</a>
                  @endcan
                  @can('comments.edit', $comment)
                      <a class="save">Edit</a>
                  @endcan
                  @can('comments.delete', $comment)
                      <a href="{{ url('comments/' . $comment->id) }}"
                         onclick="event.preventDefault();document.getElementById('comment-delete-form-{{ $comment->id }}').submit();">
                          Delete
                      </a>
                      <form id="comment-delete-form-{{ $comment->id }}" action="{{ url('comments/' . $comment->id) }}" method="POST" style="display: none;">
                          @method('DELETE')
                          @csrf
                      </form>
                  @endcan
              </p>
          </div>
      </div>


    @foreach($comment->children as $child)
        @include('comments::components.comment.comment', [
            'comment' => $child,
            'reply' => true
        ])
    @endforeach
  </div>
@if(isset($reply) && $reply === true)

@else

@endif

    {{--
        @include('comments::components.comment.forms')--}}

