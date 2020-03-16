  
   [README на русском](readme-ru.md)
 
# laraComments        
 This package can be used to comment on any model you have in your application.        
                    
### Features 
- [x] View comments        
- [x] Create comment        
- [x] Delete comment        
- [x] Edit comment        
- [x] Reply to comment        
- [x] Authorization rules | with customization      
- [x] View customization        
- [x] Dispatch events   
- [x] Likes | Dislikes | Comment rating       
- [x] API for basic function: get, update, delete, create      
- [x] HTML filter customization (using HTMLPurifier)      
        
## [Upgrade guides](#upgrade-guides)
+ [From 2.x.x to 3.0](#from-2xx-to-30) 

## Requirements 
- php 7.1 + 
- laravel 5.6 +      
- `Your application should contain auth module.`
    - Laravel 6.x: [https://laravel.com/docs/6.x/authentication](https://laravel.com/docs/6.x/authentication)
    - Laravel 5.6: [https://laravel.com/docs/5.6/authentication](https://laravel.com/docs/5.6/authentication)

## Installation 

```bash 
composer require tizis/lara-comments 
```   

### 1. Run migrations        
 We need to create the table for comments.        
        
```bash  
 php artisan migrate 
 ``` 
 ### 2. Add Commenter trait to your User model        
 Add the `Commenter` trait to your User model so that you can retrieve the comments for a user:        
        
```php 
use tizis\laraComments\Traits\Commenter;
     
class User extends Authenticatable {   
	use ..., Commenter;   
 ``` 
  ### 3. Create Comment model 
  

 ```php 
  
 use tizis\laraComments\Entity\Comment as laraComment;
 
 class Comment extends laraComment
 {
 
 }
 ``` 


 ### 4. Add `Commentable` trait and the `ICommentable` interface to models        
 Add the `Commentable` trait and the `ICommentable` interface to the model for which you want to enable comments for:        
  
 ```php 
 use tizis\laraComments\Contracts\ICommentable;
 use tizis\laraComments\Traits\Commentable;     
      
 class Post extends Model implements ICommentable {        
    use Commentable;        
 ```        
 
 ### 5. Custom comment policy (optional)
 If you need, you can overwrite default comment policy class:
 
  ```php 
 <?php
 namespace App\Http\Policies;
 
 use App\Entity\Comment;
 
 use tizis\laraComments\Policies\CommentPolicy as CommentPolicyPackage;
 
 class CommentPolicy extends CommentPolicyPackage
 {
     // overwrite delete rule
     public function delete($user, $comment): bool
     {
         // ever true
         return true;
     }
 }
 ```
 
 Then register policy in `AuthServiceProvider`:
 ```php 
 use Illuminate\Support\Facades\Gate;
 use App\Http\Policies\CommentPolicy;
 ...
 public function boot()
 {
     Gate::resource('comments_custom', CommentPolicy::class, [
         'delete' => 'delete',
         'reply' => 'reply',
         'edit' => 'edit',
         'vote' => 'vote',
         'store => 'store'
     ]);
 }
 ```
 And add policy prefix to comments.php config
 ```php
     'policy_prefix' => 'comments_custom',
 ```
 
 ### Publish Config & configure (optional)        
 In the `config` file you can specify:        
        
- where is your User model located; the default is `\App\User::class` 
- where is your Comment model located; the default is `\App\Comment::class` 
- policy prefix, you can create custom policy class and extends `tizis\laraComments\Policies\CommentPolicy;`    
- allow tags for html filter    
- API prefix  
        
Publish the config file (optional):        
        
```bash 
php artisan vendor:publish --provider="tizis\laraComments\Providers\ServiceProvider" --tag=config 
```        
 ### Publish views (customization)        
 
 The default UI is made for `Bootstrap 4`, but `you can change it` however you want. 
 
 ⚠⚠⚠⚠**WARNING**⚠⚠⚠⚠     
 
 All view examples include js/css files for correct working. `The possibility of conflict` with your scripts and styles.
        
```bash 
php artisan vendor:publish --provider="tizis\laraComments\Providers\ServiceProvider" --tag=views 
```        
 ## Usage 

### 1. Backend rendering:  
  In the view where you want to display comments, place this code and modify it:        
        
``` 
@comments(['model' => $book])  
@endcomments   
``` 
In the example above we are setting argument the `model` as class of the book model. 

Behind the scenes, the package detects the currently logged in user if any.        
        
If you open the page containing the view where you have placed the above code, you should see a working comments form.        
        

 
### 2. Frontend rendering (API):  

|Title| Method |  Url | Params| Route name |
|--|--|--| -- | --|
|Get comments|GET |  /api/comments/ | commentable_encrypted_key, order_by (column name, default is id), order_direction (default is asc) |  route('comments.get') |
|Store comment| POST | /api/comments/ | commentable_encrypted_key, message |route('comments.store') | 
|Delete comment|DELETE|/api/comments/{comment_id}| -- | route('comments.delete', $comment_id)  |
|Edit comment|POST|/api/comments/{comment_id}| message|  route('comments.update', $comment_id)
|Reply to comment|POST|/api/comments/{comment_id}| message | route('comments.reply', $comment_id)
|Vote to comment|POST|/api/comments/{comment_id}/vote| vote(bool) | route('comments.vote', $comment_id)

### 3. Access to the comment service

If you don't want use  out of the box features: API, or the CommentController, but want to access the built-in features - you can use `tizis\laraComments\UseCases\CommentService`

 `CommentService` class used inside default comment controller for request processing. 

To disable API routes by default, set the `route.root => null` config value.

**Methods**:
1. Сreate comment: `CommentService::createComment`
  ```
  $user = Auth::user();
  $modelId = decrypt($request->commentable_encrypted_key)['id']; // get model id from encrypted model key 
  $model = $model = Post::findOrFail($modelId);
  $message = '123'
  
  $parent = rand(1, 100); // optional
  
  $createdComment = CommentService::createComment(new Comment(), $user, $model, $message, [optional $parent]);
```
 2. Delete comment: `CommentService::deleteComment`
  ```
  $comment = Comment::findOrFail(123);

  CommentService::deleteComment($comment);
```

2. Update comment: `CommentService::updateComment`
  ```
    $comment = Comment::findOrFail(123);
    $message = 'new text';
    
    $updatedComment = CommentService::updateComment($comment, $message);
```

## Events        
This package fires events to let you know when things happen.        
    
- `tizis\laraComments\Events\CommentCreated` 
- `tizis\laraComments\Events\CommentUpdated` 
- `tizis\laraComments\Events\CommentDeleted`

## API preprocessing

⚠ **WARNING! Only for API!** ⚠

Supported preprocessors for attributes of get api:
- user **[Object]**
- comment **[String]**

#### 1. Description
Sometimes additional processing of content is necessary before transmission over API.


#### 2. Config:
```
    'api' => [
        'get' => [
            'preprocessor' => [
                'comment' =>  App\Helpers\CommentPreprocessor\Comment::class,
                'user' =>  App\Helpers\CommentPreprocessor\User::class 
                ...
            ]
        ]
    ]
``` 

#### 3. Contract     
 Create preprocessor class and implement `ICommentPreprocessor` interface:      
 
 **Examples**:
 
 Comment:
 ```
 
namespace App\Helpers\CommentPreprocessor;

use tizis\laraComments\Contracts\ICommentPreprocessor;

class Comment implements ICommentPreprocessor
{
    public function process($comment): array
    {
        return 'Hi, ' . $comment . '!';
    }
}
           
 ```        
 User:
  ```
  
 namespace App\Helpers\CommentPreprocessor;
 
 use tizis\laraComments\Contracts\ICommentPreprocessor;
 
 class User implements ICommentPreprocessor
 {
     public function process($user): array
     {
         $user->name = $user->name . '[Moderator]' 
         return $user;
     }
 }
            
  ```  
 
#### 4. Example:

Without preprocessing:
```
$comment = 1;
echo $comment; // 1

$user = Auth::user();
echo $user->name; // user1
``` 
With preprocessing:

```
$comment = 1;
echo $comment; // Hi, 1 !

$user = Auth::user();
echo $user->name; // user1[Moderator]
``` 

## Features of Commentable model 
- Scope withCommentsCount()

#### Example:

```
/**
 * Add comments_count attribute to model
 */
Posts::withCommentsCount()->orderBy('id', 'desc')->get() 
``` 

## Static Helper    
 
 ` use tizis\laraComments\Http\CommentsHelper;` 

#### Methods:
- getNewestComments(default $take = 10, default $commentable_type = null)
- getCommenterRating(int $userId, [optional Carbon $cacheTtl])
- moveCommentTo(CommentInterface $comment, ICommentable $newCommentableAssociate)
- moveCommentToAndRemoveParentAssociateOfRoot(CommentInterface $comment, ICommentable $newCommentableAssociate)

#### Example:

```
CommentsHelper::getNewestComments(20) // Return last 20 comments
CommentsHelper::getNewestComments(20, Book::class) // Return last 20 comments of Book model
``` 


 ## Examples    
This repository include only `bootstrap4` template, but you can create you own UI. This is just a example of package features.

This is example of `backend`rendering, `this way have bad performance` when 100+ comments on post due to the need to check user permissions (reply, edit, delete etc) for each comment. 

`A good idea` is use API and build UI with Vue js (or any other library) with verification of user permissions (only for UI) on frontend.

1. Build with semantic ui    
![2222d](https://user-images.githubusercontent.com/16865573/48430226-0124c680-e799-11e8-9341-daac331236b2.png)      
2. Build with bootstrap 4    
![3333](https://user-images.githubusercontent.com/16865573/48430227-0124c680-e799-11e8-8cdb-8dd042155550.png)      


##Upgrade-guides
### From 2.x.x to 3.0
`commentable_type`  and  `commentable_id` request attributes was merged into single ```commentable_encrypted_key``` 
 
You need to replace these deprecated attributes.

Example:

```
Old /bootstrap4/form.blade.php
<input type="hidden" name="commentable_type" value="\{{ get_class($model) }}"/>
<input type="hidden" name="commentable_id" value="{{ $model->id }}"/>

``` 
```
New /bootstrap4/form.blade.php
<input type="hidden" name="commentable_encrypted_key" value="{{ $model->getEncryptedKey() }}"/>
``` 