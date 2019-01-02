  
    
# laraComments        
 This package can be used to comment on any model you have in your application.        
            
 Example - is realization of backend rendering, this is REALLY bad way. Good way is using api for get data through JS and build ui with Vue js (or any another library).       
        
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
        
## Requirements 
- php 7.1 + 
- laravel 5.5 +      

## Installation 
```bash 
composer require tizis/lara-comments 
```   

### Run migrations        
 We need to create the table for comments.        
        
```bash  
 php artisan migrate 
 ``` 
 ### Add Commenter trait to your User model        
 Add the `Commenter` trait to your User model so that you can retrieve the comments for a user:        
        
```php 
use tizis\laraComments\Traits\Commenter;
     
class User extends Authenticatable {   
	use ..., Commenter;   
 ``` 
  ### Create Comment model 
  

 ```php 
  
 use tizis\laraComments\Entity\Comment as laraComment;
 
 class Comment extends laraComment
 {
 
 }
 ``` 
### Custom comment policy
Create policy class, like this:
 ```php 
<?php
namespace App\Http\Policies;

use App\Entity\Comment;

use tizis\laraComments\Policies\CommentPolicy as CommentPolicyPackage;

class CommentPolicy extends CommentPolicyPackage
{
    // rewrite delete rule
    public function delete($user, $comment): bool
    {
        // ever true
        return true;
    }
}
   ```
   Register policy in AuthServiceProvider:
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
        'vote' => 'vote'
    ]);
}
```
Add policy prefix to comments.php config
```php
    'policy_prefix' => 'comments_custom',
```

 ### Add Commentable trait to models        
 Add the `Commentable` trait and the `ICommentable` interface to the model for which you want to enable comments for:        
  
 ```php 
 use tizis\laraComments\Contracts\ICommentable;
 use tizis\laraComments\Traits\Commentable;     
      
 class Post extends Model implements ICommentable {        
    use Commentable;        
 ```        
 ## Examples    
* This repository include only bootstap template, but you can create you own UI.    
Build with semantic ui    
![2222d](https://user-images.githubusercontent.com/16865573/48430226-0124c680-e799-11e8-9341-daac331236b2.png)      
Build with bootstrap 4    
![3333](https://user-images.githubusercontent.com/16865573/48430227-0124c680-e799-11e8-8cdb-8dd042155550.png)      
      
 ### Publish Config & configure (optional)        
 In the `config` file you can specify:        
        
- where is your User model located; the default is `\App\User::class` 
- policy prefix, you can create custom policy class and implement ICommentPolicy;        
- allow tags for html filter      
        
Publish the config file (optional):        
        
```bash 
php artisan vendor:publish --provider="tizis\laraComments\Providers\ServiceProvider" --tag=config 
```        
 ### Publish views (customization)        
 The default UI is made for Bootstrap 4, but you can change it however you want.        
        
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
In the example above we are setting the `commentable_type` to the class of the book. We are also passing the `commentable_id` the `id` of the book so that we know to which book the comments relate to. Behind the scenes, the package detects the currently logged in user if any.        
        
If you open the page containing the view where you have placed the above code, you should see a working comments form.        
        

 
### 2. Frontend rendering (API):  

|Title| Method |  Url | Params| Route name |
|--|--|--| -- | --|
|Get comments|GET |  /api/comments/ | commentable_type, commentable_id|  route('comments.get') |
|Store comment| POST | /api/comments/ | commentable_type, commentable_id, message |route('comments.store') | 
|Delete comment|DELETE|/api/comments/{comment_id}| -- | route('comments.delete', $comment_id)  |
|Edit comment|POST|/api/comments/{comment_id}| message|  route('comments.update', $comment_id)
|Reply to comment|POST|/api/comments/{comment_id}| message | route('comments.reply', $comment_id)
|Vote to comment|POST|/api/comments/{comment_id}/vote| vote(bool) | route('comments.vote', $comment_id)


 ## Events        
 This package fires events to let you know when things happen.        
        
- `tizis\laraComments\Events\CommentCreated` 
- `tizis\laraComments\Events\CommentUpdated` 
- `tizis\laraComments\Events\CommentDeleted`

 ## Static Helper    
 
 ` use tizis\laraComments\Http\CommentsHelper;` 

#### Methods:
- getNewestComments($take = 10)
- ...
#### Example:

` CommentsHelper::getNewestComments(10) // Return last 10 comments` 