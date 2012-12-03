<?
//Damn Small Framework ver 1.4
/*
 * TODO: validation on widgets through scaffolding
 * collection class to generate models, count records, delete massive records
 * 
 */
class DSF_Configs
{
	public $config;
	function getConfigs(){
		$this->config["debug_mode"] = 0; //Show Errors
		$this->config["library_path"] = "libraries/";
		$this->config["db_driver"] = "DSF_dbMySQL";
				
		$this->config["controller_var"] = "pag";
		$this->config["controller_source"] = "REQUEST";
		$this->config["controller_default"] = "default";
		$this->config["controller_prefix"] = "public_";
		$this->config["controller_error"] = "error";

		$this->config["db_log"] = 1; //0=none 1=error 2=all
		$this->config["db_host"] = "localhost";
		$this->config["db_user"] = "";
		$this->config["db_pass"] = "";
		$this->config["db_name"] = "";
		$this->config["db_prefix"] = "";
		
		$this->config["model_path"] = "models/";

		$this->config["widget_path"] = "widgets/";
		$this->config["widget_order"] = Array("Static","Combo","Check","Float","Int","Textarea","Text","Hidden");

		$this->config["security_tags_XSS"] = Array('html','body','meta','link','head','script','iframe');

		$this->config["template_path"] = "views/";
		$this->config["template_l_lim"] = "{";
		$this->config["template_r_lim"] = "}";
		$this->config["template_end_block"] = "/";
		$this->config["template_negation_block"] = "!";
		$this->config["template_rounds"] = "_round";
		$this->config["template_not_defined_is_false"] = false;
		$this->config["template_clear_at_end"] = true;
		
		$this->config["lang_auto"] = true;
		$this->config["lang_path"] = "lang/";
		$this->config["lang_common"] = "common";
		$this->config["lang_file_prefix"] = "lang_";
		$this->config["lang_session_var"] = "language";
		$this->config["lang_default"] = "en";
		$this->config["lang_request_var"] = "lang";
		$this->config["lang_scaffolding_section"] = "scaffolding";
		
		$this->config["pager_per_page"] = "20";
		$this->config["pager_get_var"] = "page";
		$this->config["pager_var_prefix"] = "";

		$this->config["scaffolding_field_var"] = "field";
		$this->config["scaffolding_label_var"] = "label";
		$this->config["scaffolding_prefix_id_html"] = "field_";

		return $this->config;
	}
}

class DSF {
	public $db;
	public $security;
	public $template;
	public $configs;
	public $pager;
	public $scaffolding;
	public $controller;

	function DSF($no_singletons = false,$no_db = false){
		$this->configs = DSF_Configs::getConfigs();
		if (!$no_singletons){
			$this->db = new $this->configs["db_driver"]($this);
			$this->security = new DSF_Security($this);
			$this->template = new DSF_Template($this);
			$this->pager = new DSF_Pager($this,$this->db);
			$this->scaffolding = new DSF_Scaffolding($this,$this->db);
		}
		$this->refresh();
	}
	function refresh(){
		ini_set('display_errors', $this->configs["debug_mode"]);
	}
	function &__call($name, $arguments){
		if (substr($name,0,3)=="new"){
			$name = substr($name,3);
			if ((!class_exists("DSF_".ucfirst(strtolower($name))))&&(file_exists($this->configs["library_path"]."dsf_".strtolower($name).".php"))){
				include_once($this->configs["library_path"]."dsf_".strtolower($name).".php");
			}
			if (class_exists("DSF_".ucfirst(strtolower($name)))){
				$class = "DSF_".ucfirst(strtolower($name));
				eval('$this->libs[$name] = '.$class.'::factory($this,$arguments,"'.$class.'");');
			}
			return $this->libs[$name];
		}
	}
	function start(&$controller){
		eval('$controller_var = $_'.$this->configs["controller_source"].";");
		$controller_var = $controller_var[$this->configs["controller_var"]];
		if (!$controller_var){
			$controller_var = $this->configs["controller_default"];
		}
		$method = $this->configs["controller_prefix"].$controller_var;
		if (in_array($method,get_class_methods(get_class($controller)))){
			$controller->$method();
		}else{
			$method = $this->configs["controller_prefix"].$this->configs["controller_error"];
			$controller->$method();
		}
	}
	function loadWidgets(){
		$list = glob($this->configs["widget_path"]."dsf_widget_*.php");
		foreach ($list as $filename) {
			include_once($filename);
			$name = ucfirst(str_replace(".php","",str_replace($this->configs["widget_path"]."dsf_widget_","",$filename)));
			array_unshift($this->configs["widget_order"],$name);
		}
	}
	function logToFile($line,$file=""){
		if (!$file){$file="logs/log_".date("Y_m_d").".txt";}
		$f = fopen($file,"a+");
		fwrite($f,str_pad(date("Y_m_d H:i:s:u   "),100,"*")."\n\n".$line."\n\n\n");
		fclose($f);
	}
}

