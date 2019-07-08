# laraComments        
 Данная библиотека может быть использована для добавления возможности комментирования любой модели (Laravel model) в вашем приложении.
                    
### Возможности 
- [x] Просмотр комментариев        
- [x] Создание комментария
- [x] Удаление комментария
- [x] Редактирование комментария
- [x] Создание ответа на комментарий        
- [x] Права на действие – удаление, создание и тд (Laravel Gate policies) | Возможность кастомизации      
- [x] Изменяемый шаблон комментариев        
- [x] События на создание, редактирование, удаление комментариев.
- [x] Система рейтинга: лайки, дизлайки.       
- [x] API: get, update, delete, create …
- [x] HTML фильтр для комментариев, с возможностью изменения правил (Используется библиотека HTMLPurifier)      

        
## Требования 
- php 7.1 + 
- laravel 5.6 +      

## Установка 
```bash 
composer require tizis/lara-comments 
```   

### 1. Запустите миграции        
 Это создаст все необходимые для комментариев таблицы в базе данных.    
        
```bash  
 php artisan migrate 
 ``` 
 ### 2.  Добавьте `Commenter trait` к вашей модели пользователя.
 Это добавит все необходимые для комментирования функции вашему пользователю.
```php 
use tizis\laraComments\Traits\Commenter;
     
class User extends Authenticatable {   
	use ..., Commenter;   
 ``` 
  ### 3. Создайте модель `Comment`
  

 ```php 
  
 use tizis\laraComments\Entity\Comment as laraComment;
 
 class Comment extends laraComment
 {
 
 }
 ``` 


 ### 4. Добавьте `Commentable trait` и `ICommentable` интерфейс вашим моделям     
  Добавьте `Commentable trait` и`ICommentable` тем моделям, к которым вы хотите добавить возможность оставлять комментарии: посты, книги, видео и тд
  
 ```php 
 use tizis\laraComments\Contracts\ICommentable;
 use tizis\laraComments\Traits\Commentable;     
      
 class Post extends Model implements ICommentable {        
    use Commentable;        
 ```        
 
 ### 5. Пользовательские `политики` (опционально)
 Если вам нужно, то вы можете изменить политики прав пользователей по умолчанию. 
 Политики - это условия проверки может ли пользователь сделать то, или иной действие: удалить комментарий, изменит его, ответить и тд

 Создаем файл политики с наследованием от правил по умолчанию:
  ```php 
 <?php
 namespace App\Http\Policies;
 
 use App\Entity\Comment;
 
 use tizis\laraComments\Policies\CommentPolicy as CommentPolicyPackage;
 
 class CommentPolicy extends CommentPolicyPackage
 {
     // Переписываем проверку прав на удаление комментария
     public function delete($user, $comment): bool
     {
         // Теперь всего будет возвращаться true т.е. любой пользователь может удалить любой комментарий.
         return true;
     }
 }
 ```
 Далее необходимо зарегистрировать новые правила политики в `AuthServiceProvider`
 
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
 И добавить название пользовательских правил политики в файл конфигурации
 ```php
     'policy_prefix' => 'comments_custom',
 ```
 
 ## Примеры    
 Этот репозиторий содержит только пример реализации с помощью фреймворка bootstrap4, но вы можете создать ваш собственный интерфейс так, как вам угодно. 
 
 Пример по умолчанию использует рендеринг комментариев на стороне сервера, и `этот способ имеет плохую производительность` при большом количестве комментариев (100+) на один пост- это связано с необходимостью проверки прав пользователя (удалени, редактирование и тд) для каждого комментарий.
    
`Правильный и современный путь реализации` - это получение сырых данных о комментариях через AJAX, и генерации интерфейса на стороне пользователя с помощью Vue js (или иной другой подобной бибилиотеки) с проверкой прав пользователя на различные действия на стороне самого пользователя. Это снимет всю лишнию нагрузку с сервера, но не повредит безопасности вашего приложения - ведь это лишь интерфейс, а все действия вроде сохранения или удаления комментария имеют собственные проверки на стороне сервера (политики).

