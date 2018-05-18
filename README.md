# PHP Domain Generator
Scripts created for source code generation from text files edited according to standards defined in this project.

## Getting Started

To generate source code one must create text files on the pattern **className.entity**. 

The contents of the file should follow the following template.
```
Attribute1-Type
Attribute2-Type
Attribute3-Type
...
```
Atributte1 = Attribute name
Type = Type (text, string, integer, double, cpf, cnpj)

The files must be placed on a folder named **input** in the same directory as the script.

Use the following command to execute the source-control creation:

```
php gent.php
```

The files generated on the proccess will be placed on a directory named **output** in the same place as the script runned.
