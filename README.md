# Jeedom-plugin-protexiom

Reprise du plugin protexiom pour Jeedom v4.2

### 14/02/2022
- Jeedom v4.0 minimum pour cette version 2.0.0 du plugin
- Corrections des templates dashboard
- Paramétres équipement et commandes dans des onglets différents.
- Ajout de la zone dans le titre des équipements.
- Réduction des interrogations de la centrale pour obtenir le statut des éléments. (L'interrogation est faite uniquement si les valeurs de tampered, link, battery, pause changent ou si au moins une porte/fenêtre est ouverte)

## TODO
- Templates des équipements sur mobile.
- Suppression des classes protexiom_ctrl et protexiom_elmt
- Suppression Protexiom non configurée impossible. Message: Adresse IP invalide
