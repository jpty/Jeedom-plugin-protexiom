<?php

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
// Déclaration des variables obligatoires
$plugin = plugin::byId('protexiom');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
  <!-- Page d'accueil du plugin -->
  <div class="col-xs-12 eqLogicThumbnailDisplay">
    <legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
    <!-- Boutons de gestion du plugin -->
    <div class="eqLogicThumbnailContainer">
      <div class="cursor eqLogicAction logoPrimary" data-action="add">
        <i class="fas fa-plus-circle"></i>
        <br>
        <span>{{Ajouter}}</span>
      </div>
      <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
        <i class="fas fa-wrench"></i>
        <br>
        <span>{{Configuration}}</span>
      </div>
      <div class="cursor logoSecondary" id="bt_healthlivebox">
        <i class="fas fa-medkit"></i>
        <br />
        <span>{{Santé}}</span>
      </div>
    </div>
    <legend><i class="fas fa-table"></i>{{Mes Alarmes Somfy}}</legend>
      <div class="panel">
        <div class="panel-body">
          <div class="eqLogicThumbnailContainer ">
            <?php
            if(count($eqLogics)) {
              foreach ($eqLogics as $eqLogic) {
                $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
                echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
                echo '<img src="' . $eqLogic->getImage() . '"/>';
                echo '<br>';
                echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
                echo '</div>';
              }
            } else {
                echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n'avez pas encore d'alarme Somfy, cliquez sur Ajouter un équipement pour commencer}}</span></center>";
            }
            ?>
          </div>
        </div>
      </div>
      <legend><i class="fas fa-table"></i> {{Composants de l\'alarme Somfy}} <span class="cursor eqLogicAction" style="color:#fcc127" data-action="discover" data-action2="clients" title="{{Scanner les clients}}"><i class="fas fa-bullseye"></i></span>&nbsp;<span class="cursor eqLogicAction" style="color:#fcc127" data-action="delete" data-action2="clients" title="{{Supprimer Clients non-actifs (et ignorer lors des prochaines sync)}}"><i class="fas fa-trash"></i></span></legend>
      <div class="input-group" style="margin-bottom:5px;">
        <input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic2" />
        <div class="input-group-btn">
          <a id="bt_resetEqlogicSearch2" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i></a>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="eqLogicThumbnailContainer  second">
            <?php
            // Liste des composants des alarmes par alarme
              foreach ($eqLogics as $eqLogic) {
          echo '<legend><i class="fas fa-table"></i> {{Composants de l\'alarme Somfy: ' .$eqLogic->getName() .'}}</legend>';
          foreach ($eqLogic->byType('protexiom_ctrl') as $subEqLogic) {
            if ( substr($subEqLogic->getLogicalId(), 0, strpos($subEqLogic->getLogicalId(),"_")) == $eqLogic->getId() ) {
              echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $subEqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
              echo "<center>";
              $subEqlogicImgFilePath=$subEqLogic->getImgFilePath();
              if ($subEqlogicImgFilePath !== false) {
                echo '<img class="lazy" src="plugins/protexiom/desktop/images/' . $subEqlogicImgFilePath . '" height="105" width="95" />';
              } else {
                echo '<img class="lazy" src="plugins/protexiom/doc/images/protexiom_icon.png" height="105" width="92" />';
              }
              echo "</center>";
              echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $subEqLogic->getHumanName(true, true) . '</center></span>';
              echo '</div>';
            }
          }
          foreach ($eqLogic->byType('protexiom_elmt') as $subEqLogic) {
            if ( substr($subEqLogic->getLogicalId(), 0, strpos($subEqLogic->getLogicalId(),"_")) == $eqLogic->getId() ) {
              echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $subEqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
              echo "<center>";
              $subEqlogicImgFilePath=$subEqLogic->getImgFilePath();
              if ($subEqlogicImgFilePath !== false) {
                echo '<img class="lazy" src="plugins/protexiom/desktop/images/' . $subEqlogicImgFilePath . '" height="105" width="95" />';
              } else {
                echo '<img class="lazy" src="plugins/protexiom/doc/images/protexiom_icon.png" height="105" width="92" />';
              }
              echo "</center>";
              echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $subEqLogic->getHumanName(true, true) . '</center></span>';
              echo '</div>';
            }
          }
        }// End foreach eqLogic
    	?>
          </div>
        </div>
      </div>
    </div> <!-- /.eqLogicThumbnailContainer -->
  </div> <!-- /.eqLogicThumbnailDisplay -->
    
  <!-- Page de présentation de l'équipement -->
  <div class="col-xs-12 eqLogic" style="display: none;">
    <!-- barre de gestion de l'équipement -->
    <div class="input-group pull-right" style="display:inline-flex">
      <span class="input-group-btn">
        <!-- Les balises <a></a> sont volontairement fermées à la ligne suivante pour éviter les espaces entre les boutons. Ne pas modifier -->
        <a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span>
        </a><a class="btn btn-sm btn-default eqLogicAction" data-action="copy"><i class="fas fa-copy"></i><span class="hidden-xs"> {{Dupliquer}}</span>
        </a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
        </a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}
        </a>
      </span>
    </div>
    <!-- Onglets -->
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
      <li role="presentation" class="active"><a href="#eqlogictabin" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
      <li role="presentation"><a href="#cmdtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Commandes}}</a></li>
    </ul>
    <div class="tab-content">
      <!-- Onglet de configuration de l'équipement -->
      <div role="tabpanel" class="tab-pane active" id="eqlogictabin">
        <!-- Partie gauche de l'onglet "Equipements" -->
        <!-- Paramètres généraux de l'équipement -->
        <form class="form-horizontal">
          <fieldset>
            <div class="col-lg-6">
              <legend><i class="fas fa-wrench"></i> {{Paramètres généraux}}</legend>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{Nom de la centrale}}</label>
                    <div class="col-sm-7">
                        <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;"/>
                        <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de la centrale Somfy Protexiom}}"/>
                    </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">{{Objet parent}}</label>
                  <div class="col-sm-7">
                    <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                      <option value="">{{Aucun}}</option>
                      <?php
                      $options = '';
                      foreach ((jeeObject::buildTree(null, false)) as $object) {
                        $options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
                      }
                      echo $options;
                            ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">{{Catégorie}}</label>
                  <div class="col-sm-7">
                    <?php
                    foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                      echo '<label class="checkbox-inline">';
                      echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                      echo '</label>';
                    }
                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label"></label>
                  <div class="col-sm-7">
                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="isEnable" checked/>{{Activer}}</label>
                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>{{Visible}}</label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">{{Adresse IP}}</label>
                  <div class="col-sm-7">
                    <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="SomfyHostPort" placeholder="{{Adresse IP ou Hostname:port}}"/>
                {{Exemple}}: alarme.mondomaine.com:80 {{ou}} 192.168.1.253:80 {{ou}} 192.1681.253
                  </div>
                </div>

            <legend><i class="fas fa-cogs"></i> {{Paramètres spécifiques}}</legend>
                <div class="form-group">
                    <label class="col-sm-3 control-label" >{{SSL Enabled}}</label>
                    <div class="col-sm-7">
                        <input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="SSLEnabled" placeholder="{{SSL Enabled}}" size="16" checked/>
									{{SSL PAS ENCORE SUPPORTE. Ne pas activer.}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{User Password}}</label>
                    <div class="col-md-3">
                        <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="UserPwd" placeholder="{{User Password}}"/>
									{{Exemple}}: s3cr3tPassw0rd
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{AuthCard Line 1}}</label>
                    <div class="col-sm-7">
                        <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="AuthCardL1" placeholder="{{AuthCard Line 1}}"/>
									{{Exemple}}: 1234|5678|9012|3456|7890|1234
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{AuthCard Line 2}}</label>
                    <div class="col-sm-7">
                        <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="AuthCardL2" placeholder="{{AuthCard Line 2}}"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{AuthCard Line 3}}</label>
                    <div class="col-sm-7">
                        <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="AuthCardL3" placeholder="{{AuthCard Line 3}}"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{AuthCard Line 4}}</label>
                    <div class="col-sm-7">
                        <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="AuthCardL4" placeholder="{{AuthCard Line 4}}"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{AuthCard Line 5}}</label>
                    <div class="col-sm-7">
                        <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="AuthCardL5" placeholder="{{AuthCard Line 5}}"/>
                    </div>
                </div>
            </div>
           </fieldset> 
        </form>
      </div><!-- /.tabpanel #eqLogictab-->

        <div class="col-sm-5">
			<form class="form-horizontal">
    			<fieldset>
    			<legend>{{Informations}}
       					<i id="bt_displayElmtTree" title="{{Voir l'arbre des composants}}" class="fa fa-tree expertModeVisible pull-right tooltips cursor"></i>
     			</legend>
    			<div class="form-group">
                    <label class="col-md-2 control-label">{{Version hardware}}<br></label>
                    <div class="col-md-3">
                    	<input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="HwVersion" placeholder="{{Non détéctée}}" disabled/>
                        	{{Autodétéctée}}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{Commentaire}}</label>
                    <div class="col-sm-10">
                    	<textarea class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="commentaire"></textarea>
                    </div>
                </div>
    			</fieldset>
    		</form>
    		
    		<form class="form-horizontal">
            <fieldset>
                <div class="form-actions">
                    <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                    <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                </div>
            </fieldset>
        	</form>
    	</div>
    	
	 </div>

