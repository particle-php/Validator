# Using ValidationResult

A lot of the times, your software will be split into separate layers, where each layer has its own
responsibility (think about MVC, for example). The chances are that the layer that does the validation
of incoming data is not the same that determines what to do next, or the layer that displays messages
to a user.

This is why Particle\Validator includes a ValidationResult class: so that you can pass that to other
layers which will then determine what to do with the result. Usage of this class can not be any
simpler:

```php
use Particle\Validator\ValidationResult;
use Particle\Validator\Validator;

class MyEntity 
{
    protected $id;
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function validate() {
        $v = new Validator;
        
        return new ValidationResult(
            $v->validate($this->values)),
            $v->getMessages()
        );
    }
}

// in a controller:
$entity = new Entity();
$entity->setId($this->getParam('id'));
$result = $entity->validate();
if (!$result->isValid()) {
    return $this->renderTemplate([
        'messages' => $result->getMessages() // or maybe even just pass in $result.
    ]);
}
```
