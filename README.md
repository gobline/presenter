# Presenter component

The Presenter component allows you to decorate your model objects with methods used by your views. A typical example used to demonstrate the use for presenters, is a presenter method formatting a date from the model in a more readable format for the user.

## Gobline\Presenter\Presenter

By decorating your model objects with a ```Gobline\Presenter\Presenter``` instance, you will be able to access private/protected fields as you would access a public field, as long as there is a getter defined.

```php
class Property
{
    protected $price;
    protected $nbRooms;
    protected $livingArea;
    protected $publicationDate;

    public function __construct($price, $nbRooms, $livingArea)
    {
        $this->price = $price;
        $this->nbRooms = $nbRooms;
        $this->livingArea = $livingArea;
        $this->publicationDate = new \DateTime();
    }
    public function getPrice() { return $this->price; }
    public function getNbRooms() { return $this->nbRooms; }
    public function getLivingArea() { return $this->livingArea; }
    public function getPublicationDate() { return $this->publicationDate; }
}

$property = new Property(650, 1, 120);
$property = new Gobline\Presenter\Presenter($property);
```
```html
<label>Living area:</label><?= $property->livingArea ?>
```

This is simply done by PHP's magic methods ```__get``` and ```__call```.

In order to add behavior to your model objects for view formatting, you will have to create your own presenter:

```php
class PropertyPresenter extends Gobline\Presenter\Presenter
{
    public function __construct(Property $subject)
    {
        parent::__construct($subject);
    }

    public function getPublicationDate()
    {
        return $this->subject->getPublicationDate()->format('d/m/Y');
    }

    public function getNbRooms()
    {
        $nbRooms = $this->subject->getNbRooms();

        if ($nbRooms === 0) {
            return 'Studio';
        }

        if ($nbRooms === 1) {
            return $nbRooms.' room';
        }

        return $nbRooms.' rooms';
    }
}

$property = new Property(650, 1, 120);
$property = new PropertyPresenter($property);
```
```html
<label>Living area:</label><?= $property->livingArea ?>
<label>Number of rooms:</label><?= $property->nbRooms ?>
<label>Publication date:</label><?= $property->publicationDate ?>
```

## Gobline\Presenter\PresenterFactoryInterface

A presenter factory is useful when your presenter requires dependencies.
You inject the dependencies once in the factory, and the factory will create the presenter on demand with all its required dependencies.

```php
use Gobline\Translator\Translator;

class PropertyPresenterFactory implements Gobline\Presenter\PresenterFactoryInterface
{
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function createPresenter($property)
    {
        return new PropertyPresenter($property, $this->translator);
    }
}
```

You would then typically inject this factory into the object that will return the decorated model instances to the view. That object can now return decorated model instances like the following:

```php
$property = $this->propertyPresenterFactory->createPresenter($property);
```

## Gobline\Presenter\CollectionPresenter

When the view needs to display data from an array of model instances, we then need to wrap this array in order to return our presenters (decorated model instances) for each array access. The collection wrapper requires a presenter factory in order to return the right presenters.

```php
$results = $this->orm->findAll();
$results = new Gobline\Presenter\CollectionPresenter($results, $this->propertyPresenterFactory);
```

## Gobline\Presenter\PresenterTrait

The presenter trait allows your private/protected properties to be accessed as public properties.

## Installation

You can install the Presenter component using the dependency management tool [Composer](https://getcomposer.org/).
Run the *require* command to resolve and download the dependencies:

```
composer require gobline/presenter
```