<?php
/* Command list
 * will be populated by the addCmdToTable() js function in desktop/protexiom.js
*/
?>
        <legend>{{Commandes}}</legend>
        <?php /* The command list is static. Lets not offer the possibility to remove them
        <a class="btn btn-success btn-sm cmdAction" data-action="add"><i class="fa fa-plus-circle"></i> {{Commandes}}</a><br/><br/>
        */ ?>
        <table id="table_cmd" class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th style="width: 50px;">{{ID}}</th>
                    <th style="width: 230px;">{{Nom}}</th>
                    <th>{{Légende widget mobile}}</th>
                    <th style="width: 110px;">{{Type}}</th>
                    <th style="width: 100px;">{{Commande}}</th>
                    <th style="width: 200px;">{{Paramètres}}</th>
                    <th style="width: 100px;"></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <form class="form-horizontal">
            <fieldset>
                <div class="form-actions">
                    <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                    <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                </div>
            </fieldset>
        </form>

  </div><!-- /.eqLogic -->
    <?php include_file('desktop', 'protexiom_ctrl', 'php', 'protexiom'); ?>
    <?php include_file('desktop', 'protexiom_elmt', 'php', 'protexiom'); ?>
</div><!-- /.row row-overflow -->

<!-- Inclusion du fichier javascript du plugin (dossier, nom_du_fichier, extension_du_fichier, id_du_plugin) -->
<?php include_file('desktop', 'protexiom', 'js', 'protexiom'); ?>
<!-- Inclusion du fichier javascript du core - NE PAS MODIFIER NI SUPPRIMER -->
<?php include_file('core', 'plugin.template', 'js'); ?>
