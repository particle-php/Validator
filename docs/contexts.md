# Using contexts

Contextual validation is a must for every serious validation library. After all,
validation can differ greatly based on the context that's being executed. Take for
example the difference between inserting some values into a database, and updating
the values:

```php
$v = new Validator;
$v->context('insert', function(Validator $context) {
    $context->required('first_name')->lengthBetween(2, 30);
});

$v->context('update', function(Validator $context) {
    $context->optional('first_name')->lengthBetween(2, 30);
});

$v->validate([], 'update'); // bool(true)
$v->validate([], 'insert'); // bool(false), because first_name is required.
```

## Copying from another context

In the above example, you'll see that the length condition was written *twice*, once
for insert, and once for update. That's generally not a good idea, because when your
rules are updated, you're likely going to forget one of the two places it happened.

To prevent this, Particle\Validator has something called context copying:

```php
$v = new Validator;
$v->context('insert', function(Validator $context) {
    $context->required('first_name')->lengthBetween(2, 30);
});

$v->context('update', function(Validator $context) {
    // copy the rules (and messages) of the "insert" context.
    $context->copyContext('insert');
   
    // make the "first_name" field optional.
    $context->optional('first_name');
});

$v->validate([], 'update'); // bool(true)
```

## Extended example of copying

As you could see in the previous example, it's possible to re-use the rules of an 
existing context in a new context. However, in the "update" context *every* key should
be optional, so adding a new rule to the "insert" context would probably break the
"update" context, because that value is then required. This, too, can be easily fixed.

```php
$v = new Validator;
$v->context('insert', function(Validator $context) {
    $context->required('first_name')->lengthBetween(2, 30);
    $context->required('last_name')->lengthBetween(2, 30);
});

$v->context('update', function(Validator $context) {
    // copy the rules (and messages) of the "insert" context.
    $context->copyContext('insert', function($rules) {
        foreach ($rules as $key => $chain) {
            $context->optional($key);
        }
    });
});
```
