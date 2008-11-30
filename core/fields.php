<?php
$Field = function() {
    $self =& new JObject();
    $self->_meta = array();

    $self->meta = function (&$self, $key, $value) {
        $self->_meta[$key] = $value;
    };

    $self->null = function (&$self, $value) {
        $self->meta('null',$value);
    };

    $self->primary_key = function (&$self, $value) {
        $self->meta('primary_key', $value);
    };

    $self->autoincrement = function (&$self, $value) {
        $self->meta('autoincrement', $value);
    };

    $self->__call__ = function &(&$self, $method, $args) {
        $self->meta($method, $args[0]);
        return $self;
    }; 

    $self->validate = function ($self, $value) {
        return true;
    };

    $self->clean = function ($self, $value) {
        return addslashes($value);
    };

    $self->value_from_db = function ($self, &$model, $name, $value) {
        $model->$name = $value;
    };

    $self->create_column = function ($self, $name, $db) {
        $db->build_column($name, $self->_meta);
    };

    return $self;
};

$IntegerField = function() use ($Field) {
    $self =& new JObject;
    $self->extend($Field);
    $self->meta('type', 'integer');

    $self->clean = function ($self, $value) {
        return (int)$value;
    };

    $self->validate = function ($self, $value) {
        return is_integer($value);
    };

    $self->value_from_db = function ($self, &$model, $name, $value) {
        $model->$name = $self->clean($value);
    };

    return $self;
};

$CharField = function() use ($Field) {
};

$TextField = function() use ($Field) {
};

$BooleanField = function() use ($Field) {
};

$DateField = function () use ($Field) {
};

$DateTimeField = function () use ($Field) {
};

$DecimalField = function () use ($Field) {
};

$FileField = function () use ($CharField) {
};

$FilePathField = function () use ($FileField) {
};

$ImageFileField = function () use ($FileField) {
};

$FloatField = function () use ($Field) {
};

$IPAddressField = function () use ($CharField) {
};

$SlugField = function () use ($CharField) {
};

$TimeField = function () use ($DateTimeField) {
};

$URLField = function () use ($CharField) {
};

$XMLField = function () use ($TextField) {
};

?>