class DSF_{
	public $dsf;
	function DSF_(&$DSF){
		$this->dsf=$DSF;
	}
	static function factory(&$dsf,$arguments,$classname=""){
		$name = $arguments[0];
		if(!$classname){
			$classname="DSF_".ucfirst(strtolower(get_class($this)));
		}
		$place=strtolower(get_class($this))."s";
		$dsf->$place[$name] = new $classname($dsf,$dsf->db);
		return $dsf->$place[$name];	
	}
}

class DSF_Controller extends DSF_ {
	function DSF_Controller(&$DSF){
		parent::DSF_($DSF);
		$this->dsf->controller = $this;
	}
	function error404(){
		header("HTTP/1.0 404 Not Found");
	}
	function redirect($url){
		header("Location: $url");
	}
	function public_error(){
		$this->error404();
	}
}

class DSF_Scaffolding extends DSF_ {
	protected $structure;
	public $keys;
	public $label;
	public $data;
	public $db;
	function DSF_Scaffolding(&$DSF,&$db){
		$this->db = $db;
		parent::DSF_($DSF);
	}
	
	function fromSource($source,$use_defaults=false){
		$this->structure = $this->db->query("SHOW FULL FIELDS FROM ".$this->db->prefix.$source,__LINE__,__FILE__,"Field");
		$this->buildDefaultValues($use_defaults);
	}
	function fromArray($structure,$use_defaults=false){
		$this->structure = $structure;
		$this->buildDefaultValues($use_defaults);
	}
	protected function buildDefaultValues($use_defaults=false){
		foreach($this->structure as $key => $element){
			if($use_defaults){$this->structure[$key]["Value"]=$element["Default"];}
			if($element["Key"]=="PRI"){$this->keys[$element["Field"]]=$element["Field"];}
		}
	}
	function getStructure(){
		return $this->structure;
	}
	function parse($lang=""){
		$this->dsf->loadWidgets();
		$return = "";
		if ($this->dsf->configs["lang_auto"]){
			$translator = $this->dsf->newLang($lang);
			$translation = $translator->load($this->dsf->configs["lang_scaffolding_section"]);
		}
		foreach($this->structure as $key => $piece){
			$return[$key]="";
			if ($this->label[$key]){
				if ($this->dsf->configs["lang_auto"]){
					$return[$key][$this->dsf->configs["scaffolding_label_var"]]=$translation[$this->label[$key]];
				}else{
					$return[$key][$this->dsf->configs["scaffolding_label_var"]]=$this->label[$key];	
				}				
			}else{
				$return[$key][$this->dsf->configs["scaffolding_label_var"]] = ucfirst(strtolower(str_replace("_"," ",$piece["Field"])));
			}
			if ($this->data[$key]){$data=$this->data[$key]; }else{ $data=$piece["Value"];}
			$return[$key][$this->dsf->configs["scaffolding_field_var"]] = $this->detectField($piece,$data);
		}
		return $return;
	}

	function detectField($piece,$value){
		$id = $this->dsf->configs["scaffolding_prefix_id_html"].$piece["Field"];
		$piece["Key"] = !!$this->keys[$piece["Field"]];
		$piece["Value"] = $value;
		foreach($this->dsf->configs["widget_order"] as $widget){
			$class = "DSF_Widget_".$widget;
			if (class_exists($class)){
				$field = new $class($this->dsf,$this->db);
				if($field->detectField($piece)){
					return $field->parse($piece);
				}
			}
		}
	}
}

abstract class DSF_Widget extends DSF_ {
	public $db;
	function DSF_Widget(&$DSF,&$db){
		$this->db = $db;
		parent::DSF_($DSF);
	}
	abstract function detectField($field);
	abstract function parse($field);
}

