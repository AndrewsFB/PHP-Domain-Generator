<?php

//=============================================================================================================
// CODE GENERATION
// Begin...

$pathInput = "./Input";
$pathOutput = "./Output";

$dirInput = dir($pathInput);
 
if(!is_dir("$pathInput")) {
  mkdir("$pathInput");
}

if(!is_dir("$pathOutput")) {
  mkdir("$pathOutput");
}

while($arquivo = $dirInput -> read()){

  if(strpos($arquivo, ".entity") === false) {
    continue;
  }
  
  if(!is_dir("$pathOutput/Entities")) {
    mkdir("$pathOutput/Entities");
  }
  
  $className = str_replace(".entity","", $arquivo);

  $inputFile = fopen("$pathInput/$arquivo","r");

  $outputFileName = "$className.class.php";
  $outputFile = fopen("$pathOutput/Entities/$outputFileName", "w");

  fwrite($outputFile, "<?php\n\n");
  fwrite($outputFile, "class $className {\n\n");

  $attrs = array();
  
  while (!feof ($inputFile)) {
    $linha = fgets($inputFile);
    $linha = str_replace("\r", "", $linha);
    $linha = str_replace("\n", "", $linha);
    $attrs[] = $linha;
    $attr = explode("-", $linha)[0];
    $instruction = "    private $$attr;\n";
    fwrite($outputFile, $instruction);
  }

  fwrite($outputFile, "\n");

  foreach($attrs as $attr){
    
    $attrData = explode("-", $attr);

    $attrType = "";
    if(isset($attrData[1])){
      $attrType = explode("-", $attr)[1];
    }

    $attrName = "";
    if(isset($attrData[0])){
      $attrName = explode("-", $attr)[0];
    }    
    
    $defaultValidation = getDefaultValidation($attrName);
    $stringValidation = getStringValidation($attrName);
    $textValidation = getTextValidation($attrName);
    $doubleValidation = getDoubleValidation($attrName);
    $integerValidation = getIntegerValidation($attrName);
    $cpfValidation = getCPFValidation($attrName);
    $cnpjValidation = getCNPJValidation($attrName);

    $validation = "";
    if(isset($attrType)){
      switch(strtolower($attrType)){
        case "string" : $validation = $stringValidation; break;
        case "text" : $validation = $textValidation; break;
        case "double" : $validation = $doubleValidation; break;
        case "integer" : $validation = $integerValidation; break;
        case "cpf" : $validation = $cpfValidation; break;
        case "cnpj" : $validation = $cnpjValidation; break;
        default: $validation = $defaultValidation;
      }
    } 
    
    fwrite($outputFile, "    public function get$attrName(){\n");
    fwrite($outputFile, "        return $"."this->$attrName;");
    fwrite($outputFile, "\n    }\n\n");

    fwrite($outputFile, "    public function set$attrName($"."value){\n");
    fwrite($outputFile, $validation);      
    fwrite($outputFile, "        $"."this->$attrName = $"."value;");
    fwrite($outputFile, "\n    }\n\n");  
  }
  
  fwrite($outputFile, "\n}");

  fclose ($inputFile);
  fclose ($outputFile);
}

$dirInput -> close();
createValidationExceptionFile($pathOutput);

// End...
// CODE GENERATION
//=============================================================================================================


//=============================================================================================================
// FUNCTIONS 
// Begin...

function createValidationExceptionFile($pathOutput){
  if(!is_dir("$pathOutput/Exceptions")) {
    mkdir("$pathOutput/Exceptions");
  }  

  $validationExceptionCode = "<?php \n\n".
                             "class ValidationException extends Exception {\n".
                             "}\n\n";  

  $validationExceptionFile = fopen("$pathOutput/Exceptions/ValidationException.class.php", "w");
  fwrite($validationExceptionFile, $validationExceptionCode);
  fclose($validationExceptionFile);
}

function getDefaultValidation(){
  return "";
}

function getStringValidation($attrName){
  return "        if(!strlen($"."value) > 255){\n ".
         "            throw new ValidationException(\"The attribute '$attrName' must have until 255 characters.\");\n".
         "        }\n";    
}

function getTextValidation($attrName){
  return "";
}

function getDoubleValidation($attrName){
  return "        if(!is_numeric($"."value)){\n ".
         "            throw new ValidationException(\"The attribute '$attrName' must be a number.\");\n".
         "        }\n";  
}

function getIntegerValidation($attrName){
  return "        if(!is_integer($"."value)){\n ".
         "            throw new ValidationException(\"The attribute '$attrName' must be integer.\");\n".
         "        }\n";
}

function getCPFValidation($attrName){
  return "        if(!validateCPF($"."value)){\n ".
         "            throw new ValidationException(\"The attribute '$attrName' must be a valid CPF.\");\n".
         "        }\n";
}

function getCNPJValidation($attrName){
  return "        if(!validateCNPJ($"."value)){\n ".
         "            throw new ValidationException(\"The attribute '$attrName' must be a valid CNPJ.\");\n".
         "        }\n";  
}

// End...
// FUNCTIONS 
//=============================================================================================================