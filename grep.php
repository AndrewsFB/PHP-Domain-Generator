<?php

//=============================================================================================================
// CODE GENERATION
// Begin...

require_once "./config.php";

$dirInput = dir(PATH_INPUT);
 
if(!is_dir(PATH_INPUT)) {
  mkdir(PATH_INPUT);
}

if(!is_dir(PATH_OUTPUT)) {
  mkdir(PATH_OUTPUT);
}

while($arquivo = $dirInput -> read()){

  if(strpos($arquivo, ".entity") === false) {
    continue;
  }
  
  if(!is_dir(PATH_OUTPUT."/Repositories")) {
    mkdir(PATH_OUTPUT."/Repositories");
  }
  
  $className = str_replace(".entity","", $arquivo);

  $inputFile = fopen(PATH_INPUT."/$arquivo","r");

  $outputFileName = $className."Repository.class.php";
  $outputFile = fopen(PATH_OUTPUT."/Repositories/$outputFileName", "w");

  fwrite($outputFile, "<?php\n\n");
  fwrite($outputFile, "class $className"."Repository {\n\n");

  fwrite($outputFile, "    private $"."UnitOfWork;\n\n");

  fwrite($outputFile, "    private function mountEntities($"."stmt){\n\n");
  fwrite($outputFile, "    }\n\n");      

  fwrite($outputFile, "    public function __construct($"."unitOfWork){\n");
  fwrite($outputFile, "        $"."this->UnitOfWork = $"."unitOfWork;\n");    
  fwrite($outputFile, "    }\n\n");    

  fwrite($outputFile, "    public function save($".lcfirst($className)."){\n\n");
  fwrite($outputFile, "    }\n\n");    

  fwrite($outputFile, "    public function delete($".lcfirst($className)."){\n\n");
  fwrite($outputFile, "    }\n\n");    

  $attrs = array();
  
  while (!feof ($inputFile)) {
    $linha = fgets($inputFile);
    $linha = str_replace("\r", "", $linha);
    $linha = str_replace("\n", "", $linha);
    $attrs[] = $linha;
    $attr = explode("-",$linha)[0];
    $sql = "SELECT * FROM $className WHERE $attr = :value";
    fwrite($outputFile, "    public function getBy$attr($".lcfirst($attr)."){\n");
    fwrite($outputFile, "        $"."pdo = $"."this->UnitOfWork->getPDO();\n");
    fwrite($outputFile, "        $"."stmt = $"."pdo->prepare(\"$sql\");\n");
    fwrite($outputFile, "        $"."pdo->bindParam(\":value\", $".lcfirst($attr).");\n");
    fwrite($outputFile, "        $"."stmt->execute();\n");
    fwrite($outputFile, "        return $"."this->mountEntities($"."stmt);\n");
    fwrite($outputFile, "    }\n\n");        
  } 

  foreach($attrs as $attr){
    
  }
  
  fwrite($outputFile, "\n}");

  fclose ($inputFile);
  fclose ($outputFile);
}

createUnitOfWorkFile(PATH_OUTPUT);

$dirInput -> close();

// End...
// CODE GENERATION
//=============================================================================================================


//=============================================================================================================
// FUNCTIONS 
// Begin...

function createUnitOfWorkFile($pathOutput){
  if(!is_dir("$pathOutput/Repositories")) {
    mkdir("$pathOutput/Repositories");
  }  

  $UnitOfWorkCode = "<?php \n\n".
                    "class UnitOfWork {\n\n".
                    "    private $"."PDO;\n\n".                              
                    "    public function __construct(){\n".
                    "        $"."this->PDO = new PDO(\"". CONNECTION_STRING."\");\n".                                                  
                    "    }\n\n".
                    "    public function beginTransaction(){\n".
                    "        $"."this->PDO->beginTransaction();\n".                                                  
                    "    }\n\n".
                    "    public function commitTransaction(){\n".
                    "        $"."this->PDO->commit();\n".                                                  
                    "    }\n\n".                         
                    "    public function rollbackTransaction(){\n".
                    "        $"."this->PDO->rollback();\n".                                                  
                    "    }\n\n".                                   
                    "    public function getPDO(){\n".
                      "        return $"."this->PDO;\n".                                                  
                      "    }\n\n".                                                                                                         
                    "}\n\n"; 

  $unitOfWorkFile = fopen("$pathOutput/Repositories/UnitOfWork.class.php", "w");
  fwrite($unitOfWorkFile, $UnitOfWorkCode);
  fclose($unitOfWorkFile);
}

// End...
// FUNCTIONS 
//=============================================================================================================