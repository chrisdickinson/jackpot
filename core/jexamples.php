<?php
$Person = function ( $fname, $lname ) use ($self) {
    $self =& new JObject();
    $self->fname = $fname;
    $self->lname = $lname;

    $self->say_hi = function (&$self) {
        echo "hello {$self}\n";
    };

    $self->say_bye = function ($self) {
        echo "bye";
    };

    $self->__unicode__ = function ($self) {
        return "{$self->fname} {$self->lname}";
    };

    return $self;
};

$Author = function ( ) use ($Person) {
    $self = $Person("hunter", "thompson");
    $self->extend($Person);

    $self->say_hi = function (&$self) {
        echo "greetings {$self->fname} {$self->lname}\n";
    };
    
    $self->tip_hat = function () {
        echo "i tip my hat to you!";
    };
    return $self;
};

?>
