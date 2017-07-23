## About EaseRequest
EaseRequest is laravel package that supports updating FormRequest validation rules at the run time.

## Example  
Let suppose you want to update a student data, student have class_id and school_id. You want to update student's class and
you want to validate that class exists in the database. With current laravel implementation, it will work fine. Here is the rule..

```
class_id => exists:classes,class_id
```
What if you want also to verify that the class also belongs to the student's school. Laravel gives you the option for adding the
where conditions to the rule.

```
class_id => exists:classes,class_id,NULL,id,school_id,1
```
But you have to provide hard coded value for where conditions.

Using this package you can update your rules at run time. You can add your keyword in culry braces and this package will fetch the
values from the Request and update the rule.

```
class_id => exists:classes,class_id,NULL,id,school_id,{school_id}
```

You can also define a method to be called for updating the rules instead of fetching value from Request class. 
Just write the method in your class with same name of the keyword written in curly braces. 
This way your method will called and returned value from your method will be added at the place of the keyword.

```
private function school_id()
{
        return (int)\Request::get('school_id');
}
```

## Installation

``composer require tkeer/ease-request 1.*``

## Usage

### Using abstract class EaseRequest
Extend your class by EaseRequest instead of FormRequest, implement
abstract function preRules in your class and define all your rules in that function.

### Using trait EaseRequestTrait
Extend your class by FormRequest and add EaseRequestTrait in the class.
Define preRules function in you class and add all your rules in that function.