class DSF_Widget_Static extends DSF_Widget {
	function DSF_Widget_Static(&$DSF,&$db){
		//parent::DSF_Widget($DSF,&$db); //--> deprecated
		parent::DSF_Widget($DSF,$db);
	}
	function detectField($field){
		if ($field["Forced"]=="static"){return true;}
		return !!$field["Key"];
	}
	function parse($field){
		return '<input class="'.$field["Class"].'" type="hidden" name="'.$field["Field"].'" id="'.$field["id"].'" value="'.$field["Value"].'" /><span class="'.$field["Class"].'" id="'.$field["id"].'" >'.$field["Value"].'</span>';
	}
}

class DSF_Widget_Text extends DSF_Widget {
	function DSF_Widget_Text(&$DSF,&$db){
		//parent::DSF_Widget($DSF,&$db);
		parent::DSF_Widget($DSF,$db);
	}
	function detectField($field){
		return true;
	}
	function parse($field){
		return '<input class="'.$field["Class"].'" type="text" name="'.$field["Field"].'" id="'.$field["id"].'" value="'.$field["Value"].'" />';
	}
}

class DSF_Widget_Textarea extends DSF_Widget {
	function DSF_Widget_Textarea(&$DSF,&$db){
		//parent::DSF_Widget($DSF,&$db); //--> deprecated
		parent::DSF_Widget($DSF,$db);
	}
	function detectField($field){
		if ($field["Forced"]=="textarea"){return true;}
		return $field["Type"]=="text";
	}
	function parse($field){
		return '<textarea class="'.$field["Class"].'" name="'.$field["Field"].'" id="'.$field["id"].'" >'.$field["Value"].'</textarea>';
	}
}

class DSF_Widget_Hidden extends DSF_Widget {
	function DSF_Widget_Hidden(&$DSF,&$db){
		//parent::DSF_Widget($DSF,&$db); //--> deprecated
		parent::DSF_Widget($DSF,$db);
	}
	function detectField($field){
		if ($field["Forced"]=="hidden"){return true;}
		return false;
	}
	function parse($field){
		return '<input class="'.$field["Class"].'" type="hidden" name="'.$field["Field"].'" id="'.$field["id"].'" value="'.$field["Value"].'" />';
	}
}

class DSF_Widget_Int extends DSF_Widget {
	function DSF_Widget_Int(&$DSF,&$db){
		//parent::DSF_Widget($DSF,&$db); //deprecated
		parent::DSF_Widget($DSF,$db); 
	}
	function detectField($field){
		if ($field["Forced"]=="int"){return true;}
		return (substr(strtolower($field["Type"]),0,6)=="bigint")OR(substr(strtolower($field["Type"]),0,3)=="int");
	}
	function parse($field){
		return '<input type="text" class="'.$field["Class"].'" name="'.$field["Field"].'" id="'.$field["id"].'" value="'.$field["Value"].'" onkeydown="if((event.keyCode>=48 && event.keyCode<=57)||(event.keyCode>=96 && event.keyCode<=105)||(event.keyCode==8)||(event.keyCode==9)||(event.keyCode==12)||(event.keyCode==27)||(event.keyCode==37)||(event.keyCode==39)||(event.keyCode==46)){return true;}else{return false;}" />';
	}
}

class DSF_Widget_Float extends DSF_Widget {
	function DSF_Widget_Float(&$DSF,&$db){
		//parent::DSF_Widget($DSF,&$db); //--> deprecated
		parent::DSF_Widget($DSF,$db); 
	}
	function detectField($field){
		if ($field["Forced"]=="float"){return true;}
		return (substr(strtolower($field["Type"]),0,5)=="float")OR(substr(strtolower($field["Type"]),0,6)=="double");
	}
	function parse($field){
		return '<input type="text" class="'.$field["Class"].'" name="'.$field["Field"].'" id="'.$field["id"].'" value="'.$field["Value"].'" onkeydown="if((event.keyCode>=48 && event.keyCode<=57)||(event.keyCode>=96 && event.keyCode<=105)||(event.keyCode==8)||(event.keyCode==9)||(event.keyCode==12)||(event.keyCode==27)||(event.keyCode==37)||(event.keyCode==39)||(event.keyCode==46)||(event.keyCode==190)){return true;}else{return false;}" />';
	}
}

class DSF_Widget_Check extends DSF_Widget {
	function DSF_Widget_Check(&$DSF,&$db){
		//parent::DSF_Widget($DSF,&$db); //--> deprecated
		parent::DSF_Widget($DSF,$db);
	}
	function detectField($field){
		if ($field["Forced"]=="check"){return true;}
		return $field["Type"]=="tinyint(1)";
	}
	function parse($field){
		if($field["Value"]){$value='checked="checked"';}else{$value="";}
		return '<input type="checkbox" class="'.$field["Class"].'" name="'.$field["Field"].'" id="'.$field["id"].'" value="1" '.$value.' />';
	}
}