1. Реализация интерфейса с помощью библиотеки semantic ui    
![2222d](https://user-images.githubusercontent.com/16865573/48430226-0124c680-e799-11e8-9341-daac331236b2.png)      
2. Реализация интерфейса с помощью библиотеки bootstrap 4    
![3333](https://user-images.githubusercontent.com/16865573/48430227-0124c680-e799-11e8-8cdb-8dd042155550.png)      

      
 ### Публикация конфига и конфигурация приложения (опционально)        
В `конфиге` вы можете определить такие настройки, как:
        
- путь, по которому находится ваша модель пользователя; по умолчанию это: `\App\User::class` 
- название политики проверки прав пользователя, вы можете создать свою собственную политики, переопределив права по умолчанию, унаследовав политику по умолчанию `tizis\laraComments\Policies\CommentPolicy`;        
- разрешенные html теги для фильтра 
- префикс API      
 
Публикация конфига:       
```bash 
php artisan vendor:publish --provider="tizis\laraComments\Providers\ServiceProvider" --tag=config 
```        
 ### Публикация примера по умолчанию (кастомизация внешнего вида)        
 
 По умолчанию интерфес реализован с помощью `Bootstrap 4`, но никакой привязки к определенным технологиям нет. Это `лишь пример` использования вомзожностей `laraComments`.
 
 ⚠⚠⚠**Внимание** ⚠⚠⚠
 
 Все примеры по умолчанию включает в себя необходимые для работы скрипты и стили. Это может вызвать конфликт с вашими Js/css файлами.
        
```bash 
php artisan vendor:publish --provider="tizis\laraComments\Providers\ServiceProvider" --tag=views 
```        
 ## Использование 

### 1. Рендеринг на стороне сервера:  
  В том месте представления (view), где вы желаете вывести комментарии, вставьте этот код:      
``` 
@comments(['model' => $book]) @endcomments   
``` 
В примере, мы передаем модель книги в качестве аргумента `model`. Из нее автоматически считывается информации об `commentable_type` и `commentable_id`.

Также, библиотека автоматически считывает вошел пользователь в систему, или нет.
        
Если вы откроете в браузере страницу, которая содержит представление с вышеуказанным кодом, то вы должны увидеть работающую форму комментариев.
        
### 2. Рендеринг на стороне пользователя (API):  

|Title| Method |  Url | Params| Route name |
|--|--|--| -- | --|
|Получить комментарии |GET |  /api/comments/ | commentable_type, commentable_id, order_by (column name, default is id), order_direction (default is asc) |  route('comments.get') |
|Сохранить комментарий| POST | /api/comments/ | commentable_type, commentable_id, message |route('comments.store') | 
|Удалить комментарий|DELETE|/api/comments/{comment_id}| -- | route('comments.delete', $comment_id)  |
|Изменить комментарий|POST|/api/comments/{comment_id}| message|  route('comments.update', $comment_id)
|Ответить на комментарий|POST|/api/comments/{comment_id}| message | route('comments.reply', $comment_id)
|Проголосовать за комментарий|POST|/api/comments/{comment_id}/vote| vote(bool) | route('comments.vote', $comment_id)

### 3. Доступ к сервису комментариев

Если вы `не желаете` использовать готовый функционал из коробки, вроде API и контроллера с предустановленными методами, но хотите получить доступ к встроенным возможностям данной библиотеки, то вы можете воспользоваться помощью `tizis\laraComments\UseCases\CommentService`

Данный класс используется внутри встроенного контроллера комментариев для обработки запросов, так что, вы не будете ничем не ограничены при его использование.     

Для отключения роутов API по умолчанию установите значение конфига `route.root => null`

**Методы**:
1. Создание комментария: `CommentService::createComment`
  ```
  $user = Auth::user();
  $model = $model = Post::findOrFail($request->commentable_id);
  $message = '123'
  
  $parent = rand(1, 100); // Необязательный параметр
  
  $createdComment = CommentService::createComment(new Comment(), $user, $model, $message, [опциональный аргумент $parent]);
```
 2. Удаление комментария: `CommentService::deleteComment`
  ```
  $comment = Comment::findOrFail(123);

  CommentService::deleteComment($comment);
```

2. Изменение комментария: `CommentService::updateComment`
  ```
    $comment = Comment::findOrFail(123);
    $message = 'new text';
    
    $updatedComment = CommentService::updateComment($comment, $message);
```

## События        
Эта библиотека содержит события на создание, удаление, и редактирование комментария.

- `tizis\laraComments\Events\CommentCreated` 
- `tizis\laraComments\Events\CommentUpdated` 
- `tizis\laraComments\Events\CommentDeleted`

## API препроцессинг

⚠⚠⚠**ДОСТУПЕН ТОЛЬКО ДЛЯ API**⚠⚠⚠

Поддержка аттрибутов (при получении комментариев):
- user **[Object]**
- comment **[String]**

#### 1. Описание
Бывают ситуации, когда перед отправкой данных пользователю необходимо провести с ними какие-либо действия. В подобной ситуации можно воспользоваться препроцессором.

#### 2. Конфиг:
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

#### 3. Настройки  
Создайте класс препроцессора и унаследуйте интерфейс `ICommentPreprocessor`
 
 **Примеры препроцессоров**:
 
 Комментарий:
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
 Пользователь:
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
 
#### 4. Примеры использования:

Без препроцесора:
```
$comment = 1;
echo $comment; // 1

$user = Auth::user();
echo $user->name; // user1
``` 
С препроцессором:

```
$comment = 1;
echo $comment; // Hi, 1 !

$user = Auth::user();
echo $user->name; // user1[Moderator]
``` 

## Возможности Commentable моделей
- Скоп (scope) withCommentsCount() // сКоличествомКомментариев

#### Пример:

```
/**
 * Добавляет аттрибут comments_count к получаемой модели поста 
 */
Posts::withCommentsCount()->orderBy('id', 'desc')->get() 
``` 

## Статический хелпер
 
 ` use tizis\laraComments\Http\CommentsHelper;` 

#### Доступные методы:
- getNewestComments(default $take = 10, default $commentable_type = null) // получитьПоследниеКомментарии
- getCommenterRating(int $userId, [optional Carbon $cacheTtl]) // получитьРейтингПользователя
- moveCommentTo(CommentInterface $comment, ICommentable $newCommentableAssociate) // переместитьКомментарийВ 

#### Пример:

```
CommentsHelper::getNewestComments(20) // Возвращает последние 20 комментариев 
CommentsHelper::getNewestComments(20, Book::class) // Возвращает последние 20 комментариев модели Книги
``` 