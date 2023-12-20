

def tri_fusion(tableau):

    if len(tableau) <= 1:
        return tableau

    else:
        milieu = len(tableau) // 2
        gauche = tableau[:milieu]
        droite = tableau[milieu:]
        gauche_trié = tri_fusion(gauche)
        droit_trié = tri_fusion(droite)
    
        return fusionner(gauche_trié, droit_trié)
    
def fusionner(gauche, droite):
   fusion = []
  #tant que gauche n'est pas vide et droit n'est pas vide
   while len(gauche) != 0 and len(droite) != 0:
       if gauche[0].score <= droite[0].score:
           #ajouter gauche[0] à fusionné
           fusion.append(gauche[0])
           #supprimer le premier élément de gauche
           gauche.pop(0)
       else:
           #ajouter droit[0] à fusionné
           fusion.append(droite[0])
           #supprimer le premier élément de droit
           droite.pop(0)
   

   fusion = fusion + gauche + droite
   return fusion

