<?php

namespace tizis\laraComments\Contracts;

/**
 * Preprocessor class Interface
 *
 * Interface ICommentPreprocessor
 * @package tizis\laraComments\Contracts
 */
interface ICommentPreprocessor
{
    public function process($object);
}