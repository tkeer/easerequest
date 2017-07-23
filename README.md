## About EaseRequest
EaseRequest is laravel package that supports updating FormRequest validation rules at run time.

## Example  
Let suppose you want to update a student data, student have class_id and school_id. You want to update student's class and
you want to validate that class exists in the database. With current laravel implementation, it will work fine. Here is the rule..

```
class_id => exists:classes,class_id
```
What if you want also to verify that the class also belongs to the current student's school. Laravel gives you the option for adding
new where conditions to the rule

```
class_id => exists:classes,class_id,NULL,id,school_id,1
```
But you have to provide hard coded value for where conditions.

Using this package you can update you rules at run time. You can add your keyword in culry braces and this package will fetch the
values from the Request and update the rule.

```
class_id => exists:classes,class_id,NULL,id,school_id,{school_id}
```

You can also define the method to be called for updating the rules. Just write the method in you class with same name of the keyword
written in curly braces. This way your method will called and returned value from you method will be added at the keyword place.

```
private function school_id()
{
        return (int)\Request::get('school_id');
}
```

## Installation

``composer required tkeer/ease-request 1.0.1``

##Usage

### Using abstract class EaseRequest
Extend your class by EaseRequest instead of FormRequest, implement
abstract function in preRules function and define all you rules in that function.

### Using trait EaseRequestTrait
Extend class by FormRequest and add EaseRequestTrait in your class.
Define preRules function in you class and add all you rules in this function.