class DSF_Widget_Combo extends DSF_Widget {
	function DSF_Widget_Combo(&$DSF,&$db){
		//parent::DSF_Widget($DSF,&$db); //--> deprecated
		parent::DSF_Widget($DSF,$db);
	}
	function detectField($field){
		if ($field["Forced"]=="combo"){return true;}
		return substr($field["Type"],0,4)=="enum";
	}
	function parse($field){
		$options_html = "";
		$options = explode(",",substr($field["Type"],5,-1));
		foreach ($options as $option){
			$option = substr($option,1,-1);
			$selected="";
			if ($option==$field["Value"]){$selected='selected="selected"';}
			$options_html.="\n\t".'<option value="'.$option.'" '.$selected.'>'.$option.'</option>';
		}
		return '<select class="'.$field["Class"].'" name="'.$field["Field"].'" id="'.$field["id"].'" >'.$options_html."\n\t".'</select>';
	}
}

class DSF_Model extends DSF_ {
	protected $data;
	protected $structure;
	protected $db;
	protected $source;
	protected $keys;
	protected $errors = false;

	function DSF_Model(&$DSF,&$db,$source=""){
		parent::DSF_($DSF);
		$this->db = $db;
		if($source){
			$this->buildSource($source);
		}
	}
	
	function buildSource($source){
		$this->source = $source;
		$this->structure = $this->db->query("SHOW FULL FIELDS FROM ".$this->db->prefix.$this->source,__LINE__,__FILE__,"Field");
		foreach($this->structure as $element){
			if($element["Key"]=="PRI"){$this->keys[$element["Field"]]=$element["Field"];}
		}
	}
	
	static function factory(&$dsf,$arguments){
		$name = $arguments[0]; $source = $arguments[1]; $class = $arguments[2];
		if (!$source){$source=$name;}
		if (!$class){
			$class = $source;
		}
		if (file_exists($dsf->configs["model_path"]."dsf_model_".strtolower($class).".php")){
			include_once($dsf->configs["model_path"]."dsf_model_".strtolower($class).".php");
		}
		if (class_exists("DSF_Model_".ucfirst(strtolower($class)))){
			$class = "DSF_Model_".ucfirst(strtolower($class));
			$dsf->models[$name] = new $class($dsf,$dsf->db,$source);
		}else{
			$dsf->models[$name] = new DSF_Model($dsf,$dsf->db,$source);
		}
		return $dsf->models[$name];
	}
	
	function __get($variable){
		$hook = "get_".$variable;
		if (method_exists($this,$hook)){
			return $this->$hook($this->data[$variable]);
		}
		return $this->data[$variable];
	}
	function __set($variable,$data){
		$hook = "set_".$variable;
		if (method_exists($this,$hook)){
			$data = $this->$hook($data);
		}else{
			if (is_array($this->structure[$variable])){
				$this->data[$variable] = $data;
			}
		}
	}
	function __isset($variable){
		$hook = "isset_".$variable;
		if (method_exists($this,$hook)){
			return $this->$hook($this->data[$variable]);
		}
		return isset($this->data[$variable]);
	}
	function getKeys(){
		return $this->keys;
	}
	function getStructure(){
		return $this->structure;
	}
	function getErrors(){
		return $this->errors;
	}
	function resetErrors(){
		$this->errors=false;
	}
	function load($key){
		$where=""; $and="";
		if (!is_array($key)){$value=$key; $key=""; $key[reset($this->keys)]=$value;}
		foreach($key as $key => $value){
			$where.=$and.$key." = '".$value."'"; $and=" AND ";
		}
		$element = $this->db->query("SELECT * FROM ".$this->db->prefix.$this->source." WHERE ".$where." LIMIT 1",__LINE__,__FILE__,1);
		if (!$element){return false;}
		foreach($element[0] as $key => $value){
			$this->data[$key] = $value;
		}
		$this->errors=false;
		return true;
	}
	function toArray(){
		$array = "";
		foreach($this->data as $variable => $data){
			$array[$variable] = $this->__get($variable);
		}
		return $array;
	}
	function setArray($array){
		foreach($array as $variable => $data){
			$this->__set($variable,$data);
		}
	}
	function save(){
		$comma=""; $sdata="";
		if($this->errors){return false;}
		foreach ($this->data as $key => $value){
			$sdata.=$comma." `".$key."` = '".$value."'"; $comma =" , ";
		}
		if($update){$insertion="UPDATE ";}else{$insertion="REPLACE INTO ";}
		$this->db->query($insertion.$this->db->prefix.$this->source." SET ".$sdata,__LINE__,__FILE__);
		return true;
	}
	function delete($key=""){
		if (!$key){
			foreach($this->keys as $akey){
				$key[$akey]=$this->data[$akey];
			}
		}
		if (!$key){return false;}
		$where=""; $and="";
		if (!is_array($key)){$value=$key; $key=""; $key[reset($this->keys)]=$value;}
		foreach($key as $akey => $value){
			$where.=$and.$akey." = '".$value."'"; $and=" AND ";
		}
		$this->db->query("DELETE FROM ".$this->db->prefix.$this->source." WHERE ".$where,__LINE__,__FILE__);
		return true;
	}
	function nextId(){
		$element = $this->db->query("SHOW TABLE STATUS WHERE Name = '".$this->db->prefix.$this->source."'",__LINE__,__FILE__,1);
		return $element[0]["Auto_increment"];
	}
}

