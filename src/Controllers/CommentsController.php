<?php

namespace tizis\laraComments\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use tizis\laraComments\Entity\Comment;
use tizis\laraComments\Requests\EditRequest;
use tizis\laraComments\Requests\SaveRequest;
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

        if (!CommentService::classExists($modelPath)) {
            throw new \DomainException('Model don\'t exists');
        }

        $model = new $modelPath;

        // $request->commentable_type

        if (!CommentService::isCommentable($model)) {
            throw new \DomainException('Model is\'t commentable');
        }

        $model = $model::findOrFail($request->commentable_id);
        $comment = $comment->createComment(auth()->user(), $model, $request->message);
        return $request->ajax() ? ['success' => true] : redirect()->to(url()->previous() . '#comment-' . $comment->id);


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

        // todo ... message filter

        $comment->updateComment($request->message);

        return $request->ajax()
            ? ['success' => true, 'comment' => $comment->comment]
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

        $reply = (new Comment)->createComment(auth()->user(), $comment->commentable, $request->message, $comment);

        return $request->ajax() ? ['success' => true] : redirect()->to(url()->previous() . '#comment-' . $reply->id);

    }
}
