<?php
Rhaco::import("resources.Message");
Rhaco::import("database.model.TableObjectBase");
Rhaco::import("database.model.DbConnection");
Rhaco::import("database.TableObjectUtil");
Rhaco::import("database.model.Table");
Rhaco::import("database.model.Column");
/**
 * #ignore
 * 
 */
class CountLogTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $times;
	/**  */
	var $sinceId;
	/**  */
	var $created;


	function CountLogTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->times = 0;
		$this->sinceId = 0;
		$this->created = time();
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","happen")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("happen"),"happen");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"happen");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","CountLog")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_happen_PREFIX")."count_log",__CLASS__),"CountLog");
		}
		return Rhaco::getVariable("_R_D_T_",null,"CountLog");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","CountLog::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			Rhaco::addVariable("_R_D_C_",$column,"CountLog::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"CountLog::Id");
	}
	/**
	 * 
	 * @return serial
	 */
	function setId($value){
		$this->id = TableObjectUtil::cast($value,"serial");
	}
	/**
	 * 
	 */
	function getId(){
		return $this->id;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnTimes(){
		if(!Rhaco::isVariable("_R_D_C_","CountLog::Times")){
			$column = new Column("column=times,variable=times,type=integer,size=22,",__CLASS__);
			$column->label(Message::_("times"));
			Rhaco::addVariable("_R_D_C_",$column,"CountLog::Times");
		}
		return Rhaco::getVariable("_R_D_C_",null,"CountLog::Times");
	}
	/**
	 * 
	 * @return integer
	 */
	function setTimes($value){
		$this->times = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getTimes(){
		return $this->times;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnSinceId(){
		if(!Rhaco::isVariable("_R_D_C_","CountLog::SinceId")){
			$column = new Column("column=since_id,variable=sinceId,type=integer,size=22,",__CLASS__);
			$column->label(Message::_("since_id"));
			Rhaco::addVariable("_R_D_C_",$column,"CountLog::SinceId");
		}
		return Rhaco::getVariable("_R_D_C_",null,"CountLog::SinceId");
	}
	/**
	 * 
	 * @return integer
	 */
	function setSinceId($value){
		$this->sinceId = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getSinceId(){
		return $this->sinceId;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnCreated(){
		if(!Rhaco::isVariable("_R_D_C_","CountLog::Created")){
			$column = new Column("column=created,variable=created,type=timestamp,",__CLASS__);
			$column->label(Message::_("created"));
			Rhaco::addVariable("_R_D_C_",$column,"CountLog::Created");
		}
		return Rhaco::getVariable("_R_D_C_",null,"CountLog::Created");
	}
	/**
	 * 
	 * @return timestamp
	 */
	function setCreated($value){
		$this->created = TableObjectUtil::cast($value,"timestamp");
	}
	/**
	 * 
	 */
	function getCreated(){
		return $this->created;
	}
	/**  */
	function formatCreated($format="Y/m/d H:i:s"){
		return DateUtil::format($this->created,$format);
	}


}
?>