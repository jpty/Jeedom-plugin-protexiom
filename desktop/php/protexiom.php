<?php

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
// Déclaration des variables obligatoires
$plugin = plugin::byId('protexiom');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
/*
foreach ($eqLogics as $eqLogic) {
  if ($eqLogic->getConfiguration('type') == '') {
    $eqLogic->setConfiguration('type', 'box');
    $eqLogic->save();
  }
  $type=$eqLogic->getConfiguration('type','');
  if($type) {
    $has[$type]=true;
  }
}
*/
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
              echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n'avez pas encore d'alarme Somfy, cliquez sur Ajouter pour commencer}}</span></center>";
          }
          ?>
        </div>
      </div>
    </div>
    <legend><i class="fas fa-table"></i> {{Composants de l'alarme Somfy}}</legend>
    <div class="input-group" style="margin-bottom:5px;">
      <input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic2" />
      <div class="input-group-btn">
        <a id="bt_resetEqlogicSearch2" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i></a>
      </div>
    </div>
    <div class="panel">
      <div class="panel-body">
        <div class="eqLogicThumbnailContainer second">
          <?php
          // Liste des composants des alarmes par alarme
          foreach ($eqLogics as $eqLogic) {
            echo '<legend>' .$eqLogic->getName() .'</legend>';
            foreach ($eqLogic->byType('protexiom_ctrl') as $subEqLogic) {
              if ( substr($subEqLogic->getLogicalId(), 0, strpos($subEqLogic->getLogicalId(),"_")) == $eqLogic->getId() ) {
                $opacity = ($subEqLogic->getIsEnable()) ? '' : 'disableCard';
                echo '<div class="eqLogicDisplayCard cursor second '.$opacity.'" data-eqLogic_id="' . $subEqLogic->getId() . '">';
                $subEqlogicImgFilePath=$subEqLogic->getImgFilePath();
                if ($subEqlogicImgFilePath !== false) {
                  echo '<img class="lazy" src="plugins/protexiom/desktop/images/' . $subEqlogicImgFilePath . '" height="105" width="95" />';
                } else {
                  echo '<img class="lazy" src="plugins/protexiom/plugin_info/protexiom_icon.png" height="105" width="92" />';
                }
                echo '<br>';
                echo '<span class="name">' . $subEqLogic->getHumanName(true, true) . '</span>';
                echo '</div>';
              }
            }
            foreach ($eqLogic->byType('protexiom_elmt') as $subEqLogic) {
              if ( substr($subEqLogic->getLogicalId(), 0, strpos($subEqLogic->getLogicalId(),"_")) == $eqLogic->getId() ) {
                $opacity = ($subEqLogic->getIsEnable()) ? '' : 'disableCard';
                echo '<div class="eqLogicDisplayCard cursor second '.$opacity.'" data-eqLogic_id="' . $subEqLogic->getId() . '">';
                $subEqlogicImgFilePath=$subEqLogic->getImgFilePath();
                if ($subEqlogicImgFilePath !== false) {
                  echo '<img class="lazy" src="plugins/protexiom/desktop/images/' . $subEqlogicImgFilePath . '" height="105" width="95" />';
                } else {
                  echo '<img class="lazy" src="plugins/protexiom/plugin_info/protexiom_icon.png" height="105" width="92" />';
                }
                echo '<br>';
                echo '<span class="name">' . $subEqLogic->getHumanName(true, true) . '</span>';
                echo '</div>';
              }
            }
          }// End foreach eqLogic
        ?>
        </div> <!-- /.eqLogicThumbnailContainer -->
      </div><!-- .panel-body -->
    </div><!-- .panel -->
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
                  <div class="col-sm-7">
                    <input id="logicalId" type="hidden" class="eqLogicAttr form-control" data-l1key="logicalId"/>
                    <input id="eqType_name" type="hidden" class="eqLogicAttr form-control" data-l1key="eqType_name"/>
                    <input type="hidden" class="eqLogicAttr form-control" data-l1key="configuration"  data-l2key="item_type"/>
                  </div>
                </div>
                <div class="form-group">
                  <label id="ProtexiomEqptName" class="col-sm-3 control-label">{{Nom}}</label>
                  <div class="col-sm-7">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;"/>
                    <input type="text" class="eqLogicAttr form-control" data-l1key="name"/>
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
              <div id="paramSpec">
              <legend><i class="fas fa-cogs"></i> {{Paramètres spécifiques}}</legend>
                <div class="form-group">
                  <label class="col-sm-3 control-label">{{Adresse IP}}</label>
                  <div class="col-sm-7">
                    <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="SomfyHostPort" placeholder="{{Adresse IP ou Hostname:port}}"/>
                {{Exemple}}: alarme.mondomaine.com:80 {{ou}} 192.168.1.253:80 {{ou}} 192.168.1.253
                  </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" >{{SSL Enabled}}</label>
                    <div class="col-sm-7">
                        <input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="SSLEnabled" placeholder="{{SSL Enabled}}" size="16"/>
									{{SSL NON SUPPORTE. Ne pas activer.}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{User Password}}</label>
                    <div class="col-md-3">
                        <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="UserPwd" placeholder="{{Exemple}}: 1234"/>
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
            </div>
            <!-- Partie droite de l'onglet "Équipement" -->
						<div class="col-lg-6">
							<legend><i class="fas fa-info"></i> {{Informations}}</legend>
							<div class="form-group">
								<div class="text-center">
									<img id="PluginImage" name="icon_visu" src="<?= $plugin->getPathImgIcon(); ?>" style="max-width:160px;"/>
								</div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">{{Zone}}<br></label>
                <div class="col-sm-7">
                  <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="item_zone" readonly/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">{{Label Somfy}}<br></label>
                <div class="col-sm-7">
                  <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="item_label" readonly/>
                </div>
              </div>
              <div id="ProtexiomHardwareVersion" class="form-group">
                <label class="col-sm-3 control-label">{{Version hardware}}<br></label>
                <div class="col-sm-7">
                  <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="HwVersion" placeholder="{{Non détectée}}" disabled/>
                      {{Autodétectée}}
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">{{Commentaire}}</label>
                <div class="col-sm-7">
                  <textarea class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="commentaire"></textarea>
                </div>
              </div>
              <legend id="ProtexiomElementTree">
                <i id="bt_displayElmtTree" title="{{Voir l'arbre des composants}}" class="fa fa-tree expertModeVisible tooltips cursor"></i> {{Composants de l'alarme}}
              </legend>
            </div>
           </fieldset> 
        </form>
      </div><!-- /.tabpanel #eqlogictabin -->
      <div role="tabpanel" class="tab-pane" id="cmdtab">
        <legend>{{Commandes}}</legend>
        <table id="table_cmd" class="table table-bordered table-condensed">
          <thead>
            <tr>
              <th style="width: 100px;">{{ID}}</th>
              <th style="width: 300px;">{{Nom}}</th>
              <th>{{Type}}</th>
              <th>{{Commande}}</th>
              <th>{{Paramètres}}</th>
              <th style="width: 100px;">{{Actions}}</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div><!-- .tabpanel #cmdtab -->
	 </div><!-- .tab-content -->
  </div><!-- /.eqLogic -->
</div><!-- /.row row-overflow -->

<!-- Inclusion du fichier javascript du plugin (dossier, nom_du_fichier, extension_du_fichier, id_du_plugin) -->
<?php include_file('desktop', 'protexiom', 'js', 'protexiom'); ?>
<!-- Inclusion du fichier javascript du core - NE PAS MODIFIER NI SUPPRIMER -->
<?php include_file('core', 'plugin.template', 'js'); ?>
