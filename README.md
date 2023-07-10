<h2 align="center"<b>EasyForms</b></h2>
<div align="center">Description</div>
<div align="center">

  A pocketmine plug-in that supports plugins to make a [FormUI](https://github.com/Frago9876543210/forms)

</div>

<hr>

### Usage

#### CustomForm
Import `CustomForm`, `Label`, `Input`, `Dropdown`, `Slider`, `StepSlider`, `Toggle` classes.
```php
use mayuri\form\type\CustomForm;
use mayuri\form\elements\components\Label;
use mayuri\form\elements\components\Input;
use mayuri\form\elements\components\Dropdown;
use mayuri\form\elements\components\Slider;
use mayuri\form\elements\components\StepSlider;
use mayuri\form\elements\components\Toggle;
```
- Create CustomForm
```php
// Title: Argument String, Elements: Argument Array, onSubmit: Argument Closure, onClose: Argument ?Closure
$form = new CustomForm("", [], function(Player $player, CustomFormResponse $response) : void{})); 
```
Example CustomForm:
```php
$form = new CustomForm("Enter data", [
      new Label("I'm label"),
      new Input("Enter your name", "Enter name", "Bob"),
      new Dropdown("Select product Fooed", ["Beer", "Cheese", "Cake"], 0),
      new Slider("Select count", 1.0, 100.0, 1.0, 50.0),
      new StepSlider("Select product Item", ["Netherite Ingot", "Diamond", "Iron Ingot"], 0),
      new Toggle("I'm toggle on", true)
], function(Player $player, CustomFormResponse $response) : void{}));
// OR
$label = new Label("I'm label");
$input = new Input("Enter your name", "Enter name", "Bob");
$dropdown = new Dropdown("Select product Fooed", ["Beer", "Cheese", "Cake"], 0);
$slider = new Slider("Select count", 1.0, 100.0, 1.0, 50.0);
$stepslider = new StepSlider("Select product Item", ["Netherite Ingot", "Diamond", "Iron Ingot"], 0); 
$toggle = new Toggle("I'm toggle on", true);
$form = new CustomForm("Enter data", [
      $label,
      $input,
      $dropdown,
      $slider,
      $stepslider,
      $toggle
], function(Player $player, CustomFormResponse $response) : void{}));
```
<hr>

- Added Label in CustomForm
```php
// Text: Argument String
$label = new Label("");
```
Example Label:
```php
$label = new Label("I'm label");
```
<hr>

- Added Input in CustomForm
```php
// Text: Argument String, Placeholder: Argument String, Default: Argument String
$input = new Input("", "", "");
```
Example Input:
```php
$input = new Input("Enter your name", "Enter name", "Bob");
```
<hr>

- Added Dropdown in CustomForm
```php
// Text: Argument String, Options: Argument Array, Default: Argument Integer
$dropdown = new Dropdown("", [], 0);
```
Example Dropdown:
```php
$dropdown = new Dropdown("Select product Fooed", ["Beer", "Cheese", "Cake"], 0);
```
<hr>

- Added Slider in CustomForm
```php
// Text: Argument String, Minimum: Argument Float, Maximum: Argument Float, Step: Argument ?Float, Default: Argument ?Float
$slider = new Slider("", 1.0, 4.0, 1.0, 1.0);
```
Example Slider:
```php
$slider = new Slider("Select count", 1.0, 100.0, 1.0, 50.0);
```
<hr>

- Added StepSlider in CustomForm
```php
// Text: Argument String, Options: Argument Array, Default: Argument Integer
$stepslider = new StepSlider("", [], 0); 
```
Example StepSlider:
```php
$stepslider = new StepSlider("Select product Item", ["Netherite Ingot", "Diamond", "Iron Ingot"], 0); 
```
<hr>

- Added Toggle in CustomForm
```php
// Text: Argument String, Default: Argument Boolean
$toggle = new Toggle("", true);
```
Example Toggle:
```php
$toggle = new Toggle("I'm toggle on", true);
// OR
$toggle = new Toggle("I'm toggle off", false);
```
<hr>
