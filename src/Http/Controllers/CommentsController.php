<?php

namespace tizis\laraComments\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use tizis\laraComments\Entity\Comment;
use tizis\laraComments\Http\Requests\EditRequest;
use tizis\laraComments\Http\Requests\GetRequest;
use tizis\laraComments\Http\Requests\SaveRequest;
use tizis\laraComments\Http\Resources\CommentResource;
use tizis\laraComments\UseCases\CommentService;

class CommentsController extends Controller
{
    use ValidatesRequests, AuthorizesRequests;

    public function __construct()
    {
        $this->middleware(['web', 'auth']);
    }

    /**
     * Creates a new comment for given model.
     *
     * @param SaveRequest $request
     * @param Comment $comment
     * @return mixed
     */
    public function store(SaveRequest $request, Comment $comment)
    {
        $modelPath = $request->commentable_type;
        $message = CommentService::htmlFilter($request->message);

        if (!CommentService::classExists($modelPath)) {
            throw new \DomainException('Model don\'t exists');
        }

        $model = new $modelPath;

        if (!CommentService::isCommentable($model)) {
            throw new \DomainException('Model is\'t commentable');
        }

        $model = $model::findOrFail($request->commentable_id);
        $comment = $comment->createComment(auth()->user(), $model, $message);
        $resource = new CommentResource($comment);
        return $request->ajax() ? ['success' => true, 'comment' => $resource] : redirect()->to(url()->previous() . '#comment-' . $comment->id);
    }

    public function get(GetRequest $request):array
    {
        $modelPath = $request->commentable_type;
        $modelId = $request->commentable_id;

        if (!CommentService::classExists($modelPath)) {
            throw new \DomainException('Model don\'t exists');
        }

        $model = new $modelPath;

        if (!CommentService::isCommentable($model)) {
            throw new \DomainException('Model is\'t commentable');
        }

        $model = $modelPath::where(['id' => $modelId])->first();

        $count = $model->comments()->count();
        $comments = $model->comments()->parentless()->get();

        $resource = CommentResource::collection($comments);

        return ['success' => true, 'comments' => $resource, 'count' => $count];
    }


    /**
     * Updates the message of the comment.
     *
     * @param EditRequest $request
     * @param Comment $comment
     * @return mixed
     */
    public function update(EditRequest $request, Comment $comment)
    {
        $this->authorize('comments.edit', $comment);

        $message = CommentService::htmlFilter($request->message);

        $comment->updateComment($message);
        $resource = new CommentResource($comment);

        return $request->ajax()
            ? ['success' => true, 'comment' => $resource]
            : redirect()->to(url()->previous() . '#comment-' . $comment->id);

    }

    /**
     * Deletes a comment.
     *
     * @param Comment $comment
     * @return mixed
     */
    public function destroy(Request $request, Comment $comment)
    {
        $this->authorize('comments.delete', $comment);
        $comment->delete();

        return $request->ajax() ? ['success' => true] : redirect()->back();
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @return mixed
     */
    public function reply(Request $request, Comment $comment)
    {
        $this->authorize('comments.reply', $comment);

        $this->validate($request, [
            'message' => 'required|string'
        ]);

        $message = CommentService::htmlFilter($request->message);

        $reply = (new Comment)->createComment(auth()->user(), $comment->commentable, $message, $comment);
        $resource = new CommentResource($reply);
        return $request->ajax() ? ['success' => true, 'comment' => $resource] : redirect()->to(url()->previous() . '#comment-' . $reply->id);

    }
}
