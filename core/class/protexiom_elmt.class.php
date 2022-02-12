<?php

/* Copyright   2014 fdp1
 * 
 * This work is free. You can redistribute it and/or modify it under the
 * terms of the Do What The Fuck You Want To Public License, Version 2,
 * as published by Sam Hocevar. See the COPYING file for more details.
 * 
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

/* * ***************************Includes********************************* */
require_once __DIR__ . '/../../../../core/php/core.inc.php';

class protexiom_elmt extends eqLogic {
    /*     * *************************Attributs****************************** */
	
	protected $_eqParent = '';

    /*     * ***********************Static methods*************************** */
    /*     * ****accessible without needing an instantiation of the class**** */


    /*     * **********************Instance methods************************** */
	
	/**
	 * instantiate $this->_eqParent as the parent Protexiom eqLogic
	 * Only if not done already
	 *
	 * @author Fdp1
	 * @return
	 */
	public function initParent()
	{
		if ( ! is_object($this->_eqParent) ) {
		$this->_eqParent = protexiom::byId(substr($this->getLogicalId(), 0, strpos($this->getLogicalId(),"_")));
			if ( ! is_object($this->_eqParent) ) {
				throw new Exception(__('Objet parent introuvable (id='.substr($this->getLogicalId(), 0, strpos($this->getLogicalId(),"_")).' / '.$this->getLogicalId().')', __FILE__));
			}
		}
		return;
	}//End initParent func
	
	/**
	 * Add a message to the protexiom Jeedom log.
	 * Prepend message with parent eqLogic Name, parent eqLogic ID and PID
	 *
	 * @author Fdp1
	 * @param string $type log type (error, info, event, debug).
	 * @param string $message message to add in the log.
	 */
	public function log($_type = 'INFO', $_message)
	{
		$this->initParent();
		$this->_eqParent->log($_type, $_message);
	}//End log func

	/**
	 * Called before setting-up or updating a plugin device
	 * Standard Jeedom function
	 * @author Fdp1
	 */
	public function preUpdate() {
		if ($this->getIsEnable()) {
			//Trying to activate the eqLogic. Let's check if parent is activated aswell
			$this->initParent();
			if (!$this->_eqParent->getIsEnable()){
				throw new Exception(__('Impossible d\'activer l\'équipement, car l\'équipement parent ('.$this->_eqParent->getName().') est désactivé', __FILE__));
			}
		} 
	}//End preUpdate func
	
	/**
	 * Called after inserting a plugin device when creating it, before the first configuration
	 * Standard Jeedom function
	 * @author Fdp1
	 *
	 */
	/*public function postInsert() {


	}//End postInsert func*/

    /*     * **********************Getteur Setteur*************************** */
	
	/**
	 * Return link to the eqLogicConfiguration page.
	 * Optional for standard eqLogic, but needed to subEqlogic to get to the parent eqLogic config page
	 * Standard Jeedom function
	 *
	 * @author Fdp1
	 */
	public function getLinkToConfiguration() {
		return 'index.php?v=d&p=protexiom&m=protexiom&id=' . $this->getId();
	}//End getLinkToConfiguration func
	
	/**
	 * search subdevice image file, and return it's path
	 * @author Fdp1
	 * @return string imgFilePath, or false if no img is found
	 */
	public function getImgFilePath() {
		$imgName=$this->getConfiguration('item_type','typedefault') . '.png';
		$localFileName=__DIR__ . '/../../desktop/images/' . $imgName;
		if(file_exists($localFileName)) {
			return $imgName;
    }
    else {
			return false;
		}
	}//End function getImgFilePath

  /**
	 * Called to display the widget with zone name in the title
   * Standard Jeedom function
   * @param string $_version Widget version to display (mobile, dashboard or scenario)
   * @return string widget HTML code
   *
	 * @author jpty - Based on jeedom core version 4.2
	 */
  public function toHtml($_version = 'dashboard') {
		$replace = $this->preToHtml($_version);
		if (!is_array($replace)) {
			return $replace;
		}
		$_version = jeedom::versionAlias($_version);

    $replace['#eqLogic_class#'] = 'eqLogic_layout_default';
    $cmd_html = '';
    $br_before = 0;
    foreach ($this->getCmd(null, null, true) as $cmd) {
      if (isset($replace['#refresh_id#']) && $cmd->getId() == $replace['#refresh_id#']) {
        continue;
      }
      if ($_version == 'dashboard' && $br_before == 0 && $cmd->getDisplay('forceReturnLineBefore', 0) == 1) {
        $cmd_html .= '<br/>';
      }
      $cmd_html .= $cmd->toHtml($_version, '');
      $br_before = 0;
      if ($_version == 'dashboard' && $cmd->getDisplay('forceReturnLineAfter', 0) == 1) {
      $cmd_html .= '<br/>';
      $br_before = 1;
      }
    }
    $replace['#cmd#'] = $cmd_html;
       // add zone name in the title of the tile
    $zone = $this->getConfiguration('item_zone','');
    if($zone != '')  $replace['#name_display#'] .= " ($zone)";

		return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $_version, 'eqLogic')));
  }  // end toHtml function
}

class protexiom_elmtCmd extends cmd 
{
	/*     * *************************Attributs****************************** */
	
	/*     * ***********************Static methods*************************** */
	/*     * ****accessible without needing an instantiation of the class**** */
	
	
	/*     * **********************Instance methods************************** */
	
	/**
	 * Tells Jeedom if it should remove existing commands during update in case they no longer exists in the POSTed form
	 * Usefull, for exemple, in case you command list is static and created during postInsert
	 * and you don't want to bother putting them in the desktop/plugin.php form.
	 * Default to False (if you don't create the function), meaning missing commands ARE removed
	 * Standard Jeedom function
	 *
	 * @return bool
	 */
	public function dontRemoveCmd() {
		return true;
	}
	
	/**
	 * Execute CMD
	 * Standard Jeedom function
	 * @param array $_options
	 * @author Fdp1
	 */
	public function execute($_options = array()) {
		$subdevice=$this->getEqLogic();
		$protexiom = protexiom::byId(substr($subdevice->getLogicalId(), 0, strpos($subdevice->getLogicalId(),"_")));
		$elementId=substr($subdevice->getLogicalId(), strpos($subdevice->getLogicalId(),"-")+1);
		
		$myError="";
		$subdevice->log('debug', "Running ".$this->name." CMD");
		$infoValue="";
		
		if($this->getSubType()=='binary'){
			if(preg_match("/^([0]|[0-9 a-z]*nok)$/i", $protexiom->getElementsFromCache()[$elementId][$this->getLogicalId()])){
				$infoValue="0";
			}else{
				$infoValue="1";
			}
		}else{
			$infoValue=$protexiom->getElementsFromCache()[$elementId][$this->getLogicalId()];
		}
		$protexiom->log('debug', $this->name." CMD run OK");
		return $infoValue;
			 
	}


}
?>
