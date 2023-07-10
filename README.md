<div align="center"><img src="icon.png" width="50%" height="50%"></div>
<h2 align="center"<b>EasyForms</b></h2>
<div align="center">Description</div>
<div align="center">

  A pocketmine plug-in that supports plugins to make a [FormUI](https://github.com/Frago9876543210/forms)

</div>

<hr>

### Usage

#### CustomForm
> Import `CustomForm`, `Label`, `Input`, `Dropdown`, `Slider`, `StepSlider`, `Toggle` classes.
```php
use mayuri\form\type\CustomForm;
use mayuri\form\elements\components\Label;
use mayuri\form\elements\components\Input;
use mayuri\form\elements\components\Dropdown;
use mayuri\form\elements\components\Slider;
use mayuri\form\elements\components\StepSlider;
use mayuri\form\elements\components\Toggle;
```

> Create CustomForm
```php
// Title: Argument String, Elements: Argument Array, onSubmit: Argument Closure, onClose: Argument ?Closure
$form = new CustomForm("", [], function(Player $player, CustomFormResponse $response) : void{}, function(Player $player): void{})); 
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
], function(Player $player, CustomFormResponse $response) : void{}, function(Player $player): void{}));
```
<br>


> Add Label to CustomForm
```php
// Text: Argument String
new Label("");
```
Example Label:
```php
new Label("I'm label");
```
<br>


> Add Input to CustomForm
```php
// Text: Argument String, Placeholder: Argument String, Default: Argument String
new Input("", "", "");
```
Example Input:
```php
new Input("Enter your name", "Enter name", "Bob");
```
<br>

> Add Dropdown to CustomForm
```php
// Text: Argument String, Options: Argument Array, Default: Argument Integer
new Dropdown("", [], 0);
```
Example Dropdown:
```php
new Dropdown("Select product Fooed", ["Beer", "Cheese", "Cake"], 0);
```
<br>

> Add Slider to CustomForm
```php
// Text: Argument String, Minimum: Argument Float, Maximum: Argument Float, Step: Argument ?Float, Default: Argument ?Float
new Slider("", 1.0, 4.0, 1.0, 1.0);
```
Example Slider:
```php
new Slider("Select count", 1.0, 100.0, 1.0, 50.0);
```
<br>

> Add StepSlider to CustomForm
```php
// Text: Argument String, Options: Argument Array, Default: Argument Integer
new StepSlider("", [], 0); 
```
Example StepSlider:
```php
new StepSlider("Select product Item", ["Netherite Ingot", "Diamond", "Iron Ingot"], 0); 
```
<br>

> Add Toggle to CustomForm
```php
// Text: Argument String, Default: Argument Boolean
new Toggle("", true);
```
Example Toggle:
```php
new Toggle("I'm toggle on", true);
// OR
new Toggle("I'm toggle off", false);
```
<hr><br>


#### SimpleForm
> Import `SimpleForm`, `Button`, `Image` classes.
```php
use mayuri\form\type\Simple;
use mayuri\form\elements\components\Button;
use mayuri\form\elements\components\Image;
```
> Create SimpleForm
```php
// Title: Argument String, Text: Argument String, Elements: Argument: Array, onSubmit: Argument Closure, onClose: Argument ?Closure
$form = new CustomForm("", "", [], function(Player $player, Button $seleted) : void{}, function(Player $player): void{})); 
```
Example SimpleForm:
```php
$form = new SimpleForm("Selet Items", "Choose Item", [
      new Button("Coal"),
      new Button("Melon", new Image("https://gamepedia.cursecdn.com/minecraft_gamepedia/1/19/Melon.png", Image::TYPE_URL)); // Use Type Url
      new Button("Diamond", new Image("textures/item/diamond.png", Image::TYPE_PATH)); // Use Type Path
], function(Player $player, Button $seleted) : void{}, function(Player $player): void{});
```
<br>

> Add Button to SimpleForm
```php
// Text: Argument String, Image: Argument ?Image
new Button("", new Image("", Image::TYPE_URL)); 
```
<br>

Example Button:
```php
new Button("Diamond Sword");
// OR, would like to add a Image
// Type Url (If you enter a Type Url, you must include an image link)
new Button("Melon", new Image("https://gamepedia.cursecdn.com/minecraft_gamepedia/1/19/Melon.png", Image::TYPE_URL));
// Type Path (If you enter a Type Path, you must include an image path)
new Button("Diamond", new Image("textures/item/diamond.png", Image::TYPE_PATH));
```
There are 2 types of images: `Image::TYPE_URL` and `Image::TYPE_PATH`
<br>
