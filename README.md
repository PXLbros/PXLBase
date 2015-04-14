## pxlFramework

### Installation

#### Composer
Add ``"pxlbros/pxlframework": "dev-master"`` to composer.json and run ``composer update``.

#### Bower
Create a file `.bowerrc` in root with the following contents:
```
{
    "directory": "public/libs/bower"
}
```

Then create a file `bower.json` with the following contents:
```
{
	"name": "Project Name",
	"dependencies":
	{
		"jquery": "latest",
		"jquery-legacy": "jquery#1",
		"normalize-css": "latest",
		"sass-mediaqueries": "latest",
		"sass-mixins": "latest"
	}
}
```