class DSF_Pager extends DSF_{
	protected $sql;
	protected $source;
	protected $template;
	protected $db;
	protected $model;
	function DSF_Pager(&$DSF,&$db){
		$this->db = $db;
		parent::DSF_($DSF);
	}
	
	function fromSource($source,$filter="",$model=""){
		$this->source = $source;
		$this->model = $model;
		$this->sql = $this->db->prefix.$source." ";
		if ($filter){
			if (is_array($filter)){
				$temp = ""; $and="";
				foreach($filter as $key => $value){
					$temp.=$and.$key." = '".$value."'"; $and=" AND ";
				}
				$filter=$temp;
			}
			$this->sql .=" WHERE ".$filter." ";
		}
	}
	function fromSQL($sql){
		$this->sql = $sql;
	}
	function getList($start="",$orderby="",$asc="",$fields="*"){
		if (!$this->sql){return 0;}
		$prefix = $this->dsf->configs["pager_var_prefix"];
		$count = $this->db->query("SELECT COUNT(*) AS c FROM ".$this->sql,__LINE__,__FILE__,1);	//count total
		$count = $count[0]["c"];
		if (!$start){$start=$_GET[$this->dsf->configs["pager_get_var"]];}	//preset some vars
		if (!$start){$start=0;}
		if ($start<0){$start=0;}
		if ($orderby){
			if ($asc){$asc=" ASC ";}else{$asc=" DESC ";}
			$this->sql .= " ORDER BY ".$orderby." ".$asc;
		}
		$this->sql .= " LIMIT ".$start.", ".$this->dsf->configs["pager_per_page"];	//prepare list
		$return[$prefix."list"] = $this->db->query("SELECT ".$fields." FROM ".$this->sql,__LINE__,__FILE__,1);
		if (($this->model)||(class_exists("DSF_Model_".$this->source))){
			$this->dsf->newModel("__pagerTemp",$this->source,$this->model);
			foreach($return[$prefix."list"] as $key => $value){
				$this->dsf->models["__pagerTemp"]->setArray($return[$prefix."list"][$key]);
				$return[$prefix."list"][$key] = $this->dsf->models["__pagerTemp"]->toArray();
			}
			unset($this->dsf->models["__pagerTemp"]);
		}

		if (strstr($_SERVER["REQUEST_URI"],"?")){	//prepare url
			$url = str_replace($this->dsf->configs["pager_get_var"]."=".$_GET[$this->dsf->configs["pager_get_var"]]."&","",$_SERVER["REQUEST_URI"]);
			$url = str_replace("?".$this->dsf->configs["pager_get_var"]."=".$_GET[$this->dsf->configs["pager_get_var"]],"?",$url);
			$url = str_replace("&".$this->dsf->configs["pager_get_var"]."=".$_GET[$this->dsf->configs["pager_get_var"]],"",$url);
			$url.="&".$this->dsf->configs["pager_get_var"];
		}else{
			$url.="?".$this->dsf->configs["pager_get_var"];
		}

		$return[$prefix."actual"] = ceil($start / $this->dsf->configs["pager_per_page"])+1;//prepare links

		$prev = ceil(($start)/$this->dsf->configs["pager_per_page"]);
		if ($start > 0){	// previous
			$this->setPagerTemplateVar($return,$prefix,"prev",$prev,$url."=".($start-($this->dsf->configs["pager_per_page"])),true);
		}else{
			$this->setPagerTemplateVar($return,$prefix,"prev","","",false);
		}

		$next=ceil(($start)/$this->dsf->configs["pager_per_page"])+2;
		if ($start+($this->dsf->configs["pager_per_page"])<=$count){	//next
			$this->setPagerTemplateVar($return,$prefix,"next",$next,$url."=".($start+($this->dsf->configs["pager_per_page"])),true);
		}else{
			$this->setPagerTemplateVar($return,$prefix,"next","","",false);
		}

		if ($return[$prefix."actual"]>2){	//first
			$this->setPagerTemplateVar($return,$prefix,"first",1,$url.$this->dsf->configs["pager_get_var"]."=0",true);
		}else{
			$this->setPagerTemplateVar($return,$prefix,"first","","",false);
		}

		$last = ceil($count / $this->dsf->configs["pager_per_page"]); //last
		$return[$prefix."total"] = $last;
		if ($return[$prefix."actual"]+1 < $last){
			$this->setPagerTemplateVar($return,$prefix,"last",$last,$url."=".floor($count / $this->dsf->configs["pager_per_page"])*$this->dsf->configs["pager_per_page"],true);
		}else{
			$this->setPagerTemplateVar($return,$prefix,"last","","",false);
		}

		$first_mid = ceil($return[$prefix."actual"]/2);	//first_mid
		if ($first_mid < $prev){
			$this->setPagerTemplateVar($return,$prefix,"mid",$first_mid,$url."=".($first_mid-1)*$this->dsf->configs["pager_per_page"],true);
		}else{
			$this->setPagerTemplateVar($return,$prefix,"mid","","",false);
		}

		$last_mid = ceil(($last-$return[$prefix."actual"])/2); //last_mid
		if (($return[$prefix."actual"]+$last_mid) > $next){
			$this->setPagerTemplateVar($return,$prefix,"last_mid",($return[$prefix."actual"]+$last_mid),$url."=".($last_mid-1)*$this->dsf->configs["pager_per_page"],true);
		}else{
			$this->setPagerTemplateVar($return,$prefix,"last_mid","","",false);
		}
		return $return;
	}

