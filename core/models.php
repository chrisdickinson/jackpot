<?php
$fields = import('jackpot.core.fields');

$Manager = function($object) {
    $self =& new JObject();

    $self->__call__ = function (&$self, $method, $args) {
        return $QuerySet($method, $args);
    }; 

    return $self;
};

$Model = function() use ($Manager) {
    $self =& new JObject();
    $self->manager = $Manager;
    $fields = import('jackpot.models.fields');

    $self->name = "Model";
    $self->pk = $fields->IntegerField()
                        ->null(false)
                        ->autoincrement(true)
                        ->primary_key(true);
    
    $self->objects = function ($self) {
        $manager = $self->manager;
        return $manager($self);
    };
 
    $self->__unicode__ = function($self) {
        return "<{$self->name} $pk>";
    };

    return $self;   
};
/*
$models = import('jackpot.core.models');
$fields = import('jackpot.core.models.fields');

$Blog = function() use ($models, $fields) {
    $self =& new JObject();
    $self->extend($models->Model);
    $self->title = $fields->CharField() 
                    ->max_length(512)
                    ->unique(true);
    $self->body = $fields->TextField()
                    ->blank(true)
                    ->default('');
    $self->author = $fields->ForeignKey('jackpot.contrib.auth.User');
    return $self;
};
*/
?>
