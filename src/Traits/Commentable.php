<?php

namespace tizis\laraComments\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Add this trait to any model that you want to be able to
 * comment upon or get comments for.
 */
trait Commentable
{
    /**
     * @return bool
     */

    public function isCommentable(): bool
    {
        return true;
    }

    /**
     * Returns all comments for this model.
     */
    public function comments()
    {
        return $this->morphMany(config('comments.models.comment'), 'commentable');
    }

    /**
     * Returns all comments for this model with recursion and commenter eager loading.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commentsWithChildrenAndCommenter()
    {
        return $this->morphMany(config('comments.models.comment'), 'commentable')
            ->with('allChildrenWithCommenter', 'allChildrenWithCommenter.commenter', 'commenter');
    }

    /**
     * @param Builder $query
     */
    public function scopeWithCommentsCount(Builder $query)
    {
        return $query->withCount('comments');
    }
    
    /**
     * Return attribute for use as ident
     *
     * @return int
     */
    public function modelIdent()
    {
        $idAttribute = 'id';

        $modelPath = get_class($this);
        if( substr($modelPath, 0, 1) != '\\')
        {
            $modelPath = '\\' . $modelPath;
        }

        $rewriteIdAttribute = config('comments.rewriteIdAttribute', []);

        if( isset($rewriteIdAttribute[$modelPath]) )
        {
            $idAttribute = $rewriteIdAttribute[$modelPath];
            if ( !method_exists ($modelPath, 'get'.$idAttribute.'Attribute') && !property_exists($modelPath, $idAttribute) ) {
                throw new \DomainException('Rewrite id attribute not exists');
            }
        }

        return $this->{$idAttribute};
    }


    /**
     * Return model name or fake name
     *
     * @return string
     */
    public function modelType()
    {
        $modelPath = get_class($this);
        if( substr($modelPath, 0, 1) != '\\')
        {
            $modelPath = '\\' . $modelPath;
        }

        $rewriteModel = config('comments.rewriteModel', []);
        if( isset($rewriteModel[$modelPath]) )
        {
            $modelPath = $rewriteModel[$modelPath];
        }

        return $modelPath;
    }    
}