	protected function setPagerTemplateVar(&$return,$prefix,$name,$number,$link,$show){
		$return[$prefix.$name] = $number;
		$return[$prefix."link_".$name] = $link;
		$return[$prefix."show_".$name] = $show;
	}
}

class DSF_dbMySQL extends DSF_{

	public $link;
	public $status;
	public $log;
	public $debug_mode;
	public $DBHost = "localhost";	public $DBName = "";	public $DBUser = "";	public $DBPass = "";	public $prefix;

	function DSF_dbMySQL(&$DSF){
		parent::DSF_($DSF);
	}

	function connect($DBUser="", $DBPass="", $DBName="", $DBHost="", $prefix="") {
		if ($DBName){ $this->DBName = $DBName; }else{ $this->DBName = $this->dsf->configs["db_name"];}
		if ($DBHost){ $this->DBHost = $DBHost; }else{ $this->DBHost = $this->dsf->configs["db_host"];}
		if ($DBUser){ $this->DBUser = $DBUser; }else{ $this->DBUser = $this->dsf->configs["db_user"];}
		if ($DBPass){ $this->DBPass = $DBPass; }else{ $this->DBPass = $this->dsf->configs["db_pass"];}
		if ($prefix){ $this->prefix = $prefix; }else{ $this->prefix = $this->dsf->configs["db_prefix"];}
		$this->link = mysql_connect($this->DBHost,$this->DBUser,$this->DBPass);
		if ($this->link){
			$this->selectDB($this->DBName);
		}else{
			$this->status = false;
		}
	}

	function selectDB($DBName){
		$this->DBName = $DBName;
		if(mysql_select_db($DBName,$this->link)){
			$this->status = true;
		}else{
			$this->status = false;
		}
	}

	protected function addToLog($query,$error,$line,$file){
		$this->log[] = array($query,$error,$line,$file);
	}

