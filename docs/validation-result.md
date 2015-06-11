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
    
    public function isValid() {
        $v = new Validator;
        $v->required('id')->integer();
        
        return new ValidationResult(
            $v->isValid($this->values())),
            $v->getMessages()
        );
    }
    
    protected function values()
    {
        return [
            'id' => $this->id,
        ];
    }
}

// in a controller:
$entity = new Entity();
$entity->setId($this->getParam('id'));

$result = $entity->isValid();

if (!$result->isValid()) {
    return $this->renderTemplate([
        'messages' => $result->getMessages() // or maybe even just pass in $result.
    ]);
}
```