	// $r = $DSF->db->query("SELECT name, surname FROM users",__FILE__,__LINE__,"name","surname"); => $r["john"] = "smith";
	function query($query, $pfile="",$pline="", $back=false, $what=0){
		$ret = "";
		if (!$this->link){
			return 0;
		}
		if (!is_array($query)){$temp=$query;$query="";$query[0]=$temp;}
		foreach ($query as $queryline){
			if ((!empty($queryline))AND(trim($queryline)!="")){
				$result = mysql_query($queryline,$this->link);
				if ($result){
					if ($back!=false){
						while ($line = mysql_fetch_array($result,MYSQL_ASSOC)){
							if ((is_string($back)) && (isset($line[$back]))){
								if ($what){
									if ($what == $back){
										$ret = $line[$what];
									}else{
										$ret[$line[$back]] = $line[$what];
									}
								}else{
									$ret[$line[$back]] = $line;
								}
							}else{
								if ($what){
									$ret[] = $line[$what];
								}else{
									$ret[] = $line;
								}
							}
						}
						if (empty($ret)){$ret = false;}
						mysql_free_result($result);
					}else{
						$ret = 1;
					}
					if ($this->dsf->configs["db_log"] == 2){
						$this->addToLog($queryline,"",$pline,$pfile);
					}
				}else{
					if (((mysql_error()!="")AND($this->dsf->configs["db_log"] == 1))OR($this->dsf->configs["db_log"] == 2)OR($this->dsf->configs["debug_mode"])){
						$this->addToLog($queryline,mysql_error(),$pline,$pfile);
						if ($this->dsf->configs["debug_mode"]){echo "<b>MYSQL ERROR:</b><br><br><i>".$queryline."</i><br><br>".mysql_error()."<br><br>in <b>".$pfile."</b> on line <b>".$pline."</b>";exit;}
					}
					$ret = 0;
				}
			}
		}
		return $ret;
	}
	function close(){
		mysql_close($this->link);
		$this->status = false;
	}
	function lastId(){
		return mysql_insert_id($this->link);
	}
	function now(){
		return date("Y-m-d H:i:s");
	}
}

class DSF_Security extends DSF_ {
	function DSF_Security(&$DSF){
		parent::DSF_($DSF);
	}
	function XSS($to_clean){
		if (is_array($to_clean)){
			foreach ($to_clean as $key => $value){
				$to_clean[$key] = $this->XSS($to_clean[$key]);
			}
		}else{
			foreach ($this->dsf->configs["security_tags_XSS"] as $tag) {
				$to_clean = preg_replace('/<[^<]*'.$tag.'[^>]*>/',"",$to_clean);
			}
		}
		return $to_clean;
	}
	function SQLInjection($to_clean){
		if (is_array($to_clean)){
			foreach ($to_clean as $key => $value){
				$to_clean[$key] = $this->SQLInjection($to_clean[$key]);
			}
		}else{
			$to_clean = mysql_real_escape_string($to_clean);
		}
		return $to_clean;
	}
	function defaultSecurity($SQLI=1,$XSS=1){
		if ($XSS){
			$_POST = $this->XSS($_POST);
			$_GET = $this->XSS($_GET);
			$_REQUEST = $this->XSS($_REQUEST);
			$_COOKIE = $this->XSS($_COOKIE);
		}
		if ($SQLI){
			$_POST = $this->SQLInjection($_POST);
			$_GET = $this->SQLInjection($_GET);
			$_REQUEST = $this->SQLInjection($_REQUEST);
			$_COOKIE = $this->SQLInjection($_COOKIE);
		}
	}
}

class DSF_Template extends DSF_ {
	public $text_template;
	public $data;
	protected $object;

	function DSF_Template(&$DSF){
		parent::DSF_($DSF);
	}

	function loadFromFile($path){
		$this->text_template = file_get_contents($this->dsf->configs["template_path"].$path);
	}

	function loadFromString($string){
		$this->text_template = $string;
	}
	function parse($data="", $template="", $lang=""){
		if ($this->dsf->configs["lang_auto"]){
			$translator = $this->dsf->newLang($lang);
			$data = $translator->loadAndMerge($data);
			$this->data = $data;
		}
	

		if ($template){
			$this->text_template = $this->loadFromFile($path);
		}
		if (!$this->text_template){	return FALSE;}
		if (!$data){ return $this->text_template;}

		foreach ($data as $key => $val){
			if ((is_bool($val))OR(is_array($val))){
				$this->text_template = $this->_parse_pair($key, $val, $this->text_template);
				$this->text_template = $this->_parse_pair($this->dsf->configs["template_negation_block"].$key, !$val, $this->text_template);
			}else{
				$this->text_template = $this->_parse_single($key, (string)$val, $this->text_template);
			}
		}

		if ($this->dsf->configs["template_not_defined_is_false"]){
			$this->text_template = $this->_parse_pair($this->dsf->configs["template_negation_block"].".+", true, $this->text_template);
		}
		if ($this->dsf->configs["template_clear_at_end"]){
			$this->text_template = preg_replace("|".$this->dsf->configs["template_l_lim"] . "/?([\!a-zA-Z0-9_-]*)" . $this->dsf->configs["template_r_lim"].".+".$this->dsf->configs["template_l_lim"] . $this->dsf->configs["template_end_block"] . "\\1" . $this->dsf->configs["template_r_lim"]."|sU","",$this->text_template);
			$this->text_template = preg_replace("|".$this->dsf->configs["template_l_lim"] . "/?([\!a-zA-Z0-9_-]*)" . $this->dsf->configs["template_r_lim"]."|sU","",$this->text_template);
		}
		return $this->text_template;
	}

	protected function _parse_single($key, $val, $string){
		return str_replace($this->dsf->configs["template_l_lim"].$key.$this->dsf->configs["template_r_lim"], $val, $string);
	}

	protected function _parse_pair($variable, $data, $string){
		$match = $this->_match_pair($string, $variable);
		
		while ($match!==FALSE){
			if (is_bool($data)){
				if ($data){
					$string = str_replace($match['0'], $match['1'], $string);
				}else{
					$string = str_replace($match['0'], '', $string);
				}
			}
			$str = '';
	
			if (is_array($data)){
				foreach ($data as $key_numb => $row){
					$row[$variable.$this->dsf->configs["template_rounds"]] = $key_numb;
					$temp = $match['1'];
					foreach ($row as $key => $val){
						if ((is_bool($val))OR(is_array($val))){
							$temp = $this->_parse_pair($key, $val, $temp);
							$temp = $this->_parse_pair($this->dsf->configs["template_negation_block"].$key, !$val, $temp);
						}else{
							$temp = $this->_parse_single($key, $val, $temp);						
						}
					}
					$str .= $temp;
				}
			}
			$string = str_replace($match['0'], $str, $string);
			$match = $this->_match_pair($string, $variable);
		}
		
		return $string;
	}

	protected function _match_pair($string, $variable)	{
		if ( ! preg_match("|".$this->dsf->configs["template_l_lim"] . $variable . $this->dsf->configs["template_r_lim"]."(.+)".$this->dsf->configs["template_l_lim"] . $this->dsf->configs["template_end_block"] . $variable . $this->dsf->configs["template_r_lim"]."|sU", $string, $match)){
			return FALSE;
		}
		return $match;
	}
}
class DSF_Collection extends DSF_ {
	function DSF_Collection(&$DSF,$db){
		parent::DSF_($DSF);
	}
	function load(){
		
	}
	function delete(){
		
	}
	function save(){
		
	}
		
}
class DSF_Lang extends DSF_ {
	public $language;
	function DSF_Lang(&$DSF,$language="",$avoidsession=false){
		parent::DSF_($DSF);
		if ((!$avoidsession)&&($_REQUEST[$this->dsf->configs["lang_request_var"]])){$_SESSION[$this->dsf->configs["lang_session_var"]] = $_REQUEST[$this->dsf->configs["lang_request_var"]];}
		if (!$language){$language = $_SESSION[$this->dsf->configs["lang_session_var"]];}
		if (!$language){$language = $this->dsf->configs["lang_default"];}
		$this->language	= $language;

	}
	static function factory(&$dsf,$arguments){
		$dsf->langs[$arguments[0]] = new DSF_Lang($dsf,$arguments[0],$arguments[1]);
		return $dsf->langs[$arguments[0]];	
	}
	function changeLanguage($lang,$avoidsession=false){
		$this->language = $lang;
		if (!$avoidsession){$_SESSION[$this->dsf->configs["lang_session_var"]] = $lang;}
	}
	function load($sections=""){
		$lang = Array();
		if (!$sections){ 
			$sections[0]=$this->dsf->configs["lang_common"];
			$sections[1]=strtolower(get_class($this->dsf->controller));			
		}
		if (!is_array($sections)){
			$sec = $sections;
			$sections = "";
			$sections[0]=$sec;
		}
		foreach($sections as $section){
			if (file_exists($this->dsf->configs["lang_path"].$this->language."/".$this->dsf->configs["lang_file_prefix"].$section.".php")){
				include_once $this->dsf->configs["lang_path"].$this->language."/".$this->dsf->configs["lang_file_prefix"].$section.".php";
			}
		}
		
		return $lang;
	}
	function loadAndMerge($array,$sections=""){
		if (!$array){$array = Array();}
		return array_merge($array,$this->load($sections));
	}
}
// :-)